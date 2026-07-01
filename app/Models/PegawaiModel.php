<?php namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nip','nama','nik','npwp','tempat_lahir','tanggal_lahir','jenis_kelamin','alamat','no_telp','email',
        'tipe_jabatan','nama_jabatan','eselon','status_asn','golongan','masa_kerja_golongan',
        'status_pernikahan','jumlah_istri_suami','jumlah_anak','jumlah_tanggungan','pasangan_pns','nip_pasangan',
        'kode_bank','nama_bank','nomor_rekening','gaji_pokok','is_active','tanggal_mulai','tanggal_selesai',
        'created_by','updated_by','created_at','updated_at'
    ];
    protected $useTimestamps = false;

    public function active()
    {
        return $this->where('is_active', 1);
    }
}
