<?php namespace App\Libraries;

use App\Models\PayrollDetailModel;
use App\Models\PegawaiModel;
use App\Models\PeriodeModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PayrollImportService
{
    public function import(string $filePath, int $bulan, int $tahun, int $userId = 0): array
    {
        LegacyPayrollLib::load();
        if (! class_exists(IOFactory::class)) {
            throw new \RuntimeException('Library PhpSpreadsheet belum tersedia.');
        }

        $db = db_connect();
        $pegawaiModel = new PegawaiModel();
        $payrollModel = new PayrollDetailModel();
        $periodeModel = new PeriodeModel();

        $periode = $periodeModel->where('bulan', $bulan)->where('tahun', $tahun)->first();
        $namaPeriode = $this->monthName($bulan) . ' ' . $tahun;
        if ($periode) {
            $periodeId = (int) $periode['id'];
        } else {
            $periodeId = (int) $periodeModel->insert([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama_periode' => $namaPeriode,
                'status' => 'draft',
                'created_by' => $userId ?: null,
            ], true);
        }

        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, false, false);
        if (count($rows) < 2) {
            throw new \RuntimeException('File Excel kosong atau tidak memiliki data.');
        }

        $headers = array_map(static fn($h) => trim((string) $h), array_shift($rows));
        $success = 0;
        $errors = [];

        $db->transStart();
        foreach ($rows as $index => $row) {
            if ($this->isRowEmpty($row)) {
                continue;
            }
            try {
                $data = [];
                foreach ($headers as $colIndex => $header) {
                    if ($header === '') {
                        continue;
                    }
                    $data[$header] = $row[$colIndex] ?? null;
                }

                $nama = trim((string) ($data['Nama Pegawai'] ?? ''));
                if ($nama === '') {
                    throw new \RuntimeException('Nama Pegawai kosong.');
                }

                $nip = trim((string) ($data['NIP Pegawai'] ?? ''));
                if ($nip === '') {
                    $nip = $this->generateNip($nama, $index + 1, $bulan, $tahun);
                }

                $pegawai = $pegawaiModel->groupStart()->where('nip', $nip)->orWhere('nama', $nama)->groupEnd()->first();
                $pegawaiPayload = [
                    'nip' => $nip,
                    'nama' => $nama,
                    'nik' => trim((string) ($data['NIK Pegawai'] ?? '')) ?: null,
                    'npwp' => trim((string) ($data['NPWP Pegawai'] ?? '')) ?: null,
                    'alamat' => trim((string) ($data['Alamat'] ?? '')) ?: null,
                    'nip_pasangan' => trim((string) ($data['NIP Pasangan'] ?? '')) ?: null,
                    'tanggal_lahir' => $this->parseDate($data['Tanggal Lahir Pegawai'] ?? ''),
                    'tipe_jabatan' => trim((string) ($data['Tipe Jabatan'] ?? '')) ?: null,
                    'nama_jabatan' => trim((string) ($data['Nama Jabatan'] ?? '')) ?: null,
                    'eselon' => trim((string) ($data['Eselon'] ?? '')) ?: null,
                    'status_asn' => $this->parseStatusAsn($data['Status ASN'] ?? null),
                    'golongan' => trim((string) ($data['golongan'] ?? '')) ?: null,
                    'masa_kerja_golongan' => (int) ($data['Masa Kerja Golongan'] ?? 0),
                    'status_pernikahan' => $this->parseStatusPernikahan($data['Status Pernikahan'] ?? null),
                    'jumlah_istri_suami' => (int) ($data['Jumlah Istri_Suami'] ?? 0),
                    'jumlah_anak' => (int) ($data['Jumlah Anak'] ?? 0),
                    'jumlah_tanggungan' => (int) ($data['Jumlah Tanggungan'] ?? 0),
                    'pasangan_pns' => $this->parseYesNo($data['Pasangan PNS'] ?? null),
                    'kode_bank' => trim((string) ($data['Kode Bank'] ?? '')) ?: null,
                    'nama_bank' => trim((string) ($data['Nama Bank'] ?? '')) ?: null,
                    'nomor_rekening' => trim((string) ($data['Nomor Rekening Bank Pegawai'] ?? '')) ?: null,
                    'gaji_pokok' => $this->parseMoney($data['Gaji Pokok'] ?? 0),
                    'updated_by' => $userId ?: null,
                ];

                if ($pegawai) {
                    $pegawaiId = (int) $pegawai['id'];
                    $pegawaiModel->update($pegawaiId, $pegawaiPayload);
                } else {
                    $pegawaiPayload['created_by'] = $userId ?: null;
                    $pegawaiId = (int) $pegawaiModel->insert($pegawaiPayload, true);
                }

                $tunjPasangan = $this->parseMoney($data['Perhitungan Suami_Istri'] ?? 0);
                $tunjAnak = $this->parseMoney($data['Perhitungan Anak'] ?? 0);
                $tunjKeluarga = $this->parseMoney($data['Tunjangan Keluarga'] ?? ($tunjPasangan + $tunjAnak));
                $payload = [
                    'periode_id' => $periodeId,
                    'pegawai_id' => $pegawaiId,
                    'gaji_pokok' => $this->parseMoney($data['Gaji Pokok'] ?? 0),
                    'tunjangan_keluarga' => $tunjKeluarga,
                    'tunjangan_pasangan_detail' => $tunjPasangan,
                    'tunjangan_anak_detail' => $tunjAnak,
                    'tunjangan_jabatan' => $this->parseMoney($data['Tunjangan Jabatan'] ?? 0),
                    'tunjangan_fungsional' => $this->parseMoney($data['Tunjangan Fungsional'] ?? 0),
                    'tunjangan_fungsional_umum' => $this->parseMoney($data['Tunjangan Fungsional Umum'] ?? 0),
                    'tunjangan_beras' => $this->parseMoney($data['Tunjangan Beras'] ?? 0),
                    'tunjangan_pph' => $this->parseMoney($data['Tunjangan PPh'] ?? 0),
                    'tunjangan_khusus_papua' => $this->parseMoney($data['Tunjangan Khusus Papua'] ?? 0),
                    'tunjangan_jht' => $this->parseMoney($data['Tunjangan Jaminan Hari Tua'] ?? 0),
                    'pembulatan' => $this->parseMoney($data['Pembulatan Gaji'] ?? 0),
                    'tunjangan_lainnya' => $this->parseMoney($data['Tunjangan Lainnya'] ?? 0),
                    'iuran_jkn' => $this->parseMoney($data['Iuran Jaminan Kesehatan'] ?? 0),
                    'iuran_jkk' => $this->parseMoney($data['Iuran Jaminan Kecelakaan Kerja'] ?? 0),
                    'iuran_jkm' => $this->parseMoney($data['Iuran Jaminan Kematian'] ?? 0),
                    'iuran_tapera' => $this->parseMoney($data['Iuran Simpanan Tapera'] ?? 0),
                    'iuran_pensiun' => $this->parseMoney($data['Iuran Pensiun'] ?? 0),
                    'potongan_iwp' => $this->parseMoney($data['Potongan IWP'] ?? 0),
                    'potongan_pph21' => $this->parseMoney($data['Potongan PPh 21'] ?? 0),
                    'zakat' => $this->parseMoney($data['Zakat'] ?? 0),
                    'bulog' => $this->parseMoney($data['Bulog'] ?? 0),
                    'potongan_lainnya' => $this->parseMoney($data['Potongan Lainnya'] ?? 0),
                    'masa_kerja' => (int) ($data['Masa Kerja Golongan'] ?? 0),
                    'eselon' => trim((string) ($data['Eselon'] ?? '')) ?: null,
                    'nama_jabatan' => trim((string) ($data['Nama Jabatan'] ?? '')) ?: null,
                    'status' => 'draft',
                    'metode_bayar' => 'transfer',
                    'created_by' => $userId ?: null,
                ];
                $payload['total_pendapatan'] = $payload['gaji_pokok'] + $payload['tunjangan_keluarga'] + $payload['tunjangan_jabatan'] + $payload['tunjangan_fungsional'] + $payload['tunjangan_fungsional_umum'] + $payload['tunjangan_beras'] + $payload['tunjangan_pph'] + $payload['tunjangan_khusus_papua'] + $payload['tunjangan_jht'] + $payload['pembulatan'] + $payload['tunjangan_lainnya'];
                $payload['total_potongan'] = $payload['iuran_jkn'] + $payload['iuran_jkk'] + $payload['iuran_jkm'] + $payload['iuran_tapera'] + $payload['iuran_pensiun'] + $payload['potongan_iwp'] + $payload['potongan_pph21'] + $payload['zakat'] + $payload['bulog'] + $payload['potongan_lainnya'];
                $payload['gaji_bersih'] = $payload['total_pendapatan'] - $payload['total_potongan'];

                // ── Koreksi: Gunakan nilai pre-computed dari SIPD sebagai sumber kebenaran ──
                // Di SIPD, Tunjangan JHT (AL) dihitung sebagai POTONGAN (masuk ke AR),
                // bukan pendapatan bersih karyawan. Nilai AQ/AR/AS adalah hasil resmi SIPD.
                $sipd_total_pendapatan = $this->parseMoney($data['Jumlah Gaji dan Tunjangan'] ?? 0);
                $sipd_total_potongan   = $this->parseMoney($data['Jumlah Potongan'] ?? 0);
                $sipd_gaji_bersih      = $this->parseMoney($data['Jumlah Ditransfer'] ?? 0);

                if ($sipd_total_pendapatan > 0) $payload['total_pendapatan'] = $sipd_total_pendapatan;
                if ($sipd_total_potongan   > 0) $payload['total_potongan']   = $sipd_total_potongan;
                if ($sipd_gaji_bersih      > 0) $payload['gaji_bersih']      = $sipd_gaji_bersih;

                $existing = $payrollModel->where('periode_id', $periodeId)->where('pegawai_id', $pegawaiId)->first();
                if ($existing) {
                    $payrollModel->update((int) $existing['id'], $payload);
                } else {
                    $payrollModel->insert($payload);
                }
                $success++;
            } catch (\Throwable $e) {
                $errors[] = 'Baris ' . ($index + 2) . ': ' . $e->getMessage();
            }
        }

        $periodeModel->update($periodeId, [
            'nama_periode' => $namaPeriode,
            'status' => 'draft',
            'total_pegawai' => $success,
            'total_gaji' => (float) ($payrollModel->selectSum('gaji_bersih')->where('periode_id', $periodeId)->first()['gaji_bersih'] ?? 0),
        ]);
        $db->transComplete();

        return [
            'periode_id' => $periodeId,
            'periode' => $namaPeriode,
            'success_count' => $success,
            'error_count' => count($errors),
            'errors' => $errors,
        ];
    }

    private function isRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }
        return true;
    }

    private function generateNip(string $nama, int $index, int $bulan, int $tahun): string
    {
        $initials = '';
        foreach (preg_split('/\s+/', $nama) as $word) {
            if ($word !== '') {
                $initials .= strtoupper(substr($word, 0, 2));
            }
        }
        return substr($initials, 0, 8) . substr((string) $tahun, -2) . str_pad((string) $bulan, 2, '0', STR_PAD_LEFT) . str_pad((string) $index, 3, '0', STR_PAD_LEFT);
    }

    private function parseStatusAsn(mixed $value): string
    {
        return ((string) $value === '1' || strtoupper((string) $value) === 'PNS') ? 'PNS' : 'Non-PNS';
    }

    private function parseYesNo(mixed $value): int
    {
        return in_array(strtolower(trim((string) $value)), ['1', 'ya', 'yes', 'true'], true) ? 1 : 0;
    }

    private function parseStatusPernikahan(mixed $status): string
    {
        $status = strtolower(trim((string) $status));
        return match ($status) {
            '1', 'menikah' => 'Menikah',
            'cerai' => 'Cerai',
            default => 'Belum Menikah',
        };
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }
        if (is_numeric($value) && (float) $value > 20000 && class_exists(\PhpOffice\PhpSpreadsheet\Shared\Date::class)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable $e) {
            }
        }
        foreach (['d-m-Y', 'Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d'] as $format) {
            $dt = \DateTime::createFromFormat($format, (string) $value);
            if ($dt !== false) {
                return $dt->format('Y-m-d');
            }
        }
        $time = strtotime((string) $value);
        return $time ? date('Y-m-d', $time) : null;
    }

    private function parseMoney(mixed $value): float
    {
        if ($value === null) return 0.0;
        if (is_int($value) || is_float($value)) return (float) $value;
        $s = trim((string) $value);
        if ($s === '') return 0.0;
        $negative = false;
        if (preg_match('/^\((.*)\)$/', $s, $m)) {
            $negative = true;
            $s = $m[1];
        }
        $s = str_replace(['Rp', 'rp', 'IDR', 'idr', "\xc2\xa0", ' '], '', $s);
        $hasComma = strpos($s, ',') !== false;
        $hasDot = strpos($s, '.') !== false;
        if ($hasComma && $hasDot) {
            if (strrpos($s, ',') > strrpos($s, '.')) {
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
            } else {
                $s = str_replace(',', '', $s);
            }
        } elseif ($hasComma) {
            $parts = explode(',', $s);
            $last = end($parts);
            $s = preg_match('/^\d{1,2}$/', $last) ? str_replace([ '.', ',' ], ['', '.'], $s) : str_replace(',', '', $s);
        } elseif ($hasDot) {
            $parts = explode('.', $s);
            $last = end($parts);
            if (! preg_match('/^\d{1,2}$/', $last)) {
                $s = str_replace('.', '', $s);
            }
        }
        $s = preg_replace('/[^0-9\.\-]/', '', $s);
        if ($s === '' || $s === '-' || $s === '.') return 0.0;
        $num = (float) $s;
        return $negative ? -$num : $num;
    }

    private function monthName(int $bulan): string
    {
        return bulan_nama($bulan);
    }
}
