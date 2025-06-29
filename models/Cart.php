<?php
require_once 'config/database.php';

class Cart {
    private $conn;
    private $table = 'carrito';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addToCart($userId, $productId, $quantity = 1, $personalizaciones = null) {
        // Verificar si el producto ya está en el carrito
        $existingItem = $this->getCartItem($userId, $productId, $personalizaciones);
        
        if ($existingItem) {
            // Actualizar cantidad
            return $this->updateQuantity($existingItem['id'], $existingItem['cantidad'] + $quantity);
        } else {
            // Agregar nuevo item
            $query = "INSERT INTO " . $this->table . " 
                      (usuario_id, producto_id, cantidad, personalizaciones, fecha_agregado) 
                      VALUES (:usuario_id, :producto_id, :cantidad, :personalizaciones, NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $userId);
            $stmt->bindParam(':producto_id', $productId);
            $stmt->bindParam(':cantidad', $quantity);
            $stmt->bindParam(':personalizaciones', $personalizaciones);

            return $stmt->execute();
        }
    }

    public function getCartItems($userId) {
        $query = "SELECT c.*, p.nombre, p.precio, p.imagen, p.stock,
                         (c.cantidad * p.precio) as subtotal
                  FROM " . $this->table . " c 
                  LEFT JOIN productos p ON c.producto_id = p.id 
                  WHERE c.usuario_id = :usuario_id AND p.activo = 1 
                  ORDER BY c.fecha_agregado DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartTotal($userId) {
        $query = "SELECT SUM(c.cantidad * p.precio) as total 
                  FROM " . $this->table . " c 
                  LEFT JOIN productos p ON c.producto_id = p.id 
                  WHERE c.usuario_id = :usuario_id AND p.activo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function updateQuantity($cartId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($cartId);
        }

        $query = "UPDATE " . $this->table . " SET cantidad = :cantidad WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $cartId);
        $stmt->bindParam(':cantidad', $quantity);

        return $stmt->execute();
    }

    public function removeFromCart($cartId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $cartId);

        return $stmt->execute();
    }

    public function clearCart($userId) {
        $query = "DELETE FROM " . $this->table . " WHERE usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);

        return $stmt->execute();
    }

    public function getCartItemCount($userId) {
        $query = "SELECT SUM(cantidad) as count FROM " . $this->table . " WHERE usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    private function getCartItem($userId, $productId, $personalizaciones) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE usuario_id = :usuario_id AND producto_id = :producto_id";
        
        if ($personalizaciones) {
            $query .= " AND personalizaciones = :personalizaciones";
        } else {
            $query .= " AND personalizaciones IS NULL";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->bindParam(':producto_id', $productId);
        
        if ($personalizaciones) {
            $stmt->bindParam(':personalizaciones', $personalizaciones);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validateCartStock($userId) {
        $query = "SELECT c.*, p.stock, p.nombre 
                  FROM " . $this->table . " c 
                  LEFT JOIN productos p ON c.producto_id = p.id 
                  WHERE c.usuario_id = :usuario_id AND c.cantidad > p.stock";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
