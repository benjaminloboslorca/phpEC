<?php
session_start();
require '../db.php';

$nombre = $contrasena = "";
$nombre_err = $contrasena_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor, ingrese su nombre.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    if (empty(trim($_POST["contrasena"]))) {
        $contrasena_err = "Por favor, ingrese su contraseña.";
    } else {
        $contrasena = trim($_POST["contrasena"]);
    }

    if (empty($nombre_err) && empty($contrasena_err)) {
        $sql = "SELECT id, nombre, correo, contrasena, rol FROM usuarios WHERE nombre = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_nombre);
            $param_nombre = $nombre;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $nombre, $correo, $hashed_contrasena, $rol);
                    if ($stmt->fetch()) {
                        if (password_verify($contrasena, $hashed_contrasena)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["nombre"] = $nombre;
                            $_SESSION["correo"] = $correo; // Guarda el correo en la sesión
                            $_SESSION["rol"] = $rol;
                            $_SESSION["usuario_id"] = $id;

                            header("location: welcome.php");
                            exit;
                        } else {
                            $contrasena_err = "La contraseña que ingresaste no es válida.";
                        }
                    }
                } else {
                    $nombre_err = "No se encontró ninguna cuenta con ese nombre.";
                }
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Inicio de sesión</h2>
        <p>Por favor, complete este formulario para iniciar sesión.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>">
                <span class="help-block"><?php echo $nombre_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($contrasena_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control">
                <span class="help-block"><?php echo $contrasena_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Iniciar sesión">
            </div>
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate ahora</a>.</p>
        </form>
    </div>
</body>
</html>
