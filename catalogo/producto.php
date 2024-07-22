<?php
session_start();
require '../db.php';

// Verificar si se envió el formulario de agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['id'])) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        header("Location: ../login/login.php");
        exit();
    }

    $usuario_id = $_SESSION['id'];

    // Obtener el carrito activo del usuario o crear uno nuevo si no existe
    $sql_carrito = "SELECT id FROM carritos WHERE usuario_id = ? AND estado = 'activo'";
    $stmt_carrito = $conn->prepare($sql_carrito);
    $stmt_carrito->bind_param("i", $usuario_id);
    $stmt_carrito->execute();
    $result_carrito = $stmt_carrito->get_result();

    if ($result_carrito->num_rows > 0) {
        $carrito = $result_carrito->fetch_assoc();
        $carrito_id = $carrito['id'];
    } else {
        $sql_nuevo_carrito = "INSERT INTO carritos (usuario_id, estado) VALUES (?, 'activo')";
        $stmt_nuevo_carrito = $conn->prepare($sql_nuevo_carrito);
        $stmt_nuevo_carrito->bind_param("i", $usuario_id);
        $stmt_nuevo_carrito->execute();
        $carrito_id = $stmt_nuevo_carrito->insert_id;
        $stmt_nuevo_carrito->close();
    }

    // Verificar el stock disponible del producto
    $sql_stock = "SELECT stock FROM productos WHERE id = ?";
    $stmt_stock = $conn->prepare($sql_stock);
    $stmt_stock->bind_param("i", $producto_id);
    $stmt_stock->execute();
    $result_stock = $stmt_stock->get_result();

    if ($result_stock->num_rows > 0) {
        $producto = $result_stock->fetch_assoc();
        $stock_disponible = $producto['stock'];

        // Verificar la cantidad en el carrito actual
        $sql_cantidad_carrito = "SELECT cantidad FROM carrito_productos WHERE carrito_id = ? AND producto_id = ?";
        $stmt_cantidad_carrito = $conn->prepare($sql_cantidad_carrito);
        $stmt_cantidad_carrito->bind_param("ii", $carrito_id, $producto_id);
        $stmt_cantidad_carrito->execute();
        $result_cantidad_carrito = $stmt_cantidad_carrito->get_result();

        if ($result_cantidad_carrito->num_rows > 0) {
            $cantidad_carrito = $result_cantidad_carrito->fetch_assoc()['cantidad'];
        } else {
            $cantidad_carrito = 0;
        }

        $stmt_cantidad_carrito->close();

        // Calcular la cantidad total (carrito + nueva)
        $cantidad_total = $cantidad_carrito + $cantidad;

        // Verificar si la cantidad total excede el stock disponible
        if ($cantidad_total > $stock_disponible) {
            $_SESSION['error_carrito'] = "No se puede agregar al carrito. Stock insuficiente.";
        } else {
            // Agregar o actualizar la cantidad en el carrito
            if ($cantidad_carrito > 0) {
                $sql_actualizar_cantidad = "UPDATE carrito_productos SET cantidad = ? WHERE carrito_id = ? AND producto_id = ?";
                $stmt_actualizar_cantidad = $conn->prepare($sql_actualizar_cantidad);
                $stmt_actualizar_cantidad->bind_param("iii", $cantidad_total, $carrito_id, $producto_id);
                $stmt_actualizar_cantidad->execute();
                $stmt_actualizar_cantidad->close();
            } else {
                $sql_agregar_producto = "INSERT INTO carrito_productos (carrito_id, producto_id, cantidad) VALUES (?, ?, ?)";
                $stmt_agregar_producto = $conn->prepare($sql_agregar_producto);
                $stmt_agregar_producto->bind_param("iii", $carrito_id, $producto_id, $cantidad);
                $stmt_agregar_producto->execute();
                $stmt_agregar_producto->close();
            }
        }
    }

    header("Location: producto.php?id=" . $producto_id);
    exit();
}

// Obtener el ID del producto desde la URL
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Obtener la información del producto
    $sql_producto = "SELECT p.*, c.nombre AS nombre_categoria FROM productos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?";
    $stmt_producto = $conn->prepare($sql_producto);
    $stmt_producto->bind_param("i", $producto_id);
    $stmt_producto->execute();
    $resultado_producto = $stmt_producto->get_result();

    if ($resultado_producto->num_rows > 0) {
        $producto = $resultado_producto->fetch_assoc();
    } else {
        // Producto no encontrado
        $producto = null;
    }

    $stmt_producto->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($producto['nombre']) ? $producto['nombre'] : 'Producto'; ?></title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="productoStyle.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <?php if ($producto): ?>
            <h2><?php echo $producto['nombre']; ?></h2>
            <img src="../bodeguero/uploads/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" style="width: 200px; height: auto;">
            <p><?php echo $producto['descripcion']; ?></p>
            <p>Precio: $<?php echo number_format($producto['precio'], 0); ?> MXN</p>
            <p>Categoría: <?php echo isset($producto['nombre_categoria']) ? $producto['nombre_categoria'] : 'No especificada'; ?></p>
            <p>Stock: <?php echo $producto['stock']; ?></p>
            
            <!-- Formulario para agregar al carrito -->
            <form method="post" action="producto.php?id=<?php echo $producto_id; ?>">
                <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" required>
                <button type="submit" name="agregar_carrito">Agregar al Carrito</button>
            </form>
            <?php
            if (isset($_SESSION['error_carrito'])) {
                echo '<p>' . $_SESSION['error_carrito'] . '</p>';
                unset($_SESSION['error_carrito']);
            }
            ?>
            
        <?php else: ?>
            <p>Producto no encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
