<?php
require_once __DIR__ . '/../models/User.php';

class Auth {
    public static function check() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if(!isset($_SESSION['user_id'])) {
            header("Location: ../views/auth/login.php");
            exit();
        }
        
        return true;
    }
    
    public static function checkRole($roleName) {
        self::check();
        
        $user = new User();
        $user->id = $_SESSION['user_id'];
        
        if(!$user->hasRole($roleName)) {
            header("Location: /implementacion-roles-permisos-php/views/unauthorized.php");
            exit();
        }
        
        return true;
    }
    
    public static function checkPermission($permissionName) {
        self::check();
        
        $user = new User();
        $user->id = $_SESSION['user_id'];
        
        if(!$user->hasPermission($permissionName)) {
            header("Location: /implementacion-roles-permisos-php/views/unauthorized.php");
            exit();
        }
        
        return true;
    }
    
    public static function user() {
        if(isset($_SESSION['user_id'])) {
            $user = new User();
            $user->id = $_SESSION['user_id'];
            $user->name = $_SESSION['user_name'];
            $user->email = $_SESSION['user_email'];
            return $user;
        }
        return null;
    }
}
?>
