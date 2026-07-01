<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'username', 'password', 'full_name', 'email', 'role', 'is_active', 'last_login', 'created_at', 'updated_at'
    ];

    public function findActiveByUsername(string $username): ?array
    {
        return $this->where('username', $username)
            ->where('is_active', 1)
            ->first();
    }
}
