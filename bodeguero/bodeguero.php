<?php
session_start();

// Verificar si el usuario tiene el rol de bodeguero
if (!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'bodeguero') {
    header("location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bodeguero - Gestión de Productos</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="bodegueroStyle.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Gestión de Productos</h2>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?>.</p>
        <div class="options">
            <ul>
                <li><a href="gestionar_productos.php">Gestionar Productos</a></li>
                <li><a href="gestionar_categorias.php">Gestionar Categorías</a></li>
                <li><a href="ver_actualizar_inventario.php">Ver y Actualizar Inventario</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
