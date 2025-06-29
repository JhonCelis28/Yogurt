<?php
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'config/config.php';

class ProductController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index() {
        $products = $this->productModel->getAllProducts();
        $categories = $this->categoryModel->getAllCategories();
        include 'views/products/index.php';
    }

    public function show($id) {
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            redirect('products');
        }

        include 'views/products/show.php';
    }

    public function category($categoryId) {
        $category = $this->categoryModel->getCategoryById($categoryId);
        $products = $this->productModel->getProductsByCategory($categoryId);
        $categories = $this->categoryModel->getAllCategories();
        
        if (!$category) {
            redirect('products');
        }

        include 'views/products/category.php';
    }

    public function personalized() {
        include 'views/products/personalized.php';
    }

    public function search() {
        $searchTerm = sanitizeInput($_GET['q'] ?? '');
        $products = [];
        $categories = $this->categoryModel->getAllCategories();
        
        if (!empty($searchTerm)) {
            $products = $this->productModel->searchProducts($searchTerm);
        }

        include 'views/products/search.php';
    }
}
?>
