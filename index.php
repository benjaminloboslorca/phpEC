<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="navbar/styleNavbar.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <?php include 'navbar/navbar.php'; ?>
    
    <div class="content">
        <h1>Bienvenido</h1>
        <p>Contenido de tu página de inicio aquí.</p>
        
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <p>
                <a href="login/logout.php" class="btn btn-danger">Cerrar sesión</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
