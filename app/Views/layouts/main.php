<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="x-page-id" content="<?= uniqid('p', true) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'DKP Slip Gaji') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/modern.css') ?>">
    <style>
        :root{--dkp-primary:#0077b6;--dkp-primary-dark:#023e8a;--dkp-accent:#00b4d8;--dkp-green:#2bb673;--dkp-yellow:#ffc857;--dkp-bg:#f3f7fb}
        body{background:radial-gradient(circle at top left,#e0f4ff 0,#f3f7fb 45%,#ffffff 100%);font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;min-height:100vh}
        .navbar{background:linear-gradient(90deg,var(--dkp-primary-dark),var(--dkp-primary))}
        .sidebar{background:#fff;min-height:calc(100vh - 56px);box-shadow:0 0 18px rgba(0,0,0,.06)}
        .sidebar .list-group-item{border:none;padding:12px 20px}.sidebar .list-group-item.active{background:linear-gradient(90deg,var(--dkp-primary),var(--dkp-accent));color:#fff;font-weight:600;border-left:4px solid var(--dkp-yellow)}
        .main-content-wrapper{padding:20px 18px 32px}
        .hero-card{background:linear-gradient(120deg,var(--dkp-primary-dark),var(--dkp-primary),var(--dkp-accent));color:#fff;border-radius:18px;overflow:hidden;position:relative}
        .hero-card::after{content:'';position:absolute;right:-60px;top:-40px;width:220px;height:220px;background:rgba(255,255,255,.12);border-radius:50%}
        .metric-card,.section-card{background:#fff;border-radius:16px;box-shadow:0 8px 18px rgba(0,0,0,.05);border:none}
        .metric-card{box-shadow:0 8px 18px rgba(2,62,138,.08)} .metric-icon{width:52px;height:52px;display:inline-flex;align-items:center;justify-content:center;border-radius:14px;color:#fff;font-size:1.15rem}
        .bg-blue{background:linear-gradient(135deg,#0077b6,#00b4d8)} .bg-green{background:linear-gradient(135deg,#1f9d67,#2bb673)} .bg-gold{background:linear-gradient(135deg,#f6a609,#ffc857)} .bg-navy{background:linear-gradient(135deg,#023e8a,#0077b6)}
        @media print {
            .navbar, .sidebar { display: none !important; }
            .col-md-9, .col-lg-10 { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; margin-top: 0 !important; }
            body { background: #fff !important; min-height: auto !important; }
            .main-content-wrapper { padding: 0 !important; }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center text-white text-decoration-none" href="<?= session('role') === 'bendahara' ? site_url('bendahara/dashboard') : site_url('dashboard') ?>">
            <img src="<?= base_url('assets/images/logo-dkp-kepri.png') ?>" alt="DKP Kepri" style="height: 54px; background: transparent; padding: 0; box-shadow: none; filter: drop-shadow(0 3px 10px rgba(0,0,0,0.4));" class="me-3">
            <span class="fw-bold" style="letter-spacing: 0.5px; font-size: 1.35rem;">DKP SLIP GAJI</span>
        </a>
        <div class="d-flex align-items-center">
            <span class="me-3 text-light"><i class="fas fa-user-circle"></i> <?= esc(session('username') ?? 'User') ?></span>
            <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-3 col-lg-2 px-0 sidebar">
      <div class="list-group list-group-flush">
        <?php if (session('role') === 'bendahara'):
            $payrollModel = new \App\Models\PayrollDetailModel();
            $badgeVerif   = $payrollModel->where('status','draft')->countAllResults();
            $badgeApprv   = $payrollModel->where('status','verified')->countAllResults();
        ?>
            <a href="<?= site_url('bendahara/dashboard') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'bendahara_dashboard' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="<?= site_url('bendahara/verifikasi') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($activeMenu ?? '') === 'verifikasi' ? 'active' : '' ?>">
                <span><i class="fas fa-clipboard-check me-2"></i> Verifikasi</span>
                <?php if ($badgeVerif > 0): ?><span class="badge rounded-pill" style="background:#f59e0b; color:#fff; font-size:0.7rem;"><?= $badgeVerif ?></span><?php endif; ?>
            </a>
            <a href="<?= site_url('bendahara/approval') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($activeMenu ?? '') === 'approval' ? 'active' : '' ?>">
                <span><i class="fas fa-thumbs-up me-2"></i> Approval</span>
                <?php if ($badgeApprv > 0): ?><span class="badge rounded-pill" style="background:#0284c7; color:#fff; font-size:0.7rem;"><?= $badgeApprv ?></span><?php endif; ?>
            </a>
            <a href="<?= site_url('bendahara/finalisasi') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'finalisasi' ? 'active' : '' ?>"><i class="fas fa-lock me-2"></i> Finalisasi</a>
            <a href="<?= site_url('bendahara/histori') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'histori' ? 'active' : '' ?>"><i class="fas fa-history me-2"></i> Histori Aksi</a>
        <?php else:
            $payrollAdminModel = new \App\Models\PayrollDetailModel();
            $badgeRevisi       = $payrollAdminModel->where('status','rejected')->countAllResults();
        ?>
            <a href="<?= site_url('dashboard') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="<?= site_url('pegawai') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'pegawai' ? 'active' : '' ?>"><i class="fas fa-users me-2"></i> Data Pegawai</a>
            <a href="<?= site_url('generate-slip') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'generate_slip' ? 'active' : '' ?>"><i class="fas fa-calculator me-2"></i> Generate Slip</a>
            <a href="<?= site_url('slip') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($activeMenu ?? '') === 'slip' ? 'active' : '' ?>">
                <span><i class="fas fa-file-pdf me-2"></i> Slip Gaji</span>
                <?php if ($badgeRevisi > 0): ?><span class="badge rounded-pill" style="background:#dc2626; color:#fff; font-size:0.7rem;"><?= $badgeRevisi ?> Revisi</span><?php endif; ?>
            </a>
            <a href="<?= site_url('laporan') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'laporan' ? 'active' : '' ?>"><i class="fas fa-chart-bar me-2"></i> Laporan</a>
            <a href="<?= site_url('backup') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'backup' ? 'active' : '' ?>"><i class="fas fa-database me-2"></i> Backup Data</a>
            <a href="<?= site_url('import-sipd') ?>" class="list-group-item list-group-item-action <?= ($activeMenu ?? '') === 'import_sipd' ? 'active' : '' ?>"><i class="fas fa-file-import me-2"></i> Import SIPD</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-md-9 col-lg-10 mt-3"><div class="main-content-wrapper"><?= $this->renderSection('content') ?></div></div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
<script>
// ── Global Flash Message Guard ──────────────────────────────────────────────────
//
// CARA KERJA:
//  - Setiap render PHP menghasilkan pageId UNIK (via meta x-page-id).
//  - sessionStorage key  =  hash(pageId + konten pesan)
//  - Render baru (edit/tambah lagi) → pageId baru → key baru → notifikasi tampil ✅
//  - Refresh (F5) namun CI4 sudah hapus flash → tidak ada elemen alert → aman ✅
//  - Tombol Back (BFCache) → pageId SAMA → key ditemukan → langsung hapus ✅
//
(function () {
    // Ambil pageId yang sudah di-embed PHP lewat meta tag
    var metaPageId = document.querySelector('meta[name="x-page-id"]');
    var pageId = metaPageId ? metaPageId.getAttribute('content') : String(Date.now());

    // Hash sederhana djb2 (cepat, tidak perlu kriptografi)
    function djb2(str) {
        var h = 5381;
        for (var i = 0; i < str.length; i++) {
            h = ((h << 5) + h) + str.charCodeAt(i);
            h = h & h; // paksa 32-bit integer
        }
        return 'fk_' + Math.abs(h).toString(36);
    }

    function sessionKey(el) {
        // Gabungkan pageId + teks konten → setiap page-render punya ruang sendiri
        var text = (el.innerText || el.textContent || '').replace(/\s+/g, ' ').trim();
        return djb2(pageId + '|' + text);
    }

    function handleAlerts() {
        document.querySelectorAll('[data-flash-token]').forEach(function (el) {
            var key = sessionKey(el);
            if (sessionStorage.getItem(key)) {
                // Sudah tampil pada render ini (BFCache) → hapus segera tanpa animasi
                el.remove();
            } else {
                // Pertama kali tampil → simpan key + auto-dismiss 4 detik
                sessionStorage.setItem(key, '1');
                setTimeout(function () {
                    if (!el.parentNode) return;
                    el.style.transition = 'opacity 0.6s ease, transform 0.5s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-8px)';
                    setTimeout(function () { if (el.parentNode) el.remove(); }, 650);
                }, 4000);
            }
        });
    }

    // Jalankan setelah DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleAlerts);
    } else {
        handleAlerts();
    }

    // BFCache: saat halaman dikembalikan dari cache browser, hapus semua alert
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            document.querySelectorAll('[data-flash-token]').forEach(function (el) {
                el.style.display = 'none'; // sembunyikan dulu (cegah flicker)
                el.remove();
            });
        }
    });

    // Sebelum halaman di-cache: sembunyikan alert agar tidak terlihat saat di-restore
    window.addEventListener('pagehide', function () {
        document.querySelectorAll('[data-flash-token]').forEach(function (el) {
            el.style.display = 'none';
        });
    });
})();
</script>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0" style="background: linear-gradient(135deg, #dc2626, #ef4444); color: white;">
        <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-sign-out-alt me-2"></i>Konfirmasi Keluar</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4 text-center">
        <div class="mb-3">
            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3.5rem;"></i>
        </div>
        <h5 class="mb-2 fw-bold">Yakin Ingin Keluar?</h5>
        <p class="text-muted mb-0">Sesi Anda akan segera diakhiri dan dikembalikan ke halaman login utama.</p>
      </div>
      <div class="modal-footer border-0 d-flex justify-content-center pb-4 pt-0">
        <button type="button" class="btn btn-light px-4 shadow-sm" data-bs-dismiss="modal">Kembali</button>
        <a href="<?= site_url('logout') ?>" class="btn btn-danger px-4 shadow-sm">Ya, Keluar Akun</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
