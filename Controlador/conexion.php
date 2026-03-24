<?php
// Definir las credenciales de conexión a la base de datos
$host_db = "localhost:3307";  // Dirección del servidor de base de datos y puerto (3307)
$user_db = "root";            // Usuario de la base de datos
$pass_db = "200477";          // Contraseña del usuario
$db_name = "condominio_citisio"; // Nombre de la base de datos a la que se desea conectar

// Crear una nueva conexión a la base de datos usando la clase mysqli
$conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

// Verificar si hubo un error al intentar conectarse
if($conexion->connect_error){ 
    // Si se encuentra un error de conexión, se muestra un mensaje en el navegador
    echo "<h1>MySQL no le está dando permisos para ejecutar consultas, verificar error</h1>";
} 
?>