<?php
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario redirige al login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /login/login.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
require '../db.php';

// Obtener el historial de compras del usuario
$usuario_id = $_SESSION['id'];
$sql_historial = "SELECT c.id AS carrito_id, c.fecha_creacion, p.nombre, cp.cantidad, p.precio
                  FROM carritos c
                  INNER JOIN carrito_productos cp ON c.id = cp.carrito_id
                  INNER JOIN productos p ON cp.producto_id = p.id
                  WHERE c.usuario_id = ? AND c.estado = 'completado'
                  ORDER BY c.fecha_creacion DESC";
$stmt_historial = $conn->prepare($sql_historial);
$stmt_historial->bind_param("i", $usuario_id);
$stmt_historial->execute();
$result_historial = $stmt_historial->get_result();

$compras = [];
while ($compra = $result_historial->fetch_assoc()) {
    $compras[] = $compra;
}

$stmt_historial->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Compras</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="historialStyle.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <h1>Historial de Compras</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($compras)): ?>
                <tr>
                    <td colspan="5">No tienes compras registradas.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($compras as $compra): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($compra['fecha_creacion']); ?></td>
                        <td><?php echo htmlspecialchars($compra['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($compra['cantidad']); ?></td>
                        <td>$<?php echo number_format($compra['precio'], 0); ?></td>
                        <td>$<?php echo number_format($compra['precio'] * $compra['cantidad'], 0); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="/index.php">Volver al inicio</a>
</body>
</html>
