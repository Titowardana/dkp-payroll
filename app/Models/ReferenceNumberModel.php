<?php namespace App\Models;

use CodeIgniter\Model;

class ReferenceNumberModel extends Model
{
    protected $table = 'reference_numbers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'reference_type', 'prefix', 'current_number', 'year', 'month', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function nextNumber(string $type, string $prefix, int $year, int $month): string
    {
        $row = $this->where('reference_type', $type)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($row) {
            $num = (int) $row['current_number'] + 1;
            $this->update($row['id'], ['current_number' => $num]);
        } else {
            $num = 1;
            $this->insert([
                'reference_type' => $type,
                'prefix' => $prefix,
                'current_number' => $num,
                'year' => $year,
                'month' => $month,
            ]);
        }

        return $prefix . $year . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . str_pad((string) $num, 4, '0', STR_PAD_LEFT);
    }
}
