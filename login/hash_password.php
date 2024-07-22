<?php
// Define la contraseña que quieres hashear
$plain_password = '123456';

// Hashea la contraseña
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Muestra el hash de la contraseña
echo "Hashed Password: " . $hashed_password;
?>
