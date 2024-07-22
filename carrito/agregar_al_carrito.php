<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Incluir archivo de conexión a la base de datos
require '../db.php';

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $usuario_id = $_SESSION['id'];

    // Verificar si existe un carrito activo para el usuario
    $sql = "SELECT id FROM carritos WHERE usuario_id = ? AND estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($carrito_id);
        $stmt->fetch();
    } else {
        // Crear un nuevo carrito si no existe uno activo
        $sql = "INSERT INTO carritos (usuario_id, estado) VALUES (?, 'activo')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $carrito_id = $stmt->insert_id;
    }
    $stmt->close();

    // Verificar si el producto ya está en el carrito
    $sql = "SELECT id, cantidad FROM carrito_productos WHERE carrito_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $carrito_id, $producto_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si el producto ya está en el carrito, actualizar la cantidad
        $stmt->bind_result($item_id, $cantidad_actual);
        $stmt->fetch();

        $cantidad_actual += $cantidad;

        $sql_update = "UPDATE carrito_productos SET cantidad = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $cantidad_actual, $item_id);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Si el producto no está en el carrito, agregarlo
        $sql_insert = "INSERT INTO carrito_productos (carrito_id, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $carrito_id, $producto_id, $cantidad);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $conn->close();
    header("Location: ../catalogo/catalogo.php");
    exit();
} else {
    // Redirigir si no se recibieron datos válidos
    header("Location: ../catalogo/catalogo.php");
    exit();
}
?>
