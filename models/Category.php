<?php
require_once 'config/database.php';

class Category {
    private $conn;
    private $table = 'categorias';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllCategories() {
        $query = "SELECT * FROM " . $this->table . " WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategory($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, imagen, fecha_creacion) 
                  VALUES (:nombre, :descripcion, :imagen, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':imagen', $data['imagen']);

        return $stmt->execute();
    }

    public function updateCategory($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion";
        
        if (!empty($data['imagen'])) {
            $query .= ", imagen = :imagen";
        }
        
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        
        if (!empty($data['imagen'])) {
            $stmt->bindParam(':imagen', $data['imagen']);
        }

        return $stmt->execute();
    }

    public function deleteCategory($id) {
        // Verificar si hay productos en esta categoría
        $query = "SELECT COUNT(*) as count FROM productos WHERE categoria_id = :id AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            return false; // No se puede eliminar si tiene productos
        }

        $query = "UPDATE " . $this->table . " SET activo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getCategoriesWithProductCount() {
        $query = "SELECT c.*, COUNT(p.id) as product_count 
                  FROM " . $this->table . " c 
                  LEFT JOIN productos p ON c.id = p.categoria_id AND p.activo = 1 
                  WHERE c.activo = 1 
                  GROUP BY c.id 
                  ORDER BY c.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
