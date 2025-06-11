<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;
    
    public function __construct() {
        $this->user = new User();
    }
    
    public function login($email, $password) {
        if($this->user->login($email, $password)) {
            session_start();
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['user_name'] = $this->user->name;
            $_SESSION['user_email'] = $this->user->email;
            
            $roles = $this->user->getRoles();
            $_SESSION['user_roles'] = $roles;
            
            // Redireccionar según el rol del usuario
            if($this->user->hasRole('Administrador')) {
                header("Location: ../views/admin/facturas.php");
            } else if($this->user->hasRole('Empleado')) {
                header("Location: ../views/empleado/facturas.php");
            } else if($this->user->hasRole('Cliente')) {
                header("Location: ../views/cliente/facturas.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            return false;
        }
    }
    
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../views/auth/login.php");
        exit();
    }
}

// Procesamiento del formulario de login
if(isset($_POST['login'])) {
    $auth = new AuthController();
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if(!$auth->login($email, $password)) {
        $_SESSION['error'] = "Credenciales incorrectas";
        header("Location: ../views/auth/login.php");
        exit();
    }
}

// Procesamiento de logout
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    $auth = new AuthController();
    $auth->logout();
}
?>
