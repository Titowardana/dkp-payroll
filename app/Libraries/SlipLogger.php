<?php namespace App\Libraries;

use App\Models\PayrollStatusLogModel;

/**
 * SlipLogger — Service untuk mencatat histori aksi perubahan status slip.
 *
 * Penggunaan:
 *   SlipLogger::verifikasi($slipRow)                  → aksi satuan
 *   SlipLogger::verifikasiMassal($slipRows)            → aksi bulk
 *   SlipLogger::approval($slipRow)
 *   SlipLogger::approvalMassal($slipRows)
 *   SlipLogger::bayar($slipRow, $periodeId)
 *   SlipLogger::finalisasi($periodeId, $periodeName, $jumlahSlip)
 */
class SlipLogger
{
    // ------------------------------------------------------------------ //
    //  Verifikasi
    // ------------------------------------------------------------------ //
    public static function verifikasi(array $slip): void
    {
        self::write([
            'aksi'             => 'verifikasi',
            'is_bulk'          => 0,
            'jumlah_slip'      => 1,
            'payroll_detail_id'=> (int) ($slip['id'] ?? 0),
            'periode_id'       => (int) ($slip['periode_id'] ?? null),
            'periode_nama'     => $slip['nama_periode'] ?? null,
            'pegawai_nama'     => $slip['nama'] ?? null,
            'pegawai_nip'      => $slip['nip'] ?? null,
            'gaji_bersih'      => (float) ($slip['gaji_bersih'] ?? 0),
        ]);
    }

    public static function verifikasiMassal(array $slips): void
    {
        $periodeNama = $slips[0]['nama_periode'] ?? null;
        $periodeId   = (int) ($slips[0]['periode_id'] ?? 0);
        self::write([
            'aksi'        => 'verifikasi',
            'is_bulk'     => 1,
            'jumlah_slip' => count($slips),
            'periode_id'  => $periodeId ?: null,
            'periode_nama'=> $periodeNama,
            'catatan'     => 'Bulk: ' . count($slips) . ' slip diverifikasi sekaligus.',
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Unverify (Kembalikan ke Draft)
    // ------------------------------------------------------------------ //
    public static function unverify(array $slip): void
    {
        self::write([
            'aksi'             => 'unverify',
            'is_bulk'          => 0,
            'jumlah_slip'      => 1,
            'payroll_detail_id'=> (int) ($slip['id'] ?? 0),
            'periode_id'       => (int) ($slip['periode_id'] ?? null),
            'periode_nama'     => $slip['nama_periode'] ?? null,
            'pegawai_nama'     => $slip['nama'] ?? null,
            'pegawai_nip'      => $slip['nip'] ?? null,
            'gaji_bersih'      => (float) ($slip['gaji_bersih'] ?? 0),
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Approval
    // ------------------------------------------------------------------ //
    public static function approval(array $slip): void
    {
        self::write([
            'aksi'             => 'approval',
            'is_bulk'          => 0,
            'jumlah_slip'      => 1,
            'payroll_detail_id'=> (int) ($slip['id'] ?? 0),
            'periode_id'       => (int) ($slip['periode_id'] ?? null),
            'periode_nama'     => $slip['nama_periode'] ?? null,
            'pegawai_nama'     => $slip['nama'] ?? null,
            'pegawai_nip'      => $slip['nip'] ?? null,
            'gaji_bersih'      => (float) ($slip['gaji_bersih'] ?? 0),
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Tolak (Revisi)
    // ------------------------------------------------------------------ //
    public static function reject(array $slip, string $alasan): void
    {
        self::write([
            'aksi'             => 'reject',
            'is_bulk'          => 0,
            'jumlah_slip'      => 1,
            'payroll_detail_id'=> (int) ($slip['id'] ?? 0),
            'periode_id'       => (int) ($slip['periode_id'] ?? null),
            'periode_nama'     => $slip['nama_periode'] ?? null,
            'pegawai_nama'     => $slip['nama'] ?? null,
            'pegawai_nip'      => $slip['nip'] ?? null,
            'gaji_bersih'      => (float) ($slip['gaji_bersih'] ?? 0),
            'catatan'          => 'Ditolak/Minta Revisi: ' . $alasan,
        ]);
    }

    public static function approvalMassal(array $slips): void
    {
        $periodeNama = $slips[0]['nama_periode'] ?? null;
        $periodeId   = (int) ($slips[0]['periode_id'] ?? 0);
        self::write([
            'aksi'        => 'approval',
            'is_bulk'     => 1,
            'jumlah_slip' => count($slips),
            'periode_id'  => $periodeId ?: null,
            'periode_nama'=> $periodeNama,
            'catatan'     => 'Bulk: ' . count($slips) . ' slip disetujui sekaligus.',
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Bayar
    // ------------------------------------------------------------------ //
    public static function bayar(array $slip, int $periodeId): void
    {
        self::write([
            'aksi'             => 'bayar',
            'is_bulk'          => 0,
            'jumlah_slip'      => 1,
            'payroll_detail_id'=> (int) ($slip['id'] ?? 0),
            'periode_id'       => $periodeId,
            'periode_nama'     => $slip['nama_periode'] ?? null,
            'pegawai_nama'     => $slip['nama'] ?? null,
            'pegawai_nip'      => $slip['nip'] ?? null,
            'gaji_bersih'      => (float) ($slip['gaji_bersih'] ?? 0),
        ]);
    }

    public static function bayarBulk(int $jumlahSlip, int $periodeId, string $periodeNama): void
    {
        self::write([
            'aksi'             => 'bayar',
            'is_bulk'          => 1,
            'jumlah_slip'      => $jumlahSlip,
            'payroll_detail_id'=> null,
            'periode_id'       => $periodeId,
            'periode_nama'     => $periodeNama,
            'pegawai_nama'     => null,
            'pegawai_nip'      => null,
            'gaji_bersih'      => null,
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Finalisasi Periode
    // ------------------------------------------------------------------ //
    public static function finalisasi(int $periodeId, string $periodeName, int $jumlahSlip): void
    {
        self::write([
            'aksi'        => 'finalisasi',
            'is_bulk'     => 1,
            'jumlah_slip' => $jumlahSlip,
            'periode_id'  => $periodeId,
            'periode_nama'=> $periodeName,
            'catatan'     => "Periode {$periodeName} difinalisasi ({$jumlahSlip} slip).",
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Internal writer
    // ------------------------------------------------------------------ //
    private static function write(array $data): void
    {
        try {
            $session = session();
            $request = \Config\Services::request();

            $logModel = new PayrollStatusLogModel();
            $logModel->insert(array_merge([
                'user_id'    => (int) ($session->get('user_id') ?? 0),
                'username'   => $session->get('username') ?? 'system',
                'full_name'  => $session->get('full_name') ?? '',
                'role'       => $session->get('role') ?? '',
                'ip_address' => $request->getIPAddress(),
                'created_at' => date('Y-m-d H:i:s'),
            ], $data));
        } catch (\Throwable $e) {
            // Logging tidak boleh menggagalkan proses utama
            log_message('error', '[SlipLogger] ' . $e->getMessage());
        }
    }
}
