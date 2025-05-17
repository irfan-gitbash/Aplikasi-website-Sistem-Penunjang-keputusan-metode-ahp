<?php
require_once 'vendor/fpdf/fpdf.php'; // Pastikan path ini benar

class PDF extends FPDF {
    protected $title = 'LAPORAN DATA PENGAJUAN';
    
    function SetTitle($title) {
        $this->title = $title;
    }
    
    function Header() {
        // Logo
        $this->Image('assets/img/logo_ptbpr.png', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, $this->title, 0, 0, 'C');
        // Line break
        $this->Ln(20);
    }
    
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
?>