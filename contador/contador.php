<?php
session_start();

// Verificar si el usuario tiene el rol de contador
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== 'contador') {
    header("location: /index.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
require '../db.php';

// Obtener los ingresos totales
$sql_ingresos = "SELECT SUM(total) AS total_ingresos FROM historial_compras";
$result_ingresos = $conn->query($sql_ingresos);
$ingresos = $result_ingresos->fetch_assoc()['total_ingresos'];

// Calcular el balance (en este caso solo mostrará ingresos ya que no hay egresos)
$balance = $ingresos; // O $balance = $ingresos - $egresos; si decides agregar una tabla de egresos en el futuro

// Aquí puedes agregar más cálculos o reportes si es necesario

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Contador</title>
    <link rel="stylesheet" href="../navbar/styleNavbar.css">
    <link rel="stylesheet" href="contadorStyle.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="contador-wrapper">
        <h2>Panel del Contador</h2>
        <p>Aquí puedes gestionar los reportes financieros y otros datos relevantes.</p>

        <!-- Reportes Financieros -->
        <div class="finanzas">
            <h3>Reporte Financiero</h3>
            <p><strong>Ingresos Totales:</strong> <?php echo htmlspecialchars(number_format($ingresos, 0, ',', '.')); ?> </p>
            <p><strong>Balance:</strong> <?php echo htmlspecialchars(number_format($balance, 0, ',', '.')); ?> </p>
        </div>

        <!-- Agregar más funcionalidades aquí si es necesario -->
    </div>
</body>
</html>

<?php
$conn->close();
?>
