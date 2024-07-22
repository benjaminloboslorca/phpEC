<?php
session_start();

// Verificar si el usuario tiene el rol de vendedor
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== 'vendedor') {
    header("location: /index.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
require '../db.php';

// Obtener todos los pedidos
$sql = "SELECT hc.id AS historial_id, hc.usuario_id, hc.carrito_id, hc.producto_nombre, hc.cantidad, hc.precio, hc.total, c.fecha_creacion
        FROM historial_compras hc
        INNER JOIN carritos c ON hc.carrito_id = c.id
        WHERE c.estado = 'completado'
        ORDER BY c.fecha_creacion DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Vendedor</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="historialStyle.css">
    <link rel="stylesheet" href="vendedorStyle.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="vendedor-wrapper">
        <h2>Panel del Vendedor</h2>
        <p>Aquí puedes gestionar los pedidos y pagos.</p>

        <!-- Tabla de pedidos -->
        <div class="pedido-list">
            <h3>Historial de Pedidos</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>ID Usuario</th>
                        <th>Carrito ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                        <th>Fecha de Compra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['historial_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['usuario_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['carrito_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['producto_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($row['precio'], 0, ',', '.')); ?></td>
                        <td><?php echo htmlspecialchars(number_format($row['total'], 0, ',', '.')); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_creacion']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Agregar más funcionalidades aquí si es necesario -->
    </div>
</body>
</html>

<?php
$conn->close();
?>
