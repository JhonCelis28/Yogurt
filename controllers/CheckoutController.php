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
        
        // Verificar si es primera compra y obtener promoción automática
        $isFirstOrder = $this->orderModel->isFirstOrder($_SESSION['user_id']);
        $firstOrderPromotion = null;
        $automaticPromotion = null;
        $discount = 0;
        $total = $subtotal;
        
        // Prioridad 1: Si es primera compra, aplicar SOLO el descuento de primera compra (no otras promociones)
        // Prioridad 2: Si NO es primera compra, aplicar SOLO UNA promoción automática (la mejor)
        
        $firstDiscount = 0;
        $autoDiscount = 0;
        
        // Promoción de primera compra (SIEMPRE se aplica si es primera compra, y SOLO esta)
        if ($isFirstOrder) {
            $firstOrderPromotion = $this->promotionModel->getFirstOrderPromotion();
            if ($firstOrderPromotion) {
                if ($firstOrderPromotion['tipo'] === 'porcentaje') {
                    $firstDiscount = ($subtotal * $firstOrderPromotion['valor_descuento']) / 100;
                } else {
                    $firstDiscount = $firstOrderPromotion['valor_descuento'];
                }
            }
            // Si es primera compra, NO buscar otras promociones automáticas
            $automaticPromotion = null;
        } else {
            // Si NO es primera compra, buscar promociones automáticas por monto
            $automaticPromotion = $this->promotionModel->getAutomaticPromotionByAmount($subtotal);
            if ($automaticPromotion) {
                if ($automaticPromotion['tipo'] === 'porcentaje') {
                    $autoDiscount = ($subtotal * $automaticPromotion['valor_descuento']) / 100;
                } else {
                    $autoDiscount = $automaticPromotion['valor_descuento'];
                }
            }
        }
        
        // Calcular descuento total y total final
        $discount = $firstDiscount + $autoDiscount;
        $total = max(0, $subtotal - $discount);
        
        // Si no hay descuento de primera compra, limpiar la variable
        if ($firstDiscount == 0) {
            $firstOrderPromotion = null;
        }
        
        // Si no hay descuento automático, limpiar la variable
        if ($autoDiscount == 0) {
            $automaticPromotion = null;
        }
        
        include 'views/checkout/index.php';
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
            
            if (empty($cartItems)) {
                redirect('cart');
            }

            $subtotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
            
            // Verificar si es primera compra y aplicar promoción automática
            $isFirstOrder = $this->orderModel->isFirstOrder($_SESSION['user_id']);
            $total = $subtotal;
            $promotionId = null;
            
            // Prioridad: primero verificar si hay un código aplicado manualmente
            if (isset($_SESSION['applied_promo_id'])) {
                $total = $this->promotionModel->applyPromotion($_SESSION['applied_promo_id'], $subtotal);
                $promotionId = $_SESSION['applied_promo_id'];
                unset($_SESSION['applied_promo_code']);
                unset($_SESSION['applied_promo_id']);
            } else {
                // Aplicar promociones automáticas
                // Prioridad 1: Si es primera compra, aplicar SOLO el descuento de primera compra (no otras promociones)
                // Prioridad 2: Si NO es primera compra, aplicar SOLO UNA promoción automática (la mejor)
                
                $firstDiscount = 0;
                $autoDiscount = 0;
                $firstOrderPromotion = null;
                $automaticPromotion = null;
                
                // Promoción de primera compra (SIEMPRE se aplica si es primera compra, y SOLO esta)
                if ($isFirstOrder) {
                    $firstOrderPromotion = $this->promotionModel->getFirstOrderPromotion();
                    if ($firstOrderPromotion) {
                        if ($firstOrderPromotion['tipo'] === 'porcentaje') {
                            $firstDiscount = ($subtotal * $firstOrderPromotion['valor_descuento']) / 100;
                        } else {
                            $firstDiscount = $firstOrderPromotion['valor_descuento'];
                        }
                    }
                    // Si es primera compra, NO buscar otras promociones automáticas
                    $automaticPromotion = null;
                } else {
                    // Si NO es primera compra, buscar promociones automáticas por monto
                    $automaticPromotion = $this->promotionModel->getAutomaticPromotionByAmount($subtotal);
                    if ($automaticPromotion) {
                        if ($automaticPromotion['tipo'] === 'porcentaje') {
                            $autoDiscount = ($subtotal * $automaticPromotion['valor_descuento']) / 100;
                        } else {
                            $autoDiscount = $automaticPromotion['valor_descuento'];
                        }
                    }
                }
                
                // Calcular descuento total y total final
                $totalDiscount = $firstDiscount + $autoDiscount;
                $total = max(0, $subtotal - $totalDiscount);
                
                // Guardar el ID de la promoción principal (prioridad a primera compra)
                if ($firstOrderPromotion && $firstDiscount > 0) {
                    $promotionId = $firstOrderPromotion['id'];
                } elseif ($automaticPromotion && $autoDiscount > 0) {
                    $promotionId = $automaticPromotion['id'];
                }
            }
            
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
                
                if ($isFirstOrder && $promotionId) {
                    $_SESSION['success_message'] = '¡Pedido realizado con éxito! Se aplicó automáticamente tu descuento de primera compra.';
                } else {
                    $_SESSION['success_message'] = '¡Pedido realizado con éxito!';
                }
                redirect('checkout/success/' . $orderId);
            } else {
                $_SESSION['error_message'] = 'Error al procesar el pedido';
                redirect('checkout');
            }
        } else {
            redirect('checkout');
        }
    }

    public function validatePromo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
            header('Content-Type: application/json');
            
            $code = sanitizeInput($_POST['codigo']);
            $promotion = $this->promotionModel->validatePromotionCode($code);
            
            if (!$promotion) {
                echo json_encode(['success' => false, 'message' => 'Código promocional inválido']);
                exit;
            }
            
            // Verificar si es código de primera compra y si el usuario ya lo usó
            $isFirstOrderCode = in_array(strtoupper($code), ['PRIMERA_COMPRA', 'PRIMERA', 'PRIMERA15']) || 
                               stripos($code, 'PRIMERA') === 0;
            
            if ($isFirstOrderCode) {
                $isFirstOrder = $this->orderModel->isFirstOrder($_SESSION['user_id']);
                if (!$isFirstOrder) {
                    echo json_encode(['success' => false, 'message' => 'Este código de primera compra ya fue utilizado']);
                    exit;
                }
            }
            
            // Guardar el código en sesión para aplicarlo en el proceso
            $_SESSION['applied_promo_code'] = $code;
            $_SESSION['applied_promo_id'] = $promotion['id'];
            
            $discountText = $promotion['tipo'] === 'porcentaje' 
                ? $promotion['valor_descuento'] . '% de descuento' 
                : formatPrice($promotion['valor_descuento']) . ' de descuento';
            
            echo json_encode(['success' => true, 'message' => $discountText]);
            exit;
        }
        
        redirect('checkout');
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
