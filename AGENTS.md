# AGENTS.md — DKP Payroll (CI4)

Payroll / slip-gaji app for Dinas Kelautan dan Perikanan. Migrated from PHP native to CodeIgniter 4.0.4 (old CI4, patched for PHP 8.x compat).

## Quick start

```bash
php spark serve                          # dev server
composer test                            # phpunit 8.5, SQLite :memory:, no external DB
```

## Architecture

- **CI4 4.0.4** — `setAutoRoute(false)`, all routes explicit in `app/Config/Routes.php`
- **Two roles**: `admin` (full CRUD) and `bendahara` (treasurer workflow)
- **Auth**: session-based (`loggedin`+`role` in session). `AuthFilter.php` checks login + 30min inactivity. `AdminFilter.php`/`BendaharaFilter.php` check role
- **DB**: MySQL `dkp_payroll` (dev/prod), SQLite `:memory:` (tests, auto-switched in `Database.php:68`)
- **CSRF**: disabled in `.env` but filter wired in `Config/Filters.php` — login routes bypass it, all POST forms must include `csrf_field()`
- **Session**: file-based, `writable/session/`, 7200s expiry, cookie `ci_session`
- **Helpers** auto-loaded: `rupiah()`, `status_badge_class()` in `app/Helpers/app_helper.php`
- **Views**: layout `app/Views/layouts/main.php` (Bootstrap 5 + Font Awesome)
- **PDF**: Dompdf via `app/Libraries/SlipPdfService.php` (string template)
- **Excel import**: PhpSpreadsheet in `app/ThirdParty/legacy_vendor/` — loaded via `LegacyPayrollLib::load()`
- **Locale**: `id`, **Timezone**: `Asia/Jakarta`, **indexPage**: empty (clean URLs)

## Routes (`app/Config/Routes.php`)

| Prefix | Filter | Controller |
|---|---|---|
| `/login`, `/` | public | `Auth` |
| `/dashboard` | `auth` | `Dashboard` |
| `/pegawai/*`, `/slip/*`, `/backup/*`, `/generate-slip/*`, `/import-sipd`, `/laporan` | `admin` | Various |
| `/bendahara/*` | `bendahara` | `Bendahara` |

## Bendahara workflow

`draft` → `verified` (verifikasi) → `approved` (approval) → `paid` (bayar) → `selesai` (finalisasi)

Logs via `SlipLogger` → `payroll_status_logs` table.

## Key conventions

- Every new route must be registered in `Routes.php` (no auto-route)
- `PegawaiModel::active()` scope filters `is_active=1` — use for employee dropdowns
- Bulk PDF generation in `GenerateSlip::bulk()` processes 25 rows per chunk (`$chunkSize=25`) with `gc_collect_cycles()`
- `PayrollDetailModel::withRelations()` joins `periode_penggajian` + `pegawai` — prefer over raw joins
- `BaseController::initController()` sets `Cache-Control: no-store` globally (prevents flash message double-show)
- `app/ThirdParty/legacy_vendor/` has own autoload — check before adding new Composer deps
- Lint: `vendor/bin/phpcs` available
- Migration: `app/Database/Migrations/2026-06-30-220000_InitialSchema.php` (10 tables)
- Login rate-limit: 5 attempts, 15min lockout (`Auth.php`)
- Seeder minimal: `composer test` requires `tests/_support/Database/Seeds/TestSeeder.php`
