<?php
session_start();
require '../db.php';

// Obtener categorías
$categorias = [];
$sql = "SELECT * FROM categorias";
$resultado = $conn->query($sql);
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $categorias[] = $fila;
    }
}

// Obtener productos según la categoría seleccionada
$categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : 0;
$productos = [];
if ($categoria_id) {
    $sql_productos = "SELECT * FROM productos WHERE categoria_id = ?";
    $stmt = $conn->prepare($sql_productos);
    $stmt->bind_param("i", $categoria_id);
} else {
    $sql_productos = "SELECT * FROM productos";
    $stmt = $conn->prepare($sql_productos);
}

$stmt->execute();
$resultado_productos = $stmt->get_result();
while ($fila_productos = $resultado_productos->fetch_assoc()) {
    $productos[] = $fila_productos;
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" type="text/css" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" type="text/css" href="catalogoStyle.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Catálogo de Productos</h2>
        <form method="get" action="catalogo.php">
            <label for="categoria_id">Filtrar por categoría:</label>
            <select name="categoria_id" id="categoria_id">
                <option value="0">Todas</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php echo $categoria_id == $categoria['id'] ? 'selected' : ''; ?>>
                        <?php echo $categoria['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrar</button>
        </form>
        <div class="productos">
            <?php if (!empty($productos)): ?>
                <ul>
                    <?php foreach ($productos as $producto): ?>
                        <li>
                            <h3><?php echo $producto['nombre']; ?></h3>
                            <a href="producto.php?id=<?php echo $producto['id']; ?>">
                                <img src="../bodeguero/uploads/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" style="width: 100px; height: auto;">
                            </a>
                            <p><?php echo $producto['descripcion']; ?></p>
                            <p>Precio: $<?php echo number_format($producto['precio'], 0); ?></p>
                            <p>Categoría: <?php echo $categorias[array_search($producto['categoria_id'], array_column($categorias, 'id'))]['nombre']; ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay productos disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
