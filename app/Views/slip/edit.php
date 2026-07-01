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

<form action="<?= site_url('slip/update/'.$slip['id']) ?>" method="post">
    <?= csrf_field() ?>

<?php $_slip_err = session()->getFlashdata('slip_error'); ?>
<?php if ($_slip_err): ?>
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius:10px; border-left: 4px solid #ef4444 !important;">
    <i class="fas fa-exclamation-circle me-2"></i><?= esc($_slip_err) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Peringatan Revisi (Jika ditolak/Bendahara Minta Revisi) -->
<?php if ($slip['status'] === 'rejected'): ?>
<div class="alert shadow-sm mb-3 d-flex align-items-center" style="border-radius:12px; background-color:#fef2f2; border:1px solid #f87171; color:#991b1b;">
    <i class="fas fa-exclamation-triangle fs-3 me-3 text-danger"></i>
    <div>
        <h5 class="fw-bold mb-1 text-danger">Slip Ini Dikembalikan Oleh Bendahara (Perlu Revisi)</h5>
        <div style="font-size:0.95rem;"><strong>Catatan Penolakan:</strong> <?= esc($slip['catatan_penolakan'] ?? 'Tidak ada catatan') ?></div>
    </div>
</div>
<?php endif; ?>

<!-- Header Banner -->
<div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body bg-yellow d-flex align-items-center justify-content-between p-3">
        <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
            <i class="fas fa-edit me-2"></i> Edit Slip Gaji
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
                        <strong>Nama:</strong> <?= esc($slip['nama']) ?><br>
                        <strong>NIP:</strong> <span class="text-danger"><?= esc($slip['nip']) ?></span>
                    </div>
                    <div class="col-sm-6">
                        <strong>Periode:</strong> <?= esc($slip['nama_periode'] ?? ($slip['bulan'].'/'.$slip['tahun'])) ?><br>
                        <strong>Status data:</strong> <span class="badge bg-secondary"><?= esc($slip['status']) ?></span>
                    </div>
                </div>
                <small class="text-muted">Format input angka tanpa titik/koma, contoh: <span class="text-danger">4042500</span></small>
            </div>
            
            <!-- Right Target Box -->
            <div class="col-md-5">
                <div class="bg-dark text-white rounded p-3 d-flex flex-column justify-content-center h-100 shadow">
                    <small class="fw-bold mb-1">GAJI BERSIH (DITERIMA)</small>
                    <h3 class="mb-0 text-yellow fw-bold" id="label-gaji-bersih">
                        Rp <?= number_format((float)($slip['gaji_bersih'] ?? 0), 0, ',', '.') ?>
                    </h3>
                    <input type="hidden" name="gaji_bersih" id="input-gaji-bersih" value="<?= (float)($slip['gaji_bersih'] ?? 0) ?>">
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
                                           name="<?= $item[0] ?>" value="<?= (float)($slip[$item[0]] ?? 0) ?>">
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Summary Income -->
                        <tr class="fw-bold bg-light">
                            <td colspan="2" class="text-end py-3">Jumlah Penghasilan</td>
                            <td class="text-end py-3 text-primary fs-5" id="label-total-pendapatan">
                                Rp <?= number_format((float)($slip['total_pendapatan'] ?? 0), 0, ',', '.') ?>
                            </td>
                            <input type="hidden" name="total_pendapatan" id="input-total-pendapatan" value="<?= (float)($slip['total_pendapatan'] ?? 0) ?>">
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end fw-bold align-middle">Tunj. Keluarga (Total)</td>
                            <td>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text bg-white border-end-0 text-muted">Rp</span>
                                    <input type="number" class="form-control border-start-0 input-rupiah" 
                                           name="tunjangan_keluarga" value="<?= (float)($slip['tunjangan_keluarga'] ?? 0) ?>">
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
                                           name="<?= $item[0] ?>" value="<?= (float)($slip[$item[0]] ?? 0) ?>">
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
                                Rp <?= number_format((float)($slip['total_potongan'] ?? 0), 0, ',', '.') ?>
                            </td>
                            <input type="hidden" name="total_potongan" id="input-total-potongan" value="<?= (float)($slip['total_potongan'] ?? 0) ?>">
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Extra Sections (Coming soon) -->
<div class="row mb-4" style="display:none;">
    <div class="col-md-6">
        <h5 class="section-title mb-3">B. TAMBAHAN PENGHASILAN PEGAWAI</h5>
        <div class="grey-box">
            <p class="text-muted small mb-3">Data dari SIPD (Coming soon)</p>
        </div>
    </div>
    <div class="col-md-6">
        <h5 class="section-title mb-3">C. HONOR PENGELOLA ADMINISTRASI KEUANGAN</h5>
        <div class="grey-box">
            <p class="text-muted small mb-3">Data dari SIPD (Coming soon)</p>
        </div>
    </div>
</div>

<!-- Footer Attributes -->
<div class="mb-4">
    <label class="fw-bold mb-2">Status Slip</label>
    <select name="status" class="form-select border-1 shadow-sm w-100 p-2 mb-2">
        <option value="draft" <?= $slip['status'] === 'draft' ? 'selected' : '' ?>>Draft (Siap Diverifikasi)</option>
        <option value="verified" <?= $slip['status'] === 'verified' ? 'selected' : '' ?>>Verified</option>
        <option value="approved" <?= $slip['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
        <option value="paid" <?= $slip['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
    </select>
    <small class="text-muted">Pilih <strong>Draft</strong> agar bendahara dapat memeriksa ulang setelah Anda melakukan perbaikan.</small>
</div>

<div class="d-flex mb-5">
    <button type="submit" class="btn btn-primary d-flex align-items-center me-3 px-4 py-2 shadow-sm">
        <i class="fas fa-save me-2"></i> Simpan Perubahan
    </button>
    <a href="<?= site_url('generate-slip/view/'.$slip['id']) ?>" class="btn btn-secondary d-flex align-items-center px-4 py-2 shadow-sm text-white">
        <i class="fas fa-eye me-2"></i> Preview Slip
    </a>
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
