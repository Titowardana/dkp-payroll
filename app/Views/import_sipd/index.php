<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Hero Banner */
    .import-hero {
        background: linear-gradient(135deg, #0077b6 0%, #023e8a 60%, #00b4d8 100%);
        border-radius: 16px;
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 119, 182, 0.35);
        margin-bottom: 24px;
        color: white;
    }
    .import-hero::before {
        content: ''; position: absolute; top: -40px; right: -50px;
        width: 200px; height: 200px; background: rgba(255,255,255,0.07);
        border-radius: 50%; pointer-events: none; z-index: 0;
    }
    .import-hero::after {
        content: ''; position: absolute; bottom: -30px; right: 80px;
        width: 130px; height: 130px; background: rgba(255,255,255,0.05);
        border-radius: 50%; pointer-events: none; z-index: 0;
    }
    .import-hero .hero-inner { position: relative; z-index: 1; }
    
    /* Upload Card */
    .upload-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        border: none;
        padding: 30px;
    }
    
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        padding: 35px 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: #00b4d8;
        background: #f0f9ff;
    }
    .upload-input {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0; cursor: pointer;
    }
    .file-name-display {
        font-weight: 600;
        color: #0369a1;
        display: none;
        margin-top: 15px;
        padding: 8px 15px;
        background: #e0f2fe;
        border-radius: 8px;
    }
    
    /* Select & Input */
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; letter-spacing: 0.3px; }
    .form-select, .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 0.95rem;
    }
    .form-select:focus, .form-control:focus {
        border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }
    
    /* Submit Button */
    .btn-import {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        border: none; border-radius: 10px; padding: 12px 24px;
        font-weight: 600; font-size: 1rem; color: white;
        transition: all 0.2s;
    }
    .btn-import:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: white;
        transform: translateY(-2px); box-shadow: 0 6px 15px rgba(2, 132, 199, 0.3);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$_flash_msg = session()->getFlashdata('im_msg');
$_flash_err = session()->getFlashdata('im_err');
$errs = session('import_errors') ?? [];
?>

<!-- Hero Banner -->
<div class="import-hero">
    <div class="hero-inner d-flex align-items-center">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px;">
            <i class="fas fa-file-excel text-white fs-4"></i>
        </div>
        <div>
            <h3 class="mb-1 fw-bold" style="letter-spacing: 0.3px; font-size: 1.5rem;">Import Data SIPD</h3>
            <div style="font-size: 0.85rem; opacity: 0.8;">
                Integrasi otomatis data Excel SIPD ke sistem DKP Payroll
            </div>
        </div>
    </div>
</div>

<?php if ($_flash_msg): ?>
<div class="alert alert-success alert-dismissible bg-white border-0 shadow-sm" style="border-left: 4px solid #10b981;" data-flash-token="<?= uniqid('im_msg_') ?>">
    <i class="fas fa-check-circle me-2" style="color: #10b981;"></i> <strong>Berhasil!</strong> <?= esc($_flash_msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($_flash_err): ?>
<div class="alert alert-danger alert-dismissible bg-white border-0 shadow-sm" style="border-left: 4px solid #ef4444;" data-flash-token="<?= uniqid('im_err_') ?>">
    <i class="fas fa-exclamation-triangle me-2" style="color: #ef4444;"></i> <strong>Gagal!</strong> <?= esc($_flash_err) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($errs): ?>
<div class="alert alert-warning border-0 shadow-sm" style="border-left: 4px solid #f59e0b; border-radius:12px;">
    <strong><i class="fas fa-info-circle me-1"></i> Terdapat baris data yang dilewati/gagal diproses:</strong>
    <ul class="mb-0 mt-2 text-dark" style="font-size:0.9rem;">
        <?php foreach($errs as $e): ?>
            <li><?= esc($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="upload-card">
            
            <div class="d-flex mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px; flex-shrink: 0;">
                    <i class="fas fa-info-circle fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Panduan Import</h5>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">Upload file Excel bersumber dari sistem SIPD format <b>.xls</b> atau <b>.xlsx</b>. Sistem akan otomatis mendeteksi pegawai baru, memperbarui data lama, dan membuat draft slip gaji sesuai periode yang Anda tentukan.</p>
                    <div class="mt-2">
                        <a href="<?= base_url('assets/templates/import_sipd_template.xlsx') ?>" class="btn btn-sm btn-outline-success fw-semibold" style="border-radius:8px;">
                            <i class="fas fa-download me-1"></i> Download Template Excel
                        </a>
                    </div>
                </div>
            </div>

            <form method="post" action="<?= site_url('import-sipd') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label"><i class="far fa-calendar-alt me-1 text-primary"></i> Bulan Penggajian</label>
                        <select name="bulan" class="form-select shadow-sm" required>
                            <?php for ($num=1; $num<=12; $num++): ?>
                                <option value="<?= $num ?>" <?= (int)old('bulan', date('n')) === $num ? 'selected' : '' ?>><?= bulan_nama($num) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="far fa-calendar-check me-1 text-primary"></i> Tahun Penggajian</label>
                        <input type="number" name="tahun" class="form-control shadow-sm" value="<?= esc(old('tahun', date('Y'))) ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-file-excel me-1 text-success"></i> File Data Excel</label>
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" name="excel_file" class="upload-input" accept=".xls,.xlsx" required id="fileInput">
                        <i class="fas fa-cloud-upload-alt text-muted mb-3" style="font-size: 3.5rem;"></i>
                        <h5 class="fw-bold text-dark mb-2">Klik atau Seret file Excel ke area ini</h5>
                        <p class="text-muted small mb-0">Format yang didukung: XLS, XLSX (Maksimal 5MB)</p>
                        <div class="file-name-display mt-3" id="fileNameDisplay">
                            <i class="fas fa-file-excel me-2"></i> <span id="fileNameText">Belum ada file dipilih</span>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-import shadow-sm">
                        <i class="fas fa-rocket me-2"></i> Mulai Proses Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileNameText = document.getElementById('fileNameText');

    fileInput.addEventListener('change', function() {
        if(this.files && this.files.length > 0) {
            fileNameText.textContent = this.files[0].name;
            fileNameDisplay.style.display = 'inline-block';
            uploadZone.style.borderColor = '#10b981';
            uploadZone.style.background = '#f0fdf4';
        } else {
            fileNameDisplay.style.display = 'none';
            uploadZone.style.borderColor = '#cbd5e1';
            uploadZone.style.background = '#f8fafc';
        }
    });

    // Drag-and-drop visual hints
    uploadZone.addEventListener('dragover', (e) => {
        uploadZone.classList.add('dragover');
    });
    uploadZone.addEventListener('dragleave', (e) => {
        uploadZone.classList.remove('dragover');
    });
    uploadZone.addEventListener('drop', (e) => {
        uploadZone.classList.remove('dragover');
    });
</script>
<?= $this->endSection() ?>
