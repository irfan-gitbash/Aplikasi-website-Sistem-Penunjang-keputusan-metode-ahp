<?php
require_once 'config/database.php';

class Pengajuan {
    private $conn;
    private $table_name = "pengajuan";

    public $id;
    public $nasabah_id;
    public $jumlah_pinjaman;
    public $jangka_waktu;
    public $tujuan_pinjaman;
    public $jaminan;
    public $nilai_jaminan;
    public $status;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        // Ganti n.nama dengan nama kolom yang benar, misalnya n.nama_lengkap
        $query = "SELECT p.*, n.nama_lengkap as nama_nasabah 
                  FROM " . $this->table_name . " p
                  LEFT JOIN nasabah n ON p.nasabah_id = n.id
                  ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        // Ganti n.nama dengan nama kolom yang benar, misalnya n.nama_lengkap
        $query = "SELECT p.*, n.nama_lengkap as nama_nasabah 
                  FROM " . $this->table_name . " p
                  LEFT JOIN nasabah n ON p.nasabah_id = n.id
                  WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambahkan property baru
    // Ubah property tanggal menjadi tanggal_pengajuan
    // Tambahkan property baru
    // Tambahkan property ini di bagian deklarasi property
    public $kode_pengajuan;
    public $tanggal_pengajuan;
    public $kondisi_ekonomi;
    public $karakter;
    public $modal;
    public $kemampuan;
    public $jenis_usaha;
    public $lama_usaha;

