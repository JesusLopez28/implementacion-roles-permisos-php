<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";
    
    public $id;
    public $name;
    public $email;
    public $password;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function login($email, $password) {
        $query = "SELECT u.id, u.name, u.email, u.password FROM " . $this->table_name . " u WHERE u.email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar entrada
        $email = htmlspecialchars(strip_tags($email));
        
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña (En producción, debería usar password_hash y password_verify)
            if($password == $row['password']) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                return true;
            }
        }
        return false;
    }
    
    public function getRoles() {
        $query = "SELECT r.id, r.name FROM roles r 
                  JOIN role_user ru ON r.id = ru.role_id 
                  WHERE ru.user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function hasRole($roleName) {
        $query = "SELECT COUNT(*) as count FROM roles r 
                  JOIN role_user ru ON r.id = ru.role_id 
                  WHERE ru.user_id = :user_id AND r.name = :role_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->bindParam(':role_name', $roleName);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
    
    public function hasPermission($permissionName) {
        $query = "SELECT COUNT(*) as count FROM permissions p 
                  JOIN permission_role pr ON p.id = pr.permission_id 
                  JOIN role_user ru ON pr.role_id = ru.role_id 
                  WHERE ru.user_id = :user_id AND p.name = :permission_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->bindParam(':permission_name', $permissionName);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
}
?>
