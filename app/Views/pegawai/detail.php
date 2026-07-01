<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 text-primary"><i class="fas fa-id-card me-2"></i> Detail Pegawai</h3>
    <a href="<?= site_url('pegawai') ?>" class="btn btn-secondary text-white shadow-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Left Column: Summary Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-navy text-white text-center py-4 border-0" style="background-color: #1e3a8a;">
                <div class="mb-3">
                    <div class="d-inline-flex bg-white rounded-circle align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-user text-navy fs-1" style="color: #1e3a8a;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1"><?= esc(strtoupper($pegawai['nama'])) ?></h5>
                <p class="mb-0 opacity-75 small"><?= esc($pegawai['nip']) ?></p>
            </div>
            <div class="card-body bg-white pt-4 text-center">
                <div class="mb-4">
                    <span class="badge bg-success px-3 py-1 ds-shadow me-2 rounded-pill">PNS</span>
                    <span class="badge bg-primary px-3 py-1 ds-shadow rounded-pill">Aktif</span>
                </div>
                
                <hr class="text-muted opacity-25">

                <div class="text-start px-2">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Jabatan</small>
                        <span class="fw-bold text-dark"><?= esc($pegawai['nama_jabatan']) ?></span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Golongan / Eselon</small>
                        <span class="fw-bold text-dark"><?= esc($pegawai['golongan'] ?? '-') ?> / <?= esc($pegawai['eselon'] ?? '-') ?></span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Gaji Pokok</small>
                        <span class="fw-bold text-dark fs-5">Rp <?= number_format((float)($pegawai['gaji_pokok'] ?? 0), 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Detailed Information -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 p-lg-5">
                
                <!-- Informasi Pribadi -->
                <div class="mb-4 pb-2">
                    <h5 class="text-primary fw-bold mb-3 d-flex align-items-center" style="color: #1e3a8a !important;">
                        <i class="fas fa-info-circle me-2"></i> Informasi Pribadi
                    </h5>
                    <hr class="text-muted opacity-25 mb-4">
                    
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">NIK</small>
                            <span class="text-dark"><?= esc($pegawai['nik'] ?: '-') ?></span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">NPWP</small>
                            <span class="text-dark"><?= esc($pegawai['npwp'] ?: '-') ?></span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">Tempat, Tanggal Lahir</small>
                            <span class="text-dark">
                                <?= esc($pegawai['tempat_lahir'] ?: '-') ?>, 
                                <?= $pegawai['tanggal_lahir'] ? date('d F Y', strtotime($pegawai['tanggal_lahir'])) : '-' ?>
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">Jenis Kelamin</small>
                            <span class="text-dark"><?= esc($pegawai['jenis_kelamin'] ?: '-') ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block fw-bold mb-1">Alamat</small>
                            <span class="text-dark"><?= esc($pegawai['alamat'] ?: '-') ?></span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">No Telp</small>
                            <span class="text-dark"><?= esc($pegawai['no_telp'] ?: '-') ?></span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">Email</small>
                            <span class="text-dark"><?= esc($pegawai['email'] ?: '-') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Keluarga -->
                <div class="mb-4 pb-2">
                    <h5 class="text-primary fw-bold mb-3 d-flex align-items-center" style="color: #1e3a8a !important;">
                        <i class="fas fa-users me-2"></i> Informasi Keluarga
                    </h5>
                    <hr class="text-muted opacity-25 mb-4">
                    
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">Status Pernikahan</small>
                            <span class="text-dark"><?= esc($pegawai['status_pernikahan'] ?: '-') ?></span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">Pasangan PNS</small>
                            <span class="text-dark"><?= ($pegawai['pasangan_pns'] ?? 0) ? 'Ya' : 'Tidak' ?></span>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block fw-bold mb-1">Jml Suami/Istri</small>
                            <span class="text-dark"><?= esc($pegawai['jumlah_istri_suami'] ?? '0') ?></span>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block fw-bold mb-1">Jml Anak</small>
                            <span class="text-dark"><?= esc($pegawai['jumlah_anak'] ?? '0') ?></span>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block fw-bold mb-1">Total Tanggungan</small>
                            <span class="text-dark"><?= esc($pegawai['jumlah_tanggungan'] ?? '0') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Data Bank & Pembayaran -->
                <div>
                    <h5 class="text-primary fw-bold mb-3 d-flex align-items-center" style="color: #1e3a8a !important;">
                        <i class="fas fa-university me-2"></i> Data Bank & Pembayaran
                    </h5>
                    <hr class="text-muted opacity-25 mb-4">
                    
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">Bank</small>
                            <span class="text-dark d-block"><?= esc(strtoupper($pegawai['nama_bank'] ?: '-')) ?></span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block fw-bold mb-1">No Rekening</small>
                            <span class="text-dark"><?= esc($pegawai['nomor_rekening'] ?: '-') ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
