<?php
require_once 'config/config.php';

// Router corregido para manejar URLs correctamente
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remover la ruta base del proyecto
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = dirname($scriptName);

// Si estamos en un subdirectorio, removerlo del path
if ($basePath !== '/') {
    $path = substr($path, strlen($basePath));
}

// Limpiar el path
$path = trim($path, '/');

// Si el path está vacío o es index.php, ir al home
if (empty($path) || $path === 'index.php') {
    $path = 'home';
}

// Dividir el path en segmentos
$segments = explode('/', $path);
$controller = $segments[0] ?? 'home';
$action = $segments[1] ?? 'index';
$param = $segments[2] ?? null;

try {
    switch ($controller) {
        case 'home':
            require_once 'controllers/HomeController.php';
            $homeController = new HomeController();
            
            switch ($action) {
                case 'index':
                    $homeController->index();
                    break;
                case 'about':
                    $homeController->about();
                    break;
                case 'contact':
                    $homeController->contact();
                    break;
                default:
                    $homeController->index();
            }
            break;
            
        case 'products':
            require_once 'controllers/ProductController.php';
            $productController = new ProductController();
            
            switch ($action) {
                case 'index':
                    $productController->index();
                    break;
                case 'show':
                    $productController->show($param);
                    break;
                case 'category':
                    $productController->category($param);
                    break;
                case 'personalized':
                    $productController->personalized();
                    break;
                case 'search':
                    $productController->search();
                    break;
                default:
                    $productController->index();
            }
            break;
            
        case 'login':
            require_once 'controllers/AuthController.php';
            $authController = new AuthController();
            $authController->login();
            break;
            
        case 'register':
            require_once 'controllers/AuthController.php';
            $authController = new AuthController();
            $authController->register();
            break;
            
        case 'logout':
            require_once 'controllers/AuthController.php';
            $authController = new AuthController();
            $authController->logout();
            break;
            
        case 'cart':
            require_once 'controllers/CartController.php';
            $cartController = new CartController();
            
            switch ($action) {
                case 'index':
                    $cartController->index();
                    break;
                case 'add':
                    $cartController->add();
                    break;
                case 'remove':
                    $cartController->remove($param);
                    break;
                case 'clear':
                    $cartController->clear();
                    break;
                case 'update':
                    $cartController->updateQuantity();
                    break;
                case 'count':
                    header('Content-Type: application/json');
                    echo json_encode(['count' => getCartItemCount()]);
                    break;
                default:
                    $cartController->index();
            }
            break;
            
        case 'profile':
            require_once 'controllers/ProfileController.php';
            $profileController = new ProfileController();
            
            switch ($action) {
                case 'index':
                    $profileController->index();
                    break;
                case 'orders':
                    $profileController->orders();
                    break;
                case 'addresses':
                    $profileController->addresses();
                    break;
                case 'update':
                    $profileController->update();
                    break;
                case 'change-password':
                    $profileController->changePassword();
                    break;
                default:
                    $profileController->index();
            }
            break;
            
        case 'promotions':
            require_once 'controllers/PromotionController.php';
            $promotionController = new PromotionController();
            $promotionController->index();
            break;
            
        case 'checkout':
            require_once 'controllers/CheckoutController.php';
            $checkoutController = new CheckoutController();
            
            switch ($action) {
                case 'index':
                    $checkoutController->index();
                    break;
                case 'process':
                    $checkoutController->process();
                    break;
                case 'success':
                    $checkoutController->success($param);
                    break;
                default:
                    $checkoutController->index();
            }
            break;
            
        case 'admin':
            if (!isAdmin()) {
                redirect('login');
            }
            require_once 'controllers/AdminController.php';
            $adminController = new AdminController();
            
            switch ($action) {
                case 'index':
                    $adminController->index();
                    break;
                case 'products':
                    $adminController->products();
                    break;
                case 'categories':
                    $adminController->categories();
                    break;
                case 'users':
                    $adminController->users();
                    break;
                case 'orders':
                    $adminController->orders();
                    break;
                case 'promotions':
                    $adminController->promotions();
                    break;
                case 'contacts':
                    $adminController->contacts();
                    break;
                default:
                    $adminController->index();
            }
            break;
            
        case 'about':
            require_once 'controllers/HomeController.php';
            $homeController = new HomeController();
            $homeController->about();
            break;
            
        case 'contact':
            require_once 'controllers/HomeController.php';
            $homeController = new HomeController();
            $homeController->contact();
            break;
            
        default:
            // Página 404
            http_response_code(404);
            $title = 'Página no encontrada';
            include 'views/errors/404.php';
            break;
    }
    
} catch (Exception $e) {
    // Manejo de errores
    error_log($e->getMessage());
    http_response_code(500);
    $title = 'Error del servidor';
    include 'views/errors/500.php';
}
?>
