<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .bnd-container { font-family: 'Outfit', sans-serif; }
    .bnd-header {
        background: linear-gradient(135deg, #021a3a 0%, #004e89 100%);
        border-radius: 20px; padding: 28px 30px; color: white;
        margin-bottom: 24px; box-shadow: 0 15px 30px rgba(0,78,137,0.15);
        position: relative; overflow: hidden;
    }
    .bnd-header::after { content:''; position:absolute; right:-5%; top:-50%; width:300px; height:300px; background:radial-gradient(circle,rgba(0,180,216,0.15) 0%,transparent 70%); border-radius:50%; }
    .bnd-title  { font-weight:700; margin:0; font-size:1.8rem; }
    .bnd-subtitle { color:rgba(255,255,255,0.7); font-size:0.9rem; margin-top:5px; }

    /* Filter bar */
    .filter-bar { background:white; border-radius:14px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; box-shadow:0 4px 12px rgba(0,0,0,0.04); border:1px solid #f1f5f9; }
    .filter-bar select { border:1px solid #e2e8f0; border-radius:8px; padding:7px 12px; font-size:0.88rem; color:#1e293b; outline:none; }
    .filter-bar .btn-filter { background:linear-gradient(135deg,#0077b6,#0096c7); color:white; border:none; border-radius:8px; padding:8px 18px; font-weight:600; font-size:0.85rem; cursor:pointer; }

    /* Bulk toolbar */
    .bulk-toolbar { background:linear-gradient(135deg,#f59e0b10,#fef3c7); border:1.5px solid #fde68a; border-radius:12px; padding:12px 20px; margin-bottom:16px; display:none; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .bulk-toolbar.show { display:flex; }
    .bulk-count { font-weight:700; color:#d97706; font-size:0.9rem; }
    .btn-bulk-verify { background:linear-gradient(135deg,#f59e0b,#d97706); color:white; border:none; border-radius:8px; padding:8px 20px; font-weight:700; font-size:0.85rem; cursor:pointer; box-shadow:0 4px 10px rgba(245,158,11,0.3); }

    .bnd-card { background:white; border-radius:20px; border:1px solid #f1f5f9; box-shadow:0 10px 30px rgba(0,0,0,0.03); padding:25px; }
    .card-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .btn-select-all { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; padding:6px 14px; font-size:0.82rem; font-weight:600; color:#475569; cursor:pointer; }

    .custom-table { width:100%; border-collapse:separate; border-spacing:0 8px; }
    .custom-table thead th { border:none; color:#64748b; font-weight:600; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.5px; padding:8px 14px; }
    .custom-table tbody tr { box-shadow:0 3px 8px rgba(0,0,0,0.02); transition:all 0.2s; }
    .custom-table tbody tr:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,180,216,0.08); }
    .custom-table tbody tr.row-selected { background:#fffbeb !important; box-shadow:0 0 0 2px #f59e0b; }
    .custom-table tbody td { background:#fff; padding:14px 16px; border:1px solid #f8fafc; border-style:solid none; vertical-align:middle; color:#334155; font-size:0.9rem; font-weight:500; }
    .custom-table tbody td:first-child { border-left:1px solid #f8fafc; border-radius:12px 0 0 12px; }
    .custom-table tbody td:last-child { border-right:1px solid #f8fafc; border-radius:0 12px 12px 0; }
    .custom-table tbody tr.row-selected td { background:#fffbeb; }

    .status-badge { display:inline-block; padding:5px 12px; border-radius:8px; font-size:0.78rem; font-weight:600; background:#fef3c7; color:#d97706; border:1px solid #fde68a; }

    .btn-verify { background:linear-gradient(135deg,#f59e0b,#d97706); color:white; border:none; border-radius:8px; padding:7px 14px; font-weight:600; font-size:0.82rem; transition:all 0.25s; box-shadow:0 3px 8px rgba(245,158,11,0.25); }
    .btn-verify:hover { transform:translateY(-2px); box-shadow:0 6px 15px rgba(245,158,11,0.4); color:white; }

    .check-slip { width:16px; height:16px; accent-color:#f59e0b; cursor:pointer; }

    .empty-state { text-align:center; padding:50px 20px; }
    .empty-icon { font-size:4rem; color:#e2e8f0; margin-bottom:16px; }

    .count-badge { background:#fef3c7; color:#d97706; border:1px solid #fde68a; border-radius:8px; padding:4px 12px; font-size:0.82rem; font-weight:700; }

    /* ── Histori ringkas ── */
    .histori-panel { background:white; border-radius:16px; border:1px solid #f1f5f9; box-shadow:0 6px 18px rgba(0,0,0,0.03); padding:20px 24px; margin-top:24px; }
    .histori-panel-title { font-weight:700; color:#1e293b; font-size:0.9rem; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between; }
    .histori-row { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px dashed #f1f5f9; }
    .histori-row:last-child { border-bottom:none; }
    .h-icon { width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:0.82rem; flex-shrink:0; }
    .h-main { font-size:0.83rem; font-weight:600; color:#334155; }
    .h-sub  { font-size:0.75rem; color:#94a3b8; margin-top:2px; }
    .h-time { font-size:0.72rem; color:#cbd5e1; white-space:nowrap; margin-left:auto; padding-left:8px; }
    .h-bulk-tag { background:#f3e8ff; color:#7c3aed; font-size:0.68rem; font-weight:700; padding:1px 7px; border-radius:20px; margin-left:6px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bnd-container">

    <div class="bnd-header">
        <h3 class="bnd-title"><i class="fas fa-clipboard-check me-2" style="color:#48cae4;"></i> Verifikasi Slip Gaji</h3>
        <div class="bnd-subtitle">Pilih satu atau beberapa slip draft untuk diverifikasi. Gunakan filter periode untuk menyaring data.</div>
    </div>

    <?php
    $_flash_msg = session()->getFlashdata('vrf_msg');
    $_flash_err = session()->getFlashdata('vrf_err');
    ?>
    <?php if ($_flash_msg): ?>
        <div class="alert alert-success alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('vrf_msg_') ?>" style="border-radius:12px; background:#d1fae5; color:#065f46;">
            <i class="fas fa-check-circle me-2"></i> <?= esc($_flash_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($_flash_err): ?>
        <div class="alert alert-danger alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('vrf_err_') ?>" style="border-radius:12px;">
            <i class="fas fa-exclamation-circle me-2"></i> <?= esc($_flash_err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <form method="get" action="<?= base_url('bendahara/verifikasi') ?>" class="filter-bar">
        <i class="fas fa-filter" style="color:#94a3b8;"></i>
        <label style="font-size:0.85rem; color:#64748b; font-weight:600;">Filter Periode:</label>
        <select name="periode_id" onchange="this.form.submit()">
            <option value="0">– Semua Periode –</option>
            <?php foreach($periodes as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $selectedPeriodeId == $p['id'] ? 'selected' : '' ?>>
                    <?= esc($p['nama_periode'] ?? ($p['bulan'].'/'.$p['tahun'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if($selectedPeriodeId > 0): ?>
            <a href="<?= base_url('bendahara/verifikasi') ?>" style="font-size:0.8rem; color:#94a3b8;">✕ Reset</a>
        <?php endif; ?>
        <span class="count-badge"><i class="fas fa-layer-group me-1"></i><?= count($rows) ?> Slip Ditemukan</span>
    </form>

    <div class="bnd-card">
        <?php if(empty($rows)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-check-double"></i></div>
                <h5 class="fw-bold text-dark">Tidak Ada Slip Draft!</h5>
                <p class="text-muted">Semua slip pada periode yang dipilih sudah terverifikasi.<br>Silakan pilih periode lain atau tunggu generate slip baru.</p>
            </div>
        <?php else: ?>
            <!-- Bulk action form wraps everything -->
            <form method="post" action="<?= base_url('bendahara/verifikasi') ?>" id="bulkForm">
                <?= csrf_field() ?>
                <div class="card-toolbar">
                    <div style="font-weight:700; color:#1e293b; font-size:0.95rem;"><i class="fas fa-list-ul me-2" style="color:#f59e0b;"></i> Daftar Slip Gaji Draft</div>
                    <button type="button" class="btn-select-all" onclick="toggleSelectAll()" id="selectAllBtn">
                        <i class="fas fa-check-square me-1"></i> Pilih Semua
                    </button>
                </div>

                <!-- Bulk toolbar (muncul saat ada yang dipilih) -->
                <div class="bulk-toolbar" id="bulkToolbar">
                    <div class="bulk-count"><i class="fas fa-check-square me-2"></i><span id="selectedCount">0</span> slip dipilih</div>
                    <button type="button" class="btn-bulk-verify" onclick="showBulkVerifyModal()">
                        <i class="fas fa-check-double me-2"></i> Verifikasi Semua Dipilih
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" id="masterCheck" class="check-slip" onclick="toggleSelectAll()"></th>
                                <th width="18%">Periode</th>
                                <th width="30%">Nama Pegawai</th>
                                <th width="18%">Gaji Bersih</th>
                                <th width="10%">Status</th>
                                <th width="20%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rows as $i => $row): ?>
                                <tr id="row-<?= $row['id'] ?>">
                                    <td><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="check-slip row-check" onchange="updateBulkBar()"></td>
                                    <td><span style="color:#0077b6;"><i class="far fa-calendar-alt me-1"></i><?= esc($row['nama_periode'] ?? ($row['bulan'].'/'.$row['tahun'])) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; flex-shrink:0; color:#64748b;"><i class="fas fa-user" style="font-size:0.75rem;"></i></div>
                                            <span><?= esc($row['nama']) ?></span>
                                        </div>
                                    </td>
                                    <td><span class="fw-bold" style="color:#16a34a;"><?= rupiah((float)($row['gaji_bersih'] ?? 0)) ?></span></td>
                                    <td><div class="status-badge"><i class="fas fa-circle me-1" style="font-size:0.4rem; vertical-align:middle;"></i> Draft</div></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <!-- Tombol Preview -->
                                            <button type="button" class="btn btn-sm btn-outline-info btn-preview-modal m-0" style="padding:0.4rem 0.6rem; border-radius:8px;" data-id="<?= $row['id'] ?>" title="Preview PDF">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Tombol Verifikasi -->
                                            <button type="button" class="btn-verify m-0" style="padding:0.4rem 0.8rem; border-radius:8px;" onclick="verifySingle(<?= $row['id'] ?>)">
                                                <i class="fas fa-check me-1"></i> Verifikasi
                                            </button>
                                            
                                            <!-- Tombol Revisi -->
                                            <button type="button" class="btn btn-sm m-0" style="background:#fef2f2; color:#dc2626; border:1px solid #f87171; font-weight:600; padding:0.4rem 0.8rem; border-radius:8px;" onclick="openRejectModal(<?= $row['id'] ?>)">
                                                <i class="fas fa-undo me-1"></i> Revisi
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $pager->links('default', 'bootstrap5') ?>
                </div>
                <!-- Hidden single-verify field -->
                <input type="hidden" name="pay_id" id="singlePayId" value="">
                <input type="hidden" name="action_type" id="actionType" value="verify">
                <input type="hidden" name="alasan_revisi" id="alasanRevisi" value="">
            </form>
        <?php endif; ?>
    </div>

    <!-- ── Tabel Terverifikasi (dapat dikembalikan ke Draft) ── -->
    <?php if (!empty($verifiedRows)): ?>
    <div class="bnd-card mt-4">
        <div style="font-weight:700; color:#1e293b; font-size:0.95rem; margin-bottom:14px;">
            <i class="fas fa-check-circle me-2" style="color:#16a34a;"></i> Slip Terverifikasi (dapat dikembalikan ke Draft)
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="18%">Periode</th>
                        <th width="30%">Nama Pegawai</th>
                        <th width="18%">Gaji Bersih</th>
                        <th width="10%">Status</th>
                        <th width="20%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($verifiedRows as $row): ?>
                    <tr>
                        <td><span style="color:#0077b6;"><i class="far fa-calendar-alt me-1"></i><?= esc($row['nama_periode'] ?? ($row['bulan'].'/'.$row['tahun'])) ?></span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; flex-shrink:0; color:#64748b;"><i class="fas fa-user" style="font-size:0.75rem;"></i></div>
                                <span><?= esc($row['nama']) ?></span>
                            </div>
                        </td>
                        <td><span class="fw-bold" style="color:#16a34a;"><?= rupiah((float)($row['gaji_bersih'] ?? 0)) ?></span></td>
                        <td><div class="status-badge" style="background:#e0f2fe; color:#0369a1; border-color:#bae6fd;"><i class="fas fa-circle me-1" style="font-size:0.4rem; vertical-align:middle;"></i> Verified</div></td>
                        <td class="text-end">
                            <form method="post" action="<?= base_url('bendahara/verifikasi') ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="pay_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action_type" value="unverify">
                                <button type="submit" class="btn btn-sm m-0" style="background:#fef2f2; color:#dc2626; border:1px solid #f87171; font-weight:600; padding:0.4rem 0.8rem; border-radius:8px;" onclick="return confirm('Kembalikan slip ini ke status Draft?')">
                                    <i class="fas fa-undo me-1"></i> Draft
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-outline-info btn-preview-modal m-0" style="padding:0.4rem 0.6rem; border-radius:8px;" data-id="<?= $row['id'] ?>" title="Preview PDF">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($verifiedPager) && $verifiedPager): ?>
        <div class="mt-3 d-flex justify-content-end">
            <?= $verifiedPager->links('default', 'bootstrap5') ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

    <!-- ── Panel Histori Ringkas ── -->
    <?php if (!empty($histori)): ?>
    <div class="histori-panel">
        <div class="histori-panel-title">
            <span><i class="fas fa-history me-2" style="color:#f59e0b;"></i> 10 Verifikasi Terakhir</span>
            <a href="<?= base_url('bendahara/histori?aksi=verifikasi') ?>" style="font-size:0.78rem; color:#0077b6; font-weight:600;">Lihat Semua &rarr;</a>
        </div>
        <?php foreach($histori as $log): ?>
        <div class="histori-row">
            <div class="h-icon" style="background:<?= (int)$log['is_bulk'] ? '#f3e8ff' : '#fef3c7' ?>;">
                <i class="fas <?= (int)$log['is_bulk'] ? 'fa-layer-group' : 'fa-user' ?>" style="color:<?= (int)$log['is_bulk'] ? '#7c3aed' : '#d97706' ?>;"></i>
            </div>
            <div style="flex:1;">
                <div class="h-main">
                    <?php if((int)$log['is_bulk']): ?>
                        <?= (int)$log['jumlah_slip'] ?> slip diverifikasi sekaligus
                        <span class="h-bulk-tag">Massal</span>
                    <?php else: ?>
                        <?= esc($log['pegawai_nama'] ?? '–') ?>
                    <?php endif; ?>
                </div>
                <div class="h-sub">
                    Oleh: <strong><?= esc($log['username']) ?></strong>
                    <?php if($log['periode_nama']): ?> &bull; <?= esc($log['periode_nama']) ?><?php endif; ?>
                    <?php if(!empty($log['gaji_bersih']) && !(int)$log['is_bulk']): ?> &bull; <span style="color:#16a34a;"><?= rupiah((float)$log['gaji_bersih']) ?></span><?php endif; ?>
                </div>
            </div>
            <div class="h-time"><?= date('d/m H:i', strtotime($log['created_at'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<!-- Modal Preview Slip -->
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

<!-- Modal Minta Revisi -->
<div class="modal fade" id="revisiModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 12px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="border-bottom:1px solid #f1f5f9; background:#fff; border-radius:16px 16px 0 0;">
        <h5 class="modal-title fw-bold" style="color:#0f172a;"><i class="fas fa-exclamation-triangle me-2 text-danger"></i> Minta Revisi Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background:#f8fafc;">
        <p style="font-size:0.95rem; color:#475569; margin-bottom:12px;">Slip gaji yang ditolak akan dikembalikan ke status <span class="fw-bold text-danger">Perlu Revisi</span> dan Admin diwajibkan untuk merevisi ulang.</p>
        <div class="mb-3">
            <label class="form-label fw-bold" style="color:#1e293b;">Alasan Revisi / Catatan Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="modalAlasan" rows="3" placeholder="Contoh: Gaji pokok tidak sesuai bulan ini... (wajib diisi)" required style="border-radius:10px;"></textarea>
            <div class="invalid-feedback">Alasan revisi wajib diisi.</div>
        </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f1f5f9; background:#fff; border-radius:0 0 16px 16px;">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px; font-weight:600;">Batal</button>
        <button type="button" class="btn btn-danger" onclick="submitReject()" style="border-radius:10px; font-weight:600;"><i class="fas fa-paper-plane me-1"></i> Kirim Revisi</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview Modal Logic
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
                    iframePreview.src = '<?= site_url('bendahara/preview-slip/') ?>' + id + '#toolbar=0&navpanes=0';
                }, 120);
                btnDL.style.display = 'none'; // Bendahara tidak butuh tombol download terpisah di sini
                modalPreview.show();
            });
        });
    }
});

function updateBulkBar() {
    var checks = document.querySelectorAll('.row-check:checked');
    var toolbar = document.getElementById('bulkToolbar');
    var count = document.getElementById('selectedCount');
    count.textContent = checks.length;
    toolbar.classList.toggle('show', checks.length > 0);

    // Highlight rows
    document.querySelectorAll('.row-check').forEach(function(cb) {
        var row = cb.closest('tr');
        row.classList.toggle('row-selected', cb.checked);
    });

    var master = document.getElementById('masterCheck');
    var all = document.querySelectorAll('.row-check');
    master.checked = checks.length === all.length && all.length > 0;
    master.indeterminate = checks.length > 0 && checks.length < all.length;
}

function toggleSelectAll() {
    var master = document.getElementById('masterCheck');
    var allChecks = document.querySelectorAll('.row-check');
    var anyUnchecked = Array.from(allChecks).some(function(c){ return !c.checked; });
    allChecks.forEach(function(c){ c.checked = anyUnchecked; });
    updateBulkBar();
}

function verifySingle(id) {
    pendingVerifyId = id;
    pendingVerifyBulk = false;
    document.getElementById('confirmVerifyBody').textContent = 'Verifikasi slip ini?';
    verifyConfirmModal.show();
}

let currentRejectId = null;
const revisiModal = new bootstrap.Modal(document.getElementById('revisiModal'));

function openRejectModal(id) {
    currentRejectId = id;
    document.getElementById('modalAlasan').value = '';
    document.getElementById('modalAlasan').classList.remove('is-invalid');
    revisiModal.show();
}

function submitReject() {
    var alasan = document.getElementById('modalAlasan').value.trim();
    if (alasan === '') {
        document.getElementById('modalAlasan').classList.add('is-invalid');
        return;
    }
    
    document.querySelectorAll('.row-check').forEach(function(c){ c.checked = false; });
    document.getElementById('actionType').value = 'reject';
    document.getElementById('alasanRevisi').value = alasan;
    document.getElementById('singlePayId').value = currentRejectId;
    document.getElementById('bulkForm').submit();
}
</script>

<!-- ── Bootstrap Confirm Modal (Verifikasi) ── -->
<div class="modal fade" id="verifyConfirmModal" tabindex="-1" aria-labelledby="verifyConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.18);">
      <div class="modal-header" style="background:linear-gradient(135deg,#0077b6,#00b4d8); border-radius:16px 16px 0 0; padding:18px 24px;">
        <h6 class="modal-title fw-bold text-white" id="verifyConfirmLabel">
          <i class="fas fa-shield-check me-2"></i> Konfirmasi Verifikasi
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4 px-4">
        <div style="width:56px;height:56px;background:#e0f2fe;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
          <i class="fas fa-check-double" style="font-size:1.5rem;color:#0077b6;"></i>
        </div>
        <p class="mb-0 fw-semibold" id="confirmVerifyBody" style="color:#1e293b;font-size:1rem;">Verifikasi slip ini?</p>
        <p class="text-muted mt-1" style="font-size:0.82rem;">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4">
        <button type="button" class="btn btn-light px-4" style="border-radius:8px;" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Batal
        </button>
        <button type="button" id="verifyConfirmBtn" class="btn px-4 fw-bold" style="background:#0077b6;color:#fff;border-radius:8px;">
          <i class="fas fa-check me-1"></i> Ya, Verifikasi
        </button>
      </div>
    </div>
  </div>
</div>

<script>
let pendingVerifyId = null;
let pendingVerifyBulk = false;
const verifyConfirmModal = new bootstrap.Modal(document.getElementById('verifyConfirmModal'));

function showBulkVerifyModal() {
    var count = document.querySelectorAll('.row-check:checked').length;
    if (count === 0) { alert('Pilih minimal 1 slip terlebih dahulu.'); return; }
    pendingVerifyBulk = true;
    pendingVerifyId = null;
    document.getElementById('confirmVerifyBody').textContent = 'Verifikasi ' + count + ' slip yang dipilih?';
    verifyConfirmModal.show();
}

document.getElementById('verifyConfirmBtn').addEventListener('click', function() {
    verifyConfirmModal.hide();
    if (pendingVerifyBulk) {
        document.getElementById('bulkForm').submit();
    } else if (pendingVerifyId !== null) {
        document.querySelectorAll('.row-check').forEach(function(c){ c.checked = false; });
        document.getElementById('actionType').value = 'verify';
        document.getElementById('alasanRevisi').value = '';
        document.getElementById('singlePayId').value = pendingVerifyId;
        document.getElementById('bulkForm').submit();
    }
});
</script>
<?= $this->endSection() ?>