    public function create() {
        // Generate kode pengajuan
        $tahun = date('Y');
        $bulan = date('m');
        
        // Cari kode pengajuan terakhir dengan format PJM-YYYYMM-XXX
        $query_last = "SELECT kode_pengajuan FROM " . $this->table_name . " 
                      WHERE kode_pengajuan LIKE 'PJM-" . $tahun . $bulan . "-%' 
                      ORDER BY id DESC LIMIT 1";
        $stmt_last = $this->conn->prepare($query_last);
        $stmt_last->execute();
        
        if ($stmt_last->rowCount() > 0) {
            $row = $stmt_last->fetch(PDO::FETCH_ASSOC);
            $last_code = $row['kode_pengajuan'];
            $last_number = intval(substr($last_code, -3));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        // Format nomor dengan leading zeros
        $formatted_number = str_pad($new_number, 3, '0', STR_PAD_LEFT);
        $this->kode_pengajuan = "PJM-" . $tahun . $bulan . "-" . $formatted_number;
        
        // Set default created_by jika tidak ada
        if (empty($this->created_by) && isset($_SESSION['user_id'])) {
            $this->created_by = $_SESSION['user_id'];
        } else if (empty($this->created_by)) {
            $this->created_by = 1; // Default ke admin atau user ID 1 jika tidak ada session
        }
        
        // Query SQL sudah benar, tidak perlu diubah
        $query = "INSERT INTO " . $this->table_name . " 
                  SET kode_pengajuan = :kode_pengajuan,
                      nasabah_id = :nasabah_id, 
                      tanggal_pengajuan = :tanggal_pengajuan,
                      kondisi_ekonomi = :kondisi_ekonomi,
                      karakter = :karakter,
                      modal = :modal,
                      kemampuan = :kemampuan,
                      jumlah_pinjaman = :jumlah_pinjaman,
                      status_pengajuan = :status_pengajuan,
                      created_by = :created_by,
                      created_at = :created_at";
        
        // TAMBAHKAN BARIS INI UNTUK MENYIAPKAN STATEMENT
        $stmt = $this->conn->prepare($query);
        
        // Hapus duplikasi binding parameter
        // Bind values - PERBAIKAN
        $stmt->bindParam(':kode_pengajuan', $this->kode_pengajuan);
        $stmt->bindParam(':nasabah_id', $this->nasabah_id);
        $stmt->bindParam(':tanggal_pengajuan', $this->tanggal_pengajuan);
        $stmt->bindParam(':kondisi_ekonomi', $this->kondisi_ekonomi);
        $stmt->bindParam(':karakter', $this->karakter);
        $stmt->bindParam(':modal', $this->modal);
        $stmt->bindParam(':kemampuan', $this->kemampuan);
        $stmt->bindParam(':jumlah_pinjaman', $this->jumlah_pinjaman);
        $stmt->bindParam(':status_pengajuan', $this->status_pengajuan);
        $stmt->bindParam(':created_by', $this->created_by);
        $stmt->bindParam(':created_at', $this->created_at);
        
        // HAPUS SELURUH BAGIAN INI:
        // Tambahkan binding untuk parameter yang ada di query tapi belum di-bind
        // $stmt->bindParam(':jumlah_pinjaman', $this->jumlah_pinjaman);
        // $stmt->bindParam(':tujuan_pinjaman', $this->tujuan_pinjaman);
        // $stmt->bindParam(':jenis_usaha', $this->jenis_usaha);
        // $stmt->bindParam(':lama_usaha', $this->lama_usaha);
        // $stmt->bindParam(':status_pengajuan', $this->status_pengajuan);
        // $stmt->bindParam(':created_by', $this->created_by);
        // $stmt->bindParam(':created_at', $this->created_at);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nasabah_id = :nasabah_id, 
                      tanggal_pengajuan = :tanggal_pengajuan,
                      kondisi_ekonomi = :kondisi_ekonomi,
                      karakter = :karakter,
                      modal = :modal,
                      kemampuan = :kemampuan,
                      jumlah_pinjaman = :jumlah_pinjaman,
                      status_pengajuan = :status_pengajuan,
                      updated_at = :updated_at 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->nasabah_id = htmlspecialchars(strip_tags($this->nasabah_id));
        $this->tanggal_pengajuan = htmlspecialchars(strip_tags($this->tanggal_pengajuan));
        $this->kondisi_ekonomi = htmlspecialchars(strip_tags($this->kondisi_ekonomi));
        $this->karakter = htmlspecialchars(strip_tags($this->karakter));
        $this->modal = htmlspecialchars(strip_tags($this->modal));
        $this->kemampuan = htmlspecialchars(strip_tags($this->kemampuan));
        
        // Validasi status_pengajuan
        $valid_statuses = ['Diajukan', 'Diproses', 'Diterima', 'Ditolak'];
        if (!in_array($this->status_pengajuan, $valid_statuses)) {
            $this->status_pengajuan = 'Diajukan'; // Default jika tidak valid
        }
        $this->status_pengajuan = htmlspecialchars(strip_tags($this->status_pengajuan));
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind values
        // Bind values
        // Bind values
        $stmt->bindParam(':nasabah_id', $this->nasabah_id);
        $stmt->bindParam(':tanggal_pengajuan', $this->tanggal_pengajuan);
        $stmt->bindParam(':kondisi_ekonomi', $this->kondisi_ekonomi);
        $stmt->bindParam(':karakter', $this->karakter);
        $stmt->bindParam(':modal', $this->modal);
        $stmt->bindParam(':kemampuan', $this->kemampuan);
        $stmt->bindParam(':jumlah_pinjaman', $this->jumlah_pinjaman);
        $stmt->bindParam(':status_pengajuan', $this->status_pengajuan);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update_status() {
        $query = "UPDATE " . $this->table_name . " 
                  SET status_pengajuan = :status_pengajuan,
                      updated_at = :updated_at 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->status_pengajuan = htmlspecialchars(strip_tags($this->status_pengajuan));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(':status_pengajuan', $this->status_pengajuan);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->bindParam(':id', $this->id);
        
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function countByStatus($status) {
        // Cek apakah kolom status_pengajuan ada dalam tabel
        try {
            $query = "SHOW COLUMNS FROM " . $this->table_name . " LIKE 'status_pengajuan'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $column_exists = $stmt->rowCount() > 0;
            
            if ($column_exists) {
                $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status_pengajuan = :status_pengajuan";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':status_pengajuan', $status);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row['total'];
            } else {
                // Kolom status_pengajuan tidak ada, kembalikan 0
                return 0;
            }
        } catch (Exception $e) {
            // Jika terjadi error, kembalikan 0
            return 0;
        }
    }

    public function getLatest($limit = 5) {
        $query = "SELECT p.*, n.nama_lengkap as nama_nasabah, p.tanggal_pengajuan as tanggal, p.status_pengajuan 
                  FROM " . $this->table_name . " p
                  LEFT JOIN nasabah n ON p.nasabah_id = n.id
                  ORDER BY p.created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $pengajuan_terbaru = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pengajuan_terbaru[] = $row;
        }
        
        return $pengajuan_terbaru;
    }
}
?>