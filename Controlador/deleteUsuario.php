<?php
// Incluir el archivo de conexión a la base de datos
require "conexion.php";

// Configurar el conjunto de caracteres para la conexión a la base de datos
mysqli_set_charset($conexion, 'utf8');

// Obtener el número de teléfono enviado por POST
$telefono = $_POST['telefono'];

// Verificar si el número de teléfono existe en la base de datos
$consultaVerificar = "SELECT * FROM residente WHERE telefono = '$telefono'";
$resultadoVerificar = mysqli_query($conexion, $consultaVerificar);

if (mysqli_num_rows($resultadoVerificar) > 0) {
    // Si existe, eliminar el registro
    $consultaEliminar = "DELETE FROM residente WHERE telefono = '$telefono'";
    if (mysqli_query($conexion, $consultaEliminar)) {
        // Redirigir a la pantalla de confirmación
        header("Location: ./eliminacionExitosa.php");
    } else {
        // Redirigir a la pantalla de error general
        header("Location: ./errorEliminar.php");
    }
} else {
    // Redirigir a la pantalla de error de teléfono no existente
    header("Location: ./telefonoNoExiste.php");
}

// Cerrar la conexión
mysqli_close($conexion);
?>