<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* ── Hero Banner ── */
    .slip-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 60%, #312e81 100%);
        border-radius: 16px;
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.35);
        margin-bottom: 20px;
    }
    .slip-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .slip-hero::after {
        content: '';
        position: absolute;
        bottom: -30px; right: 80px;
        width: 130px; height: 130px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .slip-hero .hero-inner { position: relative; z-index: 1; }
    .slip-hero h3 { font-size: 1.5rem; letter-spacing: 0.3px; }
    .slip-hero .hero-sub { font-size: 0.85rem; opacity: 0.9; }

    /* ── Filter Card ── */
    .filter-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        border: none;
        padding: 20px 24px;
        margin-bottom: 20px;
    }
    .filter-card .form-select,
    .filter-card .form-control {
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        font-size: 0.875rem;
        height: 38px;
    }
    .filter-card .form-select:focus,
    .filter-card .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .btn-filter { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; border: none; border-radius: 8px; font-weight: 600;}
    .btn-filter:hover { background: linear-gradient(135deg, #4338ca, #4f46e5); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35); }

    /* ── Stat Cards ── */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.05);
        border: none;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; justify-content: center; align-items: center;
        font-size: 1.5rem;
    }
    .icon-blue { background: #e0e7ff; color: #4f46e5; }
    .icon-red { background: #fee2e2; color: #dc2626; }
    .icon-green { background: #dcfce7; color: #15803d; }
    
    .stat-details .stat-title { font-size: 0.8rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .stat-details .stat-value { font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0; line-height: 1.2; }
    .stat-details .val-red { color: #dc2626; }
    .stat-details .val-green { color: #059669; }

    /* ── Table Styling ── */
    .table-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        padding: 20px 24px;
        border: none;
    }
    .table { font-size: 0.875rem; margin-bottom: 0; }
    .table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 14px;
        white-space: nowrap;
        border-top: none;
    }
    .table tbody tr { transition: background 0.12s; }
    .table tbody tr:hover { background: #f0f9ff; }
    .table tbody td { padding: 12px 14px; vertical-align: middle; white-space: nowrap; border-color: #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    .employee-name { font-weight: 700; color: #1e3a5f; }
    
    /* ── Status Badges ── */
    .badge-status {
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        text-transform: uppercase;
    }
    .badge-draft    { background:#f1f5f9; color:#64748b; border: 1.5px solid #cbd5e1; }
    .badge-verified { background:#e0f2fe; color:#0369a1; border: 1.5px solid #bae6fd; }
    .badge-approved { background:#dbeafe; color:#1d4ed8; border: 1.5px solid #bfdbfe; }
    .badge-paid     { background:#dcfce7; color:#15803d; border: 1.5px solid #bbf7d0; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Banner -->
<div class="slip-hero mb-4">
    <div class="hero-inner d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="d-flex align-items-center mb-0">
            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px;">
                <i class="fas fa-chart-line text-white fs-4"></i>
            </div>
            <div>
                <h3 class="text-white mb-1 fw-bold">Laporan Payroll</h3>
                <div class="text-white hero-sub">
                    Rangkuman pembayaran gaji, potongan, dan statistik bulanan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="filter-card">
    <form method="get" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label text-muted fw-bold" style="font-size:0.75rem; letter-spacing:0.5px;">
                <i class="far fa-calendar-alt me-1"></i> PERTAMA PILIH PERIODE
            </label>
            <select name="periode_id" class="form-select shadow-sm">
                <option value="0">Semua Periode</option>
                <?php foreach($periodes as $p): ?>
                <option value="<?= $p['id'] ?>" <?= (int)$selectedPeriodeId === (int)$p['id'] ? 'selected' : '' ?>><?= esc($p['nama_periode'] ?? ($p['bulan'].'/'.$p['tahun'])) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-filter w-100 shadow-sm">
                <i class="fas fa-search me-1"></i> Tampilkan
            </button>
        </div>
    </form>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-details">
                <div class="stat-title">Total Slip</div>
                <div class="stat-value"><?= number_format($summary['total_slip']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon icon-red">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-details">
                <div class="stat-title">Total Potongan</div>
                <div class="stat-value val-red"><?= rupiah($summary['total_potongan']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon icon-green">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <div class="stat-details">
                <div class="stat-title">Total Gaji Bersih</div>
                <div class="stat-value val-green"><?= rupiah($summary['total_bersih']) ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="table-container mb-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">#</th>
                    <th>Periode</th>
                    <th>Nama Pegawai</th>
                    <th>Total Pendapatan</th>
                    <th>Total Potongan</th>
                    <th>Gaji Bersih</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $i => $row): ?>
                <tr>
                    <td class="text-center text-muted"><?= $i+1 ?></td>
                    <td><?= esc($row['nama_periode'] ?? ($row['bulan'].'/'.$row['tahun'])) ?></td>
                    <td class="employee-name"><?= esc($row['nama']) ?></td>
                    <td class="text-muted fw-bold"><?= rupiah((float)($row['total_pendapatan'] ?? 0)) ?></td>
                    <td class="text-danger fw-bold"><?= rupiah((float)($row['total_potongan'] ?? 0)) ?></td>
                    <td class="val-green fw-bold" style="color: #059669;"><?= rupiah((float)($row['gaji_bersih'] ?? 0)) ?></td>
                    <td class="text-center">
                        <span class="badge-status badge-<?= strtolower($row['status'] ?? 'draft') ?>">
                            <?= esc($row['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; if(!$rows): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-chart-bar fs-1 mb-3 opacity-25"></i>
                        <br>Belum ada data laporan untuk periode ini.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($pager) && $pager): ?>
<div class="mt-3 d-flex justify-content-end">
    <?= $pager->links('default', 'bootstrap5') ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
