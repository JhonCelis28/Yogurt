<?php
require_once 'models/Cart.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'config/config.php';

class CartController {
    private $cartModel;
    private $productModel;
    private $orderModel;

    public function __construct() {
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }

    public function index() {
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
        
        // Verificar si es primera compra para mostrar/ocultar promoción
        $isFirstOrder = $this->orderModel->isFirstOrder($_SESSION['user_id']);
        
        include 'views/cart/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            
            $productId = (int)$_POST['product_id'];
            $quantity = (int)($_POST['quantity'] ?? 1);
            $personalizaciones = $_POST['personalizaciones'] ?? null;
            
            // personalizaciones ya viene como JSON string desde el cliente
            // Solo validar que sea JSON válido si está presente
            if ($personalizaciones) {
                $decoded = json_decode($personalizaciones, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo json_encode(['success' => false, 'message' => 'Datos de personalización inválidos']);
                    exit;
                }
                // Mantener como JSON string para guardar en BD
            }

            if ($this->cartModel->addToCart($_SESSION['user_id'], $productId, $quantity, $personalizaciones)) {
                echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar producto']);
            }
            exit;
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
            header('Content-Type: application/json; charset=utf-8');
            
            $cartId = (int)$_POST['cart_id'];
            $quantity = (int)$_POST['quantity'];

            if ($this->cartModel->updateQuantity($cartId, $quantity)) {
                $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
                echo json_encode(['success' => true, 'total' => $total]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
    }

    public function saveEnvases() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cantidad = (int)($_POST['cantidad'] ?? 0);
            $_SESSION['envases_devueltos'] = $cantidad;
            echo json_encode(['success' => true, 'cantidad' => $cantidad]);
            exit;
        }
    }
}
?>
