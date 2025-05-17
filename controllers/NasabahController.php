<?php
require_once 'models/Nasabah.php';

class NasabahController {
    private $nasabah;

    public function __construct() {
        $this->nasabah = new Nasabah();
    }

    public function index() {
        $nasabah = $this->nasabah->getAll();
        
        include 'views/templates/header.php';
        include 'views/templates/sidebar.php';
        include 'views/nasabah/index.php';
        include 'views/templates/footer.php';
    }

    public function add() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode_nasabah = $_POST['kode_nasabah'] ?? '';
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $nik = $_POST['nik'] ?? '';
            $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
            $tempat_lahir = $_POST['tempat_lahir'] ?? '';
            $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
            $alamat = $_POST['alamat'] ?? '';
            $no_telepon = $_POST['no_telepon'] ?? '';
            $pekerjaan = $_POST['pekerjaan'] ?? '';
            
            // Handle penghasilan_bulanan dengan benar
            $penghasilan_bulanan = $_POST['penghasilan_bulanan'] ?? '';
            // Hapus pemisah ribuan jika ada
            $penghasilan_bulanan = str_replace('.', '', $penghasilan_bulanan);
            // Pastikan nilai adalah numerik dan tidak kosong
            $penghasilan_bulanan = !empty($penghasilan_bulanan) && is_numeric($penghasilan_bulanan) ? $penghasilan_bulanan : 0;
            
            // Tambahkan debugging jika diperlukan
            // echo "Nilai penghasilan: " . $penghasilan_bulanan; die();
            
            $jumlah_tanggungan = $_POST['jumlah_tanggungan'] ?? 0;
            $status_pernikahan = $_POST['status_pernikahan'] ?? '';
            
