<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un administrador
if(!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'admin'){
    header("location: ../login/login.php");
    exit;
}

// Verificar si el ID del usuario está presente en la URL
if(!isset($_GET["id"]) || empty(trim($_GET["id"]))){
    // Redirigir al usuario a la página de error si no se encuentra el ID
    header("location: error.php");
    exit;
}

// Incluir el archivo de configuración de la base de datos
require_once "../db.php";

// Preparar la declaración DELETE
$sql = "DELETE FROM usuarios WHERE id = ?";

if($stmt = $conn->prepare($sql)){
    // Vincular variables a la declaración preparada como parámetros
    $stmt->bind_param("i", $param_id);
    
    // Establecer parámetros
    $param_id = trim($_GET["id"]);
    
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

// Cerrar conexión
$conn->close();
?>
