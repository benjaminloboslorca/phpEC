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
$nombre = $correo = $rol = "";
$nombre_err = $correo_err = $rol_err = "";

// Obtener el ID del usuario desde la URL
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Preparar una declaración SELECT
    $sql = "SELECT nombre, correo, rol FROM usuarios WHERE id = ?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $param_id);
        
        // Establecer parámetros
        $param_id = trim($_GET["id"]);
        
        // Intentar ejecutar la declaración preparada
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows == 1){
                // Obtener la fila del resultado como un array asociativo
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                // Recuperar valores de campo individual
                $nombre = $row["nombre"];
                $correo = $row["correo"];
                $rol = $row["rol"];
            } else{
                // No se encontró ningún usuario con ese ID
                header("location: error.php");
                exit();
            }
        } else{
            echo "Oops! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
        }

        // Cerrar declaración
        $stmt->close();
    }
}

// Procesar datos del formulario cuando se envía
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validar nombre de usuario
    if(empty(trim($_POST["nombre"]))){
        $nombre_err = "Por favor, ingrese un nombre de usuario.";
    } else{
        $nombre = trim($_POST["nombre"]);
    }

    // Validar correo electrónico
    if(empty(trim($_POST["correo"]))){
        $correo_err = "Por favor, ingrese un correo electrónico.";
    } elseif(!filter_var(trim($_POST["correo"]), FILTER_VALIDATE_EMAIL)){
        $correo_err = "Por favor, ingrese un correo electrónico válido.";
    } else{
        $correo = trim($_POST["correo"]);
    }
    
    // Validar rol
    if(empty(trim($_POST["rol"]))){
        $rol_err = "Por favor, seleccione un rol.";
    } else{
        $rol = trim($_POST["rol"]);
    }
    
    // Verificar errores antes de actualizar en la base de datos
    if(empty($nombre_err) && empty($correo_err) && empty($rol_err)){
        
        // Preparar la declaración UPDATE
        $sql = "UPDATE usuarios SET nombre = ?, correo = ?, rol = ? WHERE id = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Vincular variables a la declaración preparada como parámetros
            $stmt->bind_param("sssi", $param_nombre, $param_correo, $param_rol, $param_id);
            
            // Establecer parámetros
            $param_nombre = $nombre;
            $param_correo = $correo;
            $param_rol = $rol;
            $param_id = $_GET["id"];
            
            // Intentar ejecutar la declaración
            if($stmt->execute()){
                // Redirigir de nuevo a la página de gestionar usuarios
                header("location: gestionar_usuarios.php");
            } else{
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            // Cerrar declaración
            $stmt->close();
        }
    }

    // Cerrar conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="styleAdmin.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Editar Usuario</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET["id"]; ?>" method="post">
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
            <div class="form-group <?php echo (!empty($rol_err)) ? 'has-error' : ''; ?>">
                <label>Rol</label>
                <select name="rol" class="form-control">
                    <option value="vendedor" <?php if($rol == 'vendedor') echo 'selected'; ?>>Vendedor</option>
                    <option value="bodeguero" <?php if($rol == 'bodeguero') echo 'selected'; ?>>Bodeguero</option>
                    <option value="contador" <?php if($rol == 'contador') echo 'selected'; ?>>Contador</option>
                    <option value="cliente" <?php if($rol == 'cliente') echo 'selected'; ?>>Cliente</option>
                </select>
                <span class="help-block"><?php echo $rol_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Guardar Cambios">
                <a href="gestionar_usuarios.php" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>    
</body>
</html>
