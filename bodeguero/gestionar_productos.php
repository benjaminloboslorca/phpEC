<?php
session_start();
require '../db.php';

// Verificar si el usuario tiene el rol de bodeguero
if (!isset($_SESSION["loggedin"]) || $_SESSION["rol"] != 'bodeguero') {
    header("location: ../login/login.php");
    exit;
}

// Inicializar variables
$nombre_producto = $descripcion = $precio = $categoria_id = $stock = "";
$accion = "crear"; // Acción por defecto es crear
$errores = [];
$mensaje = "";

// Manejar formulario de producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar acción
    $accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

    if ($accion == "crear") {
        // Crear producto
        $nombre_producto = isset($_POST["nombre_producto"]) ? trim($_POST["nombre_producto"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "";
        $precio = isset($_POST["precio"]) ? trim($_POST["precio"]) : "";
        $categoria_id = isset($_POST["categoria_id"]) ? trim($_POST["categoria_id"]) : "";
        $stock = isset($_POST["stock"]) ? trim($_POST["stock"]) : "";

        // Validar campos obligatorios
        if (empty($nombre_producto) || empty($descripcion) || empty($precio) || empty($categoria_id) || empty($stock)) {
            $errores[] = "Todos los campos son obligatorios.";
        }

        // Procesar imagen si se ha subido
        $nombre_imagen = '';
        if (!empty($_FILES['imagen_producto']['name'])) {
            $nombre_imagen = $_FILES['imagen_producto']['name'];
            $temp_imagen = $_FILES['imagen_producto']['tmp_name'];
            $ruta_imagen = __DIR__ . '/uploads/' . $nombre_imagen;

            // Mover la imagen al directorio deseado
            if (!move_uploaded_file($temp_imagen, $ruta_imagen)) {
                $errores[] = "Error al subir la imagen.";
            }
        }

        // Insertar producto si no hay errores
        if (empty($errores)) {
            $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id, stock, imagen) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssdiss", $nombre_producto, $descripcion, $precio, $categoria_id, $stock, $nombre_imagen);
                if ($stmt->execute()) {
                    $mensaje = "Producto creado exitosamente.";
                    // Limpiar campos después de crear
                    $nombre_producto = $descripcion = $precio = $categoria_id = $stock = "";
                } else {
                    $errores[] = "Error al crear el producto: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errores[] = "Error en la preparación de la consulta: " . $conn->error;
            }
        }
    } elseif ($accion == "editar") {
        // Editar producto
        $producto_id = isset($_POST["producto_id"]) ? $_POST["producto_id"] : "";
        $nombre_producto = isset($_POST["nombre_producto"]) ? trim($_POST["nombre_producto"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "";
        $precio = isset($_POST["precio"]) ? trim($_POST["precio"]) : "";
        $categoria_id = isset($_POST["categoria_id"]) ? trim($_POST["categoria_id"]) : "";
        $stock = isset($_POST["stock"]) ? trim($_POST["stock"]) : "";

        // Validar ID del producto
        if (empty($producto_id)) {
            $errores[] = "ID de producto no especificado.";
        }

        // Validar campos obligatorios
        if (empty($nombre_producto) || empty($descripcion) || empty($precio) || empty($categoria_id) || empty($stock)) {
            $errores[] = "Todos los campos son obligatorios.";
        }

        // Procesar imagen si se ha subido una nueva
        $nombre_imagen = '';
        if (!empty($_FILES['imagen_producto']['name'])) {
            $nombre_imagen = $_FILES['imagen_producto']['name'];
            $temp_imagen = $_FILES['imagen_producto']['tmp_name'];
            $ruta_imagen = __DIR__ . '/uploads/' . $nombre_imagen;

            // Mover la imagen al directorio deseado
            if (!move_uploaded_file($temp_imagen, $ruta_imagen)) {
                $errores[] = "Error al subir la imagen.";
            }
        } else {
            // Conservar la imagen existente en la base de datos
            $sql_imagen_existente = "SELECT imagen FROM productos WHERE id = ?";
            if ($stmt_imagen_existente = $conn->prepare($sql_imagen_existente)) {
                $stmt_imagen_existente->bind_param("i", $producto_id);
                $stmt_imagen_existente->execute();
                $stmt_imagen_existente->store_result();

                if ($stmt_imagen_existente->num_rows > 0) {
                    $stmt_imagen_existente->bind_result($imagen_existente);
                    $stmt_imagen_existente->fetch();
                    $nombre_imagen = $imagen_existente;
                }

                $stmt_imagen_existente->close();
            } else {
                $errores[] = "Error al obtener la imagen existente: " . $conn->error;
            }
        }

        // Actualizar producto si no hay errores
        if (empty($errores)) {
            if (!empty($_FILES['imagen_producto']['name'])) {
                // Si se cargó una nueva imagen, actualizar la base de datos
                $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria_id = ?, stock = ?, imagen = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssdissi", $nombre_producto, $descripcion, $precio, $categoria_id, $stock, $nombre_imagen, $producto_id);
                    if ($stmt->execute()) {
                        $mensaje = "Producto actualizado exitosamente.";
                        // Limpiar campos después de editar
                        $nombre_producto = $descripcion = $precio = $categoria_id = $stock = "";
                        $accion = "crear"; // Cambiar acción a crear después de editar
                    } else {
                        $errores[] = "Error al actualizar el producto: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $errores[] = "Error en la preparación de la consulta: " . $conn->error;
                }
            } else {
                // Si no se cargó una nueva imagen, conservar la imagen existente en la base de datos
                $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria_id = ?, stock = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssdiii", $nombre_producto, $descripcion, $precio, $categoria_id, $stock, $producto_id);
                    if ($stmt->execute()) {
                        $mensaje = "Producto actualizado exitosamente.";
                        // Limpiar campos después de editar
                        $nombre_producto = $descripcion = $precio = $categoria_id = $stock = "";
                        $accion = "crear"; // Cambiar acción a crear después de editar
                    } else {
                        $errores[] = "Error al actualizar el producto: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $errores[] = "Error en la preparación de la consulta: " . $conn->error;
                }
            }
        }
            } elseif ($accion == "eliminar") {
        // Eliminar producto
        $producto_id = isset($_POST["producto_id"]) ? $_POST["producto_id"] : "";

        // Validar ID del producto
        if (empty($producto_id)) {
            $errores[] = "ID de producto no especificado.";
        }

        // Eliminar producto si no hay errores
        if (empty($errores)) {
            $sql = "DELETE FROM productos WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $producto_id);
                if ($stmt->execute()) {
                    $mensaje = "Producto eliminado exitosamente.";
                    $accion = "crear"; // Cambiar acción a crear después de eliminar
                } else {
                    $errores[] = "Error al eliminar el producto.";
                }
                $stmt->close();
            } else {
                $errores[] = "Error en la preparación de la consulta.";
            }
        }
    }
}

// Obtener categorías
$categorias = [];
$sql = "SELECT * FROM categorias";
$resultado = $conn->query($sql);
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $categorias[$fila["id"]] = $fila["nombre"];
    }
}

