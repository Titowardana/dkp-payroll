<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitialSchema extends Migration
{
    public function up()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100),
            email VARCHAR(100),
            role ENUM(\'admin\', \'bendahara\') DEFAULT \'bendahara\',
            is_active BOOLEAN DEFAULT TRUE,
            last_login DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_username (username),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS pegawai (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nip VARCHAR(20) UNIQUE NOT NULL,
            nama VARCHAR(100) NOT NULL,
            nik VARCHAR(16),
            npwp VARCHAR(20),
            tempat_lahir VARCHAR(50),
            tanggal_lahir DATE,
            jenis_kelamin ENUM(\'L\', \'P\') DEFAULT \'L\',
            alamat TEXT,
            no_telp VARCHAR(15),
            email VARCHAR(100),
            tipe_jabatan VARCHAR(50),
            nama_jabatan VARCHAR(100),
            eselon VARCHAR(10),
            status_asn ENUM(\'PNS\', \'Non-PNS\') DEFAULT \'PNS\',
            golongan VARCHAR(10),
            masa_kerja_golongan INT DEFAULT 0,
            status_pernikahan ENUM(\'Menikah\', \'Belum Menikah\', \'Cerai\') DEFAULT \'Belum Menikah\',
            jumlah_istri_suami INT DEFAULT 0,
            jumlah_anak INT DEFAULT 0,
            jumlah_tanggungan INT DEFAULT 0,
            pasangan_pns BOOLEAN DEFAULT FALSE,
            nip_pasangan VARCHAR(20),
            kode_bank VARCHAR(10),
            nama_bank VARCHAR(50),
            nomor_rekening VARCHAR(30),
            gaji_pokok DECIMAL(12,2) DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            tanggal_mulai DATE,
            tanggal_selesai DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT,
            updated_by INT,
            INDEX idx_nip (nip),
            INDEX idx_nama (nama),
            INDEX idx_golongan (golongan),
            INDEX idx_status_asn (status_asn),
            INDEX idx_is_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS periode_penggajian (
            id INT PRIMARY KEY AUTO_INCREMENT,
            bulan TINYINT NOT NULL,
            tahun YEAR NOT NULL,
            nama_periode VARCHAR(50),
            tanggal_mulai DATE,
            tanggal_selesai DATE,
            status ENUM(\'draft\', \'proses\', \'selesai\', \'dibayar\') DEFAULT \'draft\',
            total_pegawai INT DEFAULT 0,
            total_gaji DECIMAL(15,2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT,
            UNIQUE KEY unique_periode (bulan, tahun),
            INDEX idx_status (status),
            INDEX idx_tahun (tahun),
            INDEX idx_bulan_tahun (bulan, tahun)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS payroll_detail (
            id INT PRIMARY KEY AUTO_INCREMENT,
            periode_id INT NOT NULL,
            pegawai_id INT NOT NULL,
            gaji_pokok DECIMAL(12,2) DEFAULT 0,
            tunjangan_keluarga DECIMAL(12,2) DEFAULT 0,
            tunjangan_pasangan_detail DECIMAL(12,2) DEFAULT 0,
            tunjangan_anak_detail DECIMAL(12,2) DEFAULT 0,
            tunjangan_jabatan DECIMAL(12,2) DEFAULT 0,
            tunjangan_fungsional DECIMAL(12,2) DEFAULT 0,
            tunjangan_fungsional_umum DECIMAL(12,2) DEFAULT 0,
            tunjangan_beras DECIMAL(12,2) DEFAULT 0,
            tunjangan_pph DECIMAL(12,2) DEFAULT 0,
            tunjangan_khusus_papua DECIMAL(12,2) DEFAULT 0,
            tunjangan_jht DECIMAL(12,2) DEFAULT 0,
            pembulatan DECIMAL(12,2) DEFAULT 0,
            tunjangan_lainnya DECIMAL(12,2) DEFAULT 0,
            iuran_jkn DECIMAL(12,2) DEFAULT 0,
            iuran_jkk DECIMAL(12,2) DEFAULT 0,
            iuran_jkm DECIMAL(12,2) DEFAULT 0,
            iuran_tapera DECIMAL(12,2) DEFAULT 0,
            iuran_pensiun DECIMAL(12,2) DEFAULT 0,
            potongan_iwp DECIMAL(12,2) DEFAULT 0,
            potongan_pph21 DECIMAL(12,2) DEFAULT 0,
            zakat DECIMAL(12,2) DEFAULT 0,
            bulog DECIMAL(12,2) DEFAULT 0,
            potongan_lainnya DECIMAL(12,2) DEFAULT 0,
            total_pendapatan DECIMAL(12,2) DEFAULT 0,
            total_potongan DECIMAL(12,2) DEFAULT 0,
            gaji_bersih DECIMAL(12,2) DEFAULT 0,
            status ENUM(\'draft\', \'verified\', \'approved\', \'paid\', \'rejected\') DEFAULT \'draft\',
            catatan_penolakan TEXT DEFAULT NULL,
            tanggal_proses DATE,
            tanggal_bayar DATE,
            metode_bayar ENUM(\'transfer\', \'tunai\') DEFAULT \'transfer\',
            referensi_bayar VARCHAR(50),
            masa_kerja INT DEFAULT 0,
            eselon VARCHAR(10),
            nama_jabatan VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT,
            UNIQUE KEY unique_payroll (periode_id, pegawai_id),
            INDEX idx_periode_id (periode_id),
            INDEX idx_pegawai_id (pegawai_id),
            INDEX idx_status (status),
            INDEX idx_tanggal_bayar (tanggal_bayar),
            INDEX idx_gaji_bersih (gaji_bersih)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS activity_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            activity_type VARCHAR(50),
            description TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_type (activity_type),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS payroll_status_logs (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id INT UNSIGNED NOT NULL,
            username VARCHAR(100) NOT NULL,
            full_name VARCHAR(200) NOT NULL DEFAULT \'\',
            role VARCHAR(50) NOT NULL DEFAULT \'\',
            aksi ENUM('verifikasi','approval','bayar','finalisasi','reject','unverify') NOT NULL,
            is_bulk TINYINT(1) NOT NULL DEFAULT 0,
            jumlah_slip INT UNSIGNED NOT NULL DEFAULT 1,
            payroll_detail_id INT UNSIGNED NULL,
            periode_id INT UNSIGNED NULL,
            periode_nama VARCHAR(100) NULL,
            pegawai_nama VARCHAR(200) NULL,
            pegawai_nip VARCHAR(50) NULL,
            gaji_bersih DECIMAL(15,2) NULL,
            catatan TEXT NULL,
            ip_address VARCHAR(45) NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_user_id (user_id),
            KEY idx_aksi (aksi),
            KEY idx_periode (periode_id),
            KEY idx_payroll (payroll_detail_id),
            KEY idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS excel_templates (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nama_template VARCHAR(100),
            file_path VARCHAR(255),
            kolom_mapping JSON,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_is_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS import_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            periode_id INT,
            filename VARCHAR(255),
            total_rows INT DEFAULT 0,
            success_rows INT DEFAULT 0,
            error_rows INT DEFAULT 0,
            error_details JSON,
            import_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            imported_by INT,
            INDEX idx_import_date (import_date),
            INDEX idx_periode_id (periode_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS system_config (
            id INT PRIMARY KEY AUTO_INCREMENT,
            config_key VARCHAR(100) UNIQUE NOT NULL,
            config_value TEXT,
            config_type ENUM(\'string\', \'number\', \'boolean\', \'json\', \'array\') DEFAULT \'string\',
            description TEXT,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_config_key (config_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->db->query('CREATE TABLE IF NOT EXISTS reference_numbers (
            id INT PRIMARY KEY AUTO_INCREMENT,
            reference_type VARCHAR(50),
            prefix VARCHAR(10),
            current_number INT DEFAULT 1,
            year INT,
            month INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_ref (reference_type, year, month),
            INDEX idx_ref_type (reference_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS payroll_status_logs');
        $this->db->query('DROP TABLE IF EXISTS import_logs');
        $this->db->query('DROP TABLE IF EXISTS activity_logs');
        $this->db->query('DROP TABLE IF EXISTS excel_templates');
        $this->db->query('DROP TABLE IF EXISTS system_config');
        $this->db->query('DROP TABLE IF EXISTS reference_numbers');
        $this->db->query('DROP TABLE IF EXISTS payroll_detail');
        $this->db->query('DROP TABLE IF EXISTS periode_penggajian');
        $this->db->query('DROP TABLE IF EXISTS pegawai');
        $this->db->query('DROP TABLE IF EXISTS users');
    }
}
