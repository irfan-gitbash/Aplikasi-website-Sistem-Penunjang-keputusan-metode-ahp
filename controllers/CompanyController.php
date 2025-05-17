<?php

class CompanyController {
    
    public function profile() {
        // Tampilkan halaman profil perusahaan
        include 'views/templates/header.php';
        include 'views/company/profile.php';
        include 'views/templates/footer.php';
    }
}
?>