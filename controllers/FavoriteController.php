<?php
require_once 'models/Favorite.php';
require_once 'config/config.php';

class FavoriteController {
    private $favoriteModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
            exit();
        }
        
        $this->favoriteModel = new Favorite();
    }

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $productId = (int)$input['product_id'];

            $result = $this->favoriteModel->toggleFavorite($_SESSION['user_id'], $productId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'action' => $result['action'],
                'message' => $result['message']
            ]);
        }
    }

    public function index() {
        $favorites = $this->favoriteModel->getUserFavorites($_SESSION['user_id']);
        include 'views/profile/favorites.php';
    }
}
?>
