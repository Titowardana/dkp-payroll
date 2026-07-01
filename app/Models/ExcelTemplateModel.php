<?php namespace App\Models;

use CodeIgniter\Model;

class ExcelTemplateModel extends Model
{
    protected $table = 'excel_templates';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama_template', 'file_path', 'kolom_mapping', 'is_active', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function active()
    {
        return $this->where('is_active', 1);
    }
}
