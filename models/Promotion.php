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
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_creacion DESC";
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
                  (nombre, descripcion, tipo, valor_descuento, codigo, condicion_minima, fecha_inicio, fecha_fin, fecha_creacion) 
                  VALUES (:nombre, :descripcion, :tipo, :valor_descuento, :codigo, :condicion_minima, :fecha_inicio, :fecha_fin, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':valor_descuento', $data['valor_descuento']);
        $stmt->bindParam(':codigo', $data['codigo']);
        $condicionMinima = isset($data['condicion_minima']) && $data['condicion_minima'] > 0 ? $data['condicion_minima'] : 0;
        $stmt->bindParam(':condicion_minima', $condicionMinima);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);

        return $stmt->execute();
    }

    public function updatePromotion($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion, tipo = :tipo, 
                      valor_descuento = :valor_descuento, codigo = :codigo, 
                      condicion_minima = :condicion_minima,
                      fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':valor_descuento', $data['valor_descuento']);
        $stmt->bindParam(':codigo', $data['codigo']);
        $condicionMinima = isset($data['condicion_minima']) && $data['condicion_minima'] > 0 ? $data['condicion_minima'] : 0;
        $stmt->bindParam(':condicion_minima', $condicionMinima);
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

    public function getFirstOrderPromotion() {
        // Buscar promoción con código especial para primera compra
        // Primero intentar buscar por código exacto (más eficiente)
        $codes = ['PRIMERA_COMPRA', 'PRIMERA', 'PRIMERA15'];
        foreach ($codes as $code) {
            // Buscar primero sin restricción de fechas (para encontrar promociones que puedan estar vencidas)
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE codigo = :codigo 
                      AND activo = 1 
                      LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':codigo', $code);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Verificar si está vigente por fechas
                $isValid = true;
                if ($result['fecha_inicio'] && strtotime($result['fecha_inicio']) > time()) {
                    $isValid = false; // Aún no ha comenzado
                }
                if ($result['fecha_fin'] && strtotime($result['fecha_fin']) < time()) {
                    $isValid = false; // Ya expiró
                }
                
                if ($isValid) {
                    return $result;
                }
            }
        }
        
        // Si no se encontró por código exacto, buscar por LIKE (sin importar mayúsculas/minúsculas)
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (UPPER(codigo) LIKE UPPER('PRIMERA%') 
                         OR LOWER(nombre) LIKE '%primera compra%' 
                         OR nombre LIKE '%Primera Compra%'
                         OR nombre LIKE '%PRIMERA COMPRA%') 
                  AND activo = 1 
                  ORDER BY fecha_creacion DESC
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Verificar si está vigente por fechas
            $isValid = true;
            if ($result['fecha_inicio'] && strtotime($result['fecha_inicio']) > time()) {
                $isValid = false; // Aún no ha comenzado
            }
            if ($result['fecha_fin'] && strtotime($result['fecha_fin']) < time()) {
                $isValid = false; // Ya expiró
            }
            
            if ($isValid) {
                return $result;
            }
        }
        
        return null;
    }

    public function getAutomaticPromotionByAmount($amount) {
        // Buscar promociones automáticas por monto mínimo
        // SOLO promociones con condicion_minima > 0 (promociones por monto)
        // Excluir promociones de primera compra y promociones que requieren código manual
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE activo = 1 
                  AND condicion_minima > 0
                  AND condicion_minima <= :amount
                  AND (codigo IS NULL 
                       OR codigo = '' 
                       OR (codigo NOT IN ('PRIMERA_COMPRA', 'PRIMERA', 'PRIMERA15', 'FIN_SEMANA_2X1', 'FIN_SEMANA')
                           AND UPPER(codigo) NOT LIKE 'PRIMERA%'
                           AND UPPER(codigo) NOT LIKE 'FIN_SEMANA%'
                           AND LOWER(nombre) NOT LIKE '%primera compra%'
                           AND LOWER(nombre) NOT LIKE '%fin de semana%'))
                  AND (fecha_inicio IS NULL OR fecha_inicio <= CURDATE()) 
                  AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())
                  ORDER BY condicion_minima DESC, valor_descuento DESC
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':amount', $amount);
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
