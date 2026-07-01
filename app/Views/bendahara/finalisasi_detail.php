<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .fin-container { font-family: 'Outfit', sans-serif; }

    .fin-header {
        background: linear-gradient(135deg, #021a3a 0%, #004e89 100%);
        border-radius: 20px; padding: 28px 30px; color: white;
        margin-bottom: 24px; box-shadow: 0 15px 30px rgba(0,78,137,0.15);
        position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
    }
    .fin-header::after {
        content: ''; position: absolute; right: -5%; top: -50%; width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(0,180,216,0.15) 0%, transparent 70%); border-radius: 50%;
    }
    .fin-title { font-weight: 700; margin: 0; font-size: 1.6rem; }
    .fin-subtitle { color: rgba(255,255,255,0.7); font-size: 0.9rem; margin-top: 4px; }

    .btn-back {
        background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3);
        border-radius: 10px; padding: 8px 18px; font-weight: 600; font-size: 0.85rem;
        transition: all 0.25s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; backdrop-filter: blur(5px);
    }
    .btn-back:hover { background: rgba(255,255,255,0.25); color: white; }

    .summary-bar {
        background: white; border-radius: 16px; padding: 18px 24px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 30px; flex-wrap: wrap; margin-bottom: 20px;
    }
    .sum-item { display: flex; flex-direction: column; gap: 2px; }
    .sum-label { font-size: 0.75rem; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
    .sum-value { font-size: 1.15rem; font-weight: 700; color: #1e293b; }

    .fin-card {
        background: white; border-radius: 20px; border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); padding: 25px;
    }
    .card-header-bar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; gap: 10px;
    }

    /* ── Table ── */
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .custom-table thead th {
        border: none; color: #64748b; font-weight: 600; font-size: 0.78rem;
        text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 14px; background: transparent;
    }
    .custom-table tbody tr { box-shadow: 0 3px 8px rgba(0,0,0,0.025); transition: all 0.2s; }
    .custom-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,120,200,0.08); }
    .custom-table tbody td {
        background: #fff; padding: 14px; border: 1px solid #f8fafc; border-style: solid none;
        vertical-align: middle; color: #334155; font-size: 0.88rem; font-weight: 500;
    }
    .custom-table tbody td:first-child { border-left: 1px solid #f8fafc; border-radius: 10px 0 0 10px; }
    .custom-table tbody td:last-child { border-right: 1px solid #f8fafc; border-radius: 0 10px 10px 0; }

    /* ── Status badges ── */
    .badge-draft  { background:#fef3c7; color:#d97706; border:1px solid #fde68a; }
    .badge-verified { background:#dbeafe; color:#1d4ed8; border:1px solid #bfdbfe; }
    .badge-approved { background:#ede9fe; color:#7c3aed; border:1px solid #ddd6fe; }
    .badge-paid   { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
    .status-pill  { display:inline-flex; align-items:center; gap:5px; padding:5px 11px; border-radius:8px; font-size:0.77rem; font-weight:600; }

    /* ── Action buttons ── */
    .btn-bayar {
        background: linear-gradient(135deg, #16a34a, #22c55e); color: white;
        border: none; border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: 0.8rem;
        transition: all 0.25s; box-shadow: 0 3px 8px rgba(22,163,74,0.25);
    }
    .btn-bayar:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(22,163,74,0.35); color: white; }
    .btn-paid-done {
        background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;
        border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: 0.8rem;
        cursor: default;
    }

    /* number formatting */
    .rp-value { font-weight: 700; }
    .rp-neg    { color: #dc2626; }
    .rp-pos    { color: #16a34a; }

    .period-chip {
        background: rgba(255,255,255,0.15); border-radius: 8px; padding: 4px 12px;
        font-size: 0.85rem; color: rgba(255,255,255,0.9); display: inline-block; backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.2);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $namaPeriode = esc($periode['nama_periode'] ?? ($periode['bulan'].'/'.$periode['tahun']));
    $totalPegawai = count($rows);
    $totalGapok  = array_sum(array_column($rows, 'gaji_pokok'));
    $totalPend   = array_sum(array_column($rows, 'total_pendapatan'));
    $totalPot    = array_sum(array_column($rows, 'total_potongan'));
    $totalBersih = array_sum(array_column($rows, 'gaji_bersih'));
    $sudahPaid   = count(array_filter($rows, function($r) { return $r['status'] === 'paid'; }));
    $belumPaid   = $totalPegawai - $sudahPaid;
?>
<div class="fin-container">

    <!-- Header -->
    <div class="fin-header">
        <div style="position:relative; z-index:1;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="period-chip"><i class="far fa-calendar-alt me-1"></i><?= $namaPeriode ?></span>
            </div>
            <h3 class="fin-title"><i class="fas fa-users me-2" style="color:#48cae4;"></i> Rincian Per Pegawai</h3>
            <div class="fin-subtitle">Detail data penggajian dan status pembayaran setiap pegawai pada periode <?= $namaPeriode ?>.</div>
        </div>
        <a href="<?= base_url('bendahara/finalisasi') ?>" class="btn-back" style="position:relative;z-index:1;">
            <i class="fas fa-arrow-left"></i> Kembali ke Finalisasi
        </a>
    </div>

    <?php
    $_flash_msg = session()->getFlashdata('fnd_msg');
    $_flash_err = session()->getFlashdata('fnd_err');
    ?>
    <?php if ($_flash_msg): ?>
        <div class="alert alert-success alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('fnd_msg_') ?>" style="border-radius:12px; background:#d1fae5; color:#065f46;">
            <i class="fas fa-check-circle me-2"></i> <?= esc($_flash_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($_flash_err): ?>
        <div class="alert alert-danger alert-dismissible border-0 mb-3" data-flash-token="<?= uniqid('fnd_err_') ?>" style="border-radius:12px;">
            <i class="fas fa-exclamation-circle me-2"></i> <?= esc($_flash_err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Summary bar -->
    <div class="summary-bar">
        <div class="sum-item">
            <span class="sum-label">Total Pegawai</span>
            <span class="sum-value"><?= $totalPegawai ?> Orang</span>
        </div>
        <div style="width:1px; height:40px; background:#f1f5f9;"></div>
        <div class="sum-item">
            <span class="sum-label">Total Pendapatan</span>
            <span class="sum-value" style="color:#0077b6;"><?= rupiah($totalPend) ?></span>
        </div>
        <div style="width:1px; height:40px; background:#f1f5f9;"></div>
        <div class="sum-item">
            <span class="sum-label">Total Potongan</span>
            <span class="sum-value" style="color:#dc2626;"><?= rupiah($totalPot) ?></span>
        </div>
        <div style="width:1px; height:40px; background:#f1f5f9;"></div>
        <div class="sum-item">
            <span class="sum-label">Total Gaji Bersih</span>
            <span class="sum-value" style="color:#16a34a;"><?= rupiah($totalBersih) ?></span>
        </div>
        <div style="width:1px; height:40px; background:#f1f5f9;"></div>
        <div class="sum-item">
            <span class="sum-label">Belum Bayar</span>
            <span class="sum-value" style="color:#be123c;"><?= $belumPaid ?> Orang</span>
        </div>
        <div class="sum-item">
            <span class="sum-label">Sudah Paid</span>
            <span class="sum-value" style="color:#16a34a;"><?= $sudahPaid ?> Orang</span>
        </div>
    </div>

    <!-- Table -->
    <div class="fin-card">
        <div class="card-header-bar">
            <div>
                <div style="font-weight:700; color:#1e293b; font-size:0.95rem; margin-bottom:4px;"><i class="fas fa-list-ul me-2 text-primary"></i> Rincian Per Pegawai &mdash; <?= $namaPeriode ?></div>
                <span style="background:#e0f2fe; color:#0369a1; border-radius:8px; padding:5px 14px; font-size:0.82rem; font-weight:700;"><?= $totalPegawai ?> Pegawai</span>
            </div>
            
            <div class="d-flex gap-2">
                <a href="<?= base_url('bendahara/finalisasi/export-pdf/' . $periode['id']) ?>" target="_blank" class="btn fw-bold" style="background:#dc2626; color:white; border-radius:10px; box-shadow:0 4px 10px rgba(220, 38, 38, 0.3);">
                    <i class="fas fa-file-pdf me-2"></i> Export Rekapan PDF
                </a>
                
                <?php 
                    // Hitung jumlah yang siap dibayar (status approved)
                    $siapBayar = count(array_filter($rows, function($r) { return $r['status'] === 'approved'; }));
                    if ($siapBayar > 0): 
                ?>
                <button type="button" class="btn btn-primary fw-bold" style="border-radius:10px; box-shadow:0 4px 10px rgba(0, 119, 182, 0.3);" onclick="showBulkBayarModal()">
                    <i class="fas fa-check-double me-2"></i> Bayar Semua Approved (<?= $siapBayar ?> Slip)
                </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="3%">#</th>
                        <th width="18%">Nama</th>
                        <th width="16%">NIP</th>
                        <th width="11%">Gaji Pokok</th>
                        <th width="11%">Total Pendapatan</th>
                        <th width="11%">Total Potongan</th>
                        <th width="11%">Gaji Bersih</th>
                        <th width="9%">Status</th>
                        <th width="10%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $i => $row): ?>
                        <tr>
                            <td><span class="text-muted fw-bold"><?= $i+1 ?></span></td>
                            <td><span class="fw-bold" style="color:#1e293b;"><?= esc($row['nama']) ?></span></td>
                            <td><span style="color:#64748b; font-size:0.82rem;"><?= esc($row['nip'] ?? '-') ?></span></td>
                            <td><span class="rp-value"><?= rupiah((float)($row['gaji_pokok'] ?? 0)) ?></span></td>
                            <td><span class="rp-value rp-pos"><?= rupiah((float)($row['total_pendapatan'] ?? 0)) ?></span></td>
                            <td><span class="rp-value rp-neg"><?= rupiah((float)($row['total_potongan'] ?? 0)) ?></span></td>
                            <td><span class="rp-value fw-bold" style="color:#0077b6;"><?= rupiah((float)($row['gaji_bersih'] ?? 0)) ?></span></td>
                            <td>
                                <?php $s = $row['status']; ?>
                                <span class="status-pill badge-<?= esc($s) ?>">
                                    <i class="fas fa-circle" style="font-size:0.4rem; vertical-align:middle;"></i>
                                    <?= ucfirst(esc($s)) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?php if ($row['status'] === 'paid'): ?>
                                    <span class="btn-paid-done"><i class="fas fa-check me-1"></i> Paid</span>
                                <?php elseif ($row['status'] === 'approved'): ?>
                                    <button type="button" class="btn-bayar" onclick="showSingleBayarModal(<?= $row['id'] ?>)">
                                        <i class="fas fa-money-bill-wave me-1"></i> Bayar
                                    </button>
                                <?php else: ?>
                                    <span style="color:#94a3b8; font-size:0.8rem;">–</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $pager->links('default', 'bootstrap5') ?>
        </div>
    </div>
</div>

<!-- Hidden Form for Payment -->
<form id="payForm" method="post" action="<?= base_url('bendahara/bayar') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="periode_id" value="<?= $periode['id'] ?>">
    <input type="hidden" name="pay_id" id="formPayId" value="">
    <input type="hidden" name="pay_all" id="formPayAll" value="0">
</form>

<!-- ── Bootstrap Confirm Modal (Bayar) ── -->
<div class="modal fade" id="bayarConfirmModal" tabindex="-1" aria-labelledby="bayarConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.18);">
      <div class="modal-header" style="background:linear-gradient(135deg,#0077b6,#00b4d8); border-radius:16px 16px 0 0; padding:18px 24px;">
        <h6 class="modal-title fw-bold text-white" id="bayarConfirmLabel">
          <i class="fas fa-money-bill-wave me-2"></i> Konfirmasi Pembayaran
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4 px-4">
        <div style="width:56px;height:56px;background:#e0f2fe;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
          <i class="fas fa-money-bill-wave" style="font-size:1.5rem;color:#0077b6;"></i>
        </div>
        <p class="mb-0 fw-semibold" id="confirmBayarBody" style="color:#1e293b;font-size:1rem;">Tandai slip ini sebagai Paid?</p>
        <p class="text-muted mt-1" style="font-size:0.82rem;">Aksi ini akan mengubah status slip menjadi dibayar.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4">
        <button type="button" class="btn btn-light px-4" style="border-radius:8px;" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Batal
        </button>
        <button type="button" id="bayarConfirmBtn" class="btn px-4 fw-bold" style="background:#0077b6;color:#fff;border-radius:8px;">
          <i class="fas fa-check me-1"></i> Ya, Bayar
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let pendingPayId = null;
let pendingPayBulk = false;
const bayarConfirmModal = new bootstrap.Modal(document.getElementById('bayarConfirmModal'));

function showSingleBayarModal(id) {
    pendingPayId = id;
    pendingPayBulk = false;
    document.getElementById('confirmBayarBody').textContent = 'Tandai slip ini sebagai Paid?';
    bayarConfirmModal.show();
}

function showBulkBayarModal() {
    pendingPayBulk = true;
    pendingPayId = null;
    document.getElementById('confirmBayarBody').textContent = 'Tandai semua slip approved sebagai Paid?';
    bayarConfirmModal.show();
}

document.getElementById('bayarConfirmBtn').addEventListener('click', function() {
    bayarConfirmModal.hide();
    const form = document.getElementById('payForm');
    if (pendingPayBulk) {
        document.getElementById('formPayAll').value = '1';
        document.getElementById('formPayId').value = '';
        form.submit();
    } else if (pendingPayId !== null) {
        document.getElementById('formPayAll').value = '0';
        document.getElementById('formPayId').value = pendingPayId;
        form.submit();
    }
});
</script>
<?= $this->endSection() ?>
