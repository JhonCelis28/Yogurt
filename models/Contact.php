<?php
require_once 'config/database.php';

class Contact {
    private $conn;
    private $table = 'contactos';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function saveContact($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, email, telefono, mensaje, fecha_contacto) 
                  VALUES (:nombre, :email, :telefono, :mensaje, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':mensaje', $data['mensaje']);

        return $stmt->execute();
    }

    public function getAllContacts() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_contacto DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContactById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markAsRead($id) {
        $query = "UPDATE " . $this->table . " SET leido = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function deleteContact($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getUnreadCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE leido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
?>
