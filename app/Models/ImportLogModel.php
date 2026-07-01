<?php namespace App\Models;

use CodeIgniter\Model;

class ImportLogModel extends Model
{
    protected $table = 'import_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'periode_id', 'filename', 'total_rows', 'success_rows', 'error_rows', 'error_details', 'import_date', 'imported_by',
    ];
    protected $useTimestamps = false;
}
