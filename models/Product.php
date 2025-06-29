<?php
require_once 'config/database.php';

class Product {
    private $conn;
    private $table = 'productos';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllProducts() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.id = :id AND p.activo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($categoryId) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.categoria_id = :category_id AND p.activo = 1 
                  ORDER BY p.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts($limit = 6) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND p.destacado = 1 
                  ORDER BY p.fecha_creacion DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPersonalizableProducts() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND p.es_personalizable = 1 
                  ORDER BY p.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createProduct($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, precio, categoria_id, stock, es_personalizable, imagen, fecha_creacion) 
                  VALUES (:nombre, :descripcion, :precio, :categoria_id, :stock, :es_personalizable, :imagen, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':categoria_id', $data['categoria_id']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':es_personalizable', $data['es_personalizable']);
        $stmt->bindParam(':imagen', $data['imagen']);

        return $stmt->execute();
    }

    public function updateProduct($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                      categoria_id = :categoria_id, stock = :stock, es_personalizable = :es_personalizable";
        
        if (!empty($data['imagen'])) {
            $query .= ", imagen = :imagen";
        }
        
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':categoria_id', $data['categoria_id']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':es_personalizable', $data['es_personalizable']);
        
        if (!empty($data['imagen'])) {
            $stmt->bindParam(':imagen', $data['imagen']);
        }

        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $query = "UPDATE " . $this->table . " SET activo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function updateStock($id, $quantity) {
        $query = "UPDATE " . $this->table . " 
                  SET stock = stock - :quantity 
                  WHERE id = :id AND stock >= :quantity";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantity', $quantity);

        return $stmt->execute();
    }

    public function searchProducts($searchTerm) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND (p.nombre LIKE :search OR p.descripcion LIKE :search) 
                  ORDER BY p.nombre";
        
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
