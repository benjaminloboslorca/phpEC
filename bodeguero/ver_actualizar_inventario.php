<?php
session_start();
require '../db.php';

// Verificar si el usuario tiene el rol de bodeguero
if (!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'bodeguero') {
    header("location: ../login/login.php");
    exit;
}

// Manejar la actualización de stock
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "actualizar_stock") {
    $producto_id = isset($_POST["producto_id"]) ? $_POST["producto_id"] : "";
    $nuevo_stock = isset($_POST["nuevo_stock"]) ? $_POST["nuevo_stock"] : "";

    // Validar campos obligatorios
    if (empty($producto_id) || empty($nuevo_stock)) {
        $errores[] = "Todos los campos son obligatorios.";
    } else {
        // Actualizar stock
        $sql = "UPDATE productos SET stock = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ii", $nuevo_stock, $producto_id);
            if ($stmt->execute()) {
                $mensaje = "Stock actualizado exitosamente.";
            } else {
                $errores[] = "Error al actualizar el stock: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errores[] = "Error en la preparación de la consulta: " . $conn->error;
        }
    }
}

// Obtener productos del inventario
$productos = [];
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bodeguero - Ver y Actualizar Inventario</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="bodegueroStyle.css">
    <style>
        .table th, .table td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Ver y Actualizar Inventario</h2>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?>.</p>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success">
                <p><?php echo $mensaje; ?></p>
            </div>
        <?php endif; ?>

        <h3>Listado de Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto["id"]; ?></td>
                        <td><?php echo $producto["nombre"]; ?></td>
                        <td><?php echo $producto["stock"]; ?></td>
                        <td>
                            <form action="ver_actualizar_inventario.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="producto_id" value="<?php echo $producto["id"]; ?>">
                                <input type="number" name="nuevo_stock" placeholder="Nuevo Stock" required>
                                <input type="hidden" name="accion" value="actualizar_stock">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
