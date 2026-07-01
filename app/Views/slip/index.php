<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* ── Hero Banner ── */
    .slip-hero {
        background: linear-gradient(135deg, #0077b6 0%, #023e8a 60%, #00b4d8 100%);
        border-radius: 16px;
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 119, 182, 0.35);
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
    .slip-hero .hero-inner {
        position: relative;
        z-index: 1;
    }
    .slip-hero h3 { font-size: 1.5rem; letter-spacing: 0.3px; }
    .slip-hero .hero-sub { font-size: 0.85rem; opacity: 0.8; }

    /* ── Stat Pills ── */
    .stat-pill {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 0.8rem;
        color: #fff;
        backdrop-filter: blur(4px);
    }

    /* ── Filter Card ── */
    .filter-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        border: none;
        padding: 20px 24px 16px;
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
        border-color: #0077b6;
        box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
    }
    .filter-divider { border-top: 1.5px dashed #e5e7eb; margin: 14px 0 10px; }

    /* ── Action Buttons ── */
    .btn-hero { border-radius: 9px; font-weight: 600; font-size: 0.85rem; padding: 8px 18px; transition: all 0.18s ease; }
    .btn-generate { background: rgba(255,255,255,0.18); border: 1.5px solid rgba(255,255,255,0.4); color: #fff; }
    .btn-generate:hover { background: rgba(255,255,255,0.3); color: #fff; }
    .btn-manual { background: #facc15; border: none; color: #1f2937; font-weight: 700; }
    .btn-manual:hover { background: #eab308; color: #1f2937; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(234,179,8,0.4); }

    .btn-dl { background: linear-gradient(135deg, #7c3aed, #8b5cf6); color: #fff; border: none; border-radius: 8px; }
    .btn-dl:hover { background: linear-gradient(135deg, #6d28d9, #7c3aed); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(124,58,237,0.35); }
    .btn-print { background: linear-gradient(135deg, #f59e0b, #facc15); color: #1f2937; border: none; border-radius: 8px; font-weight: 600; }
    .btn-print:hover { background: linear-gradient(135deg, #d97706, #f59e0b); color: #1f2937; transform: translateY(-1px); }
    .btn-filter { background: linear-gradient(135deg, #0077b6, #00b4d8); color: #fff; border: none; border-radius: 8px; }
    .btn-filter:hover { background: linear-gradient(135deg, #023e8a, #0077b6); color: #fff; }

    /* ── Table Styling ── */
    #slipTable { font-size: 0.875rem; }
    #slipTable thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 14px;
        white-space: nowrap;
    }
    #slipTable tbody tr { transition: background 0.12s; }
    #slipTable tbody tr:hover { background: #f0f9ff; }
    #slipTable tbody td { padding: 12px 14px; vertical-align: middle; white-space: nowrap; border-color: #f1f5f9; }
    .employee-name { font-weight: 700; color: #1e3a5f; }
    .nip-text { font-family: monospace; font-size: 0.8rem; color: #64748b; }
    .gaji-text { font-weight: 700; color: #0077b6; }

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
    .badge-rejected { background:#fef2f2; color:#dc2626; border: 1.5px solid #fecaca; }

    /* ── Action buttons in table ── */
    .action-group { display: flex; gap: 5px; align-items: center; }
    .btn-act { border-radius: 7px; font-size: 0.75rem; font-weight: 600; padding: 5px 10px; border: none; transition: all 0.15s; }
    .btn-act-pdf     { background:#eff6ff; color:#1d4ed8; } .btn-act-pdf:hover     { background:#1d4ed8; color:#fff; }
    .btn-act-preview { background:#f0fdf4; color:#15803d; } .btn-act-preview:hover { background:#15803d; color:#fff; }
    .btn-act-edit    { background:#fffbeb; color:#b45309; } .btn-act-edit:hover    { background:#b45309; color:#fff; }
    .btn-act-danger  { background:#fef2f2; color:#dc2626; } .btn-act-danger:hover  { background:#dc2626; color:#fff; }

    /* ── Checkbox ── */
    .table-checkbox { width: 15px; height: 15px; cursor: pointer; accent-color: #0077b6; }
    .dataTables_wrapper .dataTables_filter { display: none; } /* Hide DataTables search — we have our own */

    @media print {
        @page { size: landscape; margin: 0; } /* Set margin to 0 to remove browser URL/Date headers and footers */
        body { padding: 1.5cm !important; } /* Add padding back to body so content doesn't hit paper edge */

        .slip-hero, .filter-card, .dataTables_length, .dataTables_info, .dataTables_paginate, .dataTables_filter,
        #debug-icon, #debug-icon-link, #debug-bar, #toolbarContainer { display: none !important; }
        
        /* Reset containers to prevent clipping */
        .card, .table-responsive { 
            overflow: visible !important; 
            border: none !important; 
            box-shadow: none !important; 
            border-radius: 0 !important; 
            padding: 0 !important; 
            margin: 0 !important;
        }
        
        /* Table styles for print */
        #slipTable { width: 100% !important; border-collapse: collapse !important; table-layout: auto !important; margin: 0 !important; }
        #slipTable th, #slipTable td { 
            border: 1px solid #000 !important; 
            color: #000 !important; 
            white-space: normal !important; 
            padding: 8px !important; 
            font-size: 10pt !important;
        }
        
        /* Hide Checkbox (col 1) and Aksi (col 8/last) */
        #slipTable th:first-child, #slipTable td:first-child,
        #slipTable th:last-child, #slipTable td:last-child { display: none !important; }
        
        /* Badges */
        .badge-status { border: none !important; background: transparent !important; color: #000 !important; padding: 0 !important; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$sessionError = session()->getFlashdata('error');
$_flash_slip  = session()->getFlashdata('slip_message');
$_slip_err    = session()->getFlashdata('slip_error');
$isCsrf       = $sessionError === 'The action you requested is not allowed.';
// CI4 otomatis menghapus flashdata setelah dibaca
?>

<?php if ($_flash_slip): ?>
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" data-flash-token="<?= uniqid('sl_msg_') ?>" role="alert" style="border-radius:10px; border-left: 4px solid #2bb673 !important;">
    <i class="fas fa-check-circle me-2 text-success"></i><strong><?= esc($_flash_slip) ?></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($sessionError && !$isCsrf): ?>
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" data-flash-token="<?= uniqid('sl_err_') ?>" role="alert" style="border-radius:10px; border-left: 4px solid #ef4444 !important;">
    <i class="fas fa-exclamation-circle me-2"></i><?= esc($sessionError) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($_slip_err): ?>
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" data-flash-token="<?= uniqid('slip_err_') ?>" role="alert" style="border-radius:10px; border-left: 4px solid #ef4444 !important;">
    <i class="fas fa-exclamation-circle me-2"></i><?= esc($_slip_err) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>


<!-- ══════════ HERO BANNER ══════════ -->
<div class="slip-hero mb-4">
    <div class="hero-inner d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <div style="width:46px;height:46px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-file-invoice-dollar text-white fa-lg"></i>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold text-white">Manajemen Slip Gaji</h3>
                    <span class="hero-sub text-white">Kelola dan pantau slip gaji seluruh pegawai</span>
                </div>
            </div>
            <div class="d-flex gap-2 mt-2">
                <span class="stat-pill"><i class="fas fa-users me-1"></i> <?= count($rows) ?> Slip</span>
                <span class="stat-pill"><i class="fas fa-calendar me-1"></i> <?= date('Y') ?></span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('generate-slip') ?>" class="btn btn-hero btn-generate">
                <i class="fas fa-magic me-1"></i> Generate Slip
            </a>
            <a href="<?= site_url('slip/create') ?>" class="btn btn-hero btn-manual">
                <i class="fas fa-plus me-1"></i> Tambah Manual
            </a>
        </div>
    </div>
</div>

<!-- ══════════ FILTER CARD ══════════ -->
<div class="filter-card">
    <form method="get" id="filterForm">
        <div class="d-flex justify-content-between flex-wrap gap-3">
            <!-- Filter Group (Kiri) -->
            <div class="d-flex gap-3 align-items-end flex-wrap">
                <div style="min-width: 180px;">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.78rem; letter-spacing:0.4px;">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i> PERIODE
                    </label>
                    <select name="periode_id" class="form-select">
                        <option value="0">🗓 Semua Periode</option>
                        <?php foreach($periodes as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= (int)$selectedPeriodeId === (int)$p['id'] ? 'selected' : '' ?>>
                            <?= esc($p['nama_periode'] ?? ($p['bulan'].'/'.$p['tahun'])) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="min-width: 140px;">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.78rem; letter-spacing:0.4px;">
                        <i class="fas fa-info-circle me-1 text-primary"></i> STATUS
                    </label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" <?= ($selectedStatus ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="verified" <?= ($selectedStatus ?? '') === 'verified' ? 'selected' : '' ?>>Verified</option>
                        <option value="approved" <?= ($selectedStatus ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($selectedStatus ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="paid" <?= ($selectedStatus ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                    </select>
                </div>
                
                <div style="min-width: 250px; flex-grow: 1;">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.78rem; letter-spacing:0.4px;">
                        <i class="fas fa-search me-1 text-primary"></i> CARI PEGAWAI
                    </label>
                    <div class="input-group" style="border-radius:8px; overflow:hidden;">
                        <span class="input-group-text bg-white" style="border:1.5px solid #e5e7eb; border-right:0;">
                            <i class="fas fa-user text-muted" style="font-size:0.85rem;"></i>
                        </span>
                        <input type="text" name="keyword" class="form-control" id="keywordInput"
                               style="border:1.5px solid #e5e7eb; border-left:0;"
                               placeholder="Ketik nama / NIP..."
                               value="<?= esc($keyword ?? '') ?>">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-filter fw-semibold px-4" style="height:38px;">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="<?= site_url('slip') ?>" class="btn btn-outline-secondary fw-semibold px-3" style="height:38px; border-radius:8px; font-size:0.875rem;">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>

            <!-- Action Select Group (Kanan) -->
            <div class="d-flex gap-2 align-items-end">
                <a href="<?= site_url('generate-slip/bulk' . ((int)$selectedPeriodeId > 0 ? '?periode_id=' . $selectedPeriodeId : '')) ?>" class="btn btn-dl fw-semibold px-3 d-flex align-items-center shadow-sm" style="height:38px; font-size:0.85rem;">
                    <i class="fas fa-download me-2"></i> Download Semua
                </a>
                <button type="button" class="btn btn-print fw-semibold px-3 d-flex align-items-center shadow-sm" style="height:38px; font-size:0.85rem;" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> Cetak Gaji
                </button>
            </div>
        </div>
        
        <div class="filter-divider mt-3"></div>
        <div class="text-muted" style="font-size:0.8rem;">
            <?php if (!empty($keyword ?? '')): ?>
                <i class="fas fa-info-circle me-1 text-primary"></i>
                Hasil pencarian untuk: <strong>"<?= esc($keyword) ?>"</strong> — <?= count($rows) ?> data ditemukan
            <?php else: ?>
                <i class="fas fa-list me-1"></i> Total <?= count($rows) ?> slip gaji ditampilkan
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ══════════ DATA TABLE ══════════ -->
<div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
    <div class="table-responsive">
        <table id="slipTable" class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3" style="width:42px;">
                        <input type="checkbox" class="table-checkbox" id="selectAll">
                    </th>
                    <th style="width:44px;">#</th>
                    <th>Nama Pegawai</th>
                    <th>NIP</th>
                    <th>Periode</th>
                    <th>Gaji Bersih</th>
                    <th>Status</th>
                    <th style="width:200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $i => $row): ?>
                <?php
                    $st = strtolower($row['status'] ?? 'draft');
                    switch ($st) {
                        case 'verified': $badgeClass = 'badge-verified'; break;
                        case 'approved': $badgeClass = 'badge-approved'; break;
                        case 'paid':     $badgeClass = 'badge-paid';     break;
                        case 'rejected': $badgeClass = 'badge-rejected';  break;
                        default:         $badgeClass = 'badge-draft';
                    }
                ?>
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="table-checkbox row-checkbox" value="<?= $row['id'] ?>">
                    </td>
                    <td class="text-muted fw-semibold"><?= $i+1 ?></td>
                    <td><span class="employee-name"><?= esc($row['nama']) ?></span></td>
                    <td><span class="nip-text"><?= esc($row['nip']) ?></span></td>
                    <td><?= esc($row['nama_periode'] ?? ($row['bulan'].'/'.$row['tahun'])) ?></td>
                    <td><span class="gaji-text"><?= rupiah((float)($row['gaji_bersih'] ?? 0)) ?></span></td>
                    <td><span class="badge-status <?= $badgeClass ?>"><?= esc(strtoupper($row['status'] ?? 'DRAFT')) ?></span></td>
                    <td>
                        <div class="action-group">
                            <a href="<?= site_url('generate-slip/download/'.$row['id']) ?>" class="btn btn-act btn-act-pdf" title="Download PDF">
                                <i class="fas fa-download me-1"></i>PDF
                            </a>
                            <button type="button" class="btn btn-act btn-act-preview btn-preview-modal" data-id="<?= $row['id'] ?>" title="Preview PDF">
                                <i class="fas fa-eye me-1"></i>Preview
                            </button>
                            <a href="<?= site_url('slip/edit/'.$row['id']) ?>" class="btn btn-act btn-act-edit" title="Edit Slip">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <?php if (in_array(strtolower($row['status'] ?? 'draft'), ['draft', 'rejected'])): ?>
                            <button type="button" class="btn btn-act btn-act-danger btn-hapus-slip" data-id="<?= $row['id'] ?>" data-nama="<?= esc($row['nama'], 'js') ?>" data-periode="<?= esc($row['nama_periode'] ?? ($row['bulan'].'/'.$row['tahun']), 'js') ?>" title="Hapus Slip">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
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

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    var table = $('#slipTable').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        columnDefs: [{ orderable: false, targets: [0, 7] }],
        drawCallback: function () {
            bindDeleteButtons();
        },
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Semua"]
        ],
        dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rtip',
    });

    // Client-side search box sync with DataTables
    $('#keywordInput').on('keyup', function () {
        // Let server-side handle it via form submit, DataTables for inline
    });

    // Select all
    $('#selectAll').on('change', function () {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
    });
    $(document).on('change', '.row-checkbox', function () {
        var total = $('.row-checkbox').length;
        var checked = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', total === checked);
    });

    // Delete buttons
    function bindDeleteButtons() {
        document.querySelectorAll('.btn-hapus-slip').forEach(btn => {
            btn.removeEventListener('click', showDeleteConfirm);
            btn.addEventListener('click', showDeleteConfirm);
        });
    }
    function showDeleteConfirm(e) {
        const btn = e.currentTarget;
        const id = btn.getAttribute('data-id');
        const nama = btn.getAttribute('data-nama');
        const periode = btn.getAttribute('data-periode');
        document.getElementById('lblNamaSlipHapus').textContent = nama + ' - ' + periode;
        document.getElementById('formHapusSlip').action = '<?= site_url('slip/delete') ?>/' + id;
        const modalEl = document.getElementById('modalHapusSlip');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
    bindDeleteButtons();

    // Preview Modal
    const modalEl = document.getElementById('modalPreviewSlip');
    if (modalEl) {
        const modalPreview = new bootstrap.Modal(modalEl);
        const iframePreview = document.getElementById('iframePreview');
        const btnDL = document.getElementById('btnDownloadPreview');

        document.querySelectorAll('.btn-preview-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                iframePreview.src = 'about:blank';
                setTimeout(() => {
                    iframePreview.src = '<?= site_url('generate-slip/view/') ?>' + id + '#toolbar=0&navpanes=0';
                }, 120);
                btnDL.href = '<?= site_url('generate-slip/download/') ?>' + id;
                modalPreview.show();
            });
        });
    }
});
</script>

<!-- Modal Hapus Slip -->
<div class="modal fade" id="modalHapusSlip" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-trash me-2"></i> Hapus Slip Gaji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3"><i class="fas fa-file-invoice text-danger" style="font-size:3.5rem; opacity:0.8;"></i></div>
                <h5 class="fw-bold text-dark mb-2">Hapus Slip Ini?</h5>
                <p class="text-muted mb-0">Slip gaji <strong id="lblNamaSlipHapus" class="text-danger"></strong> akan dihapus secara permanen.</p>
                <div class="alert alert-warning mt-3 text-start small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i> Tindakan ini tidak dapat dibatalkan.
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #fecaca;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                <form id="formHapusSlip" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger fw-bold shadow-sm">
                        <i class="fas fa-trash me-1"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="modalPreviewSlip" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white border-0" style="background: linear-gradient(135deg,#0077b6,#00b4d8); padding: 16px 24px;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-pdf me-2"></i> Preview Slip Gaji
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="background:#e5e7eb;">
                <iframe id="iframePreview" src="" style="width:100%; height:78vh; border:none; display:block;"></iframe>
            </div>
            <div class="modal-footer border-0" style="background:#f8fafc; padding:12px 20px;">
                <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal" style="border-radius:8px;">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <a id="btnDownloadPreview" href="#" class="btn fw-bold text-white" style="background:linear-gradient(135deg,#0077b6,#00b4d8); border-radius:8px; border:none;">
                    <i class="fas fa-download me-1"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
