<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .his-container { font-family: 'Outfit', sans-serif; }

    /* ── Header ── */
    .his-header {
        background: linear-gradient(135deg, #021a3a 0%, #1e3a5f 100%);
        border-radius: 20px; padding: 28px 30px; color: white;
        margin-bottom: 24px; box-shadow: 0 15px 30px rgba(0,78,137,0.15);
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
    }
    .his-header::after { content:''; position:absolute; right:-5%; top:-50%; width:280px; height:280px; background:radial-gradient(circle,rgba(0,180,216,0.15) 0%,transparent 70%); border-radius:50%; }
    .his-title { font-weight:700; margin:0; font-size:1.7rem; }
    .his-subtitle { color:rgba(255,255,255,0.7); font-size:0.88rem; margin-top:4px; }

    /* ── Summary cards ── */
    .sum-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(170px,1fr)); gap:14px; margin-bottom:22px; }
    .sum-card { background:white; border-radius:14px; padding:18px 20px; box-shadow:0 6px 16px rgba(0,0,0,0.04); border:1px solid #f1f5f9; display:flex; align-items:center; gap:14px; }
    .sum-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:white; flex-shrink:0; }
    .sum-label { font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; }
    .sum-num   { font-size:1.4rem; font-weight:800; color:#1e293b; line-height:1; }
    .sum-sub   { font-size:0.72rem; color:#94a3b8; }

    /* ── Filter bar ── */
    .filter-bar { background:white; border-radius:14px; padding:14px 20px; margin-bottom:18px;
        display:flex; align-items:center; gap:12px; flex-wrap:wrap; box-shadow:0 4px 12px rgba(0,0,0,0.03); border:1px solid #f1f5f9; }
    .filter-bar select, .filter-bar input { border:1px solid #e2e8f0; border-radius:8px; padding:7px 12px; font-size:0.86rem; color:#1e293b; outline:none; }
    .btn-filter { background:linear-gradient(135deg,#0077b6,#0096c7); color:white; border:none; border-radius:8px; padding:8px 18px; font-weight:600; font-size:0.85rem; cursor:pointer; }
    .btn-reset  { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; border-radius:8px; padding:8px 14px; font-weight:600; font-size:0.85rem; text-decoration:none; }

    /* ── Table ── */
    .his-card { background:white; border-radius:20px; border:1px solid #f1f5f9; box-shadow:0 10px 30px rgba(0,0,0,0.03); padding:24px; }
    .card-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:8px; }

    .his-table { width:100%; border-collapse:separate; border-spacing:0 6px; }
    .his-table thead th { border:none; color:#64748b; font-weight:600; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px; padding:8px 12px; }
    .his-table tbody tr { box-shadow:0 2px 6px rgba(0,0,0,0.025); transition:all 0.15s; }
    .his-table tbody tr:hover { transform:translateX(3px); box-shadow:0 4px 12px rgba(0,120,200,0.07); }
    .his-table tbody td { background:#fff; padding:12px 12px; border:1px solid #f8fafc; border-style:solid none; vertical-align:middle; color:#334155; font-size:0.875rem; }
    .his-table tbody td:first-child { border-left:1px solid #f8fafc; border-radius:10px 0 0 10px; }
    .his-table tbody td:last-child  { border-right:1px solid #f8fafc; border-radius:0 10px 10px 0; }

    /* ── Pill badges ── */
    .pill { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:0.75rem; font-weight:700; }
    .pill-verifikasi { background:#fef3c7; color:#d97706; }
    .pill-approval   { background:#e0f2fe; color:#0284c7; }
    .pill-bayar      { background:#d1fae5; color:#059669; }
    .pill-finalisasi { background:#fce7f3; color:#be185d; }
    .pill-reject     { background:#fee2e2; color:#ef4444; }
    .pill-unverify   { background:#f1f5f9; color:#475569; }
    .pill-bulk { background:#f3e8ff; color:#7c3aed; font-size:0.7rem; padding:2px 7px; }
    .pill-single { background:#f0fdf4; color:#16a34a; font-size:0.7rem; padding:2px 7px; }

    .empty-state { text-align:center; padding:60px 20px; }
    .empty-icon  { font-size:3.5rem; color:#e2e8f0; margin-bottom:14px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    // Susun summary dari data
    $summaryMap = [];
    foreach ($summary as $s) { $summaryMap[$s['aksi']] = $s; }
    $totalAksi = array_sum(array_column($summary, 'total'));
    $totalSlipDiproses = array_sum(array_column($summary, 'total_slip'));
?>
<div class="his-container">

    <!-- Header -->
    <div class="his-header">
        <div style="position:relative;z-index:1;">
            <h3 class="his-title"><i class="fas fa-history me-2" style="color:#48cae4;"></i> Histori Aksi</h3>
            <div class="his-subtitle">Rekam jejak seluruh aksi verifikasi, approval, pembayaran, dan finalisasi periode.</div>
        </div>
        <div style="position:relative;z-index:1;text-align:right;">
            <div style="font-size:1.5rem; font-weight:800;"><?= $totalAksi ?> <span style="font-size:0.9rem; font-weight:400; opacity:0.7;">total aksi</span></div>
            <div style="font-size:0.82rem; opacity:0.65;"><?= $totalSlipDiproses ?> slip telah diproses</div>
        </div>
    </div>

    <!-- Summary cards -->
    <div class="sum-grid">
        <?php
        $cards = [
            'verifikasi' => ['icon'=>'fa-clipboard-check', 'color'=>'linear-gradient(135deg,#f59e0b,#d97706)', 'label'=>'Verifikasi'],
            'approval'   => ['icon'=>'fa-thumbs-up',       'color'=>'linear-gradient(135deg,#0284c7,#0077b6)', 'label'=>'Approval'],
            'bayar'      => ['icon'=>'fa-money-bill-wave', 'color'=>'linear-gradient(135deg,#16a34a,#22c55e)', 'label'=>'Bayar'],
            'reject'     => ['icon'=>'fa-times-circle',    'color'=>'linear-gradient(135deg,#ef4444,#dc2626)', 'label'=>'Reject'],
            'finalisasi' => ['icon'=>'fa-lock',            'color'=>'linear-gradient(135deg,#be185d,#ec4899)', 'label'=>'Finalisasi'],
            'unverify'   => ['icon'=>'fa-undo',            'color'=>'linear-gradient(135deg,#64748b,#475569)', 'label'=>'Unverify'],
        ];
        foreach ($cards as $key => $c):
            $s = $summaryMap[$key] ?? ['total'=>0,'total_slip'=>0];
        ?>
        <div class="sum-card">
            <div class="sum-icon" style="background:<?= $c['color'] ?>;"><i class="fas <?= $c['icon'] ?>"></i></div>
            <div>
                <div class="sum-label"><?= $c['label'] ?></div>
                <div class="sum-num"><?= (int)$s['total'] ?></div>
                <div class="sum-sub"><?= (int)$s['total_slip'] ?> slip diproses</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Filter -->
    <form method="get" action="<?= base_url('bendahara/histori') ?>" class="filter-bar">
        <i class="fas fa-filter" style="color:#94a3b8; flex-shrink:0;"></i>
        <select name="aksi">
            <option value="">– Semua Aksi –</option>
            <?php foreach(['verifikasi','approval','bayar','reject','finalisasi','unverify'] as $a): ?>
                <option value="<?= $a ?>" <?= ($filterAksi === $a) ? 'selected' : '' ?>><?= ucfirst($a) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="periode_id">
            <option value="0">– Semua Periode –</option>
            <?php foreach($periodes as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($selectedPeriodeId == $p['id']) ? 'selected' : '' ?>>
                    <?= esc($p['nama_periode'] ?? ($p['bulan'].'/'.$p['tahun'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-filter"><i class="fas fa-search me-1"></i> Filter</button>
        <a href="<?= base_url('bendahara/histori') ?>" class="btn-reset">✕ Reset</a>
    </form>

    <!-- Histori table -->
    <div class="his-card">
        <div class="card-top">
            <div>
                <div style="font-weight:700; color:#1e293b; font-size:0.95rem; display:inline-block;"><i class="fas fa-list-ul me-2 text-primary"></i> Log Aktivitas</div>
                <span class="ms-2" style="background:#f1f5f9; color:#64748b; border-radius:8px; padding:4px 12px; font-size:0.8rem; font-weight:600;"><?= count($rows) ?> entri</span>
            </div>
            
            <?php if(!empty($rows)): ?>
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#clearHistoriModal" style="border-radius:8px; font-weight:600; padding:6px 14px;">
                <i class="fas fa-trash-alt me-1"></i> Bersihkan Log Histori
            </button>
            <?php endif; ?>
        </div>

        <?php if(empty($rows)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-history"></i></div>
                <h5 class="fw-bold text-dark">Belum Ada Histori</h5>
                <p class="text-muted">Histori aksi akan muncul di sini secara otomatis<br>setelah verifikasi, approval, atau pembayaran dilakukan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="his-table">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th width="14%">Waktu</th>
                            <th width="14%">Pelaku</th>
                            <th width="10%">Aksi</th>
                            <th width="8%">Tipe</th>
                            <th width="10%">Jml Slip</th>
                            <th width="18%">Pegawai / Periode</th>
                            <th width="22%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $i => $log): ?>
                            <tr>
                                <td><span style="color:#94a3b8; font-size:0.8rem;"><?= $i+1 ?></span></td>
                                <td>
                                    <div style="font-weight:600; color:#1e293b; font-size:0.83rem;"><?= date('d/m/Y', strtotime($log['created_at'])) ?></div>
                                    <div style="color:#94a3b8; font-size:0.76rem;"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight:600; color:#334155; font-size:0.85rem;"><?= esc($log['username']) ?></div>
                                    <div style="color:#94a3b8; font-size:0.75rem;"><?= esc($log['role']) ?></div>
                                </td>
                                <td>
                                    <?php $pmap = ['verifikasi'=>'pill-verifikasi','approval'=>'pill-approval','bayar'=>'pill-bayar','reject'=>'pill-reject','finalisasi'=>'pill-finalisasi','unverify'=>'pill-unverify']; ?>
                                    <span class="pill <?= $pmap[$log['aksi']] ?? '' ?>">
                                        <i class="fas <?= ['verifikasi'=>'fa-clipboard-check','approval'=>'fa-thumbs-up','bayar'=>'fa-money-bill-wave','reject'=>'fa-times-circle','finalisasi'=>'fa-lock','unverify'=>'fa-undo'][$log['aksi']] ?? 'fa-circle' ?>"></i>
                                        <?= ucfirst(esc($log['aksi'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if((int)$log['is_bulk']): ?>
                                        <span class="pill pill-bulk"><i class="fas fa-layer-group me-1"></i>Massal</span>
                                    <?php else: ?>
                                        <span class="pill pill-single"><i class="fas fa-user me-1"></i>Satuan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span style="font-size:1.15rem; font-weight:800; color:#0077b6;"><?= (int)$log['jumlah_slip'] ?></span>
                                    <span style="color:#94a3b8; font-size:0.75rem;"> slip</span>
                                </td>
                                <td>
                                    <?php if($log['pegawai_nama']): ?>
                                        <div style="font-weight:600; color:#334155; font-size:0.83rem;"><?= esc($log['pegawai_nama']) ?></div>
                                        <div style="color:#94a3b8; font-size:0.75rem;"><?= esc($log['pegawai_nip'] ?? '') ?></div>
                                    <?php elseif($log['periode_nama']): ?>
                                        <div style="color:#0077b6; font-size:0.83rem;"><i class="far fa-calendar-alt me-1"></i><?= esc($log['periode_nama']) ?></div>
                                    <?php else: ?>
                                        <span style="color:#94a3b8;">–</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color:#64748b; font-size:0.8rem; line-height:1.4;">
                                    <?php if($log['catatan']): ?>
                                        <?= esc($log['catatan']) ?>
                                    <?php elseif($log['gaji_bersih']): ?>
                                        Gaji: <strong style="color:#16a34a;"><?= rupiah((float)$log['gaji_bersih']) ?></strong>
                                    <?php else: ?>
                                        –
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?= $pager->links('default', 'bootstrap5') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Clear Histori -->
<div class="modal fade" id="clearHistoriModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 bg-danger text-white" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
        <h5 class="modal-title"><i class="fas fa-radiation-alt me-2"></i>Peringatan Penghapusan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4 text-center">
        <i class="fas fa-trash-alt text-danger mb-3" style="font-size:3rem;"></i>
        <h5 class="mb-2 fw-bold text-dark">Lakukan Wipe Data Histori?</h5>
        <p class="text-muted mb-0">Tindakan ini akan <b>menghapus secara permanen</b> seluruh rekam jejak log aktivitas Anda. Aksi ini tidak dapat dibatalkan!</p>
      </div>
      <div class="modal-footer border-0 d-flex justify-content-center pb-4 pt-0">
        <form method="post" action="<?= base_url('bendahara/histori') ?>" class="w-100 d-flex justify-content-center gap-2">
            <?= csrf_field() ?>
            <input type="hidden" name="action_clear" value="all">
            <button type="button" class="btn btn-light px-4 shadow-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger px-4 shadow-sm">Ya, Lenyapkan Data</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
