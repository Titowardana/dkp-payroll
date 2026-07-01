<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Backup & Restore Database</h3>
</div>

<?php if (session()->getFlashdata('backup_message')): ?>
    <div class="alert alert-success alert-dismissible fade show" data-flash-token="<?= uniqid('bk_msg_') ?>">
        <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('backup_message')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('backup_error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" data-flash-token="<?= uniqid('bk_err_') ?>">
        <i class="fas fa-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('backup_error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4 d-flex align-items-stretch">
    <!-- CREATE BACKUP -->
    <div class="col-sm-6 col-xl-4">
        <div class="card metric-card text-center h-100 shadow-sm border-0 w-100">
            <div class="card-body d-flex flex-column">
                <div class="metric-icon bg-green mb-3 mx-auto">
                    <i class="fas fa-save"></i>
                </div>
                <h5 class="fw-bold">Create Backup</h5>
                <p class="text-muted small flex-grow-1">Buat backup database saat ini</p>
                <button type="button" class="btn btn-success mt-2" onclick="showBackupModal()">
                    <i class="fas fa-download"></i> Backup Sekarang
                </button>
            </div>
        </div>
    </div>
    
    <!-- CLEANUP OLD BACKUPS -->
    <div class="col-sm-6 col-xl-4">
        <div class="card metric-card text-center h-100 shadow-sm border-0 w-100">
            <div class="card-body d-flex flex-column">
                <div class="metric-icon bg-gold mb-3 mx-auto">
                    <i class="fas fa-broom"></i>
                </div>
                <h5 class="fw-bold">Cleanup Backups</h5>
                <p class="text-muted small flex-grow-1">Hapus file backup tertinggal (> 30 hari)</p>
                <button type="button" class="btn btn-warning mt-2 w-100 fw-bold" onclick="showCleanupModal()">
                    <i class="fas fa-trash"></i> Cleanup
                </button>
            </div>
        </div>
    </div>

    <!-- PRUNE DATA CARD -->
    <div class="col-sm-6 col-xl-4">
        <div class="card metric-card text-center h-100 shadow-sm w-100" style="border: 1.5px dashed #ef4444; background: #fffcfc;">
            <div class="card-body d-flex flex-column">
                <div class="metric-icon mb-3 mx-auto" style="background:#fee2e2; color:#dc2626; border-radius:12px;">
                    <i class="fas fa-skull-crossbones"></i>
                </div>
                <h5 class="text-danger fw-bold">Prune Data</h5>
                <p class="text-danger small flex-grow-1 mb-2">Hapus riwayat slip gaji kuno (Permanen)</p>
                <form id="formPruneData" action="<?= site_url('backup/prunedata') ?>" method="POST" class="mt-auto">
                    <?= csrf_field() ?>
                    <select name="months" id="pruneSelectMonths" class="form-select form-select-sm mb-2 text-center" style="border-color:#fca5a5; background-color:#fff;">
                        <option value="3">Usia > 3 Bulan</option>
                        <option value="6">Usia > 6 Bulan</option>
                        <option value="12">Usia > 1 Tahun</option>
                        <option value="36" selected>Usia > 3 Tahun</option>
                        <option value="60">Usia > 5 Tahun</option>
                    </select>
                    <button type="button" class="btn btn-danger btn-sm w-100 fw-bold shadow-sm" onclick="showPruneModal()">
                        <i class="fas fa-fire me-1"></i>Eksekusi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card section-card">
    <div class="card-header bg-white border-0 pt-4 pb-0">
        <h5 class="mb-0"><i class="fas fa-history text-muted me-2"></i> Riwayat Backup</h5>
        <div class="text-end" style="margin-top: -30px;">
            <span class="badge bg-info p-2">
                <i class="fas fa-database me-1"></i> Database Size: <?= esc($db_size) ?>
            </span>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama File</th>
                    <th>Tanggal</th>
                    <th>Ukuran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($backups)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Belum ada file backup</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($backups as $i => $backup): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <i class="far fa-file-code text-primary me-2"></i>
                            <?= esc($backup['name']) ?>
                        </td>
                        <td><?= date('d/m/Y H:i:s', $backup['date']) ?></td>
                        <td><?= number_format($backup['size'] / 1024, 2) ?> KB</td>
                        <td>
                            <div class="d-flex" style="gap: 5px;">
                                <a href="<?= site_url('backup/download/' . $backup['name']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="showActionRestore('<?= esc($backup['name'], 'js') ?>')">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-danger" onclick="showActionDelete('<?= esc($backup['name'], 'js') ?>')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create Backup -->
<div class="modal fade" id="modalCreateBackup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-save me-2"></i> Konfirmasi Backup</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3"><i class="fas fa-cloud-download-alt text-success" style="font-size: 4rem; opacity: 0.8;"></i></div>
                <h5 class="fw-bold text-dark mb-2">Buat Backup Database Baru?</h5>
                <p class="text-muted mb-0">Proses pencadangan ini mungkin akan memakan waktu beberapa saat tergantung pada besarnya ukuran database Anda.</p>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #bbf7d0;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                <form action="<?= site_url('backup/create') ?>" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success fw-bold shadow-sm"><i class="fas fa-download me-1"></i> Ya, Backup Sekarang!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cleanup Backup -->
<div class="modal fade" id="modalCleanupBackup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="fas fa-broom me-2"></i> Konfirmasi Cleanup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3"><i class="fas fa-trash-alt text-warning" style="font-size: 4rem; opacity: 0.8;"></i></div>
                <h5 class="fw-bold text-dark mb-2">Bersihkan File Backup Kuno?</h5>
                <p class="text-muted mb-0">File-file arsip *backup* lama yang telah berusia lebih dari <strong>30 Hari</strong> akan dihapus permanen dari memori server.</p>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #fde68a;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                <form action="<?= site_url('backup/cleanup') ?>" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-warning fw-bold shadow-sm"><i class="fas fa-trash me-1"></i> Ya, Bersihkan!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Prune Warning -->
<div class="modal fade" id="modalPruneWarning" tabindex="-1" aria-labelledby="modalPruneWarningLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="modalPruneWarningLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Peringatan Destruktif!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-fire-alt text-danger" style="font-size: 4rem; opacity: 0.8;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Anda Yakin Ingin Menghancurkan Data?</h5>
                <p class="text-muted mb-0">
                    Seluruh riwayat <strong>Slip Gaji</strong> dan <strong>Periode Penggajian</strong> yang ditetapkan (<span id="pruneWarningText" class="fw-bold text-danger"></span>) 
                    akan dilenyapkan secara permanen dari *database*.
                </p>
                <div class="alert alert-warning mt-3 text-start small mb-0">
                    <i class="fas fa-info-circle me-1"></i> Data yang telah dihapus tidak dapat dipulihkan kecuali Anda mengatur file *Backup.sql* sebelumnya.
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #fecaca;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-danger fw-bold shadow-sm" onclick="submitPruneForm()">
                    <i class="fas fa-skull-crossbones me-1"></i> Ya, Hancurkan!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Action Restore (Table) -->
<div class="modal fade" id="modalActionRestore" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-undo-alt me-2"></i> Peringatan Restore Database</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3"><i class="fas fa-database text-info" style="font-size: 4rem; opacity: 0.8;"></i></div>
                <h5 class="fw-bold text-dark mb-2">Timpa Database Saat Ini?</h5>
                <p class="text-muted mb-0">Seluruh data *Payroll* terkini akan direlakan dan ditimpa ke versi file backup: <br> <strong id="lblRestoreFilename" class="text-info"></strong></p>
                <div class="alert alert-warning mt-3 text-start small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i> Tindakan restorasi ini bersifat mengikat dan tidak dapat dibatalkan (kecuali Anda punya backup cadangan lain).
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #bae6fd;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                <form id="formActionRestore" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-info fw-bold shadow-sm text-white"><i class="fas fa-check-circle me-1"></i> Ya, Restore Sekarang!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Action Delete (Table) -->
<div class="modal fade" id="modalActionDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-trash me-2"></i> Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3"><i class="far fa-file-excel text-danger" style="font-size: 4rem; opacity: 0.8;"></i></div>
                <h5 class="fw-bold text-dark mb-2">Hapus File Backup Ini?</h5>
                <p class="text-muted mb-0">File backup <strong id="lblDeleteFilename" class="text-danger"></strong> akan dibuang secara permanen dari server Anda.</p>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #fecaca;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                <form id="formActionDelete" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger fw-bold shadow-sm"><i class="fas fa-trash me-1"></i> Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Script remains here, appending modal functions below -->
<script>
// Modal Logic for Create Backup
let backupModalInstance;
function showBackupModal() {
    var modalEl = document.getElementById('modalCreateBackup');
    if (!backupModalInstance) backupModalInstance = new bootstrap.Modal(modalEl);
    backupModalInstance.show();
}

// Modal Logic for Cleanup Backup
let cleanupModalInstance;
function showCleanupModal() {
    var modalEl = document.getElementById('modalCleanupBackup');
    if (!cleanupModalInstance) cleanupModalInstance = new bootstrap.Modal(modalEl);
    cleanupModalInstance.show();
}

// Modal Logic for Prune Data
let pruneModalInstance;
function showPruneModal() {
    // Get selected text
    var select = document.getElementById('pruneSelectMonths');
    var selectedText = select.options[select.selectedIndex].text;
    document.getElementById('pruneWarningText').textContent = selectedText;
    
    // Show modal
    var modalEl = document.getElementById('modalPruneWarning');
    if (!pruneModalInstance) {
        pruneModalInstance = new bootstrap.Modal(modalEl);
    }
    pruneModalInstance.show();
}

function submitPruneForm() {
    document.getElementById('formPruneData').submit();
}

// Modal Logic for Table Action: Restore
let restoreActionModalInstance;
function showActionRestore(filename) {
    document.getElementById('lblRestoreFilename').textContent = filename;
    document.getElementById('formActionRestore').action = "<?= site_url('backup/restore') ?>/" + filename;
    
    var modalEl = document.getElementById('modalActionRestore');
    if (!restoreActionModalInstance) restoreActionModalInstance = new bootstrap.Modal(modalEl);
    restoreActionModalInstance.show();
}

// Modal Logic for Table Action: Delete
let deleteActionModalInstance;
function showActionDelete(filename) {
    document.getElementById('lblDeleteFilename').textContent = filename;
    document.getElementById('formActionDelete').action = "<?= site_url('backup/delete') ?>/" + filename;
    
    var modalEl = document.getElementById('modalActionDelete');
    if (!deleteActionModalInstance) deleteActionModalInstance = new bootstrap.Modal(modalEl);
    deleteActionModalInstance.show();
}
</script>
<?= $this->endSection() ?>
