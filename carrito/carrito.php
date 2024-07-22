<?php
session_start();
require '../db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Obtener el ID del usuario actual
$usuario_id = $_SESSION['id'];

// Función para actualizar la cantidad de un producto en el carrito
if (isset($_POST['update_cantidad'])) {
    $item_id = $_POST['item_id'];
    $cantidad_nueva = $_POST['cantidad_nueva'];

    // Obtener el stock disponible del producto
    $sql_stock = "SELECT p.stock FROM productos p
                INNER JOIN carrito_productos cp ON p.id = cp.producto_id
                WHERE cp.id = ?";
    $stmt_stock = $conn->prepare($sql_stock);
    $stmt_stock->bind_param("i", $item_id);
    $stmt_stock->execute();
    $stmt_stock->bind_result($stock_disponible);
    $stmt_stock->fetch();
    $stmt_stock->close();

    // Validar que la nueva cantidad no supere el stock disponible
    if ($cantidad_nueva <= $stock_disponible) {
        // Actualizar la cantidad en el carrito
        $sql_update = "UPDATE carrito_productos SET cantidad = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $cantidad_nueva, $item_id);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Mostrar un mensaje de error si la cantidad excede el stock disponible
        echo "<p style='color: red;'>No puedes añadir más unidades de este producto al carrito debido a la disponibilidad limitada.</p>";
    }

    // Redirigir para evitar reenvío de formulario
    header("Location: carrito.php");
    exit();
}

// Función para eliminar un producto del carrito
if (isset($_POST['eliminar_producto'])) {
    $item_id = $_POST['item_id'];

    $sql_delete = "DELETE FROM carrito_productos WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $item_id);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Redirigir para evitar reenvío de formulario
    header("Location: carrito.php");
    exit();
}

