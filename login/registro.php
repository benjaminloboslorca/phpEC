<?php
require '..\db.php';

$nombre = $correo = $contrasena = $confirm_contrasena = "";
$nombre_err = $correo_err = $contrasena_err = $confirm_contrasena_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar nombre
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor, ingrese un nombre.";
    } else {
        $sql = "SELECT id FROM usuarios WHERE nombre = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_nombre);
            $param_nombre = trim($_POST["nombre"]);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $nombre_err = "Este nombre ya está en uso.";
                } else {
                    $nombre = trim($_POST["nombre"]);
                }
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            $stmt->close();
        }
    }

    // Validar correo
    if (empty(trim($_POST["correo"]))) {
        $correo_err = "Por favor, ingrese un correo.";
    } else {
        $sql = "SELECT id FROM usuarios WHERE correo = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_correo);
            $param_correo = trim($_POST["correo"]);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $correo_err = "Este correo ya está en uso.";
                } else {
                    $correo = trim($_POST["correo"]);
                }
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            $stmt->close();
        }
    }

    // Validar contraseña
    if (empty(trim($_POST["contrasena"]))) {
        $contrasena_err = "Por favor, ingrese una contraseña.";
    } elseif (strlen(trim($_POST["contrasena"])) < 6) {
        $contrasena_err = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $contrasena = trim($_POST["contrasena"]);
    }

    // Validar confirmación de contraseña
    if (empty(trim($_POST["confirm_contrasena"]))) {
        $confirm_contrasena_err = "Por favor, confirme la contraseña.";
    } else {
        $confirm_contrasena = trim($_POST["confirm_contrasena"]);
        if (empty($contrasena_err) && ($contrasena != $confirm_contrasena)) {
            $confirm_contrasena_err = "Las contraseñas no coinciden.";
        }
    }

    // Verificar errores antes de insertar en la base de datos
    if (empty($nombre_err) && empty($correo_err) && empty($contrasena_err) && empty($confirm_contrasena_err)) {
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_nombre, $param_correo, $param_contrasena);
            $param_nombre = $nombre;
            $param_correo = $correo;
            $param_contrasena = password_hash($contrasena, PASSWORD_DEFAULT); // Cifrar la contraseña
            

            if ($stmt->execute()) {
                header("location: login.php");
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
    <title>Registro</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 350px; padding: 20px; }
    </style>
</head>
<body>
<?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Registro</h2>
        <p>Por favor, complete este formulario para crear una cuenta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                <span class="help-block"><?php echo $nombre_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($correo_err)) ? 'has-error' : ''; ?>">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" value="<?php echo $correo; ?>">
                <span class="help-block"><?php echo $correo_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($contrasena_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" value="<?php echo $contrasena; ?>">
                <span class="help-block"><?php echo $contrasena_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_contrasena_err)) ? 'has-error' : ''; ?>">
                <label>Confirme su contraseña</label>
                <input type="password" name="confirm_contrasena" class="form-control" value="<?php echo $confirm_contrasena; ?>">
                <span class="help-block"><?php echo $confirm_contrasena_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrarse">
            </div>
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </form>
    </div>
</body>
</html>
