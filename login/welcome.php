<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario redirige al login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=\index.php">
    <title>Bienvenido</title>
    <style>
        body { font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["nombre"]); ?></b>. Bienvenido a nuestra página.</h1>
    </div>
    <p>Serás redirigido a la página principal en unos segundos...</p>
</body>
</html>
