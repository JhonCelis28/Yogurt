<?php
class Database {
    // Configuración de la base de datos
    private $host = 'localhost';
    private $db_name = 'yogurt_san_francisco';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Método para obtener la conexión
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            
            // Configurar PDO para mostrar errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
            die();
        }

        return $this->conn;
    }

    // Método para probar la conexión
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            if ($conn) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
}
?>
