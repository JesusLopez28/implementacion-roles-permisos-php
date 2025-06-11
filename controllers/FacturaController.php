<?php
require_once __DIR__ . '/../models/Factura.php';
require_once __DIR__ . '/../middleware/Auth.php';

class FacturaController {
    private $factura;
    
    public function __construct() {
        $this->factura = new Factura();
    }
    
    public function index() {
        Auth::check();
        
        $user = Auth::user();
        
        if($user->hasRole('Administrador') || $user->hasRole('Empleado')) {
            return $this->factura->getAll();
        } else {
            // Cliente: solo ve sus propias facturas
            return $this->factura->getUserFacturas($user->id);
        }
    }
    
    public function show($id) {
        Auth::check();
        Auth::checkPermission('read');
        
        $factura = $this->factura->getOne($id);
        $user = Auth::user();
        
        // Verificar si es cliente y está intentando ver factura ajena
        if($user->hasRole('Cliente') && $factura['user_id'] != $user->id) {
            header("Location: /views/unauthorized.php");
            exit();
        }
        
        return $factura;
    }
    
    public function store($data) {
        Auth::check();
        Auth::checkPermission('create');
        
        $this->factura->user_id = $data['user_id'];
        $this->factura->descripcion = $data['descripcion'];
        $this->factura->monto = $data['monto'];
        $this->factura->fecha = $data['fecha'];
        
        if($this->factura->create()) {
            return true;
        }
        return false;
    }
    
    public function update($id, $data) {
        Auth::check();
        Auth::checkPermission('update');
        
        $factura = $this->factura->getOne($id);
        $user = Auth::user();
        
        // Verificar si es cliente y está intentando modificar factura ajena
        if($user->hasRole('Cliente') && $factura['user_id'] != $user->id) {
            header("Location: /views/unauthorized.php");
            exit();
        }
        
        $this->factura->id = $id;
        $this->factura->descripcion = $data['descripcion'];
        $this->factura->monto = $data['monto'];
        $this->factura->fecha = $data['fecha'];
        
        if($this->factura->update()) {
            return true;
        }
        return false;
    }
    
    public function destroy($id) {
        Auth::check();
        Auth::checkPermission('delete');
        
        $factura = $this->factura->getOne($id);
        $user = Auth::user();
        
        // Verificar si es cliente y está intentando eliminar factura ajena
        if($user->hasRole('Cliente') && $factura['user_id'] != $user->id) {
            header("Location: /views/unauthorized.php");
            exit();
        }
        
        $this->factura->id = $id;
        
        if($this->factura->delete()) {
            return true;
        }
        return false;
    }
}
?>
