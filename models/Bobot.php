<?php
require_once 'config/database.php';

class Bobot {
    private $conn;
    private $table_name = "kriteria";
    private $table_perbandingan = "perbandingan_kriteria";

    public $id;
    public $nama_kriteria;
    public $kode;
    public $deskripsi;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
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
                  SET nama_kriteria = :nama_kriteria, 
                      kode = :kode, 
                      deskripsi = :deskripsi, 
                      created_at = :created_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->nama_kriteria = htmlspecialchars(strip_tags($this->nama_kriteria));
        $this->kode = htmlspecialchars(strip_tags($this->kode));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi));
        $this->created_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(':nama_kriteria', $this->nama_kriteria);
        $stmt->bindParam(':kode', $this->kode);
        $stmt->bindParam(':deskripsi', $this->deskripsi);
        $stmt->bindParam(':created_at', $this->created_at);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nama_kriteria = :nama_kriteria, 
                      kode = :kode, 
                      deskripsi = :deskripsi 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->nama_kriteria = htmlspecialchars(strip_tags($this->nama_kriteria));
        $this->kode = htmlspecialchars(strip_tags($this->kode));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind values
        $stmt->bindParam(':nama_kriteria', $this->nama_kriteria);
        $stmt->bindParam(':kode', $this->kode);
        $stmt->bindParam(':deskripsi', $this->deskripsi);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // Delete related perbandingan first
        $query = "DELETE FROM " . $this->table_perbandingan . " 
                  WHERE kriteria_1 = :id OR kriteria_2 = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        // Then delete the kriteria
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tambahkan metode ini di class Bobot

    public function savePerbandinganEkonomi($kriteria1, $kriteria2, $nilai) {
        // Nama tabel untuk perbandingan ekonomi
        $table = "perbandingan_ekonomi";
        
        // Check if perbandingan already exists
        $query = "SELECT * FROM " . $table . " 
                  WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing
            $query = "UPDATE " . $table . " 
                      SET nilai = :nilai 
                      WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        } else {
            // Insert new
            $query = "INSERT INTO " . $table . " 
                      SET kriteria_1 = :kriteria1, 
                          kriteria_2 = :kriteria2, 
                          nilai = :nilai";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind values
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->bindParam(':nilai', $nilai);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function savePerbandinganKarakter($kriteria1, $kriteria2, $nilai) {
        // Nama tabel untuk perbandingan karakter
        $table = "perbandingan_karakter";
        
        // Check if perbandingan already exists
        $query = "SELECT * FROM " . $table . " 
                  WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing
            $query = "UPDATE " . $table . " 
                      SET nilai = :nilai 
                      WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        } else {
            // Insert new
            $query = "INSERT INTO " . $table . " 
                      SET kriteria_1 = :kriteria1, 
                          kriteria_2 = :kriteria2, 
                          nilai = :nilai";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind values
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->bindParam(':nilai', $nilai);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tambahkan metode ini di class Bobot

    public function savePerbandinganModal($kriteria1, $kriteria2, $nilai) {
        // Nama tabel untuk perbandingan modal
        $table = "perbandingan_modal";
        
        // Check if perbandingan already exists
        $query = "SELECT * FROM " . $table . " 
                  WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing
            $query = "UPDATE " . $table . " 
                      SET nilai = :nilai 
                      WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        } else {
            // Insert new
            $query = "INSERT INTO " . $table . " 
                      SET kriteria_1 = :kriteria1, 
                          kriteria_2 = :kriteria2, 
                          nilai = :nilai";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind values
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->bindParam(':nilai', $nilai);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function savePerbandinganKemampuan($kriteria1, $kriteria2, $nilai) {
        // Nama tabel untuk perbandingan kemampuan
        $table = "perbandingan_kemampuan";
        
        // Check if perbandingan already exists
        $query = "SELECT * FROM " . $table . " 
                  WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing
            $query = "UPDATE " . $table . " 
                      SET nilai = :nilai 
                      WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        } else {
            // Insert new
            $query = "INSERT INTO " . $table . " 
                      SET kriteria_1 = :kriteria1, 
                          kriteria_2 = :kriteria2, 
                          nilai = :nilai";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind values
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->bindParam(':nilai', $nilai);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function savePerbandinganJaminan($kriteria1, $kriteria2, $nilai) {
        // Nama tabel untuk perbandingan jaminan
        $table = "perbandingan_jaminan";
        
        // Check if perbandingan already exists
        $query = "SELECT * FROM " . $table . " 
                  WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing
            $query = "UPDATE " . $table . " 
                      SET nilai = :nilai 
                      WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        } else {
            // Insert new
            $query = "INSERT INTO " . $table . " 
                      SET kriteria_1 = :kriteria1, 
                          kriteria_2 = :kriteria2, 
                          nilai = :nilai";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind values
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->bindParam(':nilai', $nilai);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function savePerbandingan($kriteria1, $kriteria2, $nilai) {
        // Validasi keberadaan kriteria
        $query_check = "SELECT id FROM kriteria WHERE id IN (:kriteria1, :kriteria2)";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':kriteria1', $kriteria1);
        $stmt_check->bindParam(':kriteria2', $kriteria2);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() < 2) {
            throw new Exception("Kriteria dengan ID yang diberikan tidak ditemukan");
        }
        
        // Nama tabel untuk perbandingan kriteria utama
        $table = "perbandingan_kriteria";
        
        // Check if perbandingan already exists
        $query = "SELECT * FROM " . $table . " 
                  WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing
            $query = "UPDATE " . $table . " 
                      SET nilai = :nilai 
                      WHERE kriteria_1 = :kriteria1 AND kriteria_2 = :kriteria2";
        } else {
            // Insert new
            $query = "INSERT INTO " . $table . " 
                      (kriteria_1, kriteria_2, nilai)
                      VALUES (:kriteria1, :kriteria2, :nilai)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind values
        $stmt->bindParam(':kriteria1', $kriteria1);
        $stmt->bindParam(':kriteria2', $kriteria2);
        $stmt->bindParam(':nilai', $nilai);
        
        return $stmt->execute();
    }

    public function getPerbandinganKriteria() {
        $query = "SELECT kriteria_1 as kriteria1_id, kriteria_2 as kriteria2_id, nilai FROM perbandingan_kriteria";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerbandinganModal() {
        $query = "SELECT kriteria_1, kriteria_2, nilai FROM perbandingan_modal";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPerbandinganKemampuan() {
        $query = "SELECT kriteria_1, kriteria_2, nilai FROM perbandingan_kemampuan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerbandinganEkonomi() {
        $query = "SELECT kriteria_1, kriteria_2, nilai FROM perbandingan_ekonomi";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerbandinganJaminan() {
        $query = "SELECT kriteria_1, kriteria_2, nilai FROM perbandingan_jaminan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPerbandinganKarakter() {
        $query = "SELECT kriteria_1, kriteria_2, nilai FROM perbandingan_karakter";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}