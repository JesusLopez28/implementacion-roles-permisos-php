<?php
require_once '../../middleware/Auth.php';
require_once '../../controllers/FacturaController.php';

// Verificar que sea empleado
Auth::checkRole('Empleado');

$facturaController = new FacturaController();
$facturas = $facturaController->index();

// Procesar eliminación
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    if($facturaController->destroy($id)) {
        header("Location: facturas.php?success=deleted");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Facturas - Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Facturas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Bienvenido, <?php echo $_SESSION['user_name']; ?> (Empleado)</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../controllers/AuthController.php?action=logout">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php 
                    if($_GET['success'] == 'updated') echo "Factura actualizada exitosamente.";
                    if($_GET['success'] == 'deleted') echo "Factura eliminada exitosamente.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de Facturas</h2>
            <div class="text-muted">
                <em>Nota: Como empleado, puede ver, editar y eliminar facturas, pero no crear nuevas.</em>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($facturas as $factura): ?>
                            <tr>
                                <td><?php echo $factura['id']; ?></td>
                                <td><?php echo $factura['usuario']; ?></td>
                                <td><?php echo $factura['descripcion']; ?></td>
                                <td>$<?php echo number_format($factura['monto'], 2); ?></td>
                                <td><?php echo $factura['fecha']; ?></td>
                                <td>
                                    <a href="edit_factura.php?id=<?php echo $factura['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?delete=<?php echo $factura['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta factura?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
