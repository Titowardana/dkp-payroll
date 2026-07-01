<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .bg-yellow { background-color: #facc15; }
    .text-yellow { color: #facc15; }
    .input-rupiah { text-align: right; font-family: monospace; font-size: 1.05rem; }
    .table-bordered th, .table-bordered td { vertical-align: middle; }
    .grey-box { background-color: #f3f4f6; border-radius: 8px; padding: 15px; }
    .section-title { font-weight: 800; color: #374151; font-size: 1.1rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form action="<?= site_url('slip/store') ?>" method="post">
    <?= csrf_field() ?>

<?php $_slip_err = session()->getFlashdata('slip_error'); ?>
<?php if ($_slip_err): ?>
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius:10px; border-left: 4px solid #ef4444 !important;">
    <i class="fas fa-exclamation-circle me-2"></i><?= esc($_slip_err) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Header Banner -->
<div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body bg-yellow d-flex align-items-center justify-content-between p-3">
        <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
            <i class="fas fa-plus me-2"></i> Tambah Slip Gaji Manual
        </h4>
        <a href="<?= site_url('slip') ?>" class="btn btn-light text-dark fw-bold shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Info Banner -->
<div class="card section-card mb-4 border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <!-- Left Info -->
            <div class="col-md-7">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="fw-bold mb-1">Periode <span class="text-danger">*</span></label>
                        <select name="periode_id" class="form-select shadow-sm" required>
                            <option value="">-- Pilih Periode --</option>
                            <?php foreach($periodes as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= esc($p['nama_periode'] ?? ($p['bulan'].'/'.$p['tahun'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="fw-bold mb-1">Pegawai <span class="text-danger">*</span></label>
                        <select name="pegawai_id" class="form-select shadow-sm" required>
                            <option value="">-- Pilih Pegawai --</option>
                            <?php foreach($pegawais as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= esc($p['nama']) ?> (<?= esc($p['nip']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <small class="text-muted">Format input angka tanpa titik/koma, contoh: <span class="text-danger">4042500</span></small>
            </div>
            
            <!-- Right Target Box -->
            <div class="col-md-5">
                <div class="bg-dark text-white rounded p-3 d-flex flex-column justify-content-center h-100 shadow">
                    <small class="fw-bold mb-1">GAJI BERSIH (DITERIMA)</small>
                    <h3 class="mb-0 text-yellow fw-bold" id="label-gaji-bersih">
                        Rp 0
                    </h3>
                    <input type="hidden" name="gaji_bersih" id="input-gaji-bersih" value="0">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- A. GAJI Grid -->
<h5 class="section-title mb-3">A. GAJI</h5>
<div class="card section-card mb-4 border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- PENGHASILAN -->
            <div class="col-md-6 border-end">
                <table class="table table-bordered mb-0 border-0">
                    <thead class="bg-light text-center">
                        <tr><th style="width: 50px;">NO</th><th>PENGHASILAN</th><th>JUMLAH</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $penghasilan = [
                            ['gaji_pokok', 'Gaji Pokok'],
                            ['tunjangan_pasangan_detail', 'Tunjangan Istri/Suami'],
                            ['tunjangan_anak_detail', 'Tunjangan Anak'],
                            ['tunjangan_jabatan', 'Tunjangan Jabatan/Eselon'],
                            ['tunjangan_fungsional_umum', 'Tunj. Fungsional Umum'],
                            ['tunjangan_fungsional', 'Tunj. Fungsional'],
                            ['tunjangan_beras', 'Tunjangan Beras'],
                            ['tunjangan_pph', 'Tunjangan PPh 21'],
                            ['tunjangan_khusus_papua', 'Tunjangan Khusus Papua'],
                            ['tunjangan_jht', 'Tunj. JHT'],
                            ['tunjangan_lainnya', 'Tunjangan Lainnya'],
                            ['pembulatan', 'Pembulatan']
                        ];
                        foreach ($penghasilan as $idx => $item): ?>
                        <tr>
                            <td class="text-center"><?= $idx + 1 ?></td>
                            <td><?= $item[1] ?></td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted">Rp</span>
                                    <input type="number" class="form-control border-start-0 input-rupiah calc-income" 
                                           name="<?= $item[0] ?>" value="0">
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Summary Income -->
                        <tr class="fw-bold bg-light">
                            <td colspan="2" class="text-end py-3">Jumlah Penghasilan</td>
                            <td class="text-end py-3 text-primary fs-5" id="label-total-pendapatan">
                                Rp 0
                            </td>
                            <input type="hidden" name="total_pendapatan" id="input-total-pendapatan" value="0">
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end fw-bold align-middle">Tunj. Keluarga (Total)</td>
                            <td>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text bg-white border-end-0 text-muted">Rp</span>
                                    <input type="number" class="form-control border-start-0 input-rupiah" 
                                           name="tunjangan_keluarga" value="0">
                                </div>
                                <small class="text-muted" style="font-size: 0.7rem;">Otomatis: Istri/Suami + Anak.</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- POTONGAN -->
            <div class="col-md-6">
                <table class="table table-bordered mb-0 border-0">
                    <thead class="bg-light text-center">
                        <tr><th style="width: 50px;">NO</th><th>POTONGAN</th><th>JUMLAH</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $potongan = [
                            ['potongan_pph21', 'Pajak PPh 21'],
                            ['iuran_jkn', 'BPJS Kes 4%'],
                            ['potongan_iwp', 'IWP 1%'],
                            ['iuran_jkk', 'JKK'],
                            ['iuran_jkm', 'JKM'],
                            ['zakat', 'Zakat'],
                            ['iuran_pensiun', 'BPJS TK / Pensiun'],
                            ['iuran_tapera', 'Tapera'],
                            ['bulog', 'Bulog'],
                            ['potongan_lainnya', 'Potongan Lainnya']
                        ];
                        foreach ($potongan as $idx => $item): ?>
                        <tr>
                            <td class="text-center"><?= $idx + 1 ?></td>
                            <td><?= $item[1] ?></td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted">Rp</span>
                                    <input type="number" class="form-control border-start-0 input-rupiah calc-deduction" 
                                           name="<?= $item[0] ?>" value="0">
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Fill empty rows to align with left -->
                        <tr><td colspan="3" class="border-0" style="height: 52px; background:white;"></td></tr>
                        <tr><td colspan="3" class="border-0" style="height: 52px; background:white;"></td></tr>

                        <!-- Summary Deduction -->
                        <tr class="fw-bold bg-light">
                            <td colspan="2" class="text-end py-3">Jumlah Potongan Gaji</td>
                            <td class="text-end py-3 text-danger fs-5" id="label-total-potongan">
                                Rp 0
                            </td>
                            <input type="hidden" name="total_potongan" id="input-total-potongan" value="0">
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Extra Sections -->
<div class="row mb-4">
    <!-- TAMBAHAN PENGHASILAN PEGAWAI -->
    <div class="col-md-6">
        <h5 class="section-title mb-3">B. TAMBAHAN PENGHASILAN PEGAWAI</h5>
        <div class="grey-box">
            <p class="text-muted small mb-3">(Fitur akan diintegrasikan dari data SIPD impor)</p>
            <table class="table table-borderless table-sm mb-0">
                <tr><td>- TPP Bruto</td><td class="text-end">Rp 0</td></tr>
                <tr><td>- TPP Bersih</td><td class="text-end">Rp 0</td></tr>
                <tr><td>- WP 1%</td><td class="text-end">Rp 0</td></tr>
                <tr><td>- PPh 21</td><td class="text-end">Rp 0</td></tr>
            </table>
        </div>
    </div>
    <!-- HONOR PENGELOLA -->
    <div class="col-md-6">
        <h5 class="section-title mb-3">C. HONOR PENGELOLA ADMINISTRASI KEUANGAN</h5>
        <div class="grey-box">
            <p class="text-muted small mb-3">(Fitur akan diintegrasikan dari data SIPD impor)</p>
            <table class="table table-borderless table-sm mb-0">
                <tr><td>- Honor Bruto</td><td class="text-end">Rp 0</td></tr>
                <tr><td>- Honor Bersih</td><td class="text-end">Rp 0</td></tr>
                <tr><td>- PPh 21</td><td class="text-end">Rp 0</td></tr>
            </table>
        </div>
    </div>
</div>

<!-- Footer Attributes -->
<div class="mb-4">
    <label class="fw-bold mb-2">Status Slip</label>
    <select name="status" class="form-select border-1 shadow-sm w-100 p-2 mb-2">
        <option value="draft" selected>Draft (Siap Diverifikasi)</option>
        <option value="verified">Verified</option>
        <option value="approved">Approved</option>
        <option value="paid">Paid</option>
    </select>
    <small class="text-muted">Pilih <strong>Draft</strong> agar bendahara dapat memeriksa ulang setelah Anda melakukan perbaikan.</small>
</div>

<div class="d-flex mb-5">
    <button type="submit" class="btn btn-primary d-flex align-items-center me-3 px-4 py-2 shadow-sm w-100 justify-content-center">
        <i class="fas fa-save me-2"></i> Simpan Slip Baru
    </button>
</div>

</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID').format(number);
    };

    const inputsIncome = document.querySelectorAll('.calc-income');
    const inputsDeduction = document.querySelectorAll('.calc-deduction');
    
    // Total Labels
    const labelIncome = document.getElementById('label-total-pendapatan');
    const inputTotalIncome = document.getElementById('input-total-pendapatan');
    
    const labelDeduction = document.getElementById('label-total-potongan');
    const inputTotalDeduction = document.getElementById('input-total-potongan');
    
    const labelGajiBersih = document.getElementById('label-gaji-bersih');
    const inputGajiBersih = document.getElementById('input-gaji-bersih');

    // Tunj. Keluarga (Target special input)
    const inputPasangan = document.querySelector('input[name="tunjangan_pasangan_detail"]');
    const inputAnak = document.querySelector('input[name="tunjangan_anak_detail"]');
    const inputTunjKeluarga = document.querySelector('input[name="tunjangan_keluarga"]');

    function calculate() {
        let totalIncome = 0;
        inputsIncome.forEach(el => {
            const val = parseFloat(el.value) || 0;
            totalIncome += val;
        });

        let totalDeduction = 0;
        inputsDeduction.forEach(el => {
            const val = parseFloat(el.value) || 0;
            totalDeduction += val;
        });

        const gajiBersih = totalIncome - totalDeduction;

        // Update Labels (adding Rp locally to match syntax)
        labelIncome.innerText = 'Rp ' + formatRupiah(totalIncome);
        inputTotalIncome.value = totalIncome;

        labelDeduction.innerText = 'Rp ' + formatRupiah(totalDeduction);
        inputTotalDeduction.value = totalDeduction;

        labelGajiBersih.innerText = 'Rp ' + formatRupiah(gajiBersih);
        inputGajiBersih.value = gajiBersih;

        // Sync Tunjangan Keluarga sum
        const valPasangan = parseFloat(inputPasangan.value) || 0;
        const valAnak = parseFloat(inputAnak.value) || 0;
        inputTunjKeluarga.value = valPasangan + valAnak;
    }

    // Attach listeners
    inputsIncome.forEach(el => el.addEventListener('input', calculate));
    inputsDeduction.forEach(el => el.addEventListener('input', calculate));

});
</script>
<?= $this->endSection() ?>
