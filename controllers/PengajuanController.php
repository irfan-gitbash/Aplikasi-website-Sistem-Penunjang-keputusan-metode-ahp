<?php
require_once 'models/Pengajuan.php';
require_once 'models/Nasabah.php';

class PengajuanController {
    private $pengajuan;
    private $nasabah;

    public function __construct() {
        $this->pengajuan = new Pengajuan();
        $this->nasabah = new Nasabah();
    }

    public function index() {
        $pengajuan = $this->pengajuan->getAll();
        
        include 'views/templates/header.php';
        include 'views/templates/sidebar.php';
        include 'views/pengajuan/index.php';
        include 'views/templates/footer.php';
    }

    public function add() {
        $nasabah = $this->nasabah->getAll();
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nasabah_id = $_POST['nasabah_id'] ?? '';
            $tanggal_pengajuan = $_POST['tanggal_pengajuan'] ?? date('Y-m-d');
            $kondisi_ekonomi = $_POST['kondisi_ekonomi'] ?? '';
            $karakter = $_POST['karakter'] ?? '';
            $modal = $_POST['modal'] ?? '';
            $kemampuan = $_POST['kemampuan'] ?? '';
            $jumlah_pinjaman = $_POST['jumlah_pinjaman'] ?? '';
            $jangka_waktu = $_POST['jangka_waktu'] ?? '';
            $jenis_usaha = $_POST['jenis_usaha'] ?? '';
            $lama_usaha = $_POST['lama_usaha'] ?? '';
            $tujuan_pinjaman = $_POST['tujuan_pinjaman'] ?? '';
            
            if (empty($nasabah_id) || empty($jumlah_pinjaman) || empty($jangka_waktu)) {
                $error = 'Nasabah, jumlah pinjaman, dan jangka waktu harus diisi!';
            } else {
                $this->pengajuan->nasabah_id = $nasabah_id;
                $this->pengajuan->tanggal_pengajuan = $tanggal_pengajuan;
                $this->pengajuan->kondisi_ekonomi = $kondisi_ekonomi;
                $this->pengajuan->karakter = $karakter;
                $this->pengajuan->modal = $modal;
                $this->pengajuan->kemampuan = $kemampuan;
                $this->pengajuan->jumlah_pinjaman = $jumlah_pinjaman;
                $this->pengajuan->jangka_waktu = $jangka_waktu;
                $this->pengajuan->jenis_usaha = $jenis_usaha;
                $this->pengajuan->lama_usaha = $lama_usaha;
                $this->pengajuan->tujuan_pinjaman = $tujuan_pinjaman;
                
                // Set created_by dari session
                if (isset($_SESSION['user_id'])) {
                    $this->pengajuan->created_by = $_SESSION['user_id'];
                } else {
                    $this->pengajuan->created_by = 1; // Default ke admin atau user ID 1
                }
                
                $result = $this->pengajuan->create();
                if ($result) {
                    $_SESSION['success'] = 'Pengajuan berhasil ditambahkan!';
                    header('Location: /spk_ahp/pengajuan');
                    exit;
                } else {
                    $error = 'Gagal menambahkan pengajuan!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/templates/sidebar.php';
        include 'views/pengajuan/form.php';
        include 'views/templates/footer.php';
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: /spk_ahp/pengajuan');
            exit;
        }
        
        $pengajuanData = $this->pengajuan->getById($id);
        if (!$pengajuanData) {
            header('Location: /spk_ahp/pengajuan');
            exit;
        }
        
        $nasabah = $this->nasabah->getAll();
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nasabah_id = $_POST['nasabah_id'] ?? '';
            $tanggal_pengajuan = $_POST['tanggal_pengajuan'] ?? date('Y-m-d');
            $kondisi_ekonomi = $_POST['kondisi_ekonomi'] ?? '';
            $karakter = $_POST['karakter'] ?? '';
            $modal = $_POST['modal'] ?? '';
            $kemampuan = $_POST['kemampuan'] ?? '';
            $jumlah_pinjaman = $_POST['jumlah_pinjaman'] ?? '';
            $jangka_waktu = $_POST['jangka_waktu'] ?? '';
            $jenis_usaha = $_POST['jenis_usaha'] ?? '';
            $lama_usaha = $_POST['lama_usaha'] ?? '';
            $tujuan_pinjaman = $_POST['tujuan_pinjaman'] ?? '';
            
            if (empty($nasabah_id) || empty($jumlah_pinjaman) || empty($jangka_waktu)) {
                $error = 'Nasabah, jumlah pinjaman, dan jangka waktu harus diisi!';
            } else {
                $this->pengajuan->id = $id;
                $this->pengajuan->nasabah_id = $nasabah_id;
                $this->pengajuan->tanggal_pengajuan = $tanggal_pengajuan;
                $this->pengajuan->kondisi_ekonomi = $kondisi_ekonomi;
                $this->pengajuan->karakter = $karakter;
                $this->pengajuan->modal = $modal;
                $this->pengajuan->kemampuan = $kemampuan;
                $this->pengajuan->jumlah_pinjaman = $jumlah_pinjaman;
                
                // Validasi status_pengajuan
                $status_pengajuan = $_POST['status_pengajuan'] ?? 'Diajukan';
                $valid_statuses = ['Diajukan', 'Diproses', 'Diterima', 'Ditolak'];
                if (!in_array($status_pengajuan, $valid_statuses)) {
                    $status_pengajuan = 'Diajukan'; // Default jika tidak valid
                }
                
                $this->pengajuan->status_pengajuan = $status_pengajuan;
                
                $result = $this->pengajuan->update();
                if ($result) {
                    $success = 'Pengajuan berhasil diupdate!';
                    $pengajuanData = $this->pengajuan->getById($id); // Refresh data
                } else {
                    $error = 'Gagal mengupdate pengajuan!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/templates/sidebar.php';
        include 'views/pengajuan/form.php';
        include 'views/templates/footer.php';
    }

    public function delete($id = null) {
        if (!$id) {
            header('Location: /spk_ahp/pengajuan');
            exit;
        }
        
        $this->pengajuan->id = $id;
        $result = $this->pengajuan->delete();
        
        if ($result) {
            $_SESSION['success'] = 'Pengajuan berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengajuan!';
        }
        
        header('Location: /spk_ahp/pengajuan');
        exit;
    }

    public function view($id) {
        // Ambil data pengajuan berdasarkan ID
        $pengajuanData = $this->pengajuan->getById($id);
        
        // Jika data tidak ditemukan, redirect ke halaman pengajuan
        if (!$pengajuanData) {
            $_SESSION['error'] = 'Data pengajuan tidak ditemukan!';
            header('Location: /spk_ahp/pengajuan');
            exit;
        }
        
        include 'views/templates/header.php';
        include 'views/templates/sidebar.php';
        include 'views/pengajuan/view.php';
        include 'views/templates/footer.php';
    }

    public function update_status() {
        // Pastikan request adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
        
        // Ambil data dari POST
        $id = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';
        
        // Debug: Log data yang diterima
        error_log("Received update_status request: ID=$id, Status=$status");
        
        // Validasi input
        if (empty($id) || empty($status)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID dan status harus diisi']);
            exit;
        }
        
        // Validasi status
        $valid_statuses = ['Diajukan', 'Diproses', 'Diterima', 'Ditolak'];
        if (!in_array($status, $valid_statuses)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Status tidak valid']);
            exit;
        }
        
        // Update status di database
        $this->pengajuan->id = $id;
        $this->pengajuan->status_pengajuan = $status;
        
        $result = $this->pengajuan->update_status();
        
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Gagal mengupdate status']);
        }
        exit;
    }

    public function export_pdf() {
        // Ambil semua data pengajuan
        $pengajuan = $this->pengajuan->getAll();
        
        // Load class PDF
        require_once 'models/PDF.php';
        
        // Instansiasi dan set properti dokumen
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        
        // Header tabel
        $pdf->Cell(10, 10, 'No', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Nama Nasabah', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Karakter', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Modal', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Kemampuan', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Status', 1, 1, 'C');
        
        // Isi tabel
        $pdf->SetFont('Arial', '', 10);
        $no = 1;
        while ($row = $pengajuan->fetch(PDO::FETCH_ASSOC)) {
            $pdf->Cell(10, 10, $no++, 1, 0, 'C');
            $pdf->Cell(40, 10, $row['nama_nasabah'], 1, 0, 'L');
            $pdf->Cell(30, 10, $row['karakter'], 1, 0, 'L');
            $pdf->Cell(30, 10, $row['modal'], 1, 0, 'L');
            $pdf->Cell(30, 10, $row['kemampuan'], 1, 0, 'L');
            $pdf->Cell(30, 10, $row['status_pengajuan'] ?? 'Diajukan', 1, 1, 'L');
        }
        
        // Output PDF
        $pdf->Output('D', 'Laporan_Pengajuan_' . date('Y-m-d') . '.pdf');
        exit;
    }
}
?>