<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table = 'usuarios';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($data) {
        $tipo = $data['tipo'] ?? 'cliente';
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, email, telefono, password, direccion, tipo, fecha_registro) 
                  VALUES (:nombre, :email, :telefono, :password, :direccion, :tipo, NOW())";

        $stmt = $this->conn->prepare($query);

        // Hash de la contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':tipo', $tipo);

        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getUserById($id, $includeInactive = false) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        if (!$includeInactive) {
            $query .= " AND activo = 1";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, telefono = :telefono, direccion = :direccion";
        
        // Si se proporciona email, actualizarlo
        if (isset($data['email'])) {
            $query .= ", email = :email";
        }
        
        // Si se proporciona activo, actualizarlo
        if (isset($data['activo'])) {
            $query .= ", activo = :activo";
        }
        
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':direccion', $data['direccion']);
        
        if (isset($data['email'])) {
            $stmt->bindParam(':email', $data['email']);
        }
        
        if (isset($data['activo'])) {
            $stmt->bindParam(':activo', $data['activo']);
        }

        return $stmt->execute();
    }

    public function changePassword($id, $newPassword) {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    }

    public function getAllUsers() {
        $query = "SELECT id, nombre, email, telefono, fecha_registro, activo 
                  FROM " . $this->table . " 
                  WHERE tipo = 'cliente' 
                  ORDER BY fecha_registro DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleUserStatus($id) {
        $query = "UPDATE " . $this->table . " 
                  SET activo = CASE WHEN activo = 1 THEN 0 ELSE 1 END 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getUserAddresses($userId) {
        $query = "SELECT * FROM direcciones_usuarios 
                  WHERE usuario_id = :usuario_id AND activa = 1 
                  ORDER BY es_principal DESC, fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
