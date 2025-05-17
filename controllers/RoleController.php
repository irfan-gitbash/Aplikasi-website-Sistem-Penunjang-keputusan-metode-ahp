<?php
require_once 'models/User.php';

class RoleController {
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
        
        $roles = $this->user->getAllRoles();
        
        include 'views/templates/header.php';
        include 'views/role/index.php';
        include 'views/templates/footer.php';
    }

    public function add() {
        // Cek apakah user memiliki akses
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: dashboard');
            exit;
        }
        
        $error = '';
        $success = '';
        $permissions = $this->user->getAllPermissions();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $selectedPermissions = $_POST['permissions'] ?? [];
            
            if (empty($name)) {
                $error = 'Nama role harus diisi!';
            } else {
                $role_id = $this->user->createRole($name, $description);
                if ($role_id) {
                    // Tambahkan permissions
                    if (!empty($selectedPermissions)) {
                        $this->user->updateRolePermissions($role_id, $selectedPermissions);
                    }
                    
                    $_SESSION['success'] = 'Role berhasil ditambahkan!';
                    header('Location: role');
                    exit;
                } else {
                    $error = 'Gagal menambahkan role!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/role/add.php';
        include 'views/templates/footer.php';
    }

    public function edit($id = null) {
        // Cek apakah user memiliki akses
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: dashboard');
            exit;
        }
        
        if (!$id) {
            header('Location: role');
            exit;
        }
        
        $error = '';
        $success = '';
        $role = $this->user->getRoleById($id);
        $permissions = $this->user->getAllPermissions();
        $rolePermissions = $this->user->getPermissionsByRole($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $selectedPermissions = $_POST['permissions'] ?? [];
            
            if (empty($name)) {
                $error = 'Nama role harus diisi!';
            } else {
                if ($this->user->updateRole($id, $name, $description)) {
                    // Update permissions
                    $this->user->updateRolePermissions($id, $selectedPermissions);
                    
                    $_SESSION['success'] = 'Role berhasil diupdate!';
                    header('Location: role');
                    exit;
                } else {
                    $error = 'Gagal mengupdate role!';
                }
            }
        }
        
        include 'views/templates/header.php';
        include 'views/role/edit.php';
        include 'views/templates/footer.php';
    }

    public function delete($id = null) {
        // Cek apakah user memiliki akses
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: dashboard');
            exit;
        }
        
        if (!$id) {
            header('Location: role');
            exit;
        }
        
        if ($this->user->deleteRole($id)) {
            $_SESSION['success'] = 'Role berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus role!';
        }
        
        header('Location: role');
        exit;
    }
}
?>