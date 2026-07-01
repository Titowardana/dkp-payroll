<?php namespace App\Models;

use CodeIgniter\Model;

class SystemConfigModel extends Model
{
    protected $table = 'system_config';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'config_key', 'config_value', 'config_type', 'description', 'is_public', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function getValue(string $key, $default = null)
    {
        $row = $this->where('config_key', $key)->first();
        return $row ? $row['config_value'] : $default;
    }
}
