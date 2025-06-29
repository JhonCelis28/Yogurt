<?php
require_once 'config/database.php';

class Promotion {
    private $conn;
    private $table = 'promociones';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPromotions() {
        $query = "SELECT * FROM " . $this->table . " WHERE activo = 1 ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivePromotions() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE activo = 1 
                  AND (fecha_inicio IS NULL OR fecha_inicio <= NOW()) 
                  AND (fecha_fin IS NULL OR fecha_fin >= NOW()) 
                  ORDER BY fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromotionById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPromotion($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, tipo, valor_descuento, codigo, fecha_inicio, fecha_fin, fecha_creacion) 
                  VALUES (:nombre, :descripcion, :tipo, :valor_descuento, :codigo, :fecha_inicio, :fecha_fin, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':valor_descuento', $data['valor_descuento']);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);

        return $stmt->execute();
    }

    public function updatePromotion($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion, tipo = :tipo, 
                      valor_descuento = :valor_descuento, codigo = :codigo, 
                      fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':valor_descuento', $data['valor_descuento']);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);

        return $stmt->execute();
    }

    public function togglePromotionStatus($id) {
        $query = "UPDATE " . $this->table . " 
                  SET activo = CASE WHEN activo = 1 THEN 0 ELSE 1 END 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function validatePromotionCode($code) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE codigo = :codigo AND activo = 1 
                  AND (fecha_inicio IS NULL OR fecha_inicio <= NOW()) 
                  AND (fecha_fin IS NULL OR fecha_fin >= NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $code);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function applyPromotion($promotionId, $total) {
        $promotion = $this->getPromotionById($promotionId);
        
        if (!$promotion) {
            return $total;
        }

        if ($promotion['tipo'] === 'porcentaje') {
            $descuento = ($total * $promotion['valor_descuento']) / 100;
        } else {
            $descuento = $promotion['valor_descuento'];
        }

        return max(0, $total - $descuento);
    }
}
?>
