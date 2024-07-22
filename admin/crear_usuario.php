<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un administrador
if(!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'admin'){
    header("location: ../login/login.php");
    exit;
}

// Incluir el archivo de configuración de la base de datos
require_once "../db.php";

// Definir variables e inicializar con valores vacíos
$nombre = $correo = $contrasena = $rol = "";
$nombre_err = $correo_err = $contrasena_err = $rol_err = "";

// Procesar los datos del formulario cuando se envía
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validar nombre de usuario
    if(empty(trim($_POST["nombre"]))){
        $nombre_err = "Por favor, ingrese un nombre de usuario.";
    } else{
        $sql = "SELECT id FROM usuarios WHERE nombre = ?";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_nombre);
            $param_nombre = trim($_POST["nombre"]);
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $nombre_err = "Este nombre de usuario ya está en uso.";
                } else{
                    $nombre = trim($_POST["nombre"]);
                }
            } else{
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            $stmt->close();
        }
    }

    // Validar correo electrónico
    if(empty(trim($_POST["correo"]))){
        $correo_err = "Por favor, ingrese un correo electrónico.";
    } elseif(!filter_var(trim($_POST["correo"]), FILTER_VALIDATE_EMAIL)){
        $correo_err = "Por favor, ingrese un correo electrónico válido.";
    } else{
        $correo = trim($_POST["correo"]);
    }
    
    // Validar contraseña
    if(empty(trim($_POST["contrasena"]))){
        $contrasena_err = "Por favor, ingrese una contraseña.";     
    } elseif(strlen(trim($_POST["contrasena"])) < 6){
        $contrasena_err = "La contraseña debe tener al menos 6 caracteres.";
    } else{
        $contrasena = trim($_POST["contrasena"]);
    }
    
    // Validar rol
    if(empty(trim($_POST["rol"]))){
        $rol_err = "Por favor, seleccione un rol.";
    } else{
        $rol = trim($_POST["rol"]);
    }
    
    // Verificar errores antes de insertar en la base de datos
    if(empty($nombre_err) && empty($correo_err) && empty($contrasena_err) && empty($rol_err)){
        
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssss", $param_nombre, $param_correo, $param_contrasena, $param_rol);
            
            $param_nombre = $nombre;
            $param_correo = $correo;
            $param_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
            $param_rol = $rol;
            
            if($stmt->execute()){
                header("location: gestionar_usuarios.php");
            } else{
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
    <title>Agregar Nuevo Usuario</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Agregar Nuevo Usuario</h2>
        <p>Complete este formulario para agregar un nuevo usuario.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label>Nombre de usuario</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                <span class="help-block"><?php echo $nombre_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($correo_err)) ? 'has-error' : ''; ?>">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" value="<?php echo $correo; ?>">
                <span class="help-block"><?php echo $correo_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($contrasena_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" value="<?php echo $contrasena; ?>">
                <span class="help-block"><?php echo $contrasena_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($rol_err)) ? 'has-error' : ''; ?>">
                <label>Rol</label>
                <select name="rol" class="form-control">
                    <option value="vendedor">Vendedor</option>
                    <option value="bodeguero">Bodeguero</option>
                    <option value="contador">Contador</option>
                    <option value="cliente">Cliente</option>
                    <option value="admin">Administrador</option> <!-- Nueva opción para administrador -->
                </select>
                <span class="help-block"><?php echo $rol_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Agregar Usuario">
            </div>
        </form>
    </div>    
</body>
</html>
