<?php
// Iniciar sesión para poder acceder a las variables de sesión
session_start();

// Destruir todas las variables de sesión y finalizar la sesión actual
session_destroy();

// Redirigir al usuario a la página principal o índice después de cerrar sesión
header("location: ../index.php");

// Terminar la ejecución del script para asegurar que no se ejecute más código
exit();
?>