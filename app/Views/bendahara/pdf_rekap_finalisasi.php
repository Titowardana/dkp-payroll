<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Penggajian <?= esc($periode['nama_periode'] ?? ($periode['bulan'].'/'.$periode['tahun'])) ?></title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; font-size: 14pt; }
        .header p { margin: 5px 0 0; font-size: 10pt; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; font-size: 9pt; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 8pt; }
        .data-table th, .data-table td { border: 1px solid #777; padding: 5px; }
        .data-table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <h2>REKAPITULASI PENGGAJIAN PEGAWAI</h2>
    <p>Periode: <strong><?= esc($periode['nama_periode'] ?? ($periode['bulan'].'/'.$periode['tahun'])) ?></strong></p>
</div>

<div class="summary">
    <table>
        <tr>
            <td width="25%"><strong>Total Pegawai:</strong> <?= $totalPegawai ?> Orang</td>
            <td width="25%"><strong>Total Pendapatan:</strong> Rp <?= number_format($totalPend, 0, ',', '.') ?></td>
            <td width="25%"><strong>Total Gaji Bersih:</strong> Rp <?= number_format($totalBersih, 0, ',', '.') ?></td>
            <td width="25%"><strong>Status Paid:</strong> <?= $sudahPaid ?> Selesai / <?= $belumPaid ?> Belum</td>
        </tr>
    </table>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th width="3%">NO</th>
            <th width="20%">NAMA</th>
            <th width="17%">NIP</th>
            <th width="12%">GAJI POKOK</th>
            <th width="14%">PENDAPATAN</th>
            <th width="14%">POTONGAN</th>
            <th width="14%">GAJI BERSIH</th>
            <th width="6%">STATUS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td class="text-center"><?= $i + 1 ?></td>
            <td><?= esc($r['nama']) ?></td>
            <td><?= esc($r['nip'] ?: '-') ?></td>
            <td class="text-right">Rp <?= number_format((float)($r['gaji_pokok']??0), 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format((float)($r['total_pendapatan']??0), 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format((float)($r['total_potongan']??0), 0, ',', '.') ?></td>
            <td class="text-right"><strong>Rp <?= number_format((float)($r['gaji_bersih']??0), 0, ',', '.') ?></strong></td>
            <td class="text-center">
                <?= ucfirst(esc($r['status'])) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-right">TOTAL KESELURUHAN:</th>
            <th class="text-right">Rp <?= number_format($totalGapok ?? 0, 0, ',', '.') ?></th>
            <th class="text-right">Rp <?= number_format($totalPend, 0, ',', '.') ?></th>
            <th class="text-right">Rp <?= number_format($totalPot, 0, ',', '.') ?></th>
            <th class="text-right">Rp <?= number_format($totalBersih, 0, ',', '.') ?></th>
            <th></th>
        </tr>
    </tfoot>
</table>

<?php
$bulanIndo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$tanggalSekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');
?>
<table style="width: 100%; border: none; margin-top: 40px; font-size:10pt;">
    <tr>
        <td style="width: 60%; border: none;"></td>
        <td style="width: 40%; text-align: center; border: none;">
            Tanjungpinang, <?= $tanggalSekarang ?><br>
            Bendahara Pengeluaran,<br><br><br><br><br>
            <strong>LH. ULUL ALBAB ,S.PI</strong><br>
            NIP. 198011242008031001
        </td>
    </tr>
</table>

</body>
</html>
