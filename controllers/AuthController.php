<?php
require_once 'models/User.php';
require_once 'config/config.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            include 'views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister();
        } else {
            include 'views/auth/register.php';
        }
    }

    public function logout() {
        session_destroy();
        redirect('');
    }

    private function handleLogin() {
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];

        $user = $this->userModel->login($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['tipo'];

            if ($user['tipo'] === 'admin') {
                redirect('admin');
            } else {
                redirect('');
            }
        } else {
            $_SESSION['error_message'] = 'Email o contraseña incorrectos';
            redirect('login');
        }
    }

    private function handleRegister() {
        $data = [
            'nombre' => sanitizeInput($_POST['nombre']),
            'email' => sanitizeInput($_POST['email']),
            'telefono' => sanitizeInput($_POST['telefono']),
            'password' => $_POST['password'],
            'direccion' => sanitizeInput($_POST['direccion'])
        ];

        // Validaciones
        if (empty($data['nombre']) || empty($data['email']) || empty($data['password'])) {
            $_SESSION['error_message'] = 'Todos los campos obligatorios deben ser completados';
            redirect('register');
        }

        if ($this->userModel->emailExists($data['email'])) {
            $_SESSION['error_message'] = 'El email ya está registrado';
            redirect('register');
        }

        if ($this->userModel->register($data)) {
            $_SESSION['success_message'] = 'Registro exitoso. Ahora puedes iniciar sesión';
            redirect('login');
        } else {
            $_SESSION['error_message'] = 'Error al registrar usuario';
            redirect('register');
        }
    }
}
?>
