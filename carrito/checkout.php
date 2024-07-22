<?php
session_start();
require '../db.php';

$usuario_id = $_SESSION['usuario_id'];

// Obtener el carrito activo
$sql = "SELECT id FROM carritos WHERE usuario_id = ? AND estado = 'activo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($carrito_id);
$stmt->fetch();
$stmt->close();

if (isset($carrito_id)) {
    // Actualizar el estado del carrito a 'completado'
    $sql = "UPDATE carritos SET estado = 'completado' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carrito_id);
    $stmt->execute();
    $stmt->close();

    // Crear un nuevo carrito para el usuario
    $sql = "INSERT INTO carritos (usuario_id) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: carrito.php");
exit();
?>
