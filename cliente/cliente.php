<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un cliente
if(!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'cliente'){
    header("location: ../login/login.php");
    exit;
}

// Incluir el archivo de configuración de la base de datos
require_once "../db.php";

// Aquí puedes agregar la funcionalidad específica del cliente
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Cliente</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 800px; padding: 20px; margin: auto; }
    </style>
</head>
<body>
<?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Panel de Cliente</h2>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?>. Aquí puedes gestionar tus pedidos.</p>
        
        <!-- Aquí puedes agregar la interfaz de usuario específica para el cliente -->
    </div>
</body>
</html>
