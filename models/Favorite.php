<?php
require_once 'config/database.php';

class Favorite {
    private $conn;
    private $table = 'favoritos';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addToFavorites($userId, $productId) {
        $query = "INSERT IGNORE INTO " . $this->table . " 
                  (usuario_id, producto_id, fecha_agregado) 
                  VALUES (:usuario_id, :producto_id, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->bindParam(':producto_id', $productId);

        return $stmt->execute();
    }

    public function removeFromFavorites($userId, $productId) {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE usuario_id = :usuario_id AND producto_id = :producto_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->bindParam(':producto_id', $productId);

        return $stmt->execute();
    }

    public function isFavorite($userId, $productId) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE usuario_id = :usuario_id AND producto_id = :producto_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->bindParam(':producto_id', $productId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getUserFavorites($userId) {
        $query = "SELECT f.*, p.nombre, p.precio, p.imagen, p.descripcion, c.nombre as categoria_nombre
                  FROM " . $this->table . " f 
                  LEFT JOIN productos p ON f.producto_id = p.id 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE f.usuario_id = :usuario_id AND p.activo = 1 
                  ORDER BY f.fecha_agregado DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleFavorite($userId, $productId) {
        if ($this->isFavorite($userId, $productId)) {
            $this->removeFromFavorites($userId, $productId);
            return ['action' => 'removed', 'message' => 'Producto eliminado de favoritos'];
        } else {
            $this->addToFavorites($userId, $productId);
            return ['action' => 'added', 'message' => 'Producto agregado a favoritos'];
        }
    }

    public function getFavoriteCount($userId) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
?>
