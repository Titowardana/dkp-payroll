<?php namespace App\Models;

use CodeIgniter\Model;

class PayrollStatusLogModel extends Model
{
    protected $table         = 'payroll_status_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id', 'username', 'full_name', 'role', 'aksi',
        'is_bulk', 'jumlah_slip', 'payroll_detail_id',
        'periode_id', 'periode_nama', 'pegawai_nama', 'pegawai_nip',
        'gaji_bersih', 'catatan', 'ip_address', 'created_at',
    ];

    /**
     * Ambil histori terbaru dengan join ke users (opsional).
     * Mendukung filter: aksi, user_id, periode_id.
     */
    public function getHistori(array $filter = [], int $limit = 50): array
    {
        $builder = $this->db->table('payroll_status_logs psl')
            ->select('psl.*, u.full_name AS user_fullname')
            ->join('users u', 'u.id = psl.user_id', 'left')
            ->orderBy('psl.created_at', 'DESC');

        if (!empty($filter['aksi'])) {
            $builder->where('psl.aksi', $filter['aksi']);
        }
        if (!empty($filter['user_id'])) {
            $builder->where('psl.user_id', (int) $filter['user_id']);
        }
        if (!empty($filter['periode_id'])) {
            $builder->where('psl.periode_id', (int) $filter['periode_id']);
        }

        return $builder->limit($limit)->get()->getResultArray();
    }

    /**
     * Ringkasan hitungan per aksi (untuk dashboard/statistik).
     */
    public function getSummary(): array
    {
        return $this->db->table('payroll_status_logs')
            ->select('aksi, COUNT(*) as total, SUM(jumlah_slip) as total_slip')
            ->groupBy('aksi')
            ->get()->getResultArray();
    }
}
