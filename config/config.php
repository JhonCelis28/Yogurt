<?php
// Configuración general del sitio
define('SITE_NAME', 'Yogurt Artesanal San Francisco');

// Detectar la URL base automáticamente
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = dirname($scriptName);

if ($basePath === '/') {
    $basePath = '';
}

define('SITE_URL', $protocol . '://' . $host . $basePath . '/');
define('ADMIN_EMAIL', 'admin@yogurtsanfrancisco.com');

// Configuración de redes sociales
define('WHATSAPP_NUMBER', '573001234567');
define('FACEBOOK_URL', 'https://facebook.com/yogurtsanfrancisco');
define('INSTAGRAM_URL', 'https://instagram.com/yogurtsanfrancisco');
define('TWITTER_URL', 'https://twitter.com/yogurtsanfrancisco');

// Configuración de archivos
define('UPLOAD_PATH', 'assets/images/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Configuración de email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'tu-email@gmail.com');
define('SMTP_PASSWORD', 'tu-password');

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Funciones auxiliares
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function redirect($url) {
    if (empty($url)) {
        header('Location: ' . SITE_URL);
    } else {
        header('Location: ' . SITE_URL . $url);
    }
    exit();
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function formatPrice($price) {
    return '$' . number_format($price, 0, ',', '.');
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

function showMessage() {
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        echo $_SESSION['success_message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
        unset($_SESSION['success_message']);
    }
    
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo $_SESSION['error_message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
        unset($_SESSION['error_message']);
    }
}

function contactWhatsApp($message = '') {
    $defaultMessage = 'Hola, me interesa conocer más sobre sus productos artesanales.';
    $finalMessage = !empty($message) ? $message : $defaultMessage;
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . urlencode($finalMessage);
}

function generateOrderNumber() {
    return 'YSF-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

function uploadImage($file, $directory = 'products') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $uploadPath = UPLOAD_PATH . $directory . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $filename;
    }
    
    return false;
}

function deleteImage($filename, $directory = 'products') {
    $filePath = UPLOAD_PATH . $directory . '/' . $filename;
    if (file_exists($filePath)) {
        return unlink($filePath);
    }
    return false;
}

function getCartItemCount() {
    if (!isLoggedIn()) {
        return 0;
    }
    
    require_once 'models/Cart.php';
    $cartModel = new Cart();
    return $cartModel->getCartItemCount($_SESSION['user_id']);
}

function sendEmail($to, $subject, $message, $isHTML = true) {
    // Implementar envío de email usando PHPMailer o similar
    // Por ahora retornamos true para simular envío exitoso
    return true;
}
?>
