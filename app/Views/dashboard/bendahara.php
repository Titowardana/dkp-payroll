<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .bendahara-container { font-family: 'Outfit', sans-serif; }
    
    /* Hero Section */
    .bnd-hero {
        background: linear-gradient(135deg, #021a3a 0%, #004e89 100%);
        border-radius: 24px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 78, 137, 0.15);
        color: white;
        margin-bottom: 30px;
    }
    .bnd-hero::before {
        content: ''; position: absolute; right: -10%; top: -50%; width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(0,180,216,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .bnd-hero::after {
        content: ''; position: absolute; left: 10%; bottom: -30%; width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
        border-radius: 50%;
    }
    
    .bnd-hero-title { font-size: 2rem; font-weight: 700; margin-bottom: 5px; }
    .bnd-hero-sub { color: rgba(255, 255, 255, 0.7); font-size: 1rem; font-weight: 300; margin-bottom: 25px; }
    
    /* Modern Glass Filter */
    .bnd-filter-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        padding: 10px 20px;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        position: relative;
        z-index: 10;
    }
    .bnd-filter-select {
        background: white;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    /* Premium Stat Cards */
    .bnd-stat-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .bnd-stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: #e2e8f0;
    }
    .bnd-stat-card::before {    
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px;
        background: var(--stat-color);
        opacity: 0; transition: opacity 0.3s;
    }
    .bnd-stat-card:hover::before { opacity: 1; }
    
    .bnd-stat-icon-wrap {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 20px;
        background: var(--stat-bg);
        color: var(--stat-color);
    }
    .bnd-stat-title { color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .bnd-stat-value { color: #0f172a; font-size: 2rem; font-weight: 800; line-height: 1.1; }
    .bnd-stat-value.sm-text { font-size: 1.6rem; }
    
    /* Smooth Timeline List */
    .bnd-activity-card {
        background: white; border-radius: 24px; border: 1px solid #f1f5f9; padding: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.02);
    }
    
    .timeline { position: relative; margin: 0; padding: 0; list-style: none; }
    .timeline::before {
        content: ''; position: absolute; top: 0; bottom: 0; left: 15px; width: 2px;
        background: #e2e8f0; border-radius: 2px;
    }
    .timeline-item { position: relative; padding-left: 45px; margin-bottom: 25px; }
    .timeline-item:last-child { margin-bottom: 0; }
    .timeline-badge {
        position: absolute; left: 6px; top: 2px; width: 20px; height: 20px;
        border-radius: 50%; background: white; border: 3px solid #00b4d8;
        box-shadow: 0 0 0 5px white; z-index: 10;
    }
    .timeline-content {
        background: #f8fafc; border-radius: 12px; padding: 15px 20px;
        border: 1px solid #f1f5f9; transition: background 0.2s;
    }
    .timeline-item:hover .timeline-content { background: #f1f5f9; }
    .timeline-time { font-size: 0.75rem; color: #94a3b8; font-weight: 600; margin-bottom: 4px; display: block;}
    .timeline-text { font-size: 0.95rem; color: #334155; margin: 0; line-height: 1.5; }
    
    .animate-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    @keyframes pulse-dot {
        0%, 100% { box-shadow: 0 0 0 3px rgba(74,222,128,0.3); }
        50%       { box-shadow: 0 0 0 6px rgba(74,222,128,0.1); }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bendahara-container">

    <!-- Super Premium Hero Card -->
    <div class="bnd-hero animate-up" style="animation-delay: 0.1s;">
        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index:1;">
            <!-- Kiri: Greeting -->
            <div>
                <div class="d-flex align-items-center mb-1">
                    <img src="<?= base_url('assets/images/logo-dkp-kepri.png') ?>" alt="DKP" style="height: 54px; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));" class="me-3">
                    <div>
                        <h1 class="bnd-hero-title">Halo, <span style="color:#48cae4;"><?= esc(session('full_name') ?? session('username')) ?>!</span></h1>
                        <p class="bnd-hero-sub mb-0">Panel Bendahara Keuangan & Penggajian DKP Kepri.</p>
                    </div>
                </div>

                <div class="bnd-filter-box mt-3">
                    <span class="text-white-50 small fw-bold"><i class="fas fa-calendar-alt text-white me-2"></i>PERIODE:</span>
                    <form method="get" class="m-0">
                        <select name="periode_id" class="bnd-filter-select" onchange="this.form.submit()">
                            <option value="0">🌍 Semua Periode (Global)</option>
                            <?php foreach ($periodes as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= (int) $p['id'] === (int) $selectedPeriodeId ? 'selected' : '' ?>>
                                    <?= esc($p['nama_periode'] ?? ($p['bulan'].'/'.$p['tahun'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Kanan: Live Clock Widget -->
            <div class="text-end d-none d-md-block" style="min-width:170px;">
                <div style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); border-radius:18px; padding:18px 24px; backdrop-filter:blur(12px);">
                    <div id="bnd-live-time" style="font-size:2.2rem; font-weight:800; letter-spacing:2px; color:#fff; line-height:1; font-variant-numeric:tabular-nums;"></div>
                    <div id="bnd-live-date" style="font-size:0.78rem; font-weight:500; color:rgba(255,255,255,0.65); margin-top:6px; letter-spacing:0.5px;"></div>
                    <div style="margin-top:10px; display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#4ade80;display:inline-block;box-shadow:0 0 0 3px rgba(74,222,128,0.3);animation:pulse-dot 2s infinite;"></span>
                        <span style="font-size:0.72rem; color:rgba(255,255,255,0.55); font-weight:600;">Sistem Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Extravagant Metric Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 animate-up" style="animation-delay: 0.2s;">
            <div class="bnd-stat-card" style="--stat-color: #f59e0b; --stat-bg: #fef3c7;">
                <div class="bnd-stat-icon-wrap"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="bnd-stat-title">Pending Verifikasi</div>
                    <div class="bnd-stat-value" style="color:#d97706;"><?= number_format($pendingVerifikasi) ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 animate-up" style="animation-delay: 0.3s;">
            <div class="bnd-stat-card" style="--stat-color: #0ea5e9; --stat-bg: #e0f2fe;">
                <div class="bnd-stat-icon-wrap"><i class="fas fa-file-signature"></i></div>
                <div>
                    <div class="bnd-stat-title">Menunggu Approval</div>
                    <div class="bnd-stat-value" style="color:#0284c7;"><?= number_format($sudahDiverifikasi) ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 animate-up" style="animation-delay: 0.4s;">
            <div class="bnd-stat-card" style="--stat-color: #ec4899; --stat-bg: #fce7f3;">
                <div class="bnd-stat-icon-wrap"><i class="fas fa-money-check-alt"></i></div>
                <div>
                    <div class="bnd-stat-title">Belum Dibayar</div>
                    <div class="bnd-stat-value" style="color:#db2777;"><?= number_format($disetujui) ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 animate-up" style="animation-delay: 0.5s;">
            <div class="bnd-stat-card" style="--stat-color: #10b981; --stat-bg: #d1fae5;">
                <div class="bnd-stat-icon-wrap"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="bnd-stat-title">Total Dibayar</div>
                    <div class="bnd-stat-value sm-text" style="color:#059669;"><?= rupiah($totalDibayar) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Activity Logs -->
    <div class="bnd-activity-card animate-up" style="animation-delay: 0.6s;">
        <h4 class="fw-bold mb-4" style="color:#0f172a;"><i class="fas fa-bolt text-warning me-2"></i> Aktivitas Sistem Terbaru</h4>
        
        <?php if (empty($activities)): ?>
            <div class="text-center py-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width:60px;height:60px;">
                    <i class="fas fa-inbox text-muted fs-4"></i>
                </div>
                <h6 class="text-muted fw-normal">Belum ada catatan aktivitas di periode ini.</h6>
            </div>
        <?php else: ?>
            <ul class="timeline">
                <?php foreach ($activities as $idx => $activity): ?>
                    <li class="timeline-item">
                        <div class="timeline-badge" style="border-color: <?= $idx === 0 ? '#0ea5e9' : '#cbd5e1' ?>;"></div>
                        <div class="timeline-content">
                            <span class="timeline-time"><i class="far fa-clock me-1"></i> <?= esc(date('d M Y, H:i', strtotime($activity['created_at'] ?? 'now'))) ?></span>
                            <p class="timeline-text">
                                <strong class="text-dark"><?= esc($activity['activity_type'] ?? '-') ?></strong> &bull; 
                                <?= esc($activity['description'] ?? '-') ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    var HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    var BULAN = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    var elTime = document.getElementById('bnd-live-time');
    var elDate = document.getElementById('bnd-live-date');
    function pad(n) { return n < 10 ? '0' + n : n; }
    function tick() {
        var now = new Date();
        if (elTime) elTime.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        if (elDate) elDate.textContent = HARI[now.getDay()] + ', ' + now.getDate() + ' ' + BULAN[now.getMonth()] + ' ' + now.getFullYear();
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
<?= $this->endSection() ?>
