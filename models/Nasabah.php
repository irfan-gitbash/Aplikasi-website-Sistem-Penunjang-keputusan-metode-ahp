<?php
require_once 'config/database.php';

class Nasabah {
    private $conn;
    private $table_name = "nasabah";

    public $id;
    public $kode_nasabah;
    public $nama_lengkap;
    public $nik;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $no_telepon;
    public $pekerjaan;
    public $penghasilan_bulanan;
    public $status_pernikahan;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET kode_nasabah = :kode_nasabah, 
                      nama_lengkap = :nama_lengkap, 
                      nik = :nik, 
                      jenis_kelamin = :jenis_kelamin, 
                      tempat_lahir = :tempat_lahir, 
                      tanggal_lahir = :tanggal_lahir, 
                      alamat = :alamat, 
                      no_telepon = :no_telepon, 
                      pekerjaan = :pekerjaan, 
                      penghasilan_bulanan = :penghasilan_bulanan, 
                      jumlah_tanggungan = :jumlah_tanggungan, 
                      status_pernikahan = :status_pernikahan, 
                      created_at = :created_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->kode_nasabah = htmlspecialchars(strip_tags($this->kode_nasabah));
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->nik = htmlspecialchars(strip_tags($this->nik));
        $this->jenis_kelamin = htmlspecialchars(strip_tags($this->jenis_kelamin));
        $this->tempat_lahir = htmlspecialchars(strip_tags($this->tempat_lahir));
        // Tanggal lahir tidak perlu sanitasi karena sudah dalam format date
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->no_telepon = htmlspecialchars(strip_tags($this->no_telepon));
        $this->pekerjaan = htmlspecialchars(strip_tags($this->pekerjaan));
        // Untuk nilai numerik, pastikan tetap numerik setelah sanitasi
        $this->penghasilan_bulanan = is_numeric($this->penghasilan_bulanan) ? $this->penghasilan_bulanan : 0;
        $this->jumlah_tanggungan = is_numeric($this->jumlah_tanggungan) ? $this->jumlah_tanggungan : 0;
        $this->status_pernikahan = htmlspecialchars(strip_tags($this->status_pernikahan));
        $this->created_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(':kode_nasabah', $this->kode_nasabah);
        $stmt->bindParam(':nama_lengkap', $this->nama_lengkap);
        $stmt->bindParam(':nik', $this->nik);
        $stmt->bindParam(':jenis_kelamin', $this->jenis_kelamin);
        $stmt->bindParam(':tempat_lahir', $this->tempat_lahir);
        $stmt->bindParam(':tanggal_lahir', $this->tanggal_lahir);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':no_telepon', $this->no_telepon);
        $stmt->bindParam(':pekerjaan', $this->pekerjaan);
        $stmt->bindParam(':penghasilan_bulanan', $this->penghasilan_bulanan);
        $stmt->bindParam(':jumlah_tanggungan', $this->jumlah_tanggungan);
        $stmt->bindParam(':status_pernikahan', $this->status_pernikahan);
        $stmt->bindParam(':created_at', $this->created_at);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET kode_nasabah = :kode_nasabah, 
                      nama_lengkap = :nama_lengkap, 
                      nik = :nik, 
                      jenis_kelamin = :jenis_kelamin, 
                      tempat_lahir = :tempat_lahir, 
                      tanggal_lahir = :tanggal_lahir, 
                      alamat = :alamat, 
                      no_telepon = :no_telepon, 
                      pekerjaan = :pekerjaan, 
                      penghasilan_bulanan = :penghasilan_bulanan, 
                      jumlah_tanggungan = :jumlah_tanggungan, 
                      status_pernikahan = :status_pernikahan, 
                      updated_at = :updated_at 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->kode_nasabah = htmlspecialchars(strip_tags($this->kode_nasabah));
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->nik = htmlspecialchars(strip_tags($this->nik));
        $this->jenis_kelamin = htmlspecialchars(strip_tags($this->jenis_kelamin));
        $this->tempat_lahir = htmlspecialchars(strip_tags($this->tempat_lahir));
        // Tanggal lahir tidak perlu sanitasi
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->no_telepon = htmlspecialchars(strip_tags($this->no_telepon));
        $this->pekerjaan = htmlspecialchars(strip_tags($this->pekerjaan));
        // Untuk nilai numerik, pastikan tetap numerik
        $this->penghasilan_bulanan = is_numeric($this->penghasilan_bulanan) ? $this->penghasilan_bulanan : 0;
        $this->jumlah_tanggungan = is_numeric($this->jumlah_tanggungan) ? $this->jumlah_tanggungan : 0;
        $this->status_pernikahan = htmlspecialchars(strip_tags($this->status_pernikahan));
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(':kode_nasabah', $this->kode_nasabah);
        $stmt->bindParam(':nama_lengkap', $this->nama_lengkap);
        $stmt->bindParam(':nik', $this->nik);
        $stmt->bindParam(':jenis_kelamin', $this->jenis_kelamin);
        $stmt->bindParam(':tempat_lahir', $this->tempat_lahir);
        $stmt->bindParam(':tanggal_lahir', $this->tanggal_lahir);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':no_telepon', $this->no_telepon);
        $stmt->bindParam(':pekerjaan', $this->pekerjaan);
        $stmt->bindParam(':penghasilan_bulanan', $this->penghasilan_bulanan);
        $stmt->bindParam(':jumlah_tanggungan', $this->jumlah_tanggungan);
        $stmt->bindParam(':status_pernikahan', $this->status_pernikahan);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind value
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getLatest($limit = 5) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $nasabah_terbaru = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nasabah_terbaru[] = $row;
        }
        
        return $nasabah_terbaru;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>