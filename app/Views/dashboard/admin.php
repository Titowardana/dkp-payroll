<?= $this->extend('layouts/main') ?>
<?= $this->section('styles') ?>
<style>
    /* ── Hero ─────────────────────────────────────────────── */
    .dash-hero {
        background: linear-gradient(135deg, #023e8a 0%, #0077b6 50%, #00b4d8 100%);
        border-radius: 20px;
        padding: 32px 36px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(0, 119, 182, 0.35);
        margin-bottom: 28px;
    }

    .dash-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -80px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
        pointer-events: none;
    }

    .dash-hero::after {
        content: '';
        position: absolute;
        bottom: -40px;
        right: 120px;
        width: 160px;
        height: 160px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    .dash-hero .hero-icon {
        width: 72px;
        height: 72px;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #fff;
        backdrop-filter: blur(4px);
        flex-shrink: 0;
    }

    .dash-hero .time-badge {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 10px 18px;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* ── Stat Cards ───────────────────────────────────────── */
    .stat-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-card .card-body {
        padding: 22px 24px;
    }

    .stat-card .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .stat-card .stat-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 2px;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
        color: #0f172a;
    }

    .stat-card .stat-sub {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 4px;
    }

    .stat-card .stat-bar {
        height: 4px;
        border-radius: 2px;
        margin-top: 14px;
        background: #f1f5f9;
        overflow: hidden;
    }

    .stat-card .stat-bar-fill {
        height: 100%;
        border-radius: 2px;
    }

    /* ── Quick Access ─────────────────────────────────────── */
    .quick-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: block;
    }

    .quick-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.1);
    }

    .quick-card .qc-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 14px;
    }

    .quick-card .qc-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
    }

    .quick-card .qc-sub {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── Status Section ───────────────────────────────────── */
    .status-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    .status-item {
        display: flex;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .status-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .status-item .si-label {
        font-size: 0.85rem;
        color: #475569;
        flex: 1;
    }

    .status-item .si-val {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.95rem;
    }

    /* ── Section Titles ───────────────────────────────────── */
    .section-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
        margin-bottom: 14px;
    }

    /* ── Period Badge ─────────────────────────────────────── */
    .period-chip {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 100px;
        gap: 6px;
    }

    /* ── Pulse animation ──────────────────────────────────── */
    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: .3
        }
    }

    .pulse {
        animation: pulse-dot 2s infinite;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Banner -->
<div class="dash-hero mb-4">
    <div class="d-flex align-items-center gap-4" style="position:relative;z-index:1;">
        <div class="hero-icon">
            <img src="<?= base_url('assets/images/logo-dkp-kepri.png') ?>" alt="Logo Kepri"
                style="max-width: 80%; max-height: 80%;">
        </div>
        <div class="flex-grow-1">
            <h2 class="text-white fw-bold mb-1" style="font-size:1.6rem;">Dashboard Admin Keuangan DKP</h2>
            <p class="mb-2 text-white" style="opacity:0.75;font-size:0.9rem;">Dinas Kelautan dan Perikanan — Sistem
                Manajemen Penggajian Pegawai</p>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="period-chip"><i class="fas fa-calendar-week"></i> Periode Aktif:
                    <?= esc($latestPeriod) ?></span>
                <span style="color:rgba(255,255,255,0.7);font-size:0.82rem;"><i
                        class="fas fa-circle pulse text-success me-1" style="font-size:0.5rem;"></i> Sistem Aktif</span>
            </div>
        </div>
        <div class="time-badge text-white text-end d-none d-lg-block">
            <div style="font-size:0.7rem;opacity:0.7;letter-spacing:1px;text-transform:uppercase;">Waktu Server</div>
            <div style="font-size:1.2rem;font-weight:700;"><?= date('d M Y') ?></div>
            <div style="font-size:0.85rem;opacity:0.8;" id="liveClock"><?= date('H:i') ?> WIB</div>
        </div>
    </div>
</div>

<!-- Main Stats Row -->
<div class="row g-3 mb-4">
    <!-- Total Pegawai -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <div class="stat-label">Total Pegawai</div>
                        <div class="stat-value"><?= number_format($countPegawai) ?></div>
                        <div class="stat-sub"><i class="fas fa-arrow-up text-success me-1"></i>Data aktif dalam sistem
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#eff6ff;">
                        <i class="fas fa-users" style="color:#2563eb;"></i>
                    </div>
                </div>
                <div class="stat-bar">
                    <div class="stat-bar-fill" style="width:100%;background:linear-gradient(90deg,#3b82f6,#60a5fa);">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Slip -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <div class="stat-label">Total Slip Gaji</div>
                        <div class="stat-value"><?= number_format($countSlip) ?></div>
                        <div class="stat-sub"><i class="fas fa-file-invoice-dollar text-success me-1"></i>Semua periode
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#f0fdf4;">
                        <i class="fas fa-file-alt" style="color:#16a34a;"></i>
                    </div>
                </div>
                <div class="stat-bar">
                    <div class="stat-bar-fill"
                        style="width:<?= min(100, round(($countSlip / max($countPegawai, 1)) * 10)) ?>%;background:linear-gradient(90deg,#22c55e,#4ade80);">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Draft -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <div class="stat-label">Status Draft</div>
                        <div class="stat-value" style="color:#d97706;"><?= number_format($countDraft) ?></div>
                        <div class="stat-sub"><i class="fas fa-hourglass-half text-warning me-1"></i>Belum diverifikasi
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#fffbeb;">
                        <i class="fas fa-clock" style="color:#d97706;"></i>
                    </div>
                </div>
                <div class="stat-bar">
                    <div class="stat-bar-fill"
                        style="width:<?= $countSlip > 0 ? min(100, round($countDraft / $countSlip * 100)) : 0 ?>%;background:linear-gradient(90deg,#f59e0b,#fcd34d);">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <div class="stat-label">Slip Disetujui</div>
                        <div class="stat-value" style="color:#059669;"><?= number_format($countApproved) ?></div>
                        <div class="stat-sub"><i class="fas fa-check-circle text-success me-1"></i>Siap dibayarkan</div>
                    </div>
                    <div class="stat-icon" style="background:#ecfdf5;">
                        <i class="fas fa-check-double" style="color:#059669;"></i>
                    </div>
                </div>
                <div class="stat-bar">
                    <div class="stat-bar-fill"
                        style="width:<?= $countSlip > 0 ? min(100, round($countApproved / $countSlip * 100)) : 0 ?>%;background:linear-gradient(90deg,#10b981,#34d399);">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row: Quick Access + Status + Info -->
<div class="row g-3">

    <!-- Quick Access Menu -->
    <div class="col-lg-5">
        <div class="section-title"><i class="fas fa-th me-2"></i>Akses Cepat</div>
        <div class="row g-3">
            <div class="col-6">
                <a href="<?= site_url('pegawai') ?>" class="quick-card card p-3">
                    <div class="qc-icon" style="background:#eff6ff;"><i class="fas fa-users" style="color:#2563eb;"></i>
                    </div>
                    <div class="qc-title">Data Pegawai</div>
                    <div class="qc-sub"><?= number_format($countPegawai) ?> pegawai</div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('generate-slip') ?>" class="quick-card card p-3">
                    <div class="qc-icon" style="background:#f0fdf4;"><i class="fas fa-calculator"
                            style="color:#16a34a;"></i></div>
                    <div class="qc-title">Generate Slip</div>
                    <div class="qc-sub">Buat slip periode baru</div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('slip') ?>" class="quick-card card p-3">
                    <div class="qc-icon" style="background:#fefce8;"><i class="fas fa-file-pdf"
                            style="color:#ca8a04;"></i></div>
                    <div class="qc-title">Slip Gaji</div>
                    <div class="qc-sub"><?= number_format($countSlip) ?> total slip</div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('laporan') ?>" class="quick-card card p-3">
                    <div class="qc-icon" style="background:#faf5ff;"><i class="fas fa-chart-bar"
                            style="color:#7c3aed;"></i></div>
                    <div class="qc-title">Laporan</div>
                    <div class="qc-sub">Rekap penggajian</div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('backup') ?>" class="quick-card card p-3">
                    <div class="qc-icon" style="background:#fff7ed;"><i class="fas fa-database"
                            style="color:#ea580c;"></i></div>
                    <div class="qc-title">Backup Data</div>
                    <div class="qc-sub">Kelola cadangan DB</div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('import-sipd') ?>" class="quick-card card p-3">
                    <div class="qc-icon" style="background:#f0f9ff;"><i class="fas fa-file-import"
                            style="color:#0284c7;"></i></div>
                    <div class="qc-title">Import SIPD</div>
                    <div class="qc-sub">Upload data SIPD</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="col-lg-4">
        <div class="section-title"><i class="fas fa-chart-pie me-2"></i>Status Slip Gaji</div>
        <div class="card status-card p-4 h-100">
            <div class="status-item">
                <div class="status-dot" style="background:#f59e0b;"></div>
                <span class="si-label">Draft (Menunggu)</span>
                <span class="si-val text-warning"><?= number_format($countDraft) ?></span>
            </div>
            <div class="status-item">
                <div class="status-dot" style="background:#3b82f6;"></div>
                <span class="si-label">Terverifikasi</span>
                <span class="si-val text-primary"><?= number_format($countVerified) ?></span>
            </div>
            <div class="status-item">
                <div class="status-dot" style="background:#10b981;"></div>
                <span class="si-label">Disetujui</span>
                <span class="si-val text-success"><?= number_format($countApproved) ?></span>
            </div>
            <div class="status-item">
                <div class="status-dot" style="background:#94a3b8;"></div>
                <span class="si-label">Total Keseluruhan</span>
                <span class="si-val"><?= number_format($countSlip) ?></span>
            </div>

            <?php
            $pct = $countSlip > 0 ? round($countApproved / $countSlip * 100) : 0;
            $pctDraft = $countSlip > 0 ? round($countDraft / $countSlip * 100) : 0;
            ?>
            <div class="mt-4 pt-3" style="border-top:1px solid #f1f5f9;">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Tingkat Persetujuan</span>
                    <span class="fw-bold text-success"><?= $pct ?>%</span>
                </div>
                <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden;">
                    <div
                        style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,#10b981,#34d399);border-radius:4px;transition:width 1s ease;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="col-lg-3">
        <div class="section-title"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</div>
        <div class="card status-card p-4 mb-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div
                    style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-user-shield text-white"></i>
                </div>
                <div>
                    <div class="small text-muted">Login sebagai</div>
                    <div class="fw-bold text-dark" style="font-size:0.9rem;">
                        <?= esc(session('full_name') ?? session('username') ?? 'Administrator') ?>
                    </div>
                    <span class="badge"
                        style="font-size:0.65rem;background:#ede9fe;color:#6d28d9;border-radius:6px;">Admin</span>
                </div>
            </div>
        </div>
        <div class="card status-card p-3">
            <div class="small text-muted mb-2 fw-bold">INFO CEPAT</div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Periode Aktif</span>
                    <span class="badge bg-primary rounded-pill"
                        style="font-size:0.7rem;"><?= esc($latestPeriod) ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Slip Perlu Aksi</span>
                    <span class="badge rounded-pill"
                        style="background:#fef9c3;color:#854d0e;font-size:0.7rem;"><?= number_format($countDraft) ?>
                        Draft</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Versi Sistem</span>
                    <span class="small fw-bold text-muted">CI4 v4.x</span>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Live clock
    function updateClock() {
        var now = new Date();
        var h = String(now.getHours()).padStart(2, '0');
        var m = String(now.getMinutes()).padStart(2, '0');
        var el = document.getElementById('liveClock');
        if (el) el.textContent = h + ':' + m + ' WIB';
    }
    setInterval(updateClock, 1000);
</script>
<?= $this->endSection() ?>
