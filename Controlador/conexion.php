<?php
$host_db = "db";          // nombre del contenedor MySQL
$user_db = "root";
$pass_db = "root";        // misma que docker-compose
$db_name = "proyecto";    // misma que docker-compose

$conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

if($conexion->connect_error){ 
    echo "<h1>Error de conexión con MySQL</h1>";
}
?>