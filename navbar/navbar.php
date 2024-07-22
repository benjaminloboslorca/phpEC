<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Navbar</title>
    
</head>
<body>
    <nav>
        <ul>
            <li><a href="/index.php">Inicio</a></li>
            <li><a href="/catalogo/catalogo.php">Catalogo</a></li>
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <li><a href="/login/logout.php">Cerrar sesión</a></li>
                <?php if($_SESSION["rol"] == 'admin'): ?>
                    <li><a href="/admin/admin.php">Admin</a></li>
                <?php elseif($_SESSION["rol"] == 'contador'): ?>
                    <li><a href="/contador/contador.php">Contador</a></li>
                <?php elseif($_SESSION["rol"] == 'vendedor'): ?>
                    <li><a href="/vendedor/vendedor.php">Vendedor</a></li>
                <?php elseif($_SESSION["rol"] == 'bodeguero'): ?>
                    <li><a href="/bodeguero/bodeguero.php">Bodeguero</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="/login/login.php">Iniciar sesión</a></li>
                <li><a href="/login/registro.php">Registrarse</a></li>
            <?php endif; ?>
            <li><a href="/carrito/carrito.php">Carrito</a></li>
        </ul>
    </nav>
</body>
</html>
