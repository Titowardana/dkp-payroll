<?php namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class SlipPdfService
{
    public function renderPdf(array $data): string
    {
        if (! class_exists(Dompdf::class)) {
            // In case composer autoload wasn't pre-loaded, ensure it
            $composerVendor = defined('ROOTPATH') ? ROOTPATH . 'vendor/autoload.php' : dirname(__DIR__, 2) . '/vendor/autoload.php';
            if (file_exists($composerVendor)) {
                require_once $composerVendor;
            }
            if (! class_exists(Dompdf::class)) {
                throw new \RuntimeException('Library Dompdf belum tersedia melalui Composer.');
            }
        }

        $appPath = defined('APPPATH') ? APPPATH : dirname(__DIR__) . '/';
        $html = file_get_contents($appPath . 'Views/slip/template_legacy.html') ?: '<html><body>Template tidak ditemukan</body></html>';
        $bulanNama = bulan_nama((int) ($data['bulan'] ?? 0));
        $replacements = [
            '{{NAMA_PEGAWAI}}' => htmlspecialchars((string) ($data['nama'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{NIP}}' => htmlspecialchars((string) ($data['nip'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            '{{STATUS_ASN}}' => htmlspecialchars((string) ($data['status_asn'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            '{{JABATAN}}' => htmlspecialchars((string) ($data['pegawai_jabatan'] ?? $data['nama_jabatan'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            '{{GOLONGAN}}' => htmlspecialchars((string) ($data['golongan'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            '{{ESELON}}' => htmlspecialchars((string) ($data['eselon'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            '{{BANK}}' => htmlspecialchars((string) ($data['nama_bank'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            '{{NOMOR_REKENING}}' => htmlspecialchars((string) ($data['nomor_rekening'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            '{{NAMA_BULAN}}' => strtoupper($bulanNama),
            '{{TAHUN}}' => (string) ($data['tahun'] ?? ''),
            '{{GAJI_POKOK}}' => $this->money($data['gaji_pokok'] ?? 0),
            '{{TUNJANGAN_PASANGAN}}' => $this->money($data['tunjangan_pasangan_detail'] ?? 0),
            '{{TUNJANGAN_ANAK}}' => $this->money($data['tunjangan_anak_detail'] ?? 0),
            '{{TUNJANGAN_KELUARGA}}' => $this->money($data['tunjangan_keluarga'] ?? 0),
            '{{TUNJANGAN_PPH}}' => $this->money($data['tunjangan_pph'] ?? 0),
            '{{TUNJANGAN_JABATAN}}' => $this->money($data['tunjangan_jabatan'] ?? 0),
            '{{TUNJANGAN_FUNGSIONAL_UMUM}}' => $this->money($data['tunjangan_fungsional_umum'] ?? 0),
            '{{TUNJANGAN_FUNGSIONAL}}' => $this->money($data['tunjangan_fungsional'] ?? 0),
            '{{TUNJANGAN_BERAS}}' => $this->money($data['tunjangan_beras'] ?? 0),
            '{{TUNJANGAN_KHUSUS_PAPUA}}' => $this->money($data['tunjangan_khusus_papua'] ?? 0),
            '{{TUNJANGAN_JHT}}' => $this->money($data['tunjangan_jht'] ?? 0),
            '{{TUNJANGAN_LAINNYA}}' => $this->money($data['tunjangan_lainnya'] ?? 0),
            '{{PEMBULATAN}}' => $this->money($data['pembulatan'] ?? 0),
            '{{POTONGAN_PPH21}}' => $this->money($data['potongan_pph21'] ?? 0),
            '{{IURAN_JKN}}' => $this->money($data['iuran_jkn'] ?? 0),
            '{{POTONGAN_IWP}}' => $this->money($data['potongan_iwp'] ?? 0),
            '{{IURAN_JKK}}' => $this->money($data['iuran_jkk'] ?? 0),
            '{{IURAN_JKM}}' => $this->money($data['iuran_jkm'] ?? 0),
            '{{ZAKAT}}' => $this->money($data['zakat'] ?? 0),
            '{{IURAN_PENSIUN}}' => $this->money($data['iuran_pensiun'] ?? 0),
            '{{IURAN_TAPERA}}' => $this->money($data['iuran_tapera'] ?? 0),
            '{{POTONGAN_LAINNYA}}' => $this->money($data['potongan_lainnya'] ?? 0),
            '{{BULOG}}' => $this->money($data['bulog'] ?? 0),
            '{{IURAN_JHT}}' => $this->money($data['tunjangan_jht'] ?? 0),
            '{{TOTAL_PENDAPATAN}}' => $this->money($data['total_pendapatan'] ?? 0),
            '{{TOTAL_POTONGAN}}' => $this->money($data['total_potongan'] ?? 0),
            '{{GAJI_BERSIH}}' => $this->money($data['gaji_bersih'] ?? 0),
            '{{TPP_BRUTO}}' => '0',
            '{{TPP_WP1}}' => '0',
            '{{TPP_BERSIH}}' => '0',
            '{{TPP_PPH21}}' => '0',
            '{{TPP_POTONGAN_TOTAL}}' => '0',
            '{{HONOR_BRUTO}}' => '0',
            '{{HONOR_PPH21}}' => '0',
            '{{HONOR_BERSIH}}' => '0',
            '{{TOTAL_PENGHASILAN_BERSIH}}' => $this->money($data['gaji_bersih'] ?? 0),
            '{{TANGGAL_CETAK}}' => date('d') . ' ' . $bulanNama . ' ' . ($data['tahun'] ?? date('Y')),
            '{{JABATAN_TANDA_TANGAN}}' => $this->signerConfig('jabatan', 'Bendahara Pengeluaran'),
            '{{NAMA_TANDA_TANGAN}}' => $this->signerConfig('nama', 'LH. ULUL ALBAB ,S.PI'),
            '{{NIP_TANDA_TANGAN}}' => $this->signerConfig('nip', '198011242008031001'),
            '{{WAKTU_CETAK}}' => date('d/m/Y H:i:s'),
            '{{PERIODE}}' => $bulanNama . ' ' . ($data['tahun'] ?? ''),
        ];
        $html = strtr($html, $replacements);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }

    private function money($amount): string
    {
        return number_format((float) $amount, 0, ',', '.');
    }

    private function signerConfig(string $key, string $default): string
    {
        static $config = [];
        if (!array_key_exists($key, $config) && class_exists(\App\Models\SystemConfigModel::class)) {
            try {
                $dbConfig = (new \App\Models\SystemConfigModel())->where('config_key', 'signer_' . $key)->first();
                $config[$key] = $dbConfig ? ($dbConfig['config_value'] ?? $default) : $default;
            } catch (\Throwable $e) {
                $config[$key] = $default;
            }
        }
        return $config[$key] ?? $default;
    }
}
