<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Backup extends BaseController
{
    private $backupPath = WRITEPATH . 'backups/';

    public function __construct()
    {
        helper('filesystem');
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
    }

    public function index()
    {
        // Get list of backups
        $files = get_filenames($this->backupPath);
        $backups = [];
        
        if ($files) {
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $fullname = $this->backupPath . $file;
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($fullname),
                        'date' => filemtime($fullname)
                    ];
                }
            }
            
            // Sort by date desc
            usort($backups, function($a, $b) {
                return $b['date'] - $a['date'];
            });
        }

        $data = [
            'title' => 'Backup Data',
            'activeMenu' => 'backup',
            'backups' => $backups,
            'db_size' => $this->getDatabaseSize()
        ];

        return $this->view('backup/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() !== 'post') throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        try {
            $db = \Config\Database::connect();
            $dbname = $db->database;
            
            $allTables = $db->query("SHOW FULL TABLES FROM " . $db->escapeIdentifiers($dbname))->getResultArray();

            $sql = "-- Database Backup\n-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            foreach ($allTables as $tableRow) {
                $tableValues = array_values($tableRow);
                $table     = $tableValues[0];   // Nama tabel/view
                $tableType = $tableValues[1];   // 'BASE TABLE' atau 'VIEW'

                $isView = ($tableType === 'VIEW');

                if ($isView) {
                    $viewQuery = $db->query("SHOW CREATE VIEW " . $db->escapeIdentifiers($table))->getRowArray();
                    $createView = $viewQuery['Create View'] ?? '';
                    $sql .= "DROP VIEW IF EXISTS `{$table}`;\n";
                    if ($createView) {
                        $sql .= $createView . ";\n\n";
                    }
                } else {
                    $tbQuery = $db->query("SHOW CREATE TABLE " . $db->escapeIdentifiers($table))->getRowArray();
                    $createTable = $tbQuery['Create Table'] ?? '';
                    $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                    if ($createTable) {
                        $sql .= $createTable . ";\n\n";
                    }

                    $rows = $db->table($table)->get()->getResultArray();
                    if (!empty($rows)) {
                        foreach ($rows as $row2) {
                            $keys = array_map(function($k) { return "`{$k}`"; }, array_keys($row2));
                            $vals = array_map(function($v) use ($db) {
                                return $v === null ? 'NULL' : $db->escape($v);
                            }, array_values($row2));
                            $sql .= "INSERT INTO `{$table}` (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $vals) . ");\n";
                        }
                        $sql .= "\n\n";
                    }
                }
            }
            
            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            helper('filesystem');
            if (write_file($this->backupPath . $filename, $sql)) {
                return redirect()->to(base_url('backup'))->with('backup_message', 'Backup berhasil dibuat: ' . $filename);
            } else {
                return redirect()->to(base_url('backup'))->with('backup_error', 'Gagal menulis file backup.');
            }
        } catch (\Exception $e) {
            return redirect()->to(base_url('backup'))->with('backup_error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function download($file)
    {
        $file = basename((string) $file);
        $path = $this->backupPath . $file;
        if (file_exists($path)) {
            return $this->response->download($path, null);
        }
        return redirect()->to(base_url('backup'))->with('backup_error', 'File tidak ditemukan.');
    }

    public function delete($file)
    {
        if ($this->request->getMethod() !== 'post') throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $file = basename((string) $file);
        $path = $this->backupPath . $file;
        if (file_exists($path)) {
            unlink($path);
            return redirect()->to(base_url('backup'))->with('backup_message', 'File backup berhasil dihapus.');
        }
        return redirect()->to(base_url('backup'))->with('backup_error', 'File tidak ditemukan.');
    }

    public function restore($file)
    {
        $file = basename((string) $file);
        $path = $this->backupPath . $file;
        if (!file_exists($path)) {
            return redirect()->to(base_url('backup'))->with('backup_error', 'File backup tidak ditemukan.');
        }

        try {
            $sql = file_get_contents($path);
            $db = \Config\Database::connect();
            
            $db->query("SET FOREIGN_KEY_CHECKS = 0");
            
            // Memisahkan query berdasarkan titik-koma yang tidak berada di dalam tanda kutip (string)
            // Ini jauh lebih aman dibanding sekadar explode(";\n", $sql);
            $queries = preg_split('/;\s*$(?![^`\'"]*[`\'"](;?>[^`\'"]*[`\'"][^`\'"]*[`\'"])*[^`\'"]*$)/m', $sql);
            
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $db->query($query);
                }
            }
            
            // Re-enable foreign keys
            $db->query("SET FOREIGN_KEY_CHECKS = 1");

            return redirect()->to(base_url('backup'))->with('backup_message', 'Database berhasil di-restore.');
        } catch (\Exception $e) {
            return redirect()->to(base_url('backup'))->with('backup_error', 'Gagal me-restore database: ' . $e->getMessage());
        }
    }

    public function cleanup()
    {
        if ($this->request->getMethod() !== 'post') throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        // Delete all backups older than 30 days
        $files = get_filenames($this->backupPath);
        $deleted = 0;
        $now = time();
        if ($files) {
            foreach ($files as $file) {
                $path = $this->backupPath . $file;
                if (is_file($path)) {
                    if ($now - filemtime($path) >= 60 * 60 * 24 * 30) {
                        unlink($path);
                        $deleted++;
                    }
                }
            }
        }
        return redirect()->to(base_url('backup'))->with('backup_message', $deleted . ' file backup lama berhasil dibersihkan.');
    }

    public function pruneData()
    {
        $months = (int) $this->request->getPost('months');
        if (!in_array($months, [3, 6, 12, 36, 60])) {
            return redirect()->to(base_url('backup'))->with('backup_error', 'Pilihan rentang waktu tidak valid.');
        }

        $calcYear = date('Y');
        $calcMonth = date('n');
        
        $thresholdAbsolute = ($calcYear * 12) + $calcMonth - $months;

        $db = \Config\Database::connect();
        $builder = $db->table('periode_penggajian');
        
        $periodes = $builder->get()->getResult();
        $targetPeriodeIds = [];
        
        foreach ($periodes as $p) {
            $absVal = ($p->tahun * 12) + $p->bulan;
            if ($absVal <= $thresholdAbsolute) {
                $targetPeriodeIds[] = $p->id;
            }
        }

        if (empty($targetPeriodeIds)) {
            return redirect()->to(base_url('backup'))->with('backup_message', 'Tidak ada data kuno yang ditemukan dalam rentang tersebut.');
        }

        $db->transStart();
        
        // Hapus detail slip gajinya trgantung ID periode yang kuno
        $db->table('payroll_detail')
           ->whereIn('periode_id', $targetPeriodeIds)
           ->delete();
           
        // Optional: Hapus periodenya juga agar bersih
        $db->table('periode_penggajian')
           ->whereIn('id', $targetPeriodeIds)
           ->delete();
           
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('backup'))->with('backup_error', 'Gagal memusnahkan data history lama.');
        }

        return redirect()->to(base_url('backup'))->with('backup_message', 'Berhasil menghapus riwayat Slip Gaji dan Periode yang berusia lebih dari '.$months.' bulan.');
    }

    private function getDatabaseSize()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema = ?", [$db->database]);
        $row = $query->getRow();
        $bytes = $row->size ?? 0;
        return number_format($bytes / 1048576, 2) . ' MB';
    }
}
