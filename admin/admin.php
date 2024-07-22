<?php
session_start();

// Verificar si el usuario tiene el rol de administrador
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== 'admin') {
    // Redirigir al usuario a la página de inicio si no es administrador
    header("location: /index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="styleAdmin.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="admin-wrapper">
        <h2>Bienvenido al Panel de Administración</h2>
        <p>Aquí puedes gestionar usuarios y otros aspectos del sitio.</p>
        
        <!-- Agregar opciones administrativas -->
        <div class="admin-options">
            <ul>
                <li><a href="gestionar_usuarios.php">Gestionar Usuarios</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
