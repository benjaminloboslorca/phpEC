<?php
session_start();

// Verificar si el usuario tiene el rol de vendedor
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== 'vendedor') {
    // Redirigir al usuario a la página de inicio si no es vendedor
    header("location: /index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Vendedor</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="vendedorStyle.css"> <!-- Cambiar a tu propio archivo CSS -->
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="vendedor-wrapper">
        <h2>Bienvenido al Panel de Vendedor</h2>
        <p>Aquí puedes gestionar tus ventas y otros aspectos relacionados.</p>
        
        <!-- Agregar opciones para el rol de vendedor -->
        <div class="vendedor-options">
            <ul>
                <li><a href="historial_compras.php">Historial de Compras</a></li> <!-- Enlace al historial de compras -->
            </ul>
        </div>
    </div>
</body>
</html>
