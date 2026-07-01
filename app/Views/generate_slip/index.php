<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* ── Hero Banner ── */
    .slip-hero {
        background: linear-gradient(135deg, #0077b6 0%, #023e8a 60%, #00b4d8 100%);
        border-radius: 16px;
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 119, 182, 0.35);
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
    .slip-hero .hero-inner {
        position: relative;
        z-index: 1;
    }
    .slip-hero h3 { font-size: 1.5rem; letter-spacing: 0.3px; }
    .slip-hero .hero-sub { font-size: 0.85rem; opacity: 0.8; }

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
    .btn-generate:hover { background: rgba(255,255,255,0.3); color: #fff; transform: translateY(-1px); }
    
    .btn-filter { background: linear-gradient(135deg, #0077b6, #00b4d8); color: #fff; border: none; border-radius: 8px; }
    .btn-filter:hover { background: linear-gradient(135deg, #023e8a, #0077b6); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,119,182,0.35); }

    /* ── Table Styling ── */
    .table-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        padding: 20px 24px;
        border: none;
    }
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

    /* ── Action buttons in table ── */
    .action-group { display: flex; gap: 5px; align-items: center; }
    .btn-act { border-radius: 7px; font-size: 0.75rem; font-weight: 600; padding: 5px 10px; border: none; transition: all 0.15s; }
    .btn-act-pdf     { background:#eff6ff; color:#1d4ed8; } .btn-act-pdf:hover     { background:#1d4ed8; color:#fff; }
    .btn-act-preview { background:#f0fdf4; color:#15803d; } .btn-act-preview:hover { background:#15803d; color:#fff; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Banner -->
<div class="slip-hero mb-4">
    <div class="hero-inner d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px;">
                <i class="fas fa-magic text-white fs-4"></i>
            </div>
            <div>
                <h3 class="text-white mb-1 fw-bold">Generate Slip Gaji</h3>
                <div class="text-white hero-sub">
                    Buat dan unduh dokumen slip gaji dalam format PDF untuk pegawai
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <?php if($rows): ?>
            <a href="<?= site_url('generate-slip/bulk?' . http_build_query(['periode_id'=>$selectedPeriodeId])) ?>" class="btn btn-hero btn-generate d-flex align-items-center">
                <i class="fas fa-file-archive me-2"></i> Generate ZIP
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="filter-card mb-4">
    <form method="get" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label text-muted fw-bold" style="font-size:0.75rem; letter-spacing:0.5px;">
                <i class="far fa-calendar-alt me-1"></i> PERIODE
            </label>
            <select name="periode_id" class="form-select shadow-sm">
                <option value="0">Semua Periode</option>
                <?php foreach($periodes as $p): ?>
                <option value="<?= $p['id'] ?>" <?= (int)$selectedPeriodeId === (int)$p['id'] ? 'selected' : '' ?>><?= esc($p['nama_periode'] ?? ($p['bulan'].'/'.$p['tahun'])) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label text-muted fw-bold" style="font-size:0.75rem; letter-spacing:0.5px;">
                <i class="fas fa-search me-1"></i> CARI PEGAWAI
            </label>
            <input type="text" class="form-control shadow-sm" placeholder="Ketik nama atau NIP pegawai..." name="search" value="<?= esc($search) ?>">
        </div>
        <div class="col-md-3">
            <button class="btn btn-filter w-100 shadow-sm fw-bold">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Table Data -->
<div class="table-container mb-4">
    <div class="table-responsive">
        <table id="slipTable" class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">#</th>
                    <th>Periode</th>
                    <th>Nama Pegawai</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Gaji Bersih</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $i => $row): ?>
                <tr>
                    <td class="text-center text-muted"><?= $i+1 ?></td>
                    <td><?= esc($row['nama_periode'] ?? ($row['bulan'].'/'.$row['tahun'])) ?></td>
                    <td class="employee-name"><?= esc($row['nama']) ?></td>
                    <td class="nip-text"><?= esc($row['nip']) ?></td>
                    <td><div class="text-truncate" style="max-width: 180px;" title="<?= esc($row['pegawai_jabatan'] ?? $row['nama_jabatan'] ?? '-') ?>"><?= esc($row['pegawai_jabatan'] ?? $row['nama_jabatan'] ?? '-') ?></div></td>
                    <td class="gaji-text"><?= rupiah((float)($row['gaji_bersih'] ?? 0)) ?></td>
                    <td class="text-center">
                        <span class="badge-status badge-<?= strtolower($row['status'] ?? 'draft') ?>">
                            <?= esc($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-group justify-content-center">
                            <button type="button" class="btn-act btn-act-preview btn-preview-modal shadow-sm" data-id="<?= $row['id'] ?>" title="Preview">
                                <i class="fas fa-eye me-1"></i> Preview
                            </button>
                            <a class="btn-act btn-act-pdf shadow-sm text-decoration-none" href="<?= site_url('generate-slip/download/' . $row['id']) ?>" title="Download PDF">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; if(!$rows): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-file-invoice-dollar fs-1 mb-3 opacity-25"></i>
                        <br>Belum ada slip untuk digenerate pada periode ini.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?= $pager->links('default', 'bootstrap5') ?>
    </div>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="modalPreviewSlip" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-eye me-2"></i> Preview Slip Gaji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background:#e5e7eb;">
                <iframe id="iframePreview" src="" style="width: 100%; height: 78vh; border: none; display: block;"></iframe>
            </div>
            <div class="modal-footer" style="background:#f3f4f6;">
                <button type="button" class="btn btn-secondary fw-bold shadow-sm" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Tutup</button>
                <a id="btnDownloadPreview" href="#" class="btn btn-info fw-bold text-white shadow-sm"><i class="fas fa-download me-1"></i> Download PDF</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewButtons = document.querySelectorAll('.btn-preview-modal');
    const modalPreview = new bootstrap.Modal(document.getElementById('modalPreviewSlip'));
    const iframePreview = document.getElementById('iframePreview');
    const btnDownloadPreview = document.getElementById('btnDownloadPreview');

    previewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            // Show loading placeholder while PDF generates
            iframePreview.src = 'about:blank';
            
            setTimeout(() => {
                iframePreview.src = '<?= site_url('generate-slip/view/') ?>' + id + '#toolbar=0&navpanes=0&scrollbar=0';
            }, 100);
            
            btnDownloadPreview.href = '<?= site_url('generate-slip/download/') ?>' + id;
            modalPreview.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
