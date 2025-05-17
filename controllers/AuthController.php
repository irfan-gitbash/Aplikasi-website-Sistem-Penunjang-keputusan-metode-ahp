<?php
require_once 'models/Auth.php';

class AuthController {
    private $auth;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->auth = new Auth();
    }

    public function login() {
        // Inisialisasi variabel untuk view
        $error = '';
        $username_valid = false;
        
        // Ambil pesan error dari session jika ada
        if (isset($_SESSION['login_error'])) {
            $error = $_SESSION['login_error'];
            unset($_SESSION['login_error']); // Hapus pesan setelah diambil
        }
        
        // Ambil status username dari session jika ada
        if (isset($_SESSION['username_valid'])) {
            $username_valid = $_SESSION['username_valid'];
            unset($_SESSION['username_valid']); // Hapus status setelah diambil
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Cek apakah username ada
            $username_exists = $this->auth->checkUsername($username);
            
            if ($username_exists) {
                // Simpan status username valid ke session
                $_SESSION['username_valid'] = true;
                
                $user = $this->auth->login($username, $password);
                if ($user && $user !== 'password_salah') {
                    // Simpan data user ke session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['last_login'] = date('Y-m-d H:i:s');
                    
                    // Tambahkan pesan selamat datang dengan waktu yang terupdate
                    $waktu_sekarang = date('H:i:s');
                    $tanggal_sekarang = date('d-m-Y');
                    
                    // Tentukan salam berdasarkan waktu
                    $jam = (int)date('H');
                    $salam = "";
                    if ($jam >= 5 && $jam < 12) {
                        $salam = "Selamat pagi";
                    } elseif ($jam >= 12 && $jam < 15) {
                        $salam = "Selamat siang";
                    } elseif ($jam >= 15 && $jam < 18) {
                        $salam = "Selamat sore";
                    } else {
                        $salam = "Selamat malam";
                    }
                    
                    $_SESSION['welcome_message'] = "$salam, {$user['nama_lengkap']}! Anda berhasil login pada $tanggal_sekarang pukul $waktu_sekarang";
                    
                    // Redirect ke dashboard dengan path absolut
                    header('Location: /spk_ahp/dashboard');
                    exit;
                } else {
                    // Simpan pesan error ke session
                    $_SESSION['login_error'] = "Password anda salah!";
                    header('Location: /spk_ahp/auth/login');
                    exit;
                }
            } else {
                // Simpan pesan error ke session
                $_SESSION['login_error'] = "Username atau password salah!";
                header('Location: /spk_ahp/auth/login');
                exit;
            }
        }
        
        // Tampilkan halaman login dengan error jika ada
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: login');
        exit;
    }
}
?>