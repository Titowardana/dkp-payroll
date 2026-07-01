<?php namespace App\Models;

use CodeIgniter\Model;

class PeriodeModel extends Model
{
    protected $table = 'periode_penggajian';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'bulan','tahun','nama_periode','tanggal_mulai','tanggal_selesai','status','total_pegawai','total_gaji','created_by','created_at','updated_at'
    ];
    protected $useTimestamps = false;
}
