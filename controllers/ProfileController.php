<?php
require_once 'models/User.php';
require_once 'models/Order.php';
require_once 'config/config.php';

class ProfileController {
    private $userModel;
    private $orderModel;

    public function __construct() {
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        $this->userModel = new User();
        $this->orderModel = new Order();
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        // Obtener estadísticas reales
        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        $totalOrders = count($orders);
        
        // Obtener envases devueltos del usuario
        $envasesDevueltos = $user['envases_devueltos'] ?? 0;
        
        include 'views/profile/index.php';
    }

    public function orders() {
        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        
        // Obtener los items de cada pedido
        $ordersWithItems = [];
        foreach ($orders as $order) {
            $order['items'] = $this->orderModel->getOrderItems($order['id']);
            $ordersWithItems[] = $order;
        }
        
        $orders = $ordersWithItems;
        include 'views/profile/orders.php';
    }

    public function addresses() {
        $addresses = $this->userModel->getUserAddresses($_SESSION['user_id']);
        include 'views/profile/addresses.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => sanitizeInput($_POST['nombre']),
                'telefono' => sanitizeInput($_POST['telefono']),
                'direccion' => sanitizeInput($_POST['direccion'])
            ];

            if ($this->userModel->updateUser($_SESSION['user_id'], $data)) {
                $_SESSION['user_name'] = $data['nombre'];
                $_SESSION['success_message'] = 'Perfil actualizado exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar perfil';
            }
        }
        
        redirect('profile');
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error_message'] = 'Las contraseñas no coinciden';
                redirect('profile');
            }

            $user = $this->userModel->getUserById($_SESSION['user_id']);
            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['error_message'] = 'Contraseña actual incorrecta';
                redirect('profile');
            }

            if ($this->userModel->changePassword($_SESSION['user_id'], $newPassword)) {
                $_SESSION['success_message'] = 'Contraseña cambiada exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al cambiar contraseña';
            }
        }
        
        redirect('profile');
    }

    public function printOrder($id) {
        $order = $this->orderModel->getOrderById($id);
        
        // Verificar que el pedido pertenece al usuario actual
        if (!$order || $order['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Pedido no encontrado o no tienes permiso para verlo';
            redirect('profile/orders');
        }
        
        // Obtener información del usuario para mostrar en la factura
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $order['cliente_nombre'] = $user['nombre'] ?? 'N/A';
        $order['cliente_email'] = $user['email'] ?? 'N/A';
        
        // Obtener los items del pedido
        $order['items'] = $this->orderModel->getOrderItems($id);
        
        include 'views/profile/print-order.php';
    }
}
?>
