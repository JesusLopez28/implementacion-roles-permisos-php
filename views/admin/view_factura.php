<?php
require_once '../../middleware/Auth.php';
require_once '../../controllers/FacturaController.php';

// Verificar que sea administrador
Auth::checkRole('Administrador');

// Verificar que se haya proporcionado un ID
if(!isset($_GET['id'])) {
    header("Location: facturas.php");
    exit();
}

$facturaController = new FacturaController();
$factura = $facturaController->show($_GET['id']);

// Si la factura no existe, redirigir
if(!$factura) {
    header("Location: facturas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Factura - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Facturas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Bienvenido, <?php echo $_SESSION['user_name']; ?> (Administrador)</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../controllers/AuthController.php?action=logout">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col">
                <a href="facturas.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detalle de Factura #<?php echo $factura['id']; ?></h4>
                    <div>
                        <a href="edit_factura.php?id=<?php echo $factura['id']; ?>" class="btn btn-light">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> <?php echo $factura['fecha']; ?></p>
                        <p><strong>Monto:</strong> $<?php echo number_format($factura['monto'], 2); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>ID de Factura:</strong> <?php echo $factura['id']; ?></p>
                        <p><strong>Cliente:</strong> <?php echo $factura['usuario']; ?></p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h5>Descripción:</h5>
                    <div class="p-3 bg-light border rounded">
                        <?php echo nl2br(htmlspecialchars($factura['descripcion'])); ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="edit_factura.php?id=<?php echo $factura['id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar factura
                    </a>
                    <a href="facturas.php?delete=<?php echo $factura['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta factura?')">
                        <i class="bi bi-trash"></i> Eliminar factura
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
