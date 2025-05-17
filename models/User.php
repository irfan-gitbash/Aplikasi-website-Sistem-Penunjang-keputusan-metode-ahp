<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "users";
    private $role_table = "user_roles";
    private $permission_table = "role_permissions";

    // Ubah properti name menjadi nama_lengkap
    public $id;
    public $username;
    public $password;
    public $nama_lengkap; // Ubah dari name menjadi nama_lengkap
    public $role;
    public $email;
    // public $phone; // Hapus atau komentari baris ini
    public $status;
    public $last_login;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET username = :username, 
                      password = :password, 
                      nama_lengkap = :nama_lengkap, 
                      role = :role, 
                      email = :email,
                      status = :status,
                      created_at = :created_at,
                      updated_at = :updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Hash password
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->email = htmlspecialchars(strip_tags($this->email));
        // $this->phone = htmlspecialchars(strip_tags($this->phone)); // Hapus atau komentari baris ini
        $this->status = htmlspecialchars(strip_tags($this->status)) ?: 'active';
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':nama_lengkap', $this->nama_lengkap);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':email', $this->email);
        // $stmt->bindParam(':phone', $this->phone); // Hapus atau komentari baris ini
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':updated_at', $this->updated_at);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET username = :username, 
                      nama_lengkap = :nama_lengkap, 
                      role = :role,
                      email = :email,
                      status = :status,
                      updated_at = :updated_at 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->email = htmlspecialchars(strip_tags($this->email));
        // $this->phone = htmlspecialchars(strip_tags($this->phone)); // Hapus atau komentari baris ini
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->updated_at = date('Y-m-d H:i:s');
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind values
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':nama_lengkap', $this->nama_lengkap);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':email', $this->email);
        // $stmt->bindParam(':phone', $this->phone); // Hapus atau komentari baris ini
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updatePassword() {
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password,
                      updated_at = :updated_at 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->updated_at = date('Y-m-d H:i:s');
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind values
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Metode untuk mendapatkan semua role
    public function getAllRoles() {
        $query = "SELECT r.*, 
                 GROUP_CONCAT(p.display_name) as permissions
                 FROM " . $this->role_table . " r
                 LEFT JOIN role_permission_map rpm ON r.id = rpm.role_id
                 LEFT JOIN role_permissions p ON rpm.permission_id = p.id
                 GROUP BY r.id
                 ORDER BY r.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Metode untuk mendapatkan role berdasarkan ID
    public function getRoleById($id) {
        $query = "SELECT * FROM " . $this->role_table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Metode untuk membuat role baru
    public function createRole($name, $description) {
        $query = "INSERT INTO " . $this->role_table . " 
                  SET name = :name, 
                      description = :description, 
                      created_at = :created_at,
                      updated_at = :updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':updated_at', $updated_at);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Metode untuk update role
    public function updateRole($id, $name, $description) {
        $query = "UPDATE " . $this->role_table . " 
                  SET name = :name, 
                      description = :description, 
                      updated_at = :updated_at 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $updated_at = date('Y-m-d H:i:s');
        $id = htmlspecialchars(strip_tags($id));
        
        // Bind values
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Metode untuk menghapus role
    public function deleteRole($id) {
        $query = "DELETE FROM " . $this->role_table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Metode untuk mendapatkan semua permission
    public function getAllPermissions() {
        $query = "SELECT * FROM " . $this->permission_table . " ORDER BY module, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Metode untuk mendapatkan permission berdasarkan role
    public function getPermissionsByRole($role_id) {
        $query = "SELECT p.id FROM " . $this->permission_table . " p
                  JOIN role_permission_map rpm ON p.id = rpm.permission_id
                  WHERE rpm.role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();
        
        $permissions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permissions[] = $row['id'];
        }
        return $permissions;
    }
    
    // Metode untuk update permission role
    public function updateRolePermissions($role_id, $permissions) {
        // Hapus permission yang ada
        $query = "DELETE FROM role_permission_map WHERE role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();
        
        // Tambahkan permission baru
        if (!empty($permissions)) {
            $values = [];
            $params = [];
            
            foreach ($permissions as $i => $permission_id) {
                $key = ":permission_id" . $i;
                $values[] = "(:role_id, " . $key . ")";
                $params[$key] = $permission_id;
            }
            
            $query = "INSERT INTO role_permission_map (role_id, permission_id) VALUES " . implode(", ", $values);
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role_id', $role_id);
            
            foreach ($params as $key => $value) {
                $stmt->bindParam($key, $value);
            }
            
            return $stmt->execute();
        }
        
        return true;
    }
    
    // Metode untuk cek apakah user memiliki permission tertentu
    public function hasPermission($user_id, $permission_name) {
        $query = "SELECT COUNT(*) as count FROM users u
                  JOIN user_roles r ON u.role = r.name
                  JOIN role_permission_map rpm ON r.id = rpm.role_id
                  JOIN role_permissions p ON rpm.permission_id = p.id
                  WHERE u.id = :user_id AND p.name = :permission_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':permission_name', $permission_name);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
    
    public function updateProfile() {
        $query = "UPDATE " . $this->table_name . " 
              SET username = :username, 
                  nama_lengkap = :nama_lengkap, 
                  email = :email,
                  updated_at = :updated_at 
              WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->updated_at = date('Y-m-d H:i:s');
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind values
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':nama_lengkap', $this->nama_lengkap);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>