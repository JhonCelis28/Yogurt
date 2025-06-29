<?php
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Contact.php';
require_once 'config/config.php';

class HomeController {
    private $productModel;
    private $categoryModel;
    private $contactModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->contactModel = new Contact();
    }

    public function index() {
        $categories = $this->categoryModel->getAllCategories();
        $featuredProducts = $this->productModel->getFeaturedProducts(6);
        include 'views/home/index.php';
    }

    public function about() {
        include 'views/home/about.php';
    }

    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContact();
        } else {
            include 'views/home/contact.php';
        }
    }

    private function handleContact() {
        $data = [
            'nombre' => sanitizeInput($_POST['nombre']),
            'email' => sanitizeInput($_POST['email']),
            'telefono' => sanitizeInput($_POST['telefono']),
            'mensaje' => sanitizeInput($_POST['mensaje'])
        ];

        if ($this->contactModel->saveContact($data)) {
            $_SESSION['success_message'] = 'Mensaje enviado exitosamente. Te contactaremos pronto.';
        } else {
            $_SESSION['error_message'] = 'Error al enviar el mensaje. Inténtalo de nuevo.';
        }

        redirect('');
    }
}
?>
