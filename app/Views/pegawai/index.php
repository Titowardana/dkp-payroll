<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* ── Hero Banner ── */
    .slip-hero {
        background: linear-gradient(135deg, #0077b6 0%, #023e8a 60%, #00b4d8 100%);
        border-radius: 16px;
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(0, 150, 199, 0.45);
        margin-bottom: 20px;
    }
    .slip-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .slip-hero::after {
        content: '';
        position: absolute;
        bottom: -30px; right: 80px;
        width: 130px; height: 130px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .slip-hero .hero-inner {
        position: relative;
        z-index: 1;
    }
    .slip-hero h3 { font-size: 1.5rem; letter-spacing: 0.3px; }
    .slip-hero .hero-sub { font-size: 0.85rem; opacity: 0.9; }

    /* ── Action Buttons ── */
    .btn-hero { border-radius: 9px; font-weight: 600; font-size: 0.85rem; padding: 8px 18px; transition: all 0.18s ease; }
    .btn-manual { background: #facc15; border: none; color: #1f2937; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .btn-manual:hover { background: #eab308; color: #1f2937; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(234,179,8,0.4); }

    /* ── Table Styling ── */
    .table-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        padding: 20px 24px;
        border: none;
    }
    #dataTable { font-size: 0.875rem; width: 100% !important; border-collapse: separate; border-spacing: 0; }
    #dataTable thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 14px;
        white-space: nowrap;
        border-top: none;
    }
    #dataTable tbody tr { transition: background 0.12s; }
    #dataTable tbody tr:hover { background: #f0f9ff; }
    #dataTable tbody td { padding: 12px 14px; vertical-align: middle; white-space: nowrap; border-color: #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    
    .employee-name { font-weight: 700; color: #1e3a5f; }
    .nip-text { font-family: monospace; font-size: 0.8rem; color: #64748b; }
    .gaji-text { font-weight: 700; color: #059669; }

    /* ── Action buttons in table ── */
    .action-group { display: flex; gap: 5px; align-items: center; }
    .btn-act { border-radius: 7px; font-size: 0.75rem; font-weight: 600; padding: 5px 10px; border: none; transition: all 0.15s; text-decoration: none; display: inline-flex; align-items: center; }
    .btn-act-preview { background:#f0fdf4; color:#15803d; } .btn-act-preview:hover { background:#15803d; color:#fff; } /* Detail */
    .btn-act-edit    { background:#fffbeb; color:#b45309; } .btn-act-edit:hover    { background:#b45309; color:#fff; } /* Edit */
    .btn-act-danger  { background:#fef2f2; color:#dc2626; } .btn-act-danger:hover  { background:#dc2626; color:#fff; } /* Hapus */

    /* Custom DataTables wrappers */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        padding: 4px 12px;
        font-size: 0.875rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #10b981;
        outline: none;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$_flash_msg  = session()->getFlashdata('pg_msg');
$_flash_err  = session()->getFlashdata('pg_err');
$_show_modal = session()->getFlashdata('show_modal');
// CI4 otomatis menghapus flashdata setelah dibaca
$isCsrfError       = $_flash_err === 'The action you requested is not allowed.';
$isPegawaiFormError = $_flash_err && !$isCsrfError && $_show_modal;
$showModal = ($editData || $isPegawaiFormError) ? true : false;
$editMode  = $editData ? true : false;
?>

<!-- Hero Banner -->
<div class="slip-hero mb-4">
    <div class="hero-inner d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px;">
                <i class="fas fa-users text-white fs-4"></i>
            </div>
            <div>
                <h3 class="text-white mb-1 fw-bold">Data Pegawai</h3>
                <div class="text-white hero-sub">
                    Kelola basis data pegawai, jabatan, golongan, serta gaji pokok
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form method="get" action="<?= site_url('pegawai') ?>" class="d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm" style="border-radius:8px; overflow:hidden;">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border:1.5px solid #e5e7eb; border-right:0;">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="keyword" class="form-control border-start-0" style="border:1.5px solid #e5e7eb; border-left:0; min-width:200px;" placeholder="Cari NIP / Nama..." value="<?= esc($keyword) ?>">
                </div>
                <button type="submit" class="btn btn-sm btn-outline-light fw-semibold" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Cari</button>
                <?php if ($keyword): ?>
                <a href="<?= site_url('pegawai') ?>" class="btn btn-sm btn-outline-light fw-semibold" style="border-radius:8px;"><i class="fas fa-undo me-1"></i> Reset</a>
                <?php endif; ?>
            </form>
            <button type="button" class="btn btn-hero btn-manual d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#pegawaiModal">
                <i class="fas fa-user-plus me-2"></i> Tambah Pegawai
            </button>
        </div>
    </div>
</div>

<?php if ($_flash_msg): ?><div class="alert alert-success alert-dismissible fade show" data-flash-token="<?= uniqid('pg_msg_') ?>"><i class="fas fa-check-circle me-2"></i><?= esc($_flash_msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($_flash_err && !$isCsrfError): ?><div class="alert alert-danger alert-dismissible fade show" data-flash-token="<?= uniqid('pg_err_') ?>"><i class="fas fa-exclamation-circle me-2"></i><?= esc($_flash_err) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>


<!-- Data Table -->
<div class="table-container mb-4">
    <div class="table-responsive">
        <table id="dataTable" class="table align-middle">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">#</th>
                    <th>NIP</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th class="text-center">Gol</th>
                    <th class="text-center">Eselon</th>
                    <th>Gaji Pokok</th>
                    <th class="text-center" style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pegawai as $i => $row): ?>
                <tr>
                    <td class="text-center text-muted"><?= $i+1 ?></td>
                    <td class="nip-text"><?= esc($row['nip']) ?></td>
                    <td class="employee-name"><?= esc($row['nama']) ?></td>
                    <td><div class="text-truncate" style="max-width: 180px;" title="<?= esc($row['nama_jabatan']) ?>"><?= esc($row['nama_jabatan']) ?></div></td>
                    <td class="text-center"><?= esc($row['golongan'] ?? '-') ?></td>
                    <td class="text-center"><?= esc($row['eselon'] ?? '-') ?></td>
                    <td class="gaji-text"><?= rupiah((float)($row['gaji_pokok'] ?? 0)) ?></td>
                    <td>
                        <div class="action-group justify-content-center">
                            <a class="btn-act btn-act-edit shadow-sm" href="<?= site_url('pegawai?edit='.$row['id']) ?>" title="Edit">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a> 
                            <a class="btn-act btn-act-preview shadow-sm" href="<?= site_url('pegawai/detail/'.$row['id']) ?>" title="Detail">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a> 
                            <button type="button" class="btn-act btn-act-danger shadow-sm" onclick="showDeleteModal('<?= esc($row['id'], 'js') ?>', '<?= esc($row['nama'], 'js') ?>')" title="Hapus">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($pager) && $pager): ?>
<div class="mt-3 d-flex justify-content-end">
    <?= $pager->links('default', 'bootstrap5') ?>
</div>
<?php endif; ?>

<!-- Modal Tambah/Edit Pegawai -->
<div class="modal fade" id="pegawaiModal" tabindex="-1" aria-labelledby="pegawaiModalLabel" aria-hidden="true" <?= $showModal ? 'data-show-modal="true"' : '' ?>>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pegawaiModalLabel"><?= $editMode ? 'Edit Pegawai' : 'Tambah Pegawai Baru' ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= site_url('pegawai/save') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= esc($editData['id'] ?? '') ?>">
                
                <div class="modal-body pb-0">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-3" id="pegawaiTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pribadi-tab" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab">Data Pribadi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pekerjaan-tab" data-bs-toggle="tab" data-bs-target="#pekerjaan" type="button" role="tab">Pekerjaan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="keluarga-tab" data-bs-toggle="tab" data-bs-target="#keluarga" type="button" role="tab">Keluarga</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab">Bank & Gaji</button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="pegawaiTabContent">
                        
                        <!-- Tab Data Pribadi -->
                        <div class="tab-pane fade show active" id="pribadi" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" name="nama" value="<?= esc(old('nama', $editData['nama'] ?? '')) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NIK *</label>
                                    <input type="text" class="form-control" name="nik" placeholder="Isi jika tersedia" value="<?= esc(old('nik', $editData['nik'] ?? '')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tempat Lahir *</label>
                                    <input type="text" class="form-control" name="tempat_lahir" placeholder="Isi jika tersedia" value="<?= esc(old('tempat_lahir', $editData['tempat_lahir'] ?? '')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir *</label>
                                    <input type="date" class="form-control" name="tanggal_lahir" value="<?= esc(old('tanggal_lahir', $editData['tanggal_lahir'] ?? '')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" name="jenis_kelamin">
                                        <option value="L" <?= old('jenis_kelamin', $editData['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-Laki</option>
                                        <option value="P" <?= old('jenis_kelamin', $editData['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NPWP *</label>
                                    <input type="text" class="form-control" name="npwp" placeholder="Isi jika tersedia" value="<?= esc(old('npwp', $editData['npwp'] ?? '')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No Telp *</label>
                                    <input type="text" class="form-control" name="no_telp" placeholder="Isi jika tersedia" value="<?= esc(old('no_telp', $editData['no_telp'] ?? '')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" placeholder="Isi jika tersedia" value="<?= esc(old('email', $editData['email'] ?? '')) ?>">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Alamat *</label>
                                    <textarea class="form-control" name="alamat" rows="2" placeholder="Isi jika tersedia"><?= esc(old('alamat', $editData['alamat'] ?? '')) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Pekerjaan -->
                        <div class="tab-pane fade" id="pekerjaan" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">NIP *</label>
                                    <input type="text" class="form-control" name="nip" value="<?= esc(old('nip', $editData['nip'] ?? '')) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jabatan *</label>
                                    <input type="text" class="form-control" name="nama_jabatan" value="<?= esc(old('nama_jabatan', $editData['nama_jabatan'] ?? '')) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Golongan *</label>
                                    <select class="form-select" name="golongan">
                                        <option value="">Pilih Golongan</option>
                                        <?php 
                                        $golongan_list = ['I/A','I/B','I/C','I/D','II/A','II/B','II/C','II/D','III/A','III/B','III/C','III/D','IV/A','IV/B','IV/C','IV/D','IV/E'];
                                        foreach($golongan_list as $gol): 
                                        ?>
                                        <option value="<?= $gol ?>" <?= old('golongan', $editData['golongan'] ?? '') === $gol ? 'selected' : '' ?>><?= $gol ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Eselon *</label>
                                    <select class="form-select" name="eselon">
                                        <option value="">Pilih Eselon</option>
                                        <?php 
                                        $eselon_list = ['00','IA','IB','IIA','IIB','IIIA','IIIB','IVA','IVB','VA','VB'];
                                        foreach($eselon_list as $esel): 
                                        ?>
                                        <option value="<?= $esel ?>" <?= old('eselon', $editData['eselon'] ?? '') === $esel ? 'selected' : '' ?>><?= $esel ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Keluarga -->
                        <div class="tab-pane fade" id="keluarga" role="tabpanel">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Status Pernikahan</label>
                                    <select class="form-select" name="status_pernikahan">
                                        <?php foreach(['Belum Menikah','Menikah','Cerai'] as $s): ?>
                                            <option value="<?= $s ?>" <?= old('status_pernikahan', $editData['status_pernikahan'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jumlah Tanggungan</label>
                                    <input type="number" class="form-control" name="jumlah_tanggungan" value="<?= esc(old('jumlah_tanggungan', $editData['jumlah_tanggungan'] ?? '0')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jumlah Anak</label>
                                    <input type="number" class="form-control" name="jumlah_anak" value="<?= esc(old('jumlah_anak', $editData['jumlah_anak'] ?? '0')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pasangan PNS</label>
                                    <select class="form-select" name="pasangan_pns">
                                        <option value="0" <?= old('pasangan_pns', $editData['pasangan_pns'] ?? '0') === '0' ? 'selected' : '' ?>>Tidak</option>
                                        <option value="1" <?= old('pasangan_pns', $editData['pasangan_pns'] ?? '0') === '1' ? 'selected' : '' ?>>Ya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Bank & Gaji -->
                        <div class="tab-pane fade" id="bank" role="tabpanel">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bank / Rekening</label>
                                    <input type="text" class="form-control" name="nama_bank" placeholder="Nama bank" value="<?= esc(old('nama_bank', $editData['nama_bank'] ?? '')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Rekening</label>
                                    <input type="text" class="form-control" name="nomor_rekening" placeholder="Nomor rekening" value="<?= esc(old('nomor_rekening', $editData['nomor_rekening'] ?? '')) ?>">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Gaji Pokok</label>
                                    <input type="number" class="form-control" name="gaji_pokok" step="0.01" value="<?= esc(old('gaji_pokok', $editData['gaji_pokok'] ?? '0')) ?>">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <?php if ($editMode): ?>
                        <a href="<?= site_url('pegawai') ?>" class="btn btn-secondary">Batal Edit</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <?php endif; ?>
                    <?php if (!$editMode): ?>
                    <button type="submit" name="action" value="save_add" class="btn btn-info text-white"><i class="fas fa-lock me-1"></i> Simpan & Tambah Lagi</button>
                    <?php endif; ?>
                    <button type="submit" name="action" value="save" class="btn btn-primary"><i class="fas fa-check me-1"></i> <?= $editMode ? 'Update' : 'Simpan' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Pegawai -->
<div class="modal fade" id="modalHapusPegawai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-times me-2"></i> Hapus Data Pegawai</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3"><i class="fas fa-user-slash text-danger" style="font-size:3.5rem; opacity:0.8;"></i></div>
                <h5 class="fw-bold text-dark mb-2">Hapus Pegawai Ini?</h5>
                <p class="text-muted mb-0">Data pegawai <strong id="lblNamaPegawaiHapus" class="text-danger"></strong> akan dihapus secara permanen dari sistem.</p>
                <div class="alert alert-warning mt-3 text-start small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i> Seluruh riwayat slip gaji terkait pegawai ini mungkin juga akan terpengaruh.
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-top: 1px solid #fecaca;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                <form id="formKonfirmasiHapusPegawai" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger fw-bold shadow-sm">
                        <i class="fas fa-trash me-1"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('pegawaiModal');
    if (modalEl && modalEl.getAttribute('data-show-modal') === 'true') {
        var myModal = new bootstrap.Modal(modalEl);
        myModal.show();
    }
});

// Bootstrap Modal Hapus Pegawai
let hapusPegawaiModalInstance;
function showDeleteModal(id, nama) {
    document.getElementById('lblNamaPegawaiHapus').textContent = nama;
    document.getElementById('formKonfirmasiHapusPegawai').action = '<?= site_url('pegawai/delete') ?>/' + id;
    var modalEl = document.getElementById('modalHapusPegawai');
    if (!hapusPegawaiModalInstance) hapusPegawaiModalInstance = new bootstrap.Modal(modalEl);
    hapusPegawaiModalInstance.show();
}
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });
});
</script>
<?= $this->endSection() ?>
