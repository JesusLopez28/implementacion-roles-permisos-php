<?php
require_once '../../middleware/Auth.php';
require_once '../../controllers/FacturaController.php';
require_once '../../config/database.php';

// Verificar que sea administrador
Auth::checkRole('Administrador');

// Verificar que se haya proporcionado un ID
if(!isset($_GET['id'])) {
    header("Location: facturas.php");
    exit();
}

$id = $_GET['id'];
$facturaController = new FacturaController();
$factura = $facturaController->show($id);

// Si la factura no existe, redirigir
if(!$factura) {
    header("Location: facturas.php");
    exit();
}

// Procesar el formulario cuando se envía
if(isset($_POST['update'])) {
    $data = [
        'descripcion' => $_POST['descripcion'],
        'monto' => $_POST['monto'],
        'fecha' => $_POST['fecha']
    ];
    
    if($facturaController->update($id, $data)) {
        header("Location: facturas.php?success=updated");
        exit();
    }
}

// Obtener lista de usuarios para el selector
$database = new Database();
$conn = $database->getConnection();
$query = "SELECT id, name FROM users";
$stmt = $conn->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Factura - Administrador</title>
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
                <h4 class="mb-0">Editar Factura #<?php echo $factura['id']; ?></h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Cliente</label>
                        <select class="form-select" id="usuario" disabled>
                            <?php foreach($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>" <?php if($user['name'] == $factura['usuario']) echo 'selected'; ?>>
                                    <?php echo $user['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted">
                            Para cambiar el cliente, cree una nueva factura y elimine esta. 
                            No se puede cambiar el propietario de una factura existente.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $factura['descripcion']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" id="monto" name="monto" value="<?php echo $factura['monto']; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $factura['fecha']; ?>" required>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="view_factura.php?id=<?php echo $id; ?>" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" name="update" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
