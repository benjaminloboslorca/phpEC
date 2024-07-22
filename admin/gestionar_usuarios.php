<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un administrador
if(!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'admin'){
    header("location: ../login/login.php");
    exit;
}

// Incluir el archivo de configuración de la base de datos
require_once "../db.php";

// Obtener la lista de usuarios
$sql = "SELECT id, nombre, correo, rol FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="styleAdmin.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 800px; padding: 20px; }
        table { width: 100%; }
        table, th, td { border: 1px solid #ddd; border-collapse: collapse; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        .actions { display: flex; }
        .actions a { margin-right: 10px; }
    </style>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Gestionar Usuarios</h2>
        <p><a href="crear_usuario.php">Agregar Nuevo Usuario</a></p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["nombre"] . "</td>";
                        echo "<td>" . $row["correo"] . "</td>";
                        echo "<td>" . $row["rol"] . "</td>";
                        echo "<td class='actions'>";
                        echo "<a href='editar_usuario.php?id=" . $row["id"] . "'>Editar</a>";
                        echo "<a href='eliminar_usuario.php?id=" . $row["id"] . "' onclick=\"return confirm('¿Estás seguro de eliminar este usuario?');\">Eliminar</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No se encontraron usuarios.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
