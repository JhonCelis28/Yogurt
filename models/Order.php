<?php
require_once 'config/database.php';

class Order {
    private $conn;
    private $table = 'pedidos';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createOrder($data) {
        try {
            $this->conn->beginTransaction();

            // Crear el pedido
            $query = "INSERT INTO " . $this->table . " 
                      (usuario_id, total, estado, direccion_entrega, telefono_contacto, notas, fecha_pedido) 
                      VALUES (:usuario_id, :total, 'pendiente', :direccion_entrega, :telefono_contacto, :notas, NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $data['usuario_id']);
            $stmt->bindParam(':total', $data['total']);
            $stmt->bindParam(':direccion_entrega', $data['direccion_entrega']);
            $stmt->bindParam(':telefono_contacto', $data['telefono_contacto']);
            $stmt->bindParam(':notas', $data['notas']);
            $stmt->execute();

            $orderId = $this->conn->lastInsertId();

            // Agregar los items del pedido
            foreach ($data['items'] as $item) {
                $this->addOrderItem($orderId, $item);
                
                // Actualizar stock del producto
                $this->updateProductStock($item['producto_id'], $item['cantidad']);
            }

            $this->conn->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getOrderById($id) {
        $query = "SELECT p.*, u.nombre as cliente_nombre, u.email as cliente_email 
                  FROM " . $this->table . " p 
                  LEFT JOIN usuarios u ON p.usuario_id = u.id 
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($orderId) {
        $query = "SELECT di.*, p.nombre, p.imagen 
                  FROM detalle_pedidos di 
                  LEFT JOIN productos p ON di.producto_id = p.id 
                  WHERE di.pedido_id = :pedido_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pedido_id', $orderId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserOrders($userId) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE usuario_id = :usuario_id 
                  ORDER BY fecha_pedido DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllOrders($limit = 50) {
        $query = "SELECT p.*, u.nombre as cliente_nombre, u.email as cliente_email 
                  FROM " . $this->table . " p 
                  LEFT JOIN usuarios u ON p.usuario_id = u.id 
                  ORDER BY p.fecha_pedido DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':estado', $status);

        return $stmt->execute();
    }

    private function addOrderItem($orderId, $item) {
        $query = "INSERT INTO detalle_pedidos 
                  (pedido_id, producto_id, cantidad, precio_unitario, personalizaciones) 
                  VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario, :personalizaciones)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pedido_id', $orderId);
        $stmt->bindParam(':producto_id', $item['producto_id']);
        $stmt->bindParam(':cantidad', $item['cantidad']);
        $stmt->bindParam(':precio_unitario', $item['precio']);
        $stmt->bindParam(':personalizaciones', $item['personalizaciones']);

        return $stmt->execute();
    }

    private function updateProductStock($productId, $quantity) {
        $query = "UPDATE productos SET stock = stock - :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $productId);
        $stmt->bindParam(':quantity', $quantity);

        return $stmt->execute();
    }

    public function getOrderStats() {
        $stats = [];
        
        // Pedidos de hoy
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE DATE(fecha_pedido) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['pedidos_hoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Ventas del mes
        $query = "SELECT COALESCE(SUM(total), 0) as total FROM " . $this->table . " 
                  WHERE MONTH(fecha_pedido) = MONTH(CURDATE()) AND YEAR(fecha_pedido) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['ventas_mes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }
}
?>
