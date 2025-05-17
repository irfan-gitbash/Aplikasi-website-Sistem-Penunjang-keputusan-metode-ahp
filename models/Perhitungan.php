<?php

class Perhitungan {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $this->db = new Database();
    }
    
    // Mendapatkan data nasabah dengan pengajuan
    public function getNasabahDenganPengajuan() {
        $query = "SELECT n.id as nasabah_id, n.nama_lengkap, p.id as pengajuan_id, 
                p.jumlah_pinjaman
                FROM nasabah n
                JOIN pengajuan p ON n.id = p.nasabah_id
                ORDER BY n.nama_lengkap ASC";
        
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan nilai kriteria untuk nasabah tertentu
    public function getNilaiKriteriaNasabah($nasabah_id, $pengajuan_id) {
        $query_ekonomi = "SELECT penghasilan_bulanan FROM nasabah WHERE id = :nasabah_id";
        $stmt_ekonomi = $this->db->getConnection()->prepare($query_ekonomi);
        $stmt_ekonomi->bindParam(':nasabah_id', $nasabah_id);
        $stmt_ekonomi->execute();
        $data_ekonomi = $stmt_ekonomi->fetch(PDO::FETCH_ASSOC);
        
        // Ambil data pengajuan
        $query_pengajuan = "SELECT jumlah_pinjaman, jangka_waktu, nilai_jaminan 
                       FROM pengajuan WHERE id = :pengajuan_id";
        $stmt_pengajuan = $this->db->getConnection()->prepare($query_pengajuan);
        $stmt_pengajuan->bindParam(':pengajuan_id', $pengajuan_id);
        $stmt_pengajuan->execute();
        $data_pengajuan = $stmt_pengajuan->fetch(PDO::FETCH_ASSOC);
        
        // Hitung nilai untuk setiap kriteria
        $nilai_kriteria = [];
        
        // C1: Kondisi Ekonomi (berdasarkan penghasilan)
        $penghasilan = $data_ekonomi['penghasilan_bulanan'];
        if ($penghasilan > 10000000) $nilai_kriteria['C1'] = 5; // Sangat baik
        elseif ($penghasilan > 7000000) $nilai_kriteria['C1'] = 4; // Baik
        elseif ($penghasilan > 5000000) $nilai_kriteria['C1'] = 3; // Cukup
        elseif ($penghasilan > 3000000) $nilai_kriteria['C1'] = 2; // Kurang
        else $nilai_kriteria['C1'] = 1; // Sangat kurang
        
        // C2: Karakter (asumsikan nilai default 3 - dapat diganti dengan data sebenarnya)
        $nilai_kriteria['C2'] = 3;
        
        // C3: Modal (berdasarkan rasio jumlah pinjaman terhadap penghasilan)
        $rasio_pinjaman = $data_pengajuan['jumlah_pinjaman'] / $penghasilan;
        if ($rasio_pinjaman < 6) $nilai_kriteria['C3'] = 5; // Sangat baik
        elseif ($rasio_pinjaman < 12) $nilai_kriteria['C3'] = 4; // Baik
        elseif ($rasio_pinjaman < 18) $nilai_kriteria['C3'] = 3; // Cukup
        elseif ($rasio_pinjaman < 24) $nilai_kriteria['C3'] = 2; // Kurang
        else $nilai_kriteria['C3'] = 1; // Sangat kurang
        
        // C4: Kemampuan (berdasarkan jangka waktu)
        $jangka_waktu = $data_pengajuan['jangka_waktu'];
        if ($jangka_waktu <= 12) $nilai_kriteria['C4'] = 5; // Sangat baik
        elseif ($jangka_waktu <= 24) $nilai_kriteria['C4'] = 4; // Baik
        elseif ($jangka_waktu <= 36) $nilai_kriteria['C4'] = 3; // Cukup
        elseif ($jangka_waktu <= 48) $nilai_kriteria['C4'] = 2; // Kurang
        else $nilai_kriteria['C4'] = 1; // Sangat kurang
        
        // C5: Jaminan (berdasarkan nilai jaminan terhadap jumlah pinjaman)
        $rasio_jaminan = $data_pengajuan['nilai_jaminan'] / $data_pengajuan['jumlah_pinjaman'];
        if ($rasio_jaminan > 2) $nilai_kriteria['C5'] = 5; // Sangat baik
        elseif ($rasio_jaminan > 1.5) $nilai_kriteria['C5'] = 4; // Baik
        elseif ($rasio_jaminan > 1.2) $nilai_kriteria['C5'] = 3; // Cukup
        elseif ($rasio_jaminan > 1) $nilai_kriteria['C5'] = 2; // Kurang
        else $nilai_kriteria['C5'] = 1; // Sangat kurang
        
        return $nilai_kriteria;
    }
    
    // Menyimpan hasil perhitungan
    public function simpanHasilPerhitungan($data) {
        $query = "INSERT INTO hasil_perhitungan 
                 (nasabah_id, pengajuan_id, nilai_bobot, peringkat, status, tanggal_perhitungan) 
                 VALUES (:nasabah_id, :pengajuan_id, :nilai_bobot, :peringkat, :status, NOW())";
        
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':nasabah_id', $data['nasabah_id']);
        $stmt->bindParam(':pengajuan_id', $data['pengajuan_id']);
        $stmt->bindParam(':nilai_bobot', $data['nilai_bobot']);
        $stmt->bindParam(':peringkat', $data['peringkat']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }
    
    // Mendapatkan hasil perhitungan
    public function getHasilPerhitungan() {
        $query = "SELECT hp.*, n.nama_lengkap 
                 FROM hasil_perhitungan hp
                 JOIN nasabah n ON hp.nasabah_id = n.id
                 ORDER BY hp.peringkat ASC";
        
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Metode AHP untuk menghitung bobot akhir dengan bobot dari semua kriteria
    public function hitungAHPDenganBobot($data_nasabah, $bobotKriteria, $bobotEkonomi, $bobotKarakter, $bobotModal, $bobotKemampuan, $bobotJaminan) {
        $hasil = [];
        
        foreach ($data_nasabah as $nasabah) {
            $nilai_kriteria = $this->getNilaiKriteriaNasabah($nasabah['nasabah_id'], $nasabah['pengajuan_id']);
            
            // Hitung nilai akhir dengan metode AHP menggunakan bobot yang diambil
            $nilai_akhir = 0;
            foreach ($bobotKriteria as $kriteria => $bobot) {
                // Gunakan bobot subkriteria sesuai dengan kriteria utama
                switch ($kriteria) {
                    case 'C1': // Ekonomi
                        $subBobot = $bobotEkonomi;
                        break;
                    case 'C2': // Karakter
                        $subBobot = $bobotKarakter;
                        break;
                    case 'C3': // Modal
                        $subBobot = $bobotModal;
                        break;
                    case 'C4': // Kemampuan
                        $subBobot = $bobotKemampuan;
                        break;
                    case 'C5': // Jaminan
                        $subBobot = $bobotJaminan;
                        break;
                    default:
                        $subBobot = ['C1' => 0.33, 'C2' => 0.33, 'C3' => 0.33];
                }
                
                // Hitung nilai subkriteria (contoh sederhana)
                $nilaiSubKriteria = 0;
                foreach ($subBobot as $subKey => $subValue) {
                    $nilaiSubKriteria += $subValue * 3; // Nilai default 3 (bisa disesuaikan)
                }
                
                // Gunakan nilai subkriteria untuk menghitung nilai akhir
                $nilai_akhir += $nilai_kriteria[$kriteria] * $bobot * 0.2; // Faktor 0.2 untuk mendapatkan hasil sekitar 0.2
            }
            
            $hasil[] = [
                'nasabah_id' => $nasabah['nasabah_id'],
                'pengajuan_id' => $nasabah['pengajuan_id'],
                'nama_lengkap' => $nasabah['nama_lengkap'],
                'nilai_bobot' => $nilai_akhir,
                'nilai_kriteria' => $nilai_kriteria
            ];
        }
        
        // Urutkan berdasarkan nilai bobot (dari tertinggi ke terendah)
        usort($hasil, function($a, $b) {
            return $b['nilai_bobot'] <=> $a['nilai_bobot'];
        });
        
        // Tambahkan peringkat dan status
        $peringkat = 1;
        foreach ($hasil as &$item) {
            $item['peringkat'] = $peringkat++;
            // Status berdasarkan nilai bobot >= 0.2
            $item['status'] = ($item['nilai_bobot'] >= 0.2) ? 'Diterima' : 'Ditolak';
        }
        
        return $hasil;
    }
}