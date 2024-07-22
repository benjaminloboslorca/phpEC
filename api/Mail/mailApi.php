<?php ;

// Enviar correo de confirmación usando SendGrid
require '../vendor/autoload.php'; // Incluye el autoload de Composer

use SendGrid\Mail\Mail;

$email = new Mail();
$email->setFrom("ferremascompany@gmail.com", "Ferremas"); //Remitente
$email->setSubject("Confirmación de Compra");
$email->addTo($_SESSION["correo"], $_SESSION["nombre"]);
$email->addContent("text/plain", "Gracias por tu compra. Esperamos disfrutes nuestros productos.");
$email->addContent("text/html", "<strong>Gracias por tu compra. Esperamos disfrutes nuestros productos.</strong>");

 // Cambia TU_API_KEY por tu clave API de SendGrid

try {
    $response = $sendgrid->send($email);
    // Opcional: Puedes verificar el código de estado si lo deseas
    if ($response->statusCode() == 202) {
        // El correo se envió correctamente
    } else {
        // Ocurrió un error al enviar el correo
    }
} catch (Exception $e) {
    // Maneja el error
    error_log("Error al enviar el correo: " . $e->getMessage());
}

?>