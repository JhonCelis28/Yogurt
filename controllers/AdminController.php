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
        $stats = [
            'productos' => count($this->productModel->getAllProducts()),
            'pedidos_hoy' => 12, // Valor de ejemplo
            'usuarios' => 156, // Valor de ejemplo
            'ventas_mes' => 2500000 // Valor de ejemplo
        ];
        
        include 'views/admin/index.php';
    }

    public function products() {
        $products = $this->productModel->getAllProducts();
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
            $product = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getAllCategories();
            include 'views/admin/edit-product.php';
        }
    }

    public function categories() {
        $categories = $this->categoryModel->getAllCategories();
        include 'views/admin/categories.php';
    }

    public function users() {
        $users = $this->userModel->getAllUsers();
        include 'views/admin/users.php';
    }

    public function orders() {
        $orders = $this->orderModel->getAllOrders();
        include 'views/admin/orders.php';
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
            'descripcion' => sanitizeInput($_POST['descripcion']),
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
        $data = [
            'nombre' => sanitizeInput($_POST['nombre']),
            'descripcion' => sanitizeInput($_POST['descripcion']),
            'precio' => (float)$_POST['precio'],
            'categoria_id' => (int)$_POST['categoria_id'],
            'stock' => (int)$_POST['stock'],
            'es_personalizable' => isset($_POST['es_personalizable']) ? 1 : 0,
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'imagen' => null
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
}
?>
