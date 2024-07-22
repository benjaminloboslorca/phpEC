<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario redirige al login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
require '../db.php';

// Obtener el carrito activo del usuario
$usuario_id = $_SESSION['id'];
$sql_carrito = "SELECT id FROM carritos WHERE usuario_id = ? AND estado = 'activo'";
$stmt_carrito = $conn->prepare($sql_carrito);
$stmt_carrito->bind_param("i", $usuario_id);
$stmt_carrito->execute();
$stmt_carrito->bind_result($carrito_id);
$stmt_carrito->fetch();
$stmt_carrito->close();

// Obtener los productos del carrito actual
$sql_productos = "SELECT cp.id, cp.cantidad, p.id AS producto_id, p.nombre AS producto_nombre, p.stock, (p.precio * cp.cantidad) AS total_precio
                  FROM carrito_productos cp
                  INNER JOIN productos p ON cp.producto_id = p.id
                  WHERE cp.carrito_id = ?";
$stmt_productos = $conn->prepare($sql_productos);
$stmt_productos->bind_param("i", $carrito_id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

// Insertar cada producto en historial_compras
while ($producto = $result_productos->fetch_assoc()) {
    // Actualizar el stock del producto vendido
    $stock_actualizado = $producto['stock'] - $producto['cantidad'];
    $sql_update_stock = "UPDATE productos SET stock = ? WHERE id = ?";
    $stmt_update_stock = $conn->prepare($sql_update_stock);
    $stmt_update_stock->bind_param("ii", $stock_actualizado, $producto['producto_id']);
    $stmt_update_stock->execute();
    $stmt_update_stock->close();

    // Insertar en historial_compras
    $sql_historial = "INSERT INTO historial_compras (usuario_id, carrito_id, producto_nombre, cantidad, precio, total, fecha_compra) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt_historial = $conn->prepare($sql_historial);
    $precio_unitario = $producto['total_precio'] / $producto['cantidad'];
    $stmt_historial->bind_param("iisidd", $usuario_id, $carrito_id, $producto['producto_nombre'], $producto['cantidad'], $precio_unitario, $producto['total_precio']);
    $stmt_historial->execute();
    $stmt_historial->close();
}

// Eliminar todos los productos del carrito
$sql_eliminar_carrito = "DELETE FROM carrito_productos WHERE carrito_id = ?";
$stmt_eliminar_carrito = $conn->prepare($sql_eliminar_carrito);
$stmt_eliminar_carrito->bind_param("i", $carrito_id);
$stmt_eliminar_carrito->execute();
$stmt_eliminar_carrito->close();

// Marcar el carrito como completado
$sql_completar_carrito = "UPDATE carritos SET estado = 'completado' WHERE id = ?";
$stmt_completar_carrito = $conn->prepare($sql_completar_carrito);
$stmt_completar_carrito->bind_param("i", $carrito_id);
$stmt_completar_carrito->execute();
$stmt_completar_carrito->close();

$conn->close();

include 'Mail/mailApi.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=..\index.php">
    <title>Gracias</title>
    <style>
        body { font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Gracias, <b><?php echo htmlspecialchars($_SESSION["nombre"]); ?></b>. Esperamos disfrute nuestro producto.</h1>
    </div>
    <p>Serás redirigido a la página principal en unos segundos...</p>
</body>
</html>
