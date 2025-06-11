<?php
session_start();

// Redirigir al login si no hay sesión
if(!isset($_SESSION['user_id'])) {
    header("Location: views/auth/login.php");
    exit();
}

// Redirigir según el rol
require_once 'models/User.php';
$user = new User();
$user->id = $_SESSION['user_id'];

if($user->hasRole('Administrador')) {
    header("Location: views/admin/facturas.php");
} else if($user->hasRole('Empleado')) {
    header("Location: views/empleado/facturas.php");
} else if($user->hasRole('Cliente')) {
    header("Location: views/cliente/facturas.php");
} else {
    // Si no tiene un rol específico, mostrar mensaje y cerrar sesión
    session_unset();
    session_destroy();
    header("Location: views/auth/login.php?error=norole");
}
?>
