<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .fin-container { font-family: 'Outfit', sans-serif; }

    .fin-header {
        background: linear-gradient(135deg, #021a3a 0%, #004e89 100%);
        border-radius: 20px; padding: 30px; color: white;
        margin-bottom: 30px; box-shadow: 0 15px 30px rgba(0,78,137,0.15);
        position: relative; overflow: hidden;
    }
    .fin-header::after {
        content: ''; position: absolute; right: -5%; top: -50%; width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(0,180,216,0.15) 0%, transparent 70%); border-radius: 50%;
    }
    .fin-title { font-weight: 700; margin: 0; font-size: 1.8rem; }
    .fin-subtitle { color: rgba(255,255,255,0.7); font-size: 0.95rem; margin-top: 5px; }

    .stat-card {
        background: white; border-radius: 16px; padding: 20px 24px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 16px; transition: all 0.25s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,120,200,0.1); }
    .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: white; flex-shrink: 0; }
    .stat-label { font-size: 0.8rem; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-value { font-size: 1.6rem; font-weight: 700; color: #1e293b; line-height: 1; }

    .fin-card {
        background: white; border-radius: 20px; border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); padding: 25px;
    }
    .card-header-bar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;
    }
    .card-header-title { font-size: 1rem; font-weight: 700; color: #1e293b; }

    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .custom-table thead th {
        border: none; color: #64748b; font-weight: 600; font-size: 0.8rem;
        text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 14px; background: transparent;
    }
    .custom-table tbody tr { box-shadow: 0 3px 8px rgba(0,0,0,0.025); transition: all 0.2s; }
    .custom-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,120,200,0.07); }
    .custom-table tbody td {
        background: #fff; padding: 16px 14px; border: 1px solid #f8fafc; border-style: solid none;
        vertical-align: middle; color: #334155; font-size: 0.9rem; font-weight: 500;
    }
    .custom-table tbody td:first-child { border-left: 1px solid #f8fafc; border-radius: 10px 0 0 10px; }
    .custom-table tbody td:last-child { border-right: 1px solid #f8fafc; border-radius: 0 10px 10px 0; }

    .badge-status { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; }
    .badge-draft { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .badge-proses { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-selesai { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }

    .mini-stat { display: flex; flex-direction: column; align-items: center; gap: 2px; min-width: 50px; }
    .mini-stat-num { font-size: 1rem; font-weight: 700; }
    .mini-stat-label { font-size: 0.68rem; color: #94a3b8; text-transform: uppercase; }

    .btn-detail {
        background: linear-gradient(135deg, #0077b6, #0096c7); color: white;
        border: none; border-radius: 8px; padding: 7px 14px; font-weight: 600; font-size: 0.82rem;
        transition: all 0.25s; box-shadow: 0 3px 8px rgba(0,119,182,0.25); text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-detail:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,119,182,0.35); color: white; }

    .btn-finalize {
        background: linear-gradient(135deg, #dc2626, #b91c1c); color: white;
        border: none; border-radius: 8px; padding: 7px 14px; font-weight: 600; font-size: 0.82rem;
        transition: all 0.25s; box-shadow: 0 3px 8px rgba(220,38,38,0.25);
    }
    .btn-finalize:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(220,38,38,0.35); color: white; }

    .belum-bayar-badge { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="fin-container">

    <div class="fin-header">
        <h3 class="fin-title"><i class="fas fa-lock me-2" style="color:#48cae4;"></i> Finalisasi Periode</h3>
        <div class="fin-subtitle">Kelola periode penggajian, pantau status pembayaran, dan finalisasi setiap periode yang telah selesai diproses.</div>
    </div>

    <?php
    $_flash_msg = session()->getFlashdata('fin_msg');
    $_flash_err = session()->getFlashdata('fin_err');
    ?>
    <?php if ($_flash_msg): ?>
        <div class="alert alert-success alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('fin_msg_') ?>" style="border-radius:12px; background:#d1fae5; color:#065f46;">
            <i class="fas fa-check-circle me-2"></i> <?= esc($_flash_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($_flash_err): ?>
        <div class="alert alert-danger alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('fin_err_') ?>" style="border-radius:12px;">
            <i class="fas fa-exclamation-circle me-2"></i> <?= esc($_flash_err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php
        $totalPeriode = count($rows);
        $totalBelumBayar = array_sum(array_column($rows, 'belum_bayar'));
        $totalPaid = array_sum(array_column($rows, 'paid_count'));
        $totalSlip = array_sum(array_column($rows, 'total_slip'));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#0077b6,#00b4d8);"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="stat-label">Total Periode</div>
                    <div class="stat-value"><?= $totalPeriode ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#0d9488,#14b8a6);"><i class="fas fa-file-alt"></i></div>
                <div>
                    <div class="stat-label">Total Slip</div>
                    <div class="stat-value"><?= $totalSlip ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#be123c,#e11d48);"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-label">Belum Bayar</div>
                    <div class="stat-value" style="color:#be123c;"><?= $totalBelumBayar ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#16a34a,#22c55e);"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-label">Sudah Paid</div>
                    <div class="stat-value" style="color:#16a34a;"><?= $totalPaid ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="fin-card">
        <div class="card-header-bar">
            <div class="card-header-title"><i class="fas fa-table me-2 text-primary"></i> Daftar Periode Penggajian</div>
        </div>
        <div class="table-responsive">
            <?php if (empty($rows)): ?>
                <div class="text-center py-5">
                    <div style="font-size:3.5rem; color:#e2e8f0;"><i class="fas fa-folder-open"></i></div>
                    <p class="text-muted mt-3">Tidak ada periode penggajian ditemukan.</p>
                </div>
            <?php else: ?>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th width="18%">Periode</th>
                            <th width="10%">Status</th>
                            <th width="10%" class="text-center">Total Slip</th>
                            <th width="10%" class="text-center">Draft</th>
                            <th width="10%" class="text-center">Approved</th>
                            <th width="10%" class="text-center" style="color:#be123c;">Belum Bayar</th>
                            <th width="10%" class="text-center" style="color:#16a34a;">Sudah Paid</th>
                            <th width="18%" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $i => $r):
                            $canFinalize = ((int)$r['total_slip'] > 0 && (int)$r['draft_count'] === 0 && $r['status'] !== 'selesai');
                            $belumBayar = (int)$r['belum_bayar'];
                        ?>
                            <tr>
                                <td><span class="text-muted fw-bold"><?= $i+1 ?></span></td>
                                <td>
                                    <div class="fw-bold" style="color:#0077b6;"><i class="far fa-calendar-alt me-1"></i> <?= esc($r['nama_periode']) ?></div>
                                </td>
                                <td>
                                    <?php $s = $r['status']; ?>
                                    <span class="badge-status <?= $s==='selesai' ? 'badge-selesai' : ($s==='proses' ? 'badge-proses' : 'badge-draft') ?>">
                                        <i class="fas fa-circle" style="font-size:0.45rem; vertical-align:middle;"></i>
                                        <?= ucfirst(esc($s)) ?>
                                    </span>
                                </td>
                                <td class="text-center"><div class="mini-stat"><span class="mini-stat-num"><?= (int)$r['total_slip'] ?></span><span class="mini-stat-label">Slip</span></div></td>
                                <td class="text-center"><div class="mini-stat"><span class="mini-stat-num" style="color:#d97706;"><?= (int)$r['draft_count'] ?></span><span class="mini-stat-label">Draft</span></div></td>
                                <td class="text-center"><div class="mini-stat"><span class="mini-stat-num" style="color:#1d4ed8;"><?= (int)$r['approved_count'] ?></span><span class="mini-stat-label">Approved</span></div></td>
                                <td class="text-center">
                                    <div class="mini-stat">
                                        <span class="mini-stat-num" style="color:<?= $belumBayar > 0 ? '#be123c' : '#94a3b8' ?>;"><?= $belumBayar ?></span>
                                        <span class="mini-stat-label">Belum</span>
                                    </div>
                                </td>
                                <td class="text-center"><div class="mini-stat"><span class="mini-stat-num" style="color:#16a34a;"><?= (int)$r['paid_count'] ?></span><span class="mini-stat-label">Paid</span></div></td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= base_url('bendahara/finalisasi/detail/' . $r['id']) ?>" class="btn-detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <?php if ($canFinalize): ?>
                                            <button type="button" class="btn-finalize" onclick="showFinalizeModal(<?= $r['id'] ?>, '<?= esc($r['nama_periode']) ?>')">
                                                <i class="fas fa-lock"></i> Finalisasi
                                            </button>
                                        <?php else: ?>
                                            <span style="font-size:0.78rem; color:#94a3b8; padding: 7px 4px;">
                                                <?= $r['status'] === 'selesai' ? '<span style="color:#16a34a;"><i class="fas fa-check"></i> Selesai</span>' : '<i class="fas fa-hourglass-half"></i> Belum siap' ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Hidden Form for Finalisasi -->
<form id="finalizeForm" method="post" action="<?= base_url('bendahara/finalisasi') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="finalize_id" id="formFinalizeId" value="">
</form>

<!-- ── Bootstrap Confirm Modal (Finalisasi) ── -->
<div class="modal fade" id="finalizeConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.18);">
      <div class="modal-header" style="background:linear-gradient(135deg,#be123c,#e11d48); border-radius:16px 16px 0 0; padding:18px 24px;">
        <h6 class="modal-title fw-bold text-white">
          <i class="fas fa-lock me-2"></i> Konfirmasi Finalisasi
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4 px-4">
        <div style="width:56px;height:56px;background:#ffe4e6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
          <i class="fas fa-lock" style="font-size:1.5rem;color:#be123c;"></i>
        </div>
        <p class="mb-0 fw-semibold" id="confirmFinalizeBody" style="color:#1e293b;font-size:1rem;"></p>
        <p class="text-muted mt-1" style="font-size:0.82rem;">Aksi ini akan mengunci periode secara permanen.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4">
        <button type="button" class="btn btn-light px-4" style="border-radius:8px;" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Batal
        </button>
        <button type="button" id="finalizeConfirmBtn" class="btn px-4 fw-bold" style="background:#be123c;color:#fff;border-radius:8px;">
          <i class="fas fa-check me-1"></i> Yakin, Finalisasi!
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let pendingFinalizeId = null;
const finalizeConfirmModal = new bootstrap.Modal(document.getElementById('finalizeConfirmModal'));

function showFinalizeModal(id, namaperiode) {
    pendingFinalizeId = id;
    document.getElementById('confirmFinalizeBody').textContent = 'Yakin finalisasi periode ' + namaperiode + '?';
    finalizeConfirmModal.show();
}

document.getElementById('finalizeConfirmBtn').addEventListener('click', function() {
    finalizeConfirmModal.hide();
    document.getElementById('formFinalizeId').value = pendingFinalizeId;
    document.getElementById('finalizeForm').submit();
});
</script>
<?= $this->endSection() ?>
