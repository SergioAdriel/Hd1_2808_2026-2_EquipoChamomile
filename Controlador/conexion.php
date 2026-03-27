<?php
$host_db = "db";
$user_db = "root";
$pass_db = "root";
$db_name = "pokedex_app"; 

$conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

if($conexion->connect_error){ 
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>