            if (empty($nama_lengkap) || empty($kode_nasabah)) {
                $error = 'Kode nasabah dan nama lengkap harus diisi!';
            } else {
                $this->nasabah->kode_nasabah = $kode_nasabah;
                $this->nasabah->nama_lengkap = $nama_lengkap;
                $this->nasabah->nik = $nik;
                $this->nasabah->jenis_kelamin = $jenis_kelamin;
                $this->nasabah->tempat_lahir = $tempat_lahir;
                $this->nasabah->tanggal_lahir = $tanggal_lahir;
                $this->nasabah->alamat = $alamat;
                $this->nasabah->no_telepon = $no_telepon;
                $this->nasabah->pekerjaan = $pekerjaan;
                $this->nasabah->penghasilan_bulanan = $penghasilan_bulanan;
                $this->nasabah->jumlah_tanggungan = $jumlah_tanggungan;
                $this->nasabah->status_pernikahan = $status_pernikahan;
                
                $result = $this->nasabah->create();
                if ($result) {
                    // Simpan pesan sukses ke session dan redirect ke halaman nasabah
                    $_SESSION['success'] = 'Nasabah berhasil ditambahkan!';
                    header('Location: /spk_ahp/nasabah');
                    exit;
                } else {
                    $error = 'Gagal menambahkan nasabah!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/templates/sidebar.php';
        include 'views/nasabah/form.php';
        include 'views/templates/footer.php';
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: ../nasabah');
            exit;
        }
        
        $nasabahData = $this->nasabah->getById($id);
        if (!$nasabahData) {
            header('Location: ../nasabah');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form dengan nama field yang benar
            $kode_nasabah = $_POST['kode_nasabah'] ?? '';
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $nik = $_POST['nik'] ?? '';
            $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
            $tempat_lahir = $_POST['tempat_lahir'] ?? '';
            $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
            $alamat = $_POST['alamat'] ?? '';
            $no_telepon = $_POST['no_telepon'] ?? '';
            $pekerjaan = $_POST['pekerjaan'] ?? '';
            
            // Handle penghasilan_bulanan dengan benar
            $penghasilan_bulanan = $_POST['penghasilan_bulanan'] ?? '';
            // Hapus pemisah ribuan jika ada
            $penghasilan_bulanan = str_replace('.', '', $penghasilan_bulanan);
            // Pastikan nilai adalah numerik dan tidak kosong
            $penghasilan_bulanan = !empty($penghasilan_bulanan) && is_numeric($penghasilan_bulanan) ? $penghasilan_bulanan : 0;
            
            $jumlah_tanggungan = $_POST['jumlah_tanggungan'] ?? 0;
            $status_pernikahan = $_POST['status_pernikahan'] ?? '';
            
            if (empty($nama_lengkap) || empty($kode_nasabah)) {
                $error = 'Kode nasabah dan nama lengkap harus diisi!';
            } else {
                $this->nasabah->id = $id; // Penting! Set ID untuk update
                $this->nasabah->kode_nasabah = $kode_nasabah;
                $this->nasabah->nama_lengkap = $nama_lengkap;
                $this->nasabah->nik = $nik;
                $this->nasabah->jenis_kelamin = $jenis_kelamin;
                $this->nasabah->tempat_lahir = $tempat_lahir;
                $this->nasabah->tanggal_lahir = $tanggal_lahir;
                $this->nasabah->alamat = $alamat;
                $this->nasabah->no_telepon = $no_telepon;
                $this->nasabah->pekerjaan = $pekerjaan;
                $this->nasabah->penghasilan_bulanan = $penghasilan_bulanan;
                $this->nasabah->jumlah_tanggungan = $jumlah_tanggungan;
                $this->nasabah->status_pernikahan = $status_pernikahan;
                
                // Panggil method update() bukan create()
                $result = $this->nasabah->update();
                if ($result) {
                    $success = 'Nasabah berhasil diupdate!';
                    $nasabahData = $this->nasabah->getById($id); // Refresh data
                } else {
                    $error = 'Gagal mengupdate nasabah!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/templates/sidebar.php';
        include 'views/nasabah/form.php';
        include 'views/templates/footer.php';
    }

    public function delete($id = null) {
        if (!$id) {
            header('Location: ../nasabah');
            exit;
        }
        
        $this->nasabah->id = $id;
        $result = $this->nasabah->delete();
        
        if ($result) {
            $_SESSION['success'] = 'Nasabah berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus nasabah!';
        }
        
        header('Location: ../nasabah');
        exit;
    }
    
    public function export_pdf() {
        // Ambil semua data nasabah
        $nasabah = $this->nasabah->getAll();
        
        // Load class PDF
        require_once 'models/PDF.php';
        
        // Instansiasi dan set properti dokumen
        $pdf = new PDF();
        // Ubah judul laporan untuk nasabah
        $pdf->SetTitle('LAPORAN DATA NASABAH');
        $pdf->AliasNbPages();
        $pdf->AddPage('L'); // Landscape orientation untuk data yang lebih lebar
        $pdf->SetFont('Arial', 'B', 10);
        
        // Header tabel
        $pdf->Cell(10, 10, 'No', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Kode Nasabah', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Nama Lengkap', 1, 0, 'C');
        $pdf->Cell(30, 10, 'NIK', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Jenis Kelamin', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Tempat Lahir', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Tanggal Lahir', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Alamat', 1, 0, 'C');
        $pdf->Cell(30, 10, 'No. Telepon', 1, 1, 'C');
        
        // Isi tabel
        $pdf->SetFont('Arial', '', 9);
        $no = 1;
        while ($row = $nasabah->fetch(PDO::FETCH_ASSOC)) {
            $pdf->Cell(10, 10, $no++, 1, 0, 'C');
            $pdf->Cell(30, 10, $row['kode_nasabah'], 1, 0, 'L');
            $pdf->Cell(40, 10, $row['nama_lengkap'], 1, 0, 'L');
            $pdf->Cell(30, 10, $row['nik'], 1, 0, 'L');
            $pdf->Cell(25, 10, $row['jenis_kelamin'], 1, 0, 'L');
            $pdf->Cell(30, 10, $row['tempat_lahir'], 1, 0, 'L');
            $pdf->Cell(25, 10, date('d-m-Y', strtotime($row['tanggal_lahir'])), 1, 0, 'L');
            $pdf->Cell(40, 10, $row['alamat'], 1, 0, 'L');
            $pdf->Cell(30, 10, $row['no_telepon'], 1, 1, 'L');
        }
        
        // Output PDF
        $pdf->Output('D', 'Laporan_Nasabah_' . date('Y-m-d') . '.pdf');
        exit;
    }
}
?>
