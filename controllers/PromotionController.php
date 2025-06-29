<?php
require_once 'models/Promotion.php';
require_once 'config/config.php';

class PromotionController {
    private $promotionModel;

    public function __construct() {
        $this->promotionModel = new Promotion();
    }

    public function index() {
        $promotions = $this->promotionModel->getActivePromotions();
        include 'views/promotions/index.php';
    }
}
?>
