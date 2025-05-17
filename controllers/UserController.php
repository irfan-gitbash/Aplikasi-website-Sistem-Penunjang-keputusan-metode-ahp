<?php
require_once 'models/User.php';

class UserController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function index() {
        // Cek apakah user memiliki akses
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: dashboard');
            exit;
        }
        
        $users = $this->user->getAll();
        $roles = $this->user->getAllRoles();
        
        include 'views/templates/header.php';
        include 'views/user/index.php';
        include 'views/templates/footer.php';
    }

    public function add() {
        // Cek apakah user memiliki akses
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: dashboard');
            exit;
        }
        
        $roles = $this->user->getAllRoles();
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $role = $_POST['role'] ?? '';
            $email = $_POST['email'] ?? '';
            // $phone = $_POST['phone'] ?? ''; // Hapus atau komentari baris ini
            $status = $_POST['status'] ?? '1';
            
            if (empty($username) || empty($password) || empty($nama_lengkap) || empty($role)) {
                $error = 'Semua field harus diisi!';
            } elseif ($password !== $confirm_password) {
                $error = 'Password dan konfirmasi password tidak sama!';
            } else {
                $this->user->username = $username;
                $this->user->password = $password;
                $this->user->nama_lengkap = $nama_lengkap;
                $this->user->role = $role;
                $this->user->email = $email;
                // $this->user->phone = $phone; // Hapus atau komentari baris ini
                $this->user->status = $status;
                
                $result = $this->user->create();
                if ($result) {
                    $success = 'User berhasil ditambahkan!';
                } else {
                    $error = 'Gagal menambahkan user!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/user/form.php';
        include 'views/templates/footer.php';
    }

    public function edit($id = null) {
        // Cek apakah user memiliki akses
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: dashboard');
            exit;
        }
        
        if (!$id) {
            header('Location: ../user');
            exit;
        }
        
        $userData = $this->user->getById($id);
        if (!$userData) {
            header('Location: ../user');
            exit;
        }
        
        $roles = $this->user->getAllRoles();
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $role = $_POST['role'] ?? '';
            $email = $_POST['email'] ?? '';
            // $phone = $_POST['phone'] ?? ''; // Hapus atau komentari baris ini
            $status = $_POST['status'] ?? '1';
            
            if (empty($username) || empty($nama_lengkap) || empty($role)) {
                $error = 'Username, nama, dan role harus diisi!';
            } else {
                $this->user->id = $id;
                $this->user->username = $username;
                $this->user->nama_lengkap = $nama_lengkap;
                $this->user->role = $role;
                $this->user->email = $email;
                // $this->user->phone = $phone; // Hapus atau komentari baris ini
                $this->user->status = $status;
                
                $result = $this->user->update();
                if ($result) {
                    $success = 'User berhasil diupdate!';
                    $userData = $this->user->getById($id); // Refresh data
                } else {
                    $error = 'Gagal mengupdate user!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/user/form.php';
        include 'views/templates/footer.php';
    }

    // Modifikasi method changePassword 
    public function changePassword($id = null) { 
        // Jika tidak ada ID, gunakan ID user yang sedang login 
        if (!$id && isset($_SESSION['user_id'])) { 
            $id = $_SESSION['user_id']; 
        } 
        
        // Cek apakah user memiliki akses 
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
            // Jika bukan admin, hanya bisa ganti password sendiri 
            if ($_SESSION['user_id'] != $id) { 
                header('Location: /spk_ahp/dashboard'); 
                exit; 
            } 
        } 
        
        if (!$id) { 
            header('Location: /spk_ahp/user'); 
            exit; 
        } 
        
        $userData = $this->user->getById($id); 
        if (!$userData) { 
            header('Location: /spk_ahp/user'); 
            exit; 
        } 
        
        $error = ''; 
        $success = ''; 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            $current_password = $_POST['current_password'] ?? ''; 
            $password = $_POST['password'] ?? ''; 
            $confirm_password = $_POST['confirm_password'] ?? ''; 
            
            // Validasi kompleks untuk password 
            $password_errors = []; 
            
            // Jika bukan admin, verifikasi password saat ini 
            if ($_SESSION['role'] != 'admin' || $_SESSION['user_id'] == $id) { 
                if (empty($current_password)) { 
                    $password_errors[] = 'Password saat ini harus diisi!'; 
                } elseif (!$this->user->verifyPassword($id, $current_password)) { 
                    $password_errors[] = 'Password saat ini salah!'; 
                } 
            } 
            
            if (empty($password)) { 
                $password_errors[] = 'Password baru harus diisi!'; 
            } elseif (strlen($password) < 8) { 
                $password_errors[] = 'Password minimal 8 karakter!'; 
            } elseif (!preg_match('/[A-Z]/', $password)) { 
                $password_errors[] = 'Password harus mengandung minimal 1 huruf besar!'; 
            } elseif (!preg_match('/[a-z]/', $password)) { 
                $password_errors[] = 'Password harus mengandung minimal 1 huruf kecil!'; 
            } elseif (!preg_match('/[0-9]/', $password)) { 
                $password_errors[] = 'Password harus mengandung minimal 1 angka!'; 
            } elseif (!preg_match('/[!@#$%^&*]/', $password)) { 
                $password_errors[] = 'Password harus mengandung minimal 1 karakter khusus (!@#$%^&*)!'; 
            } 
            
            if ($password !== $confirm_password) { 
                $password_errors[] = 'Password dan konfirmasi password tidak sama!'; 
            } 
            
            if (!empty($password_errors)) { 
                $error = implode('<br>', $password_errors); 
            } else { 
                $this->user->id = $id; 
                $this->user->password = $password; 
                
                $result = $this->user->updatePassword(); 
                if ($result) { 
                    $success = 'Password berhasil diubah!'; 
                    
                    // Jika user mengganti password sendiri, tambahkan log aktivitas 
                    if ($_SESSION['user_id'] == $id) { 
                        $this->user->logActivity($id, 'change_password', 'User mengubah password'); 
                        
                        // Jika fitur email notifikasi aktif, kirim email
                        if (!empty($userData['email'])) {
                            // Implementasi pengiriman email notifikasi perubahan password
                            // sendEmail($userData['email'], 'Perubahan Password', 'Password akun Anda telah diubah.');
                        }
                    }
                } else { 
                    $error = 'Gagal mengubah password!'; 
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/user/change_password.php';
        include 'views/templates/footer.php';
    }

    public function delete($id = null) {
        // Cek apakah user memiliki akses
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: dashboard');
            exit;
        }
        
        if (!$id) {
            header('Location: ../user');
            exit;
        }
        
        // Tidak bisa menghapus diri sendiri
        if ($_SESSION['user_id'] == $id) {
            $_SESSION['error'] = 'Anda tidak dapat menghapus akun yang sedang digunakan!';
            header('Location: ../user');
            exit;
        }
        
        $this->user->id = $id;
        $result = $this->user->delete();
        
        if ($result) {
            $_SESSION['success'] = 'User berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus user!';
        }
        
        header('Location: ../user');
        exit;
    }

    public function profile() {
        // Ambil data user yang sedang login
        $id = $_SESSION['user_id'] ?? 0;
        $userData = $this->user->getById($id);
        
        if (!$userData) {
            header('Location: /spk_ahp/dashboard');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (empty($username) || empty($nama_lengkap)) {
                $error = 'Username dan nama lengkap harus diisi!';
            } else {
                $this->user->id = $id;
                $this->user->username = $username;
                $this->user->nama_lengkap = $nama_lengkap;
                $this->user->email = $email;
                
                $result = $this->user->updateProfile();
                if ($result) {
                    // Update session data
                    $_SESSION['username'] = $username;
                    $_SESSION['nama_lengkap'] = $nama_lengkap;
                    
                    $success = 'Profil berhasil diupdate!';
                    $userData = $this->user->getById($id); // Refresh data
                } else {
                    $error = 'Gagal mengupdate profil!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/user/profile.php';
        include 'views/templates/footer.php';
    }
}
?>