// Obtener el carrito activo del usuario
$sql = "SELECT id FROM carritos WHERE usuario_id = ? AND estado = 'activo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($carrito_id);
    $stmt->fetch();
    $stmt->close();

    // Obtener los productos del carrito actual
    $sql_productos = "SELECT cp.id, p.nombre, p.descripcion, p.precio, p.stock, c.nombre AS categoria_nombre, cp.cantidad
                    FROM carrito_productos cp
                    INNER JOIN productos p ON cp.producto_id = p.id
                    INNER JOIN categorias c ON p.categoria_id = c.id
                    WHERE cp.carrito_id = ?";
    $stmt_productos = $conn->prepare($sql_productos);
    $stmt_productos->bind_param("i", $carrito_id);
    $stmt_productos->execute();
    $result_productos = $stmt_productos->get_result();

    $productos_carrito = [];
    while ($fila = $result_productos->fetch_assoc()) {
        $productos_carrito[] = $fila;
    }
    $stmt_productos->close();
} else {
    // No se encontró un carrito activo para el usuario
    $productos_carrito = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="carritoStyle.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AULLenYcjbkjN3M4F33lT0guRqELRStbEq9WN3JQTtQRVKzoL9U2z_6qjS_93QdqcVeV3LvOm4SUN3M8&currency=MXN"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABV44tFw6z2wDElbAAyP5YQ9HWS13lVnc&callback=initMap" async defer></script>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <h2>Carrito de Compras</h2>
        
        <?php if (!empty($productos_carrito)): ?>
            <?php
                // Calcular subtotal inicial
                $subtotal = array_sum(array_map(function($p) { return $p['precio'] * $p['cantidad']; }, $productos_carrito));
            ?>
            <table id="carrito-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos_carrito as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                            <td>
                                <form method="post" action="carrito.php">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($producto['id']); ?>">
                                    <input type="number" name="cantidad_nueva" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" min="1" max="<?php echo htmlspecialchars($producto['stock']); ?>">
                                    <input type="submit" name="update_cantidad" value="Actualizar">
                                </form>
                            </td>
                            <td>$<?php echo number_format($producto['precio'], 0, '.', ','); ?></td>
                            <td>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 0, '.', ','); ?></td>
                            <td>
                                <form method="post" action="carrito.php">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($producto['id']); ?>">
                                    <input type="submit" name="eliminar_producto" value="Eliminar">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">Subtotal</td>
                        <td id="subtotal">$<?php echo number_format($subtotal, 0, '.', ','); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5">Costo de Envío</td>
                        <td id="costo-envio">$0</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5">Total a Pagar</td>
                        <td id="total-a-pagar">$<?php echo number_format($subtotal, 0, '.', ','); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div id="paypal-button-container"></div>
        <?php else: ?>
            <p>No hay productos en tu carrito.</p>
        <?php endif; ?>

        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>
    <script>
        var map;
        var marker;

        function initMap() {
            var tiendaLatLng = { lat: -33.7473, lng: -71.2015 }; // Coordenadas de Melipilla

            map = new google.maps.Map(document.getElementById('map'), {
                center: tiendaLatLng,
                zoom: 12
            });

            new google.maps.Marker({
                position: tiendaLatLng,
                map: map,
                title: 'Ubicación de la tienda'
            });

            marker = new google.maps.Marker({
                map: map,
                position: tiendaLatLng,
                draggable: true,
                title: 'Selecciona tu lugar de despacho'
            });

            google.maps.event.addListener(marker, 'position_changed', function() {
                calcularCostoEnvio();
            });

            map.addListener('click', function(event) {
                marker.setPosition(event.latLng);
                calcularCostoEnvio();
            });

            // Inicializar el total a pagar con el subtotal
            var subtotal = parseFloat(document.getElementById('subtotal').innerText.replace('$', '').replace(',', ''));
            document.getElementById('total-a-pagar').innerText = '$' + formatearNumero(subtotal);
        }

        function calcularCostoEnvio() {
            var tiendaLatLng = { lat: -33.7473, lng: -71.2015 }; // Coordenadas de Melipilla
            var lugarDespachoLatLng = marker.getPosition();

            var distanciaService = new google.maps.DistanceMatrixService();
            distanciaService.getDistanceMatrix({
                origins: [tiendaLatLng],
                destinations: [lugarDespachoLatLng],
                travelMode: 'DRIVING'
            }, function(response, status) {
                if (status === 'OK') {
                    var distancia = response.rows[0].elements[0].distance.value; // distancia en metros
                    var costoEnvio = calcularEnvio(distancia);

                    document.getElementById('costo-envio').innerText = '$' + formatearNumero(costoEnvio);
                    var subtotal = parseFloat(document.getElementById('subtotal').innerText.replace('$', '').replace(',', ''));
                    var totalPagar = subtotal + costoEnvio;
                    document.getElementById('total-a-pagar').innerText = '$' + formatearNumero(totalPagar);
                }
            });
        }

        function calcularEnvio(distancia) {
            var costoBase = 50; // costo base en pesos
            var costoPorKm = 5; // costo por kilómetro

            var distanciaKm = distancia / 1000;

            return costoBase + (costoPorKm * distanciaKm);
        }

        function formatearNumero(numero) {
            return numero.toLocaleString('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        }

        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                var subtotal = 0;
                <?php foreach ($productos_carrito as $producto): ?>
                    subtotal += <?php echo $producto['precio'] * $producto['cantidad']; ?>;
                <?php endforeach; ?>
                
                var costoEnvio = parseFloat(document.getElementById('costo-envio').innerText.replace('$', '').replace(',', ''));
                var total = subtotal + costoEnvio;
                
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: total.toFixed(0) // Formatear el total como entero
                        }
                    }]
                });
            },

            onApprove: function(data, actions) {
                return actions.order.capture().then(function(detalles) {
                    window.location.href="../api/gracias.php";
                });
            },
            onCancel: function(data){
                alert("Pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>



