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

    .filter-bar { background:white; border-radius:14px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; box-shadow:0 4px 12px rgba(0,0,0,0.04); border:1px solid #f1f5f9; }
    .filter-bar select { border:1px solid #e2e8f0; border-radius:8px; padding:7px 12px; font-size:0.88rem; color:#1e293b; outline:none; }

    /* Bulk toolbar */
    .bulk-toolbar { background:linear-gradient(135deg,#10b98110,#d1fae5); border:1.5px solid #a7f3d0; border-radius:12px; padding:12px 20px; margin-bottom:16px; display:none; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .bulk-toolbar.show { display:flex; }
    .bulk-count { font-weight:700; color:#059669; font-size:0.9rem; }
    .btn-bulk-approve { background:linear-gradient(135deg,#10b981,#059669); color:white; border:none; border-radius:8px; padding:8px 20px; font-weight:700; font-size:0.85rem; cursor:pointer; box-shadow:0 4px 10px rgba(16,185,129,0.3); }

    .bnd-card { background:white; border-radius:20px; border:1px solid #f1f5f9; box-shadow:0 10px 30px rgba(0,0,0,0.03); padding:25px; }
    .card-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .btn-select-all { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; padding:6px 14px; font-size:0.82rem; font-weight:600; color:#475569; cursor:pointer; }

    .custom-table { width:100%; border-collapse:separate; border-spacing:0 8px; }
    .custom-table thead th { border:none; color:#64748b; font-weight:600; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.5px; padding:8px 14px; }
    .custom-table tbody tr { box-shadow:0 3px 8px rgba(0,0,0,0.02); transition:all 0.2s; }
    .custom-table tbody tr:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,180,216,0.08); }
    .custom-table tbody tr.row-selected td { background:#f0fdf4 !important; }
    .custom-table tbody td { background:#fff; padding:14px 16px; border:1px solid #f8fafc; border-style:solid none; vertical-align:middle; color:#334155; font-size:0.9rem; font-weight:500; }
    .custom-table tbody td:first-child { border-left:1px solid #f8fafc; border-radius:12px 0 0 12px; }
    .custom-table tbody td:last-child { border-right:1px solid #f8fafc; border-radius:0 12px 12px 0; }

    .status-badge-verified { display:inline-block; padding:5px 12px; border-radius:8px; font-size:0.78rem; font-weight:600; background:#e0f2fe; color:#0284c7; border:1px solid #bae6fd; }

    .btn-approve { background:linear-gradient(135deg,#10b981,#059669); color:white; border:none; border-radius:8px; padding:7px 14px; font-weight:600; font-size:0.82rem; transition:all 0.25s; box-shadow:0 3px 8px rgba(16,185,129,0.25); }
    .btn-approve:hover { transform:translateY(-2px); box-shadow:0 6px 15px rgba(16,185,129,0.4); color:white; }

    .btn-preview { background:#f0f9ff; color:#0077b6; border:1px solid #bae6fd; border-radius:8px; padding:7px 12px; font-weight:600; font-size:0.8rem; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s; }
    .btn-preview:hover { background:#0077b6; color:white; }

    .check-slip { width:16px; height:16px; accent-color:#10b981; cursor:pointer; }
    .empty-state { text-align:center; padding:50px 20px; }
    .empty-icon { font-size:4rem; color:#e2e8f0; margin-bottom:16px; }
    .count-badge { background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; border-radius:8px; padding:4px 12px; font-size:0.82rem; font-weight:700; }

    /* ── Histori ringkas ── */
    .histori-panel { background:white; border-radius:16px; border:1px solid #f1f5f9; box-shadow:0 6px 18px rgba(0,0,0,0.03); padding:20px 24px; margin-top:24px; }
    .histori-panel-title { font-weight:700; color:#1e293b; font-size:0.9rem; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between; }
    .histori-row { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px dashed #f1f5f9; }
    .histori-row:last-child { border-bottom:none; }
    .h-icon { width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:0.82rem; flex-shrink:0; }
    .h-main { font-size:0.83rem; font-weight:600; color:#334155; }
    .h-sub  { font-size:0.75rem; color:#94a3b8; margin-top:2px; }
    .h-time { font-size:0.72rem; color:#cbd5e1; white-space:nowrap; margin-left:auto; padding-left:8px; }
    .h-bulk-tag { background:#e0f2fe; color:#0284c7; font-size:0.68rem; font-weight:700; padding:1px 7px; border-radius:20px; margin-left:6px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bnd-container">

    <div class="bnd-header">
        <h3 class="bnd-title"><i class="fas fa-thumbs-up me-2" style="color:#48cae4;"></i> Approval Slip Gaji</h3>
        <div class="bnd-subtitle">Pilih satu atau beberapa slip untuk disetujui sekaligus. Klik ikon mata untuk pratinjau slip sebelum approve.</div>
    </div>

    <?php
    $_flash_msg = session()->getFlashdata('apr_msg');
    $_flash_err = session()->getFlashdata('apr_err');
    ?>
    <?php if ($_flash_msg): ?>
        <div class="alert alert-success alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('apr_msg_') ?>" style="border-radius:12px; background:#d1fae5; color:#065f46;">
            <i class="fas fa-check-circle me-2"></i> <?= esc($_flash_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($_flash_err): ?>
        <div class="alert alert-danger alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('apr_err_') ?>" style="border-radius:12px;">
            <i class="fas fa-exclamation-circle me-2"></i> <?= esc($_flash_err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <form method="get" action="<?= base_url('bendahara/approval') ?>" class="filter-bar">
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
            <a href="<?= base_url('bendahara/approval') ?>" style="font-size:0.8rem; color:#94a3b8;">✕ Reset</a>
        <?php endif; ?>
        <span class="count-badge"><i class="fas fa-layer-group me-1"></i><?= count($rows) ?> Slip Ditemukan</span>
    </form>

    <div class="bnd-card">
        <?php if(empty($rows)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-shield-alt"></i></div>
                <h5 class="fw-bold text-dark">Tidak Ada Slip Verified!</h5>
                <p class="text-muted">Semua slip pada periode yang dipilih sudah disetujui.<br>Silakan pastikan slip sudah diverifikasi terlebih dahulu.</p>
            </div>
        <?php else: ?>
            <form method="post" action="<?= base_url('bendahara/approval') ?>" id="bulkForm">
                <?= csrf_field() ?>
                <div class="card-toolbar">
                    <div style="font-weight:700; color:#1e293b; font-size:0.95rem;"><i class="fas fa-list-ul me-2" style="color:#10b981;"></i> Daftar Slip Gaji Verified</div>
                    <button type="button" class="btn-select-all" onclick="toggleSelectAll()">
                        <i class="fas fa-check-square me-1"></i> Pilih Semua
                    </button>
                </div>

                <div class="bulk-toolbar" id="bulkToolbar">
                    <div class="bulk-count"><i class="fas fa-check-double me-2"></i><span id="selectedCount">0</span> slip dipilih</div>
                    <button type="button" class="btn-bulk-approve" onclick="showBulkApproveModal()">
                        <i class="fas fa-check-double me-2"></i> Setujui Semua Dipilih
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" id="masterCheck" class="check-slip" onclick="toggleSelectAll()"></th>
                                <th width="16%">Periode</th>
                                <th width="28%">Nama Pegawai</th>
                                <th width="16%">Gaji Bersih</th>
                                <th width="10%">Status</th>
                                <th width="26%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rows as $i => $row): ?>
                                <tr id="row-<?= $row['id'] ?>">
                                    <td><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="check-slip row-check" onchange="updateBulkBar()"></td>
                                    <td><span style="color:#0077b6;"><i class="far fa-calendar-alt me-1"></i><?= esc($row['nama_periode'] ?? ($row['bulan'].'/'.$row['tahun'])) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; flex-shrink:0; color:#64748b;"><i class="fas fa-user-check" style="font-size:0.75rem;"></i></div>
                                            <span><?= esc($row['nama']) ?></span>
                                        </div>
                                    </td>
                                    <td><span class="fw-bold" style="color:#16a34a;"><?= rupiah((float)($row['gaji_bersih'] ?? 0)) ?></span></td>
                                    <td><div class="status-badge-verified"><i class="fas fa-circle me-1" style="font-size:0.4rem; vertical-align:middle;"></i> Verified</div></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <!-- Pratinjau slip PDF -->
                                            <a href="<?= base_url('bendahara/preview-slip/' . $row['id']) ?>" target="_blank" class="btn-preview" title="Pratinjau Slip">
                                                <i class="fas fa-eye"></i> Preview
                                            </a>
                                            <button type="button" class="btn-approve" onclick="approveSingle(<?= $row['id'] ?>)">
                                                <i class="fas fa-check me-1"></i> Setujui
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $pager->links('default', 'bootstrap5') ?>
                </div>
                <input type="hidden" name="pay_id" id="singlePayId" value="">
            </form>
        <?php endif; ?>
    </div>

    <!-- ── Panel Histori Ringkas ── -->
    <?php if (!empty($histori)): ?>
    <div class="histori-panel">
        <div class="histori-panel-title">
            <span><i class="fas fa-history me-2" style="color:#0284c7;"></i> 10 Approval Terakhir</span>
            <a href="<?= base_url('bendahara/histori?aksi=approval') ?>" style="font-size:0.78rem; color:#0077b6; font-weight:600;">Lihat Semua &rarr;</a>
        </div>
        <?php foreach($histori as $log): ?>
        <div class="histori-row">
            <div class="h-icon" style="background:<?= (int)$log['is_bulk'] ? '#e0f2fe' : '#e0f2fe' ?>;">
                <i class="fas <?= (int)$log['is_bulk'] ? 'fa-layer-group' : 'fa-thumbs-up' ?>" style="color:#0284c7;"></i>
            </div>
            <div style="flex:1;">
                <div class="h-main">
                    <?php if((int)$log['is_bulk']): ?>
                        <?= (int)$log['jumlah_slip'] ?> slip disetujui sekaligus
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function updateBulkBar() {
    var checks = document.querySelectorAll('.row-check:checked');
    var toolbar = document.getElementById('bulkToolbar');
    document.getElementById('selectedCount').textContent = checks.length;
    toolbar.classList.toggle('show', checks.length > 0);
    document.querySelectorAll('.row-check').forEach(function(cb) {
        cb.closest('tr').classList.toggle('row-selected', cb.checked);
    });
    var master = document.getElementById('masterCheck');
    var all = document.querySelectorAll('.row-check');
    master.checked = checks.length === all.length && all.length > 0;
    master.indeterminate = checks.length > 0 && checks.length < all.length;
}
function toggleSelectAll() {
    var all = document.querySelectorAll('.row-check');
    var anyUnchecked = Array.from(all).some(function(c){ return !c.checked; });
    all.forEach(function(c){ c.checked = anyUnchecked; });
    updateBulkBar();
}
function approveSingle(id) {
    pendingApproveId = id;
    pendingApproveBulk = false;
    document.getElementById('confirmApproveBody').textContent = 'Setujui slip ini?';
    approveConfirmModal.show();
}
</script>

<!-- ── Bootstrap Confirm Modal (Approval) ── -->
<div class="modal fade" id="approveConfirmModal" tabindex="-1" aria-labelledby="approveConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.18);">
      <div class="modal-header" style="background:linear-gradient(135deg,#059669,#10b981); border-radius:16px 16px 0 0; padding:18px 24px;">
        <h6 class="modal-title fw-bold text-white" id="approveConfirmLabel">
          <i class="fas fa-thumbs-up me-2"></i> Konfirmasi Persetujuan
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4 px-4">
        <div style="width:56px;height:56px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
          <i class="fas fa-thumbs-up" style="font-size:1.5rem;color:#059669;"></i>
        </div>
        <p class="mb-0 fw-semibold" id="confirmApproveBody" style="color:#1e293b;font-size:1rem;">Setujui slip ini?</p>
        <p class="text-muted mt-1" style="font-size:0.82rem;">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4">
        <button type="button" class="btn btn-light px-4" style="border-radius:8px;" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Batal
        </button>
        <button type="button" id="approveConfirmBtn" class="btn px-4 fw-bold" style="background:#059669;color:#fff;border-radius:8px;">
          <i class="fas fa-check me-1"></i> Ya, Setujui
        </button>
      </div>
    </div>
  </div>
</div>

<script>
let pendingApproveId = null;
let pendingApproveBulk = false;
const approveConfirmModal = new bootstrap.Modal(document.getElementById('approveConfirmModal'));

function showBulkApproveModal() {
    var count = document.querySelectorAll('.row-check:checked').length;
    if (count === 0) { alert('Pilih minimal 1 slip terlebih dahulu.'); return; }
    pendingApproveBulk = true;
    pendingApproveId = null;
    document.getElementById('confirmApproveBody').textContent = 'Setujui ' + count + ' slip yang dipilih?';
    approveConfirmModal.show();
}

document.getElementById('approveConfirmBtn').addEventListener('click', function() {
    approveConfirmModal.hide();
    if (pendingApproveBulk) {
        document.getElementById('bulkForm').submit();
    } else if (pendingApproveId !== null) {
        document.querySelectorAll('.row-check').forEach(function(c){ c.checked = false; });
        document.getElementById('singlePayId').value = pendingApproveId;
        document.getElementById('bulkForm').submit();
    }
});
</script>
<?= $this->endSection() ?>
