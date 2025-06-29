<?php
require_once 'models/Cart.php';
require_once 'models/Product.php';
require_once 'config/config.php';

class CartController {
    private $cartModel;
    private $productModel;

    public function __construct() {
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    public function index() {
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
        include 'views/cart/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            $quantity = (int)($_POST['quantity'] ?? 1);
            $personalizaciones = $_POST['personalizaciones'] ?? null;
            
            if ($personalizaciones) {
                $personalizaciones = json_encode($personalizaciones);
            }

            if ($this->cartModel->addToCart($_SESSION['user_id'], $productId, $quantity, $personalizaciones)) {
                echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar producto']);
            }
        }
    }

    public function remove($cartId) {
        if ($this->cartModel->removeFromCart($cartId)) {
            $_SESSION['success_message'] = 'Producto eliminado del carrito';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar producto';
        }
        
        redirect('cart');
    }

    public function clear() {
        if ($this->cartModel->clearCart($_SESSION['user_id'])) {
            $_SESSION['success_message'] = 'Carrito vaciado';
        } else {
            $_SESSION['error_message'] = 'Error al vaciar carrito';
        }
        
        redirect('cart');
    }

    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartId = (int)$_POST['cart_id'];
            $quantity = (int)$_POST['quantity'];

            if ($this->cartModel->updateQuantity($cartId, $quantity)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
    }
}
?>
