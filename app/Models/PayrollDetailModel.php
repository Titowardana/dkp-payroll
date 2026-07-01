<?php namespace App\Models;

use CodeIgniter\Model;

class PayrollDetailModel extends Model
{
    protected $table = 'payroll_detail';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'periode_id','pegawai_id','gaji_pokok','tunjangan_keluarga','tunjangan_pasangan_detail','tunjangan_anak_detail',
        'tunjangan_jabatan','tunjangan_fungsional','tunjangan_fungsional_umum','tunjangan_beras','tunjangan_pph',
        'tunjangan_khusus_papua','tunjangan_jht','pembulatan','tunjangan_lainnya','iuran_jkn','iuran_jkk','iuran_jkm',
        'iuran_tapera','iuran_pensiun','potongan_iwp','potongan_pph21','zakat','bulog','potongan_lainnya',
        'total_pendapatan','total_potongan','gaji_bersih','status','catatan_penolakan','tanggal_proses','tanggal_bayar',
        'metode_bayar','referensi_bayar','masa_kerja','eselon','nama_jabatan','created_by','created_at','updated_at'
    ];
    protected $useTimestamps = false;

    public function withRelations()
    {
        return $this->select('payroll_detail.*, pegawai.nama, pegawai.nip, pegawai.nama_jabatan as pegawai_jabatan, periode_penggajian.nama_periode, periode_penggajian.bulan, periode_penggajian.tahun')
            ->join('pegawai', 'pegawai.id = payroll_detail.pegawai_id', 'left')
            ->join('periode_penggajian', 'periode_penggajian.id = payroll_detail.periode_id', 'left');
    }
}
