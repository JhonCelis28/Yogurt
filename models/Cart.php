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
        $query = "SELECT c.*, p.nombre, p.precio, p.imagen, p.stock
                  FROM " . $this->table . " c 
                  LEFT JOIN productos p ON c.producto_id = p.id 
                  WHERE c.usuario_id = :usuario_id 
                  AND (p.activo = 1 OR c.producto_id IN (999, 998))
                  ORDER BY c.fecha_agregado DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Procesar items para productos personalizados
        foreach ($items as &$item) {
            if ($item['producto_id'] == 999 || $item['producto_id'] == 998) {
                // Producto personalizado
                $item['nombre'] = $item['producto_id'] == 999 ? 'Yogurt Personalizado' : 'Torta Personalizada';
                $item['imagen'] = $item['producto_id'] == 999 ? 'yogurt1.jpg' : 'tortas2.jpg';
                
                // Calcular precio basado en personalizaciones
                $precioCalculado = $this->calculatePersonalizedPrice($item['producto_id'], $item['personalizaciones']);
                $item['precio'] = $precioCalculado;
                $item['stock'] = 999;
            } else if ($item['personalizaciones']) {
                // Producto normal con personalizaciones (como producto ID 7)
                // Verificar si es una torta con harina especial
                $personalizaciones = json_decode($item['personalizaciones'], true);
                if ($personalizaciones && isset($personalizaciones['harina'])) {
                    $harina = trim(strtolower($personalizaciones['harina']));
                    if ($harina === 'almendras' || $harina === 'coco') {
                        // Precio fijo de $50.000 para harina especial
                        $item['precio'] = 50000;
                    }
                }
            }
            $item['subtotal'] = $item['cantidad'] * ($item['precio'] ?? 0);
        }
        
        return $items;
    }
    
    private function calculatePersonalizedPrice($productId, $personalizacionesJson) {
        if (!$personalizacionesJson) {
            return $productId == 999 ? 15000 : 35000;
        }
        
        $personalizaciones = json_decode($personalizacionesJson, true);
        if (!$personalizaciones || json_last_error() !== JSON_ERROR_NONE) {
            return $productId == 999 ? 15000 : 35000;
        }
        
        // Precio base para yogurt
        if ($productId == 999) {
            return 15000;
        }
        
        // Precio para torta según opciones
        if ($productId == 998) {
            // Si tiene harina especial (almendras o coco), precio fijo de $50.000
            $harina = isset($personalizaciones['harina']) ? trim(strtolower($personalizaciones['harina'])) : 'normal';
            
            if ($harina === 'almendras' || $harina === 'coco') {
                return 50000;
            }
            
            // Precio base normal
            return 35000;
        }
        
        return 0;
    }

    public function getCartTotal($userId) {
        $items = $this->getCartItems($userId);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        
        return $total;
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
