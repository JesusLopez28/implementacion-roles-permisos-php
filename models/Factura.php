<?php
require_once __DIR__ . '/../config/database.php';

class Factura {
    private $conn;
    private $table_name = "facturas";
    
    public $id;
    public $user_id;
    public $descripcion;
    public $monto;
    public $fecha;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAll() {
        $query = "SELECT f.id, f.user_id, f.descripcion, f.monto, f.fecha, u.name as usuario 
                  FROM " . $this->table_name . " f
                  JOIN users u ON f.user_id = u.id
                  ORDER BY f.fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserFacturas($user_id) {
        $query = "SELECT f.id, f.user_id, f.descripcion, f.monto, f.fecha, u.name as usuario 
                  FROM " . $this->table_name . " f
                  JOIN users u ON f.user_id = u.id
                  WHERE f.user_id = :user_id
                  ORDER BY f.fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOne($id) {
        $query = "SELECT f.id, f.user_id, f.descripcion, f.monto, f.fecha, u.name as usuario 
                  FROM " . $this->table_name . " f
                  JOIN users u ON f.user_id = u.id
                  WHERE f.id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, descripcion, monto, fecha) 
                  VALUES (:user_id, :descripcion, :monto, :fecha)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar entrada
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->monto = htmlspecialchars(strip_tags($this->monto));
        
        // Bind de parámetros
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':monto', $this->monto);
        $stmt->bindParam(':fecha', $this->fecha);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET descripcion = :descripcion, monto = :monto, fecha = :fecha 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar entrada
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->monto = htmlspecialchars(strip_tags($this->monto));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind de parámetros
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':monto', $this->monto);
        $stmt->bindParam(':fecha', $this->fecha);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar entrada
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind de parámetros
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
