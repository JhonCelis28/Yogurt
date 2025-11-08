<?php
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/User.php';
require_once 'models/Order.php';
require_once 'models/Promotion.php';
require_once 'models/Contact.php';
require_once 'config/config.php';

class AdminController {
    private $productModel;
    private $categoryModel;
    private $userModel;
    private $orderModel;
    private $promotionModel;
    private $contactModel;

    public function __construct() {
        if (!isAdmin()) {
            redirect('login');
        }
        
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->promotionModel = new Promotion();
        $this->contactModel = new Contact();
    }

    public function index() {
        // Obtener estadísticas para el dashboard
        $orderStats = $this->orderModel->getOrderStats();
        $allUsers = $this->userModel->getAllUsers();
        $allProducts = $this->productModel->getAllProducts(true); // Incluir inactivos para estadísticas
        
        $stats = [
            'productos' => count($allProducts),
            'pedidos_hoy' => $orderStats['pedidos_hoy'] ?? 0,
            'usuarios' => count($allUsers),
            'ventas_mes' => $orderStats['ventas_mes'] ?? 0
        ];
        
        // Obtener pedidos recientes
        $recentOrders = $this->orderModel->getAllOrders(10);
        
        include 'views/admin/index.php';
    }

    public function products() {
        $products = $this->productModel->getAllProducts(true); // Incluir inactivos en admin
        $categories = $this->categoryModel->getAllCategories();
        include 'views/admin/products.php';
    }

    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAddProduct();
        } else {
            $categories = $this->categoryModel->getAllCategories();
            include 'views/admin/add-product.php';
        }
    }

    public function editProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditProduct($id);
        } else {
            // Si es una petición AJAX para obtener datos del producto
            if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
                header('Content-Type: application/json');
                $product = $this->productModel->getProductById($id, true);
                if ($product) {
                    echo json_encode($product);
                } else {
                    echo json_encode(['error' => 'Producto no encontrado']);
                }
                exit;
            }
            // Si no es AJAX, redirigir a la lista de productos
            redirect('admin/products');
        }
    }

    public function categories() {
        $categories = $this->categoryModel->getCategoriesWithProductCount();
        include 'views/admin/categories.php';
    }

    public function users() {
        $users = $this->userModel->getAllUsers();
        include 'views/admin/users.php';
    }

    public function editUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => sanitizeInput($_POST['nombre']),
                'email' => sanitizeInput($_POST['email']),
                'telefono' => sanitizeInput($_POST['telefono'] ?? ''),
                'direccion' => sanitizeInput($_POST['direccion'] ?? ''),
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            // Verificar si el email cambió y si ya existe
            $currentUser = $this->userModel->getUserById($id, true); // Incluir inactivos
            if ($currentUser && $currentUser['email'] !== $data['email']) {
                if ($this->userModel->emailExists($data['email'])) {
                    $_SESSION['error_message'] = 'El email ya está registrado';
                    redirect('admin/users');
                }
            }

            if ($this->userModel->updateUser($id, $data)) {
                $_SESSION['success_message'] = 'Usuario actualizado exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar usuario';
            }
            
            redirect('admin/users');
        } else {
            // Si es una petición AJAX para obtener datos del usuario
            if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
                header('Content-Type: application/json');
                $user = $this->userModel->getUserById($id, true); // Incluir inactivos
                if ($user) {
                    // No devolver la contraseña
                    unset($user['password']);
                    echo json_encode($user);
                } else {
                    echo json_encode(['error' => 'Usuario no encontrado']);
                }
                exit;
            }
            // Si no es AJAX, redirigir a la lista de usuarios
            redirect('admin/users');
        }
    }

    public function orders() {
        $filteredUser = null;
        // Si hay un filtro por usuario, obtener solo esos pedidos
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $userId = (int)$_GET['user_id'];
            $userOrders = $this->orderModel->getUserOrders($userId);
            // Formatear para que tenga la misma estructura que getAllOrders
            $filteredUser = $this->userModel->getUserById($userId);
            $orders = [];
            foreach ($userOrders as $order) {
                $order['cliente_nombre'] = $filteredUser['nombre'] ?? 'N/A';
                $order['cliente_email'] = $filteredUser['email'] ?? 'N/A';
                $orders[] = $order;
            }
        } else {
            $orders = $this->orderModel->getAllOrders();
        }
        include 'views/admin/orders.php';
    }

    public function viewOrderDetails($id) {
        // Si es una petición AJAX para obtener datos del pedido
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            header('Content-Type: application/json');
            $order = $this->orderModel->getOrderById($id);
            if ($order) {
                $order['items'] = $this->orderModel->getOrderItems($id);
                echo json_encode($order);
            } else {
                echo json_encode(['error' => 'Pedido no encontrado']);
            }
            exit;
        }
        // Si no es AJAX, redirigir a la lista de pedidos
        redirect('admin/orders');
    }

    public function printOrder($id) {
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            $_SESSION['error_message'] = 'Pedido no encontrado';
            redirect('admin/orders');
        }
        
        $order['items'] = $this->orderModel->getOrderItems($id);
        include 'views/admin/print-order.php';
    }

    public function promotions() {
        $promotions = $this->promotionModel->getAllPromotions();
        include 'views/admin/promotions.php';
    }

    public function contacts() {
        $contacts = $this->contactModel->getAllContacts();
        include 'views/admin/contacts.php';
    }

    private function handleAddProduct() {
        $data = [
            'nombre' => sanitizeInput($_POST['nombre']),
            'descripcion' => sanitizeInput($_POST['descripcion'] ?? ''),
            'precio' => (float)$_POST['precio'],
            'categoria_id' => (int)$_POST['categoria_id'],
            'stock' => (int)$_POST['stock'],
            'es_personalizable' => isset($_POST['es_personalizable']) ? 1 : 0,
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'imagen' => 'default.jpg' // Valor por defecto
        ];

        // Manejar la carga de imagen si existe
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $uploadDir = 'assets/images/products/';
            $fileName = time() . '_' . $_FILES['imagen']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                $data['imagen'] = $fileName;
            }
        }

        if ($this->productModel->createProduct($data)) {
            $_SESSION['success_message'] = 'Producto agregado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al agregar producto';
        }
        
        redirect('admin/products');
    }

    private function handleEditProduct($id) {
        $product = $this->productModel->getProductById($id, true); // Incluir inactivos en admin
        if (!$product) {
            $_SESSION['error_message'] = 'Producto no encontrado';
            redirect('admin/products');
        }
        
        $data = [
            'nombre' => sanitizeInput($_POST['nombre']),
            'descripcion' => sanitizeInput($_POST['descripcion'] ?? ''),
            'precio' => (float)$_POST['precio'],
            'categoria_id' => (int)$_POST['categoria_id'],
            'stock' => (int)$_POST['stock'],
            'es_personalizable' => isset($_POST['es_personalizable']) ? 1 : 0,
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'activo' => isset($_POST['activo']) ? 1 : 0,
            'imagen' => $product['imagen'] // Mantener imagen actual si no se sube nueva
        ];

        // Manejar la carga de imagen si existe
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $uploadDir = 'assets/images/products/';
            $fileName = time() . '_' . $_FILES['imagen']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                $data['imagen'] = $fileName;
            }
        }

        if ($this->productModel->updateProduct($id, $data)) {
            $_SESSION['success_message'] = 'Producto actualizado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al actualizar producto';
        }
        
        redirect('admin/products');
    }

    public function deleteProduct($id) {
        if ($this->productModel->deleteProduct($id)) {
            $_SESSION['success_message'] = 'Producto eliminado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar producto';
        }
        redirect('admin/products');
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => sanitizeInput($_POST['nombre']),
                'descripcion' => sanitizeInput($_POST['descripcion'] ?? ''),
                'imagen' => null
            ];

            if ($this->categoryModel->createCategory($data)) {
                $_SESSION['success_message'] = 'Categoría agregada exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al agregar categoría';
            }
            
            redirect('admin/categories');
        } else {
            include 'views/admin/add-category.php';
        }
    }

    public function editCategory($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => sanitizeInput($_POST['nombre']),
                'descripcion' => sanitizeInput($_POST['descripcion'] ?? ''),
                'imagen' => null
            ];

            if ($this->categoryModel->updateCategory($id, $data)) {
                $_SESSION['success_message'] = 'Categoría actualizada exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar categoría';
            }
            
            redirect('admin/categories');
        } else {
            // Si es una petición AJAX para obtener datos de la categoría
            if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
                header('Content-Type: application/json');
                $category = $this->categoryModel->getCategoryById($id);
                if ($category) {
                    echo json_encode($category);
                } else {
                    echo json_encode(['error' => 'Categoría no encontrada']);
                }
                exit;
            }
            // Si no es AJAX, redirigir a la lista de categorías
            redirect('admin/categories');
        }
    }

    public function deleteCategory($id) {
        if ($this->categoryModel->deleteCategory($id)) {
            $_SESSION['success_message'] = 'Categoría eliminada exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar categoría. Asegúrate de que no tenga productos asociados.';
        }
        redirect('admin/categories');
    }

    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => sanitizeInput($_POST['nombre']),
                'email' => sanitizeInput($_POST['email']),
                'telefono' => sanitizeInput($_POST['telefono'] ?? ''),
                'password' => $_POST['password'],
                'direccion' => sanitizeInput($_POST['direccion'] ?? ''),
                'tipo' => sanitizeInput($_POST['tipo'] ?? 'cliente')
            ];

            if ($this->userModel->emailExists($data['email'])) {
                $_SESSION['error_message'] = 'El email ya está registrado';
                redirect('admin/users');
            }

            if ($this->userModel->register($data)) {
                $_SESSION['success_message'] = 'Usuario agregado exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al agregar usuario';
            }
            
            redirect('admin/users');
        }
    }

    public function toggleUserStatus($id) {
        if ($this->userModel->toggleUserStatus($id)) {
            $_SESSION['success_message'] = 'Estado del usuario actualizado';
        } else {
            $_SESSION['error_message'] = 'Error al actualizar estado';
        }
        redirect('admin/users');
    }

    public function updateOrderStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estado'])) {
            $status = sanitizeInput($_POST['estado']);
            if ($this->orderModel->updateOrderStatus($id, $status)) {
                $_SESSION['success_message'] = 'Estado del pedido actualizado';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar estado';
            }
        }
        redirect('admin/orders');
    }

    public function addPromotion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => sanitizeInput($_POST['nombre']),
                'descripcion' => sanitizeInput($_POST['descripcion'] ?? ''),
                'tipo' => sanitizeInput($_POST['tipo']),
                'valor_descuento' => (float)$_POST['valor_descuento'],
                'codigo' => sanitizeInput($_POST['codigo']),
                'condicion_minima' => !empty($_POST['condicion_minima']) ? (float)$_POST['condicion_minima'] : 0,
                'fecha_inicio' => !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null,
                'fecha_fin' => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null
            ];

            if ($this->promotionModel->createPromotion($data)) {
                $_SESSION['success_message'] = 'Promoción creada exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al crear promoción';
            }
            
            redirect('admin/promotions');
        }
    }

    public function editPromotion($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => sanitizeInput($_POST['nombre']),
                'descripcion' => sanitizeInput($_POST['descripcion'] ?? ''),
                'tipo' => sanitizeInput($_POST['tipo']),
                'valor_descuento' => (float)$_POST['valor_descuento'],
                'codigo' => sanitizeInput($_POST['codigo']),
                'condicion_minima' => !empty($_POST['condicion_minima']) ? (float)$_POST['condicion_minima'] : 0,
                'fecha_inicio' => !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null,
                'fecha_fin' => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null
            ];

            if ($this->promotionModel->updatePromotion($id, $data)) {
                $_SESSION['success_message'] = 'Promoción actualizada exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar promoción';
            }
            
            redirect('admin/promotions');
        } else {
            // Si es una petición AJAX para obtener datos de la promoción
            if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
                header('Content-Type: application/json');
                $promotion = $this->promotionModel->getPromotionById($id);
                if ($promotion) {
                    echo json_encode($promotion);
                } else {
                    echo json_encode(['error' => 'Promoción no encontrada']);
                }
                exit;
            }
            // Si no es AJAX, redirigir a la lista de promociones
            redirect('admin/promotions');
        }
    }

    public function togglePromotionStatus($id) {
        if ($this->promotionModel->togglePromotionStatus($id)) {
            $_SESSION['success_message'] = 'Estado de la promoción actualizado';
        } else {
            $_SESSION['error_message'] = 'Error al actualizar estado';
        }
        redirect('admin/promotions');
    }

    public function deletePromotion($id) {
        // En lugar de eliminar, desactivamos
        if ($this->promotionModel->togglePromotionStatus($id)) {
            $_SESSION['success_message'] = 'Promoción desactivada';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar promoción';
        }
        redirect('admin/promotions');
    }

    public function markContactAsRead($id) {
        if ($this->contactModel->markAsRead($id)) {
            $_SESSION['success_message'] = 'Contacto marcado como leído';
        } else {
            $_SESSION['error_message'] = 'Error al marcar contacto';
        }
        redirect('admin/contacts');
    }

    public function deleteContact($id) {
        if ($this->contactModel->deleteContact($id)) {
            $_SESSION['success_message'] = 'Contacto eliminado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar contacto';
        }
        redirect('admin/contacts');
    }
}
?>
