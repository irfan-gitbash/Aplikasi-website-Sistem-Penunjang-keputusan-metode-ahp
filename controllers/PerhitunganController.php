<?php

class PerhitunganController {
    private $perhitungan;
    private $bobot;
    
    public function __construct() {
        require_once __DIR__ . '/../models/Perhitungan.php';
        require_once __DIR__ . '/../models/Bobot.php';
        $this->perhitungan = new Perhitungan();
        $this->bobot = new Bobot();
        
        // Cek apakah user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /spk_ahp/auth/login');
            exit;
        }
    }
    
    public function index() {
        include 'views/templates/header.php';
        include 'views/perhitungan/index.php';
        include 'views/templates/footer.php';
    }
    
    public function kriteria() {
        // Data kriteria
        $namaKriteria = ['C1', 'C2', 'C3', 'C4', 'C5'];
        $deskripsiKriteria = [
            'Kondisi Ekonomi',
            'Karakter',
            'Modal',
            'Kemampuan',
            'Jaminan'
        ];
        
        // Modifikasi matriks perbandingan
        $matriksKriteria = [
            [1.0, 4.0, 1.0, 1.0, 1.0],
            [0.25, 1.0, 5.0, 1.0, 1.0],
            [1.00, 0.20, 1.0, 4.0, 1.0],
            [1.00, 1.00, 0.25, 1.0, 7.0],
            [1.00, 1.00, 1.00, 1.0, 1]
        ];
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung Consistency Ratio (CR)
        // 1. Hitung λ max
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlah = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlah += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $matriksBobot[$i] = $jumlah;
            $lambdaMax += $jumlah / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // 2. Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // 3. Hitung Consistency Ratio (CR)
        // Random Index (RI) untuk n=0.1 adalah 1.12
        $RI = 1.12;
        $CR = $CI / $RI;
        
        // Matriks konsisten jika CR <= 0.1
        $konsisten = $CR <= 0.1;
        
        include 'views/templates/header.php';
        include 'views/perhitungan/kriteria.php';
        include 'views/templates/footer.php';
    }
    
    public function ekonomi() {
        // Data kriteria ekonomi
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Penghasilan',
            'Pengeluaran',
            'Tanggungan'
        ];
        
        // Matriks perbandingan berpasangan dari gambar
        $matriksKriteria = [
            [1.0, 3.0, 5.0],
            [0.33, 1.0, 1.0],
            [0.2, 1.0, 1.0]
        ];
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung Consistency Ratio (CR)
        // 1. Hitung λ max
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlah = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlah += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $matriksBobot[$i] = $jumlah;
            $lambdaMax += $jumlah / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // 2. Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // 3. Hitung Consistency Ratio (CR)
        // Random Index (RI) untuk n=3 adalah 0.58
        $RI = 0.58;
        $CR = $CI / $RI;
        
        // Matriks konsisten jika CR <= 0.1
        $konsisten = $CR <= 0.1;
        
        include 'views/templates/header.php';
        include 'views/perhitungan/ekonomi.php';
        include 'views/templates/footer.php';
    }
    
    public function karakter() {
        // Data kriteria karakter
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Kepribadian',
            'Kejujuran',
            'Komitmen'
        ];
        
        // Matriks perbandingan berpasangan dari gambar
        $matriksKriteria = [
            [1.0, 3.0, 1.0],
            [0.33, 1.0, 5.0],
            [1.0, 0.2, 1.0]
        ];
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung Consistency Ratio (CR)
        // 1. Hitung λ max
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlah = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlah += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $matriksBobot[$i] = $jumlah;
            $lambdaMax += $jumlah / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // 2. Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // 3. Hitung Consistency Ratio (CR)
        // Random Index (RI) untuk n=3 adalah 0.58
        $RI = 0.58;
        $CR = $CI / $RI;
        
        // Matriks konsisten jika CR <= 0.1
        $konsisten = $CR <= 0.1;
        
        include 'views/templates/header.php';
        include 'views/perhitungan/karakter.php';
        include 'views/templates/footer.php';
    }
    
    public function modal() {
        // Data kriteria modal
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Sumber Modal',
            'Besar Modal',
            'Penggunaan Modal'
        ];
        
        // Matriks perbandingan berpasangan dari data bobot
        $matriksKriteria = [
            [1.0, 3.0, 2.0],
            [0.33, 1.0, 1.0],
            [0.50, 1.00, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganModal();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung Consistency Ratio (CR)
        // 1. Hitung λ max
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlah = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlah += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $matriksBobot[$i] = $jumlah;
            $lambdaMax += $jumlah / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // 2. Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // 3. Hitung Consistency Ratio (CR)
        // Random Index (RI) untuk n=3 adalah 0.58
        $RI = 0.58;
        $CR = $CI / $RI;
        
        // Matriks konsisten jika CR <= 0.1
        $konsisten = $CR <= 0.1;
        
        include 'views/templates/header.php';
        include 'views/perhitungan/modal.php';
        include 'views/templates/footer.php';
    }
    
    public function kemampuan() {
        // Data kriteria kemampuan
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Pendapatan',
            'Pengeluaran',
            'Sisa Pendapatan'
        ];
        
        // Matriks perbandingan berpasangan dari data bobot
        $matriksKriteria = [
            [1.0, 5.0, 1.0],
            [0.20, 1.0, 5.0],
            [1.00, 0.20, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganKemampuan();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung Consistency Ratio (CR)
        // 1. Hitung λ max
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlah = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlah += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $matriksBobot[$i] = $jumlah;
            $lambdaMax += $jumlah / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // 2. Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // 3. Hitung Consistency Ratio (CR)
        // Random Index (RI) untuk n=3 adalah 0.58
        $RI = 0.58;
        $CR = $CI / $RI;
        
        // Matriks konsisten jika CR <= 0.1
        $konsisten = $CR <= 0.1;
        
        include 'views/templates/header.php';
        include 'views/perhitungan/kemampuan.php';
        include 'views/templates/footer.php';
    }
    
    public function jaminan() {
        // Data kriteria jaminan
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Nilai Jaminan',
            'Status Kepemilikan',
            'Kelengkapan Dokumen'
        ];
        
        // Matriks perbandingan berpasangan dari data bobot
        $matriksKriteria = [
            [1.0, 7.0, 1.0],
            [0.14, 1.0, 5.0],
            [1.00, 0.20, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganJaminan();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung Consistency Ratio (CR)
        // 1. Hitung λ max
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlah = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlah += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $matriksBobot[$i] = $jumlah;
            $lambdaMax += $jumlah / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // 2. Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // 3. Hitung Consistency Ratio (CR)
        // Random Index (RI) untuk n=3 adalah 0.58
        $RI = 0.58;
        $CR = $CI / $RI;
        
        // Matriks konsisten jika CR <= 0.1
        $konsisten = $CR <= 0.1;
        
        include 'views/templates/header.php';
        include 'views/perhitungan/jaminan.php';
        include 'views/templates/footer.php';
    }
    
    
    // Fungsi untuk mendapatkan bobot kriteria utama
    private function getBobotKriteria() {
        // Data kriteria
        $namaKriteria = ['C1', 'C2', 'C3', 'C4', 'C5'];
        $deskripsiKriteria = [
            'Kondisi Ekonomi',
            'Karakter',
            'Modal',
            'Kemampuan',
            'Jaminan'
        ];
        
        // Matriks perbandingan
        $matriksKriteria = [
            [1.0, 4.0, 1.0, 1.0, 1.0],
            [0.25, 1.0, 5.0, 1.0, 1.0],
            [1.00, 0.20, 1.0, 4.0, 1.0],
            [1.00, 1.00, 0.25, 1.0, 7.0],
            [1.00, 1.00, 1.00, 1.0, 1]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganKriteria();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria1_id'] - 1;
                $j = $data['kriteria2_id'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Kembalikan bobot dalam bentuk array asosiatif
        return [
            'C1' => $bobotPrioritas[0],
            'C2' => $bobotPrioritas[1],
            'C3' => $bobotPrioritas[2],
            'C4' => $bobotPrioritas[3],
            'C5' => $bobotPrioritas[4]
        ];
    }
    
    // Fungsi untuk mendapatkan bobot ekonomi
    private function getBobotEkonomi() {
        // Data kriteria ekonomi
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Penghasilan',
            'Pengeluaran',
            'Tanggungan'
        ];
        
        // Matriks perbandingan berpasangan
        $matriksKriteria = [
            [1.0, 3.0, 5.0],
            [0.33, 1.0, 1.0],
            [0.2, 1.0, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganEkonomi();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Kembalikan bobot dalam bentuk array asosiatif
        return [
            'C1' => $bobotPrioritas[0],
            'C2' => $bobotPrioritas[1],
            'C3' => $bobotPrioritas[2]
        ];
    }
    
    // Fungsi untuk mendapatkan bobot karakter
    private function getBobotKarakter() {
        // Data kriteria karakter
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Kepribadian',
            'Kejujuran',
            'Komitmen'
        ];
        
        // Matriks perbandingan berpasangan
        $matriksKriteria = [
            [1.0, 3.0, 1.0],
            [0.33, 1.0, 5.0],
            [1.0, 0.2, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganKarakter();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Kembalikan bobot dalam bentuk array asosiatif
        return [
            'C1' => $bobotPrioritas[0],
            'C2' => $bobotPrioritas[1],
            'C3' => $bobotPrioritas[2]
        ];
    }
    
    // Fungsi untuk mendapatkan bobot modal
    private function getBobotModal() {
        // Data kriteria modal
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Sumber Modal',
            'Besar Modal',
            'Penggunaan Modal'
        ];
        
        // Matriks perbandingan berpasangan
        $matriksKriteria = [
            [1.0, 3.0, 2.0],
            [0.33, 1.0, 1.0],
            [0.50, 1.00, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganModal();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Kembalikan bobot dalam bentuk array asosiatif
        return [
            'C1' => $bobotPrioritas[0],
            'C2' => $bobotPrioritas[1],
            'C3' => $bobotPrioritas[2]
        ];
    }
    
    // Fungsi untuk mendapatkan bobot kemampuan
    private function getBobotKemampuan() {
        // Data kriteria kemampuan
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Pendapatan',
            'Pengeluaran',
            'Sisa Pendapatan'
        ];
        
        // Matriks perbandingan berpasangan
        $matriksKriteria = [
            [1.0, 5.0, 1.0],
            [0.20, 1.0, 5.0],
            [1.00, 0.20, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganKemampuan();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Kembalikan bobot dalam bentuk array asosiatif
        return [
            'C1' => $bobotPrioritas[0],
            'C2' => $bobotPrioritas[1],
            'C3' => $bobotPrioritas[2]
        ];
    }
    
    // Fungsi untuk mendapatkan bobot jaminan
    private function getBobotJaminan() {
        // Data kriteria jaminan
        $namaKriteria = ['C1', 'C2', 'C3'];
        $deskripsiKriteria = [
            'Nilai Jaminan',
            'Status Kepemilikan',
            'Kelengkapan Dokumen'
        ];
        
        // Matriks perbandingan berpasangan
        $matriksKriteria = [
            [1.0, 7.0, 1.0],
            [0.14, 1.0, 5.0],
            [1.00, 0.20, 1.0]
        ];
        
        // Ambil data perbandingan dari database jika ada
        $perbandinganData = $this->bobot->getPerbandinganJaminan();
        if (!empty($perbandinganData)) {
            // Update matriks dengan data dari database
            foreach ($perbandinganData as $data) {
                $i = $data['kriteria_1'] - 1;
                $j = $data['kriteria_2'] - 1;
                $matriksKriteria[$i][$j] = floatval($data['nilai']);
                // Update nilai reciprocal
                if ($i != $j) {
                    $matriksKriteria[$j][$i] = 1 / floatval($data['nilai']);
                }
            }
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = [];
        for ($j = 0; $j < count($namaKriteria); $j++) {
            $jumlah = 0;
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlah += $matriksKriteria[$i][$j];
            }
            $jumlahKolom[$j] = $jumlah;
        }
        
        // Normalisasi matriks
        $matriksNormalisasi = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormalisasi[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormalisasi[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Kembalikan bobot dalam bentuk array asosiatif
        return [
            'C1' => $bobotPrioritas[0],
            'C2' => $bobotPrioritas[1],
            'C3' => $bobotPrioritas[2]
        ];
    }
    
    public function hitungPengajuan() {
        // Ambil data nasabah dengan pengajuan
        $data_nasabah = $this->perhitungan->getNasabahDenganPengajuan();
        
        // Ambil bobot dari masing-masing kriteria
        $bobotKriteria = $this->getBobotKriteria();
        $bobotEkonomi = $this->getBobotEkonomi();
        $bobotKarakter = $this->getBobotKarakter();
        $bobotModal = $this->getBobotModal();
        $bobotKemampuan = $this->getBobotKemampuan();
        $bobotJaminan = $this->getBobotJaminan();
        
        // Hitung dengan metode AHP menggunakan bobot yang diambil
        $hasil_perhitungan = $this->perhitungan->hitungAHPDenganBobot(
            $data_nasabah, 
            $bobotKriteria, 
            $bobotEkonomi, 
            $bobotKarakter, 
            $bobotModal, 
            $bobotKemampuan, 
            $bobotJaminan
        );
        
        // Simpan hasil perhitungan ke database
        foreach ($hasil_perhitungan as $hasil) {
            $data_simpan = [
                'nasabah_id' => $hasil['nasabah_id'],
                'pengajuan_id' => $hasil['pengajuan_id'],
                'nilai_bobot' => $hasil['nilai_bobot'],
                'peringkat' => $hasil['peringkat'],
                'status' => $hasil['status']
            ];
            $this->perhitungan->simpanHasilPerhitungan($data_simpan);
            
            // Update status pengajuan
            require_once __DIR__ . '/../models/Pengajuan.php';
            $pengajuan = new Pengajuan();
            $pengajuan->id = $hasil['pengajuan_id'];
            $pengajuan->status_pengajuan = $hasil['status'] == 'Diterima' ? 'Diterima' : 'Ditolak';
            $pengajuan->update_status();
        }
        
        // Redirect ke halaman hasil
        header('Location: /spk_ahp/perhitungan');
        exit;
    }
    
    public function exportPDF() {
        require_once __DIR__ . '/../models/PDF.php';
        
        // Ambil data hasil perhitungan
        $hasil = $this->perhitungan->getHasilPerhitungan();
        
        // Buat objek PDF
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage('L', 'A4');
        $pdf->SetFont('Arial', 'B', 12);
        
        // Judul
        $pdf->Cell(0, 10, 'HASIL PERHITUNGAN KELAYAKAN KREDIT DENGAN METODE AHP', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Header tabel
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Nama Nasabah', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Nilai Bobot', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Peringkat', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Status', 1, 1, 'C');
        
        // Isi tabel
        $pdf->SetFont('Arial', '', 10);
        $no = 1;
        foreach ($hasil as $row) {
            $pdf->Cell(10, 10, $no++, 1, 0, 'C');
            $pdf->Cell(60, 10, $row['nama_lengkap'], 1, 0, 'L');
            $pdf->Cell(40, 10, number_format($row['nilai_bobot'], 5), 1, 0, 'C');
            $pdf->Cell(30, 10, $row['peringkat'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['status'], 1, 1, 'C');
        }
        
        // Output PDF
        $pdf->Output('D', 'Hasil_Perhitungan_AHP.pdf');
        exit;
    }
}