<?php
session_start();

// Autoload classes
spl_autoload_register(function($class) {
    if (file_exists('controllers/' . $class . '.php')) {
        require_once 'controllers/' . $class . '.php';
    } elseif (file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    }
});

// Simple router
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Check if user is logged in
if (!isset($_SESSION['user_id']) && $url[0] != 'auth') {
    header('Location: /spk_ahp/auth/login');
    exit;
}

// Route to appropriate controller
switch ($url[0]) {
    case 'auth':
        $controller = new AuthController();
        if (isset($url[1])) {
            switch ($url[1]) {
                case 'login':
                    $controller->login();
                    break;
                case 'logout':
                    $controller->logout();
                    break;
                default:
                    $controller->login();
                    break;
            }
        } else {
            $controller->login();
        }
        break;
    
    case 'nasabah':
        $controller = new NasabahController();
        if (isset($url[1])) {
            switch ($url[1]) {
                case 'add':
                    $controller->add();
                    break;
                case 'edit':
                    $controller->edit(isset($url[2]) ? $url[2] : null);
                    break;
                case 'delete':
                    $controller->delete(isset($url[2]) ? $url[2] : null);
                    break;
                default:
                    $controller->index();
                    break;
            }
        } else {
            $controller->index();
        }
        break;
    
    case 'pengajuan':
        $controller = new PengajuanController();
        if (isset($url[1])) {
            switch ($url[1]) {
                case 'add':
                    $controller->add();
                    break;
                case 'edit':
                    $controller->edit(isset($url[2]) ? $url[2] : null);
                    break;
                case 'delete':
                    $controller->delete(isset($url[2]) ? $url[2] : null);
                    break;
                case 'view':
                    $controller->view(isset($url[2]) ? $url[2] : null);
                    break;
                case 'export_pdf': // Tambahkan case ini
                    $controller->export_pdf();
                    break;
                case 'update_status': // Tambahkan case ini
                    $controller->update_status();
                    break;
                default:
                    $controller->index();
                    break;
            }
        } else {
            $controller->index();
        }
        break;
    
    case 'bobot':
        $controller = new BobotController();
        if (isset($url[1])) {
            switch ($url[1]) {
                case 'add':
                    $controller->add();
                    break;
                case 'edit':
                    $controller->edit(isset($url[2]) ? $url[2] : null);
                    break;
                case 'delete':
                    $controller->delete(isset($url[2]) ? $url[2] : null);
                    break;
                // Tambahkan case untuk kriteria dan subkriteria
                case 'kriteria':
                    $controller->kriteria();
                    break;
                case 'ekonomi':
                    $controller->ekonomi();
                    break;
                case 'karakter':
                    $controller->karakter();
                    break;
                case 'modal':
                    $controller->modal();
                    break;
                case 'kemampuan':
                    $controller->kemampuan();
                    break;
                case 'jaminan':
                    $controller->jaminan();
                    break;
                // Tambahkan case untuk save methods
                case 'save_kriteria':
                    $controller->save_kriteria();
                    break;
                case 'save_ekonomi':
                    $controller->save_ekonomi();
                    break;
                case 'save_karakter':
                    $controller->save_karakter();
                    break;
                case 'save_modal':
                    $controller->save_modal();
                    break;
                case 'save_kemampuan':
                    $controller->save_kemampuan();
                    break;
                case 'save_jaminan':
                    $controller->save_jaminan();
                    break;
                default:
                    $controller->index();
                    break;
            }
        } else {
            $controller->index();
        }
        break;
    
        case 'perhitungan':
            $controller = new PerhitunganController();
            if (isset($url[1])) {
                switch ($url[1]) {
                    case 'kriteria':
                        $controller->kriteria();
                        break;
                    case 'ekonomi':
                        $controller->ekonomi();
                        break;
                    case 'karakter':
                        $controller->karakter();
                        break;
                    case 'hitungPengajuan':
                        $controller->hitungPengajuan();
                        break;
                    case 'exportPDF':
                        $controller->exportPDF();
                        break;
                    default:
                        $controller->index();
                        break;
                }
            } else {
                $controller->index();
            }
            break;
        $controller = new PerhitunganController();
        if (isset($url[1])) {
            switch ($url[1]) {
                case 'calculate':
                    $controller->calculate(isset($url[2]) ? $url[2] : null);
                    break;
                case 'export':
                    $controller->exportPDF(isset($url[2]) ? $url[2] : null);
                    break;
                default:
                    $controller->index();
                    break;
            }
        } else {
            $controller->index();
        }
        break;
    
    case 'user':
        $controller = new UserController();
        if (isset($url[1])) {
            switch ($url[1]) {
                case 'add':
                    $controller->add();
                    break;
                case 'edit':
                    $controller->edit(isset($url[2]) ? $url[2] : null);
                    break;
                case 'delete':
                    $controller->delete(isset($url[2]) ? $url[2] : null);
                    break;
                default:
                    $controller->index();
                    break;
            }
        } else {
            $controller->index();
        }
        break;
    
    case 'home':
    default:
        // Di bagian route untuk dashboard
        if ($url[0] === 'dashboard' || $url[0] === 'home' || $url[0] === '') {
            // Hitung total nasabah
            require_once 'models/Nasabah.php';
            $nasabah_model = new Nasabah();
            $total_nasabah = $nasabah_model->countAll();
            $nasabah_terbaru = $nasabah_model->getLatest(5); // Ambil 5 nasabah terbaru
            
            // Hitung total pengajuan dan status
            require_once 'models/Pengajuan.php';
            $pengajuan_model = new Pengajuan();
            $total_pengajuan = $pengajuan_model->countAll();
            
            // Tangani kemungkinan error saat menghitung berdasarkan status
            try {
                $pengajuan_diterima = $pengajuan_model->countByStatus('Diterima');
                $pengajuan_ditolak = $pengajuan_model->countByStatus('Ditolak');
            } catch (Exception $e) {
                // Jika terjadi error, set nilai default
                $pengajuan_diterima = 0;
                $pengajuan_ditolak = 0;
            }
            
            $pengajuan_terbaru = $pengajuan_model->getLatest(5); // Ambil 5 pengajuan terbaru
            
            include 'views/templates/header.php';
            include 'views/templates/sidebar.php';
            include 'views/templates/dashboard.php';
            include 'views/templates/footer.php';
        }
        // Fix for company profile route
        else if ($url[0] === 'company' && isset($url[1]) && $url[1] === 'profile') {
            require_once 'controllers/CompanyController.php';
            $companyController = new CompanyController();
            $companyController->profile();
        }
        // Tambahkan route ini di bagian route pengajuan
        // Hapus kode berikut
        else if ($url[1] == 'pengajuan' && isset($url[2]) && $url[2] == 'update_status') {
            $controller->update_status();
        }
        else if ($url[1] == 'pengajuan' && isset($url[2]) && $url[2] == 'export_pdf') {
            $controller->export_pdf();
        }
        break;
}
?>
