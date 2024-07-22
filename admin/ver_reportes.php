<?php
// Iniciar la sesión si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Ver Reportes</title>
    <link rel="stylesheet" href="styleAdmin.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="admin-wrapper">
        <h2>Ver Reportes</h2>
        <!-- Aquí puedes agregar la lógica para mostrar los reportes -->
    </div>
</body>
</html>