// Obtener productos para mostrar en la tabla
$productos = [];
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bodeguero - Gestionar Productos</title>
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
        <h2>Gestionar Productos</h2>
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
        
        <form action="gestionar_productos.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
            <div class="form-group">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre_producto" class="form-control" value="<?php echo $nombre_producto; ?>">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"><?php echo $descripcion; ?></textarea>
            </div>
            <div class="form-group">
                <label>Precio</label>
                <input type="text" name="precio" class="form-control" value="<?php echo $precio; ?>">
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="text" name="stock" class="form-control" value="<?php echo $stock; ?>">
            </div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria_id" class="form-control">
                    <option value="">Seleccionar Categoría</option>
                    <?php foreach ($categorias as $id => $nombre): ?>
                        <option value="<?php echo $id; ?>" <?php echo ($categoria_id == $id) ? "selected" : ""; ?>><?php echo $nombre; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Foto del Producto</label>
                <input type="file" name="imagen_producto" class="form-control-file">
            </div>
            <div class="form-group">
                <?php if ($accion == "editar"): ?>
                    <input type="hidden" name="accion" value="editar">
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                <?php else: ?>
                    <input type="hidden" name="accion" value="crear">
                    <button type="submit" class="btn btn-success">Crear Producto</button>
                <?php endif; ?>
            </div>
        </form>

        <?php if (!empty($productos)): ?>
            <h3>Lista de Productos</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto["id"]; ?></td>
                            <td><?php echo $producto["nombre"]; ?></td>
                            <td><?php echo $producto["descripcion"]; ?></td>
                            <td><?php echo number_format($producto['precio'], 0) ?></td>
                            <td><?php echo $producto["stock"]; ?></td>
                            <td><?php echo $categorias[$producto["categoria_id"]]; ?></td>
                            <td>
                                <?php if (!empty($producto["imagen"])): ?>
                                    <img src="/bodeguero/uploads/<?php echo $producto["imagen"]; ?>" alt="Imagen del Producto" style="width: 100px; height: 100px;">
                                <?php else: ?>
                                    <img src="/bodeguero/uploads/defecto.jpg" alt="Imagen por defecto" style="width: 100px; height: 100px;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="gestionar_productos.php" method="post">
                                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                                    <input type="hidden" name="accion" value="editar">
                                    <button type="submit" class="btn btn-sm btn-primary">Editar</button>
                                </form>
                                <form action="gestionar_productos.php" method="post">
                                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </div>
</body>
</html>
