<?php
session_start();
require '../db.php';

// Verificar si el usuario tiene el rol de bodeguero
if (!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'bodeguero') {
    header("location: ../login/login.php");
    exit;
}

// Inicializar variables
$nombre_categoria = "";
$accion = "crear"; // Acción por defecto es crear
$errores = [];
$mensaje = "";

// Manejar formulario de categoría
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar acción
    $accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

    if ($accion == "crear") {
        // Crear categoría
        $nombre_categoria = isset($_POST["nombre_categoria"]) ? trim($_POST["nombre_categoria"]) : "";

        // Validar campos obligatorios
        if (empty($nombre_categoria)) {
            $errores[] = "El nombre de la categoría es obligatorio.";
        }

        // Insertar categoría si no hay errores
        if (empty($errores)) {
            $sql = "INSERT INTO categorias (nombre) VALUES (?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $nombre_categoria);
                if ($stmt->execute()) {
                    $mensaje = "Categoría creada exitosamente.";
                    // Limpiar campos después de crear
                    $nombre_categoria = "";
                } else {
                    $errores[] = "Error al crear la categoría: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errores[] = "Error en la preparación de la consulta: " . $conn->error;
            }
        }
    } elseif ($accion == "editar") {
        // Editar categoría
        $categoria_id = isset($_POST["categoria_id"]) ? $_POST["categoria_id"] : "";
        $nombre_categoria = isset($_POST["nombre_categoria"]) ? trim($_POST["nombre_categoria"]) : "";

        // Validar ID de la categoría
        if (empty($categoria_id)) {
            $errores[] = "ID de categoría no especificado.";
        }

        // Validar campos obligatorios
        if (empty($nombre_categoria)) {
            $errores[] = "El nombre de la categoría es obligatorio.";
        }

        // Actualizar categoría si no hay errores
        if (empty($errores)) {
            $sql = "UPDATE categorias SET nombre = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $nombre_categoria, $categoria_id);
                if ($stmt->execute()) {
                    $mensaje = "Categoría actualizada exitosamente.";
                    // Limpiar campos después de editar
                    $nombre_categoria = "";
                    $accion = "crear"; // Cambiar acción a crear después de editar
                } else {
                    $errores[] = "Error al actualizar la categoría: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errores[] = "Error en la preparación de la consulta: " . $conn->error;
            }
        }
    } elseif ($accion == "eliminar") {
        // Eliminar categoría
        $categoria_id = isset($_POST["categoria_id"]) ? $_POST["categoria_id"] : "";

        // Validar ID de la categoría
        if (empty($categoria_id)) {
            $errores[] = "ID de categoría no especificado.";
        }

        // Verificar si la categoría está siendo usada por algún producto
        $sql_check = "SELECT COUNT(*) as count_productos FROM productos WHERE categoria_id = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("i", $categoria_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $row_check = $result_check->fetch_assoc();
            $count_productos = $row_check["count_productos"];
            $stmt_check->close();

            if ($count_productos > 0) {
                // Si hay productos asociados a la categoría, mostrar mensaje de error
                $errores[] = "No se puede eliminar la categoría porque está siendo utilizada por uno o más productos.";
            } else {
                // Si no hay productos asociados, proceder con la eliminación
                $sql = "DELETE FROM categorias WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $categoria_id);
                    if ($stmt->execute()) {
                        $mensaje = "Categoría eliminada exitosamente.";
                        $accion = "crear"; // Cambiar acción a crear después de eliminar
                    } else {
                        $errores[] = "Error al eliminar la categoría.";
                    }
                    $stmt->close();
                } else {
                    $errores[] = "Error en la preparación de la consulta.";
                }
            }
        } else {
            $errores[] = "Error al verificar la existencia de productos asociados a la categoría.";
        }
    }
}

// Obtener categorías para mostrar en la tabla
$categorias = [];
$sql = "SELECT * FROM categorias";
$resultado = $conn->query($sql);
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $categorias[] = $fila;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bodeguero - Gestionar Categorías</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="bodegueroStyle.css">
    <style>
        .table th, .table td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Gestionar Categorías</h2>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?>.</p>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success">
                <p><?php echo $mensaje; ?></p>
            </div>
        <?php endif; ?>
        
        <form action="gestionar_categorias.php" method="post">
            <input type="hidden" name="categoria_id" value="<?php echo $categoria_id; ?>">
            <div class="form-group">
                <label>Nombre de la Categoría</label>
                <input type="text" name="nombre_categoria" class="form-control" value="<?php echo $nombre_categoria; ?>">
            </div>
            <div class="form-group">
                <?php if ($accion == "crear" || isset($_POST['accion']) && $_POST['accion'] == 'eliminar'): ?>
                    <input type="hidden" name="accion" value="crear">
                    <button type="submit" class="btn btn-primary">Crear</button>
                <?php elseif ($accion == "editar"): ?>
                    <input type="hidden" name="accion" value="editar">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                <?php endif; ?>
            </div>
        </form>

        <h3>Listado de Categorías</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo $categoria["id"]; ?></td>
                        <td><?php echo $categoria["nombre"]; ?></td>
                        <td>
                            <form action="gestionar_categorias.php" method="post">
                                <input type="hidden" name="categoria_id" value="<?php echo $categoria["id"]; ?>">
                                <input type="hidden" name="accion" value="editar">
                                <button type="submit" class="btn btn-warning">Editar</button>
                            </form>
                            <form action="gestionar_categorias.php" method="post">
                                <input type="hidden" name="categoria_id" value="<?php echo $categoria["id"]; ?>">
                                <input type="hidden" name="accion" value="eliminar">
                                <?php if (empty($errores)): ?>
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">Eliminar</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
