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
        
        // Aplicar descuento por envases devueltos
        $envasesDevueltos = isset($_SESSION['envases_devueltos']) ? (int)$_SESSION['envases_devueltos'] : 0;
        $descuentoEnvases = $envasesDevueltos * 2000; // $2.000 por envase
        $subtotalConEnvases = max(0, $subtotal - $descuentoEnvases);
        
        // Verificar si hay un código promocional aplicado manualmente
        $manualPromotion = null;
        $manualDiscount = 0;
        $firstOrderPromotion = null;
        $automaticPromotion = null;
        $firstDiscount = 0;
        $autoDiscount = 0;
        $discount = 0;
        $total = $subtotalConEnvases;
        
            // Prioridad 1: Si hay un código promocional aplicado manualmente, usar ese
            if (isset($_SESSION['applied_promo_id'])) {
                $manualPromotion = $this->promotionModel->getPromotionById($_SESSION['applied_promo_id']);
                if ($manualPromotion && $manualPromotion['activo']) {
                    if ($manualPromotion['tipo'] === 'porcentaje') {
                        $manualDiscount = ($subtotalConEnvases * $manualPromotion['valor_descuento']) / 100;
                    } else {
                        $manualDiscount = $manualPromotion['valor_descuento'];
                    }
                    $discount = $manualDiscount;
                    $total = max(0, $subtotalConEnvases - $discount);
                } else {
                    // Si la promoción no es válida, limpiar la sesión
                    unset($_SESSION['applied_promo_id']);
                    unset($_SESSION['applied_promo_code']);
                }
            } else {
                // Prioridad 2: Si es primera compra, aplicar SOLO el descuento de primera compra (no otras promociones)
                // Prioridad 3: Si NO es primera compra, aplicar SOLO UNA promoción automática (la mejor)
                
                $isFirstOrder = $this->orderModel->isFirstOrder($_SESSION['user_id']);
                
                // Promoción de primera compra (SIEMPRE se aplica si es primera compra, y SOLO esta)
                if ($isFirstOrder) {
                    $firstOrderPromotion = $this->promotionModel->getFirstOrderPromotion();
                    if ($firstOrderPromotion) {
                        if ($firstOrderPromotion['tipo'] === 'porcentaje') {
                            $firstDiscount = ($subtotalConEnvases * $firstOrderPromotion['valor_descuento']) / 100;
                        } else {
                            $firstDiscount = $firstOrderPromotion['valor_descuento'];
                        }
                    }
                    // Si es primera compra, NO buscar otras promociones automáticas
                    $automaticPromotion = null;
                } else {
                    // Si NO es primera compra, buscar promociones automáticas por monto
                    $automaticPromotion = $this->promotionModel->getAutomaticPromotionByAmount($subtotalConEnvases);
                    if ($automaticPromotion) {
                        if ($automaticPromotion['tipo'] === 'porcentaje') {
                            $autoDiscount = ($subtotalConEnvases * $automaticPromotion['valor_descuento']) / 100;
                        } else {
                            $autoDiscount = $automaticPromotion['valor_descuento'];
                        }
                    }
                }
                
                // Calcular descuento total y total final
                $discount = $firstDiscount + $autoDiscount;
                $total = max(0, $subtotalConEnvases - $discount);
            
            // Si no hay descuento de primera compra, limpiar la variable
            if ($firstDiscount == 0) {
                $firstOrderPromotion = null;
            }
            
            // Si no hay descuento automático, limpiar la variable
            if ($autoDiscount == 0) {
                $automaticPromotion = null;
            }
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
            
            // Aplicar descuento por envases devueltos
            $envasesDevueltos = isset($_SESSION['envases_devueltos']) ? (int)$_SESSION['envases_devueltos'] : 0;
            $descuentoEnvases = $envasesDevueltos * 2000; // $2.000 por envase
            $subtotalConEnvases = max(0, $subtotal - $descuentoEnvases);
            
            // Verificar si es primera compra y aplicar promoción automática
            $isFirstOrder = $this->orderModel->isFirstOrder($_SESSION['user_id']);
            $total = $subtotalConEnvases;
            $promotionId = null;
            
            // Prioridad: primero verificar si hay un código aplicado manualmente
            if (isset($_SESSION['applied_promo_id'])) {
                $total = $this->promotionModel->applyPromotion($_SESSION['applied_promo_id'], $subtotalConEnvases);
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
                            $firstDiscount = ($subtotalConEnvases * $firstOrderPromotion['valor_descuento']) / 100;
                        } else {
                            $firstDiscount = $firstOrderPromotion['valor_descuento'];
                        }
                    }
                    // Si es primera compra, NO buscar otras promociones automáticas
                    $automaticPromotion = null;
                } else {
                    // Si NO es primera compra, buscar promociones automáticas por monto
                    $automaticPromotion = $this->promotionModel->getAutomaticPromotionByAmount($subtotalConEnvases);
                    if ($automaticPromotion) {
                        if ($automaticPromotion['tipo'] === 'porcentaje') {
                            $autoDiscount = ($subtotalConEnvases * $automaticPromotion['valor_descuento']) / 100;
                        } else {
                            $autoDiscount = $automaticPromotion['valor_descuento'];
                        }
                    }
                }
                
                // Calcular descuento total y total final
                $totalDiscount = $firstDiscount + $autoDiscount;
                $total = max(0, $subtotalConEnvases - $totalDiscount);
                
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

            $metodoPago = sanitizeInput($_POST['metodo_pago'] ?? 'efectivo');
            
            // Preparar información de pago según el método
            $infoPago = ['metodo' => $metodoPago];
            
            if ($metodoPago === 'transferencia') {
                $infoPago['numero_cuenta'] = sanitizeInput($_POST['numero_cuenta'] ?? '');
            } elseif ($metodoPago === 'nequi' || $metodoPago === 'bancolombia') {
                $infoPago['numero_celular'] = sanitizeInput($_POST['numero_celular'] ?? '');
            }
            
            // Guardar información de envases devueltos
            $infoPago['envases_devueltos'] = $envasesDevueltos;
            $infoPago['descuento_envases'] = $descuentoEnvases;
            $infoPago['subtotal_sin_descuento'] = $subtotal; // Subtotal antes de aplicar descuento de envases
            
            // Guardar información de promoción si existe
            if ($promotionId) {
                $promotion = $this->promotionModel->getPromotionById($promotionId);
                if ($promotion) {
                    $infoPago['promocion_id'] = $promotionId;
                    $infoPago['promocion_nombre'] = $promotion['nombre'];
                    $infoPago['descuento_promocion'] = $totalDiscount ?? ($subtotalConEnvases - $total);
                }
            }
            
            $orderData = [
                'usuario_id' => $_SESSION['user_id'],
                'total' => $total,
                'direccion_entrega' => sanitizeInput($_POST['direccion_entrega'] ?? ''),
                'telefono_contacto' => sanitizeInput($_POST['telefono_contacto'] ?? ''),
                'notas' => sanitizeInput($_POST['notas'] ?? ''),
                'metodo_pago' => $metodoPago,
                'info_pago' => json_encode($infoPago),
                'items' => $items
            ];

            $orderId = $this->orderModel->createOrder($orderData);
            
            if ($orderId) {
                // Limpiar carrito y sesión de envases
                $this->cartModel->clearCart($_SESSION['user_id']);
                unset($_SESSION['envases_devueltos']);
                
                if ($isFirstOrder && $promotionId) {
                    $_SESSION['success_message'] = '¡Pedido realizado con éxito! Se aplicó automáticamente tu descuento de primera compra.';
                } else {
                    $_SESSION['success_message'] = '¡Pedido realizado con éxito!';
                }
                redirect('checkout/success/' . $orderId);
            } else {
                // Verificar si hay algún error específico
                $errorMsg = 'Error al procesar el pedido. Por favor verifica que todos los campos estén completos.';
                
                // Verificar campos requeridos
                if (empty($orderData['direccion_entrega']) || empty($orderData['telefono_contacto'])) {
                    $errorMsg = 'Por favor completa todos los campos requeridos (dirección y teléfono de contacto).';
                }
                
                $_SESSION['error_message'] = $errorMsg;
                redirect('checkout');
            }
        } else {
            redirect('checkout');
        }
    }

    public function validatePromo() {
        // Limpiar cualquier salida previa
        ob_clean();
        
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
            
            // Verificar si es código de cumpleaños
            $isCumpleCode = stripos($code, 'cumple') !== false || 
                           stripos($code, 'cumpleaños') !== false ||
                           strtoupper($code) === 'MI_CUMPLE';
            
            if ($isCumpleCode) {
                // Mensaje especial de cumpleaños
                $discountText = $promotion['tipo'] === 'porcentaje' 
                    ? $promotion['valor_descuento'] . '% de descuento' 
                    : formatPrice($promotion['valor_descuento']) . ' de descuento';
                
                $cumpleMessage = '<div class="text-center p-3" style="background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%); border-radius: 10px; color: white;">';
                $cumpleMessage .= '<i class="fas fa-birthday-cake fa-3x mb-3" style="color: #ffd700;"></i>';
                $cumpleMessage .= '<h4 class="mb-2"><strong>¡Feliz Cumpleaños!</strong></h4>';
                $cumpleMessage .= '<p class="mb-2">De parte de toda la familia de</p>';
                $cumpleMessage .= '<h5 class="mb-3"><strong>Yogurt Artesanal San Francisco</strong></h5>';
                $cumpleMessage .= '<p class="mb-0">🎉 ' . $discountText . ' 🎉</p>';
                $cumpleMessage .= '<p class="mt-2 mb-0"><small>¡Que tengas un día lleno de dulzura y alegría!</small></p>';
                $cumpleMessage .= '</div>';
                
                echo json_encode(['success' => true, 'message' => $discountText, 'special_message' => $cumpleMessage]);
            } else {
                $discountText = $promotion['tipo'] === 'porcentaje' 
                    ? $promotion['valor_descuento'] . '% de descuento' 
                    : formatPrice($promotion['valor_descuento']) . ' de descuento';
                
                echo json_encode(['success' => true, 'message' => $discountText]);
            }
            exit;
        }
        
        redirect('checkout');
    }

    public function paymentGateway() {
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        
        if (empty($cartItems)) {
            redirect('cart');
        }
        
        $method = $_GET['method'] ?? 'transferencia';
        if (!in_array($method, ['transferencia', 'nequi', 'bancolombia'])) {
            redirect('checkout');
        }
        
        $subtotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
        $total = $subtotal; // Calcular con descuentos si es necesario
        
        include 'views/checkout/payment-gateway.php';
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
            
            if (empty($cartItems)) {
                redirect('cart');
            }

            $subtotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
            $metodoPago = sanitizeInput($_POST['metodo_pago'] ?? '');
            
            // Validar método de pago
            if (!in_array($metodoPago, ['transferencia', 'nequi', 'bancolombia'])) {
                $_SESSION['error_message'] = 'Método de pago inválido';
                redirect('checkout');
            }
            
            // Validar datos según el método de pago
            if ($metodoPago === 'transferencia') {
                $bancoOrigen = sanitizeInput($_POST['banco_origen'] ?? '');
                $tipoCuenta = sanitizeInput($_POST['tipo_cuenta'] ?? '');
                $numeroCuenta = sanitizeInput($_POST['numero_cuenta'] ?? '');
                $titularCuenta = sanitizeInput($_POST['titular_cuenta'] ?? '');
                $numeroIdentificacion = sanitizeInput($_POST['numero_identificacion'] ?? '');
                $numeroReferencia = sanitizeInput($_POST['numero_referencia'] ?? '');
                
                if (empty($bancoOrigen)) {
                    $_SESSION['error_message'] = 'Por favor selecciona tu banco de origen';
                    redirect('checkout/payment-gateway?method=transferencia');
                }
                if (empty($tipoCuenta)) {
                    $_SESSION['error_message'] = 'Por favor selecciona el tipo de cuenta';
                    redirect('checkout/payment-gateway?method=transferencia');
                }
                if (empty($numeroCuenta) || !preg_match('/^[0-9]{6,20}$/', $numeroCuenta)) {
                    $_SESSION['error_message'] = 'Por favor ingresa un número de cuenta válido';
                    redirect('checkout/payment-gateway?method=transferencia');
                }
                if (empty($titularCuenta)) {
                    $_SESSION['error_message'] = 'Por favor ingresa el nombre del titular de la cuenta';
                    redirect('checkout/payment-gateway?method=transferencia');
                }
                if (empty($numeroIdentificacion) || !preg_match('/^[0-9]{6,15}$/', $numeroIdentificacion)) {
                    $_SESSION['error_message'] = 'Por favor ingresa un número de identificación válido';
                    redirect('checkout/payment-gateway?method=transferencia');
                }
                if (empty($numeroReferencia)) {
                    $_SESSION['error_message'] = 'Por favor ingresa el número de referencia de la transferencia';
                    redirect('checkout/payment-gateway?method=transferencia');
                }
            } elseif ($metodoPago === 'nequi' || $metodoPago === 'bancolombia') {
                $numeroCelular = sanitizeInput($_POST['numero_celular'] ?? '');
                $codigoVerificacion = sanitizeInput($_POST['codigo_verificacion'] ?? '');
                
                if (empty($numeroCelular) || !preg_match('/^[0-9]{10}$/', $numeroCelular)) {
                    $_SESSION['error_message'] = 'Por favor ingresa un número de celular válido de 10 dígitos';
                    redirect('checkout/payment-gateway?method=' . $metodoPago);
                }
                
                if (empty($codigoVerificacion) || !preg_match('/^[0-9]{6}$/', $codigoVerificacion)) {
                    $_SESSION['error_message'] = 'Por favor ingresa un código de verificación válido de 6 dígitos';
                    redirect('checkout/payment-gateway?method=' . $metodoPago);
                }
            }
            
            // Obtener datos del checkout desde POST (vienen en checkout_data como JSON)
            $direccionEntrega = '';
            $telefonoContacto = '';
            $notas = '';
            
            if (isset($_POST['checkout_data']) && !empty($_POST['checkout_data'])) {
                $checkoutData = json_decode($_POST['checkout_data'], true);
                if ($checkoutData) {
                    $direccionEntrega = sanitizeInput($checkoutData['direccion_entrega'] ?? '');
                    $telefonoContacto = sanitizeInput($checkoutData['telefono_contacto'] ?? '');
                    $notas = sanitizeInput($checkoutData['notas'] ?? '');
                }
            }
            
            // Si no vienen en POST, intentar obtenerlos directamente de POST
            if (empty($direccionEntrega)) {
                $direccionEntrega = sanitizeInput($_POST['direccion_entrega'] ?? '');
            }
            if (empty($telefonoContacto)) {
                $telefonoContacto = sanitizeInput($_POST['telefono_contacto'] ?? '');
            }
            if (empty($notas)) {
                $notas = sanitizeInput($_POST['notas'] ?? '');
            }
            
            // Si aún no hay datos, intentar obtenerlos de la sesión
            if (empty($direccionEntrega) && isset($_SESSION['checkout_data'])) {
                $checkoutData = json_decode($_SESSION['checkout_data'], true);
                if ($checkoutData) {
                    $direccionEntrega = $checkoutData['direccion_entrega'] ?? '';
                    $telefonoContacto = $checkoutData['telefono_contacto'] ?? '';
                    $notas = $checkoutData['notas'] ?? '';
                }
            }
            
            if (empty($direccionEntrega) || empty($telefonoContacto)) {
                $_SESSION['error_message'] = 'Por favor completa todos los campos requeridos';
                redirect('checkout');
            }
            
            // Aplicar descuento por envases devueltos
            $envasesDevueltos = isset($_SESSION['envases_devueltos']) ? (int)$_SESSION['envases_devueltos'] : 0;
            $descuentoEnvases = $envasesDevueltos * 2000; // $2.000 por envase
            $subtotalConEnvases = max(0, $subtotal - $descuentoEnvases);
            
            // Verificar si es primera compra y aplicar promoción automática
            $isFirstOrder = $this->orderModel->isFirstOrder($_SESSION['user_id']);
            $total = $subtotalConEnvases;
            $promotionId = null;
            
            // Prioridad: primero verificar si hay un código aplicado manualmente
            if (isset($_SESSION['applied_promo_id'])) {
                $total = $this->promotionModel->applyPromotion($_SESSION['applied_promo_id'], $subtotalConEnvases);
                $promotionId = $_SESSION['applied_promo_id'];
                unset($_SESSION['applied_promo_code']);
                unset($_SESSION['applied_promo_id']);
            } else {
                // Aplicar promociones automáticas
                $firstDiscount = 0;
                $autoDiscount = 0;
                $firstOrderPromotion = null;
                $automaticPromotion = null;
                
                // Promoción de primera compra
                if ($isFirstOrder) {
                    $firstOrderPromotion = $this->promotionModel->getFirstOrderPromotion();
                    if ($firstOrderPromotion) {
                        if ($firstOrderPromotion['tipo'] === 'porcentaje') {
                            $firstDiscount = ($subtotalConEnvases * $firstOrderPromotion['valor_descuento']) / 100;
                        } else {
                            $firstDiscount = $firstOrderPromotion['valor_descuento'];
                        }
                    }
                    $automaticPromotion = null;
                } else {
                    // Si NO es primera compra, buscar promociones automáticas por monto
                    $automaticPromotion = $this->promotionModel->getAutomaticPromotionByAmount($subtotalConEnvases);
                    if ($automaticPromotion) {
                        if ($automaticPromotion['tipo'] === 'porcentaje') {
                            $autoDiscount = ($subtotalConEnvases * $automaticPromotion['valor_descuento']) / 100;
                        } else {
                            $autoDiscount = $automaticPromotion['valor_descuento'];
                        }
                    }
                }
                
                // Calcular descuento total y total final
                $totalDiscount = $firstDiscount + $autoDiscount;
                $total = max(0, $subtotalConEnvases - $totalDiscount);
                
                // Guardar el ID de la promoción principal
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

            // Preparar información de pago
            $infoPago = [
                'metodo' => $metodoPago
            ];
            
            if ($metodoPago === 'transferencia') {
                $infoPago['banco_origen'] = sanitizeInput($_POST['banco_origen'] ?? '');
                $infoPago['tipo_cuenta'] = sanitizeInput($_POST['tipo_cuenta'] ?? '');
                $infoPago['numero_cuenta'] = sanitizeInput($_POST['numero_cuenta'] ?? '');
                $infoPago['titular_cuenta'] = sanitizeInput($_POST['titular_cuenta'] ?? '');
                $infoPago['numero_identificacion'] = sanitizeInput($_POST['numero_identificacion'] ?? '');
                $infoPago['numero_referencia'] = sanitizeInput($_POST['numero_referencia'] ?? '');
            } elseif ($metodoPago === 'nequi' || $metodoPago === 'bancolombia') {
                $infoPago['numero_celular'] = sanitizeInput($_POST['numero_celular'] ?? '');
                $infoPago['codigo_verificacion'] = sanitizeInput($_POST['codigo_verificacion'] ?? '');
            }
            
            // Guardar información de envases devueltos
            $infoPago['envases_devueltos'] = $envasesDevueltos;
            $infoPago['descuento_envases'] = $descuentoEnvases;
            $infoPago['subtotal_sin_descuento'] = $subtotal; // Subtotal antes de aplicar descuento de envases
            
            // Guardar información de promoción si existe
            if ($promotionId) {
                $promotion = $this->promotionModel->getPromotionById($promotionId);
                if ($promotion) {
                    $infoPago['promocion_id'] = $promotionId;
                    $infoPago['promocion_nombre'] = $promotion['nombre'];
                    $infoPago['descuento_promocion'] = $totalDiscount ?? ($subtotalConEnvases - $total);
                }
            }

            $orderData = [
                'usuario_id' => $_SESSION['user_id'],
                'total' => $total,
                'direccion_entrega' => $direccionEntrega,
                'telefono_contacto' => $telefonoContacto,
                'notas' => $notas,
                'metodo_pago' => $metodoPago,
                'info_pago' => json_encode($infoPago),
                'items' => $items
            ];

            $orderId = $this->orderModel->createOrder($orderData);
            
            if ($orderId) {
                // Limpiar carrito y datos de checkout
                $this->cartModel->clearCart($_SESSION['user_id']);
                unset($_SESSION['checkout_data']);
                unset($_SESSION['envases_devueltos']);
                
                if ($isFirstOrder && $promotionId) {
                    $_SESSION['success_message'] = '¡Pago procesado con éxito! Se aplicó automáticamente tu descuento de primera compra.';
                } else {
                    $_SESSION['success_message'] = '¡Pago procesado con éxito! Tu pedido ha sido confirmado.';
                }
                redirect('checkout/success/' . $orderId);
            } else {
                $_SESSION['error_message'] = 'Error al procesar el pago';
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
