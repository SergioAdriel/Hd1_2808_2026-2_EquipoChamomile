<?php
// Incluir archivo de conexión a la base de datos
require 'conexion.php';
// Iniciar sesión para poder almacenar valores en las variables de sesión
session_start();

// Obtener los valores del formulario enviados por POST (teléfono y clave)
$telefono = $_POST['telefono'];
$clave = $_POST['clave'];

// La función COUNT devuelve el número de filas que cumplen con una condición en la base de datos
// Se verifica si existe un registro con el teléfono y la clave proporcionados
$q = "SELECT COUNT(*) as contar from residente where telefono= '$telefono' and password = '$clave'";

// Ejecutar la consulta SQL
$consulta = mysqli_query($conexion, $q);

// Obtener el resultado de la consulta como un arreglo
$array = mysqli_fetch_array($consulta);

// Si el número de registros encontrados es mayor que 0 (es decir, el usuario existe con el teléfono y la clave proporcionados)
if ($array['contar'] > 0) {
    // Si existe, se guarda el teléfono en la variable de sesión 'username'
    $_SESSION['username'] = $telefono;

    // Redirigir al usuario a la página principal
    header("location: ../principal.php");
    
} else {
    // Si no existe el usuario con esas credenciales, redirigir al usuario a la página de error
    header("location: ../errorLoguin.php");
}
?>
