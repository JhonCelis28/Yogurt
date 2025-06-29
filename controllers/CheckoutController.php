<?php
require_once 'models/Cart.php';
require_once 'models/Order.php';
require_once 'models/Promotion.php';
require_once 'config/config.php';

class CheckoutController {
    private $cartModel;
    private $orderModel;
    private $promotionModel;

    public function __construct() {
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        $this->cartModel = new Cart();
        $this->orderModel = new Order();
        $this->promotionModel = new Promotion();
    }

    public function index() {
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        
        if (empty($cartItems)) {
            redirect('cart');
        }

        $subtotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
        $promotions = $this->promotionModel->getActivePromotions();
        
        include 'views/checkout/index.php';
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
            
            if (empty($cartItems)) {
                redirect('cart');
            }

            $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
            
            // Preparar los items para el pedido
            $items = [];
            foreach ($cartItems as $item) {
                $items[] = [
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'personalizaciones' => $item['personalizaciones']
                ];
            }

            $orderData = [
                'usuario_id' => $_SESSION['user_id'],
                'total' => $total,
                'direccion_entrega' => sanitizeInput($_POST['direccion_entrega'] ?? ''),
                'telefono_contacto' => sanitizeInput($_POST['telefono_contacto'] ?? ''),
                'notas' => sanitizeInput($_POST['notas'] ?? ''),
                'items' => $items
            ];

            $orderId = $this->orderModel->createOrder($orderData);
            
            if ($orderId) {
                // Limpiar carrito
                $this->cartModel->clearCart($_SESSION['user_id']);
                
                $_SESSION['success_message'] = '¡Pedido realizado con éxito!';
                redirect('checkout/success/' . $orderId);
            } else {
                $_SESSION['error_message'] = 'Error al procesar el pedido';
                redirect('checkout');
            }
        } else {
            redirect('checkout');
        }
    }

    public function success($orderId) {
        // Simulamos un pedido exitoso si no existe
        if (!$orderId) {
            $order = [
                'id' => time(),
                'total' => 0,
                'estado' => 'pendiente',
                'fecha_pedido' => date('Y-m-d H:i:s')
            ];
            $orderItems = [];
        } else {
            // Intentamos obtener el pedido real
            $order = $this->orderModel->getOrderById($orderId) ?? [
                'id' => $orderId,
                'total' => 0,
                'estado' => 'pendiente',
                'fecha_pedido' => date('Y-m-d H:i:s')
            ];
            $orderItems = $this->orderModel->getOrderItems($orderId) ?? [];
        }
        
        include 'views/checkout/success.php';
    }
}
?>
