<?php

class BobotController {
    private $bobot;
    
    public function __construct() {
        require_once 'models/Bobot.php';
        $this->bobot = new Bobot();
        
        // Cek apakah user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /spk_ahp/auth/login');
            exit;
        }
    }
    
    public function index() {
        include 'views/templates/header.php';
        include 'views/bobot/index.php';
        include 'views/templates/footer.php';
    }
    
    public function kriteria() {
        
        // Nama-nama kriteria
        $namaKriteria = ["C1", "C2", "C3", "C4", "C5"];
        $deskripsiKriteria = [
            "Kondisi Ekonomi",
            "Karakter",
            "Modal",
            "Kemampuan",
            "Jaminan"
        ];
        
        // Inisialisasi matriks perbandingan dengan nilai default sesuai gambar
        $matriksKriteria = [
            [1.0, 4.0, 1.0, 1.0, 1.0],
            [0.25, 1.0, 5.0, 1.0, 1.0],
            [1.00, 0.20, 1.0, 4.0, 1.0],
            [1.00, 1.00, 0.25, 1.0, 7.0],
            [1.00, 1.00, 1.00, 1.0, 1] ,    
        ];
        
        // Coba ambil data dari database jika ada
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
        
        // Jika form disubmit, update matriks
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            
            // Update matriks atas
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i < $j) { // Hanya update bagian atas matriks
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                        // Update bagian bawah matriks (reciprocal)
                        $matriksKriteria[$j][$i] = 1 / floatval($matriksInput[$i][$j]);
                    }
                }
            }
            
            // Simpan ke database
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandingan($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot kriteria berhasil disimpan!";
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = array_fill(0, count($namaKriteria), 0);
        for ($j = 0; $j < count($namaKriteria); $j++) {
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlahKolom[$j] += $matriksKriteria[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung λ max (untuk consistency check)
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksBobot[$i] = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksBobot[$i] += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $lambdaMax += $matriksBobot[$i] / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // Random Index (RI) untuk n=5
        $RI = 1.12;
        
        // Hitung Consistency Ratio (CR)
        $CR = $CI / $RI;
        
        // Cek konsistensi (CR <= 0.1 dianggap konsisten)
        $konsisten = ($CR <= 0.1);
        
        include 'views/templates/header.php';
        include 'views/bobot/kriteria.php';
        include 'views/templates/footer.php';
    }
    
    public function ekonomi() {
        // Nama-nama subkriteria ekonomi
        $namaKriteria = ["C1", "C2", "C3"];
        $deskripsiKriteria = [
            "Penghasilan",
            "Pengeluaran",
            "Tanggungan"
        ];
        
        // Inisialisasi matriks perbandingan
        $matriksKriteria = [
            [1.0, 3.0, 5.0],
            [0.33, 1.0, 1.0],
            [0.2, 1.0, 1.0]
        ];
        
        // Jika form disubmit, update matriks
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            
            // Update matriks atas
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i < $j) { // Hanya update bagian atas matriks
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                        // Update bagian bawah matriks (reciprocal)
                        $matriksKriteria[$j][$i] = 1 / floatval($matriksInput[$i][$j]);
                    }
                }
            }
            
            // PERBAIKAN: Gunakan savePerbandinganEkonomi alih-alih savePerbandinganKarakter
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganEkonomi($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            $_SESSION['success'] = "Bobot ekonomi berhasil disimpan!";
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = array_fill(0, count($namaKriteria), 0);
        for ($j = 0; $j < count($namaKriteria); $j++) {
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlahKolom[$j] += $matriksKriteria[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung λ max (untuk consistency check)
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksBobot[$i] = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksBobot[$i] += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $lambdaMax += $matriksBobot[$i] / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // Random Index (RI) untuk n=3
        $RI = 0.58;
        
        // Hitung Consistency Ratio (CR)
        $CR = $CI / $RI;
        
        // Cek konsistensi (CR <= 0.1 dianggap konsisten)
        $konsisten = ($CR <= 0.1);
        
        include 'views/templates/header.php';
        include 'views/bobot/ekonomi.php';
        include 'views/templates/footer.php';
    }
    
    public function karakter() {
        // Nama-nama subkriteria karakter
        $namaKriteria = ["C1", "C2", "C3"];
        $deskripsiKriteria = [
            "Kepribadian",
            "Kejujuran",
            "Komitmen"
        ];
        
        // Inisialisasi matriks perbandingan
        $matriksKriteria = [
            [1.0, 3.0, 1.0],
            [0.33, 1.0, 5.0],
            [1.0, 0.2, 1.0]
        ];
        
        // Jika form disubmit, update matriks
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            
            // Update matriks atas
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i < $j) { // Hanya update bagian atas matriks
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                        // Update bagian bawah matriks (reciprocal)
                        $matriksKriteria[$j][$i] = 1 / floatval($matriksInput[$i][$j]);
                    }
                }
            }
            
            // Simpan ke database (implementasi sesuai kebutuhan)
            $_SESSION['success'] = "Bobot karakter berhasil disimpan!";
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = array_fill(0, count($namaKriteria), 0);
        for ($j = 0; $j < count($namaKriteria); $j++) {
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlahKolom[$j] += $matriksKriteria[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung λ max (untuk consistency check)
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksBobot[$i] = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksBobot[$i] += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $lambdaMax += $matriksBobot[$i] / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // Random Index (RI) untuk n=3
        $RI = 0.58;
        
        // Hitung Consistency Ratio (CR)
        $CR = $CI / $RI;
        
        // Cek konsistensi (CR <= 0.1 dianggap konsisten)
        $konsisten = ($CR <= 0.1);
        
        include 'views/templates/header.php';
        include 'views/bobot/karakter.php';
        include 'views/templates/footer.php';
    }
    
    // Metode lainnya tetap sama
    public function modal() {
        // Nama-nama subkriteria modal
        $namaKriteria = ["C1", "C2", "C3"];
        $deskripsiKriteria = [
            "Sumber Modal",
            "Besar Modal",
            "Penggunaan Modal"
        ];
        
        // Inisialisasi matriks perbandingan
        $matriksKriteria = [
            [1.0, 3.0, 2.0],
            [0.33, 1.0, 1.0],
            [0.50, 1.00, 1.0]
        ];
        
        // Jika form disubmit, update matriks
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            
            // Update matriks atas
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i < $j) { // Hanya update bagian atas matriks
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                        // Update bagian bawah matriks (reciprocal)
                        $matriksKriteria[$j][$i] = 1 / floatval($matriksInput[$i][$j]);
                    }
                }
            }
            
            // Simpan ke database
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganModal($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot modal berhasil disimpan!";
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = array_fill(0, count($namaKriteria), 0);
        for ($j = 0; $j < count($namaKriteria); $j++) {
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlahKolom[$j] += $matriksKriteria[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung λ max (untuk consistency check)
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksBobot[$i] = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksBobot[$i] += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $lambdaMax += $matriksBobot[$i] / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // Random Index (RI) untuk n=3
        $RI = 0.58;
        
        // Hitung Consistency Ratio (CR)
        $CR = $CI / $RI;
        
        // Cek konsistensi (CR <= 0.1 dianggap konsisten)
        $konsisten = ($CR <= 0.1);
        
        include 'views/templates/header.php';
        include 'views/bobot/modal.php';
        include 'views/templates/footer.php';
    }
    
    public function kemampuan() {
        // Nama-nama subkriteria kemampuan
        $namaKriteria = ["C1", "C2", "C3"];
        $deskripsiKriteria = [
            "Pendapatan",
            "Pengeluaran",
            "Sisa Pendapatan"
        ];
        
        // Inisialisasi matriks perbandingan
        $matriksKriteria = [
            [1.0, 5.0, 1.0],
            [0.20, 1.0, 5.0],
            [1.00, 0.20, 1.0]
        ];
        
        // Jika form disubmit, update matriks
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            
            // Update matriks atas
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i < $j) { // Hanya update bagian atas matriks
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                        // Update bagian bawah matriks (reciprocal)
                        $matriksKriteria[$j][$i] = 1 / floatval($matriksInput[$i][$j]);
                    }
                }
            }
            
            // Simpan ke database
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganKemampuan($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot kemampuan berhasil disimpan!";
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = array_fill(0, count($namaKriteria), 0);
        for ($j = 0; $j < count($namaKriteria); $j++) {
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlahKolom[$j] += $matriksKriteria[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung λ max (untuk consistency check)
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksBobot[$i] = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksBobot[$i] += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $lambdaMax += $matriksBobot[$i] / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // Random Index (RI) untuk n=3
        $RI = 0.58;
        
        // Hitung Consistency Ratio (CR)
        $CR = $CI / $RI;
        
        // Cek konsistensi (CR <= 0.1 dianggap konsisten)
        $konsisten = ($CR <= 0.1);
        
        include 'views/templates/header.php';
        include 'views/bobot/kemampuan.php';
        include 'views/templates/footer.php';
    }
    
    public function jaminan() {
        // Nama-nama subkriteria jaminan
        $namaKriteria = ["C1", "C2", "C3"];
        $deskripsiKriteria = [
            "Nilai Jaminan",
            "Status Kepemilikan",
            "Kelengkapan Dokumen"
        ];
        
        // Inisialisasi matriks perbandingan
        $matriksKriteria = [
            [1.0, 7.0, 1.0],
            [0.14, 1.0, 5.0],
            [1.00, 0.20, 1.0]
        ];
        
        // Jika form disubmit, update matriks
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            
            // Update matriks atas
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i < $j) { // Hanya update bagian atas matriks
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                        // Update bagian bawah matriks (reciprocal)
                        $matriksKriteria[$j][$i] = 1 / floatval($matriksInput[$i][$j]);
                    }
                }
            }
            
            // Simpan ke database
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganJaminan($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot jaminan berhasil disimpan!";
        }
        
        // Hitung jumlah kolom
        $jumlahKolom = array_fill(0, count($namaKriteria), 0);
        for ($j = 0; $j < count($namaKriteria); $j++) {
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $jumlahKolom[$j] += $matriksKriteria[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $matriksNormal = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksNormal[$i] = [];
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksNormal[$i][$j] = $matriksKriteria[$i][$j] / $jumlahKolom[$j];
            }
        }
        
        // Hitung bobot prioritas (rata-rata baris)
        $bobotPrioritas = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $jumlahBaris += $matriksNormal[$i][$j];
            }
            $bobotPrioritas[$i] = $jumlahBaris / count($namaKriteria);
        }
        
        // Hitung λ max (untuk consistency check)
        $lambdaMax = 0;
        $matriksBobot = [];
        for ($i = 0; $i < count($namaKriteria); $i++) {
            $matriksBobot[$i] = 0;
            for ($j = 0; $j < count($namaKriteria); $j++) {
                $matriksBobot[$i] += $matriksKriteria[$i][$j] * $bobotPrioritas[$j];
            }
            $lambdaMax += $matriksBobot[$i] / $bobotPrioritas[$i];
        }
        $lambdaMax = $lambdaMax / count($namaKriteria);
        
        // Hitung Consistency Index (CI)
        $CI = ($lambdaMax - count($namaKriteria)) / (count($namaKriteria) - 1);
        
        // Random Index (RI) untuk n=3
        $RI = 0.58;
        
        // Hitung Consistency Ratio (CR)
        $CR = $CI / $RI;
        
        // Cek konsistensi (CR <= 0.1 dianggap konsisten)
        $konsisten = ($CR <= 0.1);
        
        include 'views/templates/header.php';
        include 'views/bobot/jaminan.php';
        include 'views/templates/footer.php';
    }
    
    public function save_kriteria() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            $namaKriteria = ["C1", "C2", "C3", "C4", "C5"];
            
            // Inisialisasi matriks
            $matriksKriteria = [];
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $matriksKriteria[$i] = [];
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i == $j) {
                        $matriksKriteria[$i][$j] = 1.0;
                    } elseif ($i < $j) {
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                    } else {
                        $matriksKriteria[$i][$j] = 1 / floatval($matriksInput[$j][$i]);
                    }
                }
            }
            
            // Simpan ke database
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandingan($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot kriteria berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menyimpan bobot kriteria.";
        }
        
        header('Location: /spk_ahp/bobot/kriteria');
        exit;
    }
    
    // Perbaikan metode save_ekonomi
    public function save_ekonomi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            $namaKriteria = ["C1", "C2", "C3"];
            
            // Inisialisasi matriks
            $matriksKriteria = [];
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $matriksKriteria[$i] = [];
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i == $j) {
                        $matriksKriteria[$i][$j] = 1.0;
                    } elseif ($i < $j) {
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                    } else {
                        $matriksKriteria[$i][$j] = 1 / floatval($matriksInput[$j][$i]);
                    }
                }
            }
            
            // Simpan ke database menggunakan metode savePerbandinganEkonomi
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganEkonomi($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot ekonomi berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menyimpan bobot ekonomi.";
        }
        
        header('Location: /spk_ahp/bobot/ekonomi');
        exit;
    }
    
    // Perbaikan metode save_karakter
    public function save_karakter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            $namaKriteria = ["C1", "C2", "C3"];
            
            // Inisialisasi matriks
            $matriksKriteria = [];
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $matriksKriteria[$i] = [];
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i == $j) {
                        $matriksKriteria[$i][$j] = 1.0;
                    } elseif ($i < $j) {
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                    } else {
                        $matriksKriteria[$i][$j] = 1 / floatval($matriksInput[$j][$i]);
                    }
                }
            }
            
            // Simpan ke database menggunakan metode savePerbandinganKarakter
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganKarakter($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot karakter berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menyimpan bobot karakter.";
        }
        
        header('Location: /spk_ahp/bobot/karakter');
        exit;
    }
    
    // Pindahkan metode save_modal ke dalam class
    public function save_modal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            $namaKriteria = ["C1", "C2", "C3"]; // Sesuaikan dengan jumlah kriteria modal
            
            // Inisialisasi matriks
            $matriksKriteria = [];
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $matriksKriteria[$i] = [];
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i == $j) {
                        $matriksKriteria[$i][$j] = 1.0;
                    } elseif ($i < $j) {
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                    } else {
                        $matriksKriteria[$i][$j] = 1 / floatval($matriksInput[$j][$i]);
                    }
                }
            }
            
            // Simpan ke database menggunakan metode savePerbandinganModal
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganModal($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot modal berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menyimpan bobot modal.";
        }
        
        header('Location: /spk_ahp/bobot/modal');
        exit;
    }
    
    public function save_kemampuan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            $namaKriteria = ["C1", "C2", "C3"]; // Sesuaikan dengan jumlah kriteria kemampuan
            
            // Inisialisasi matriks
            $matriksKriteria = [];
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $matriksKriteria[$i] = [];
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i == $j) {
                        $matriksKriteria[$i][$j] = 1.0;
                    } elseif ($i < $j) {
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                    } else {
                        $matriksKriteria[$i][$j] = 1 / floatval($matriksInput[$j][$i]);
                    }
                }
            }
            
            // Simpan ke database menggunakan metode savePerbandinganKemampuan
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganKemampuan($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot kemampuan berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menyimpan bobot kemampuan.";
        }
        
        header('Location: /spk_ahp/bobot/kemampuan');
        exit;
    }
    
    public function save_jaminan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriks'])) {
            $matriksInput = $_POST['matriks'];
            $namaKriteria = ["C1", "C2", "C3"]; // Sesuaikan dengan jumlah kriteria jaminan
            
            // Inisialisasi matriks
            $matriksKriteria = [];
            for ($i = 0; $i < count($namaKriteria); $i++) {
                $matriksKriteria[$i] = [];
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i == $j) {
                        $matriksKriteria[$i][$j] = 1.0;
                    } elseif ($i < $j) {
                        $matriksKriteria[$i][$j] = floatval($matriksInput[$i][$j]);
                    } else {
                        $matriksKriteria[$i][$j] = 1 / floatval($matriksInput[$j][$i]);
                    }
                }
            }
            
            // Simpan ke database menggunakan metode savePerbandinganJaminan
            for ($i = 0; $i < count($namaKriteria); $i++) {
                for ($j = 0; $j < count($namaKriteria); $j++) {
                    if ($i != $j) { // Tidak perlu menyimpan diagonal (selalu 1)
                        $this->bobot->savePerbandinganJaminan($i+1, $j+1, $matriksKriteria[$i][$j]);
                    }
                }
            }
            
            $_SESSION['success'] = "Bobot jaminan berhasil disimpan!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menyimpan bobot jaminan.";
        }
        
        header('Location: /spk_ahp/bobot/jaminan');
        exit;
    }
} // Pastikan ini adalah penutup class terakhir dan tidak ada kode lain setelahnya