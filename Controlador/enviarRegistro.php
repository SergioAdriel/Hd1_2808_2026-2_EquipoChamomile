<?php
include "./conexion.php";
mysqli_set_charset($conexion, 'utf8');

session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

$buscarUsuario = "SELECT * FROM residente WHERE nombre_usuario = '$_POST[nombre_usuario]'";
$resultadoUsuario = mysqli_query($conexion, $buscarUsuario);

$buscarTelefono = "SELECT * FROM residente WHERE telefono = '$_POST[telefono]'";
$resultadoTelefono = mysqli_query($conexion, $buscarTelefono);

if (mysqli_num_rows($resultadoUsuario) > 0) {
    header("location: ./errorNombreUsuario.php");
    exit;
} elseif (mysqli_num_rows($resultadoTelefono) > 0) {
    header("location: ./errorTelefono.php");
    exit;
} else {
    $insertar = "INSERT INTO residente (nombre_usuario, letra_edificio, numero_departamento, email, telefono, password) 
                     VALUES ('$_POST[nombre_usuario]', '$_POST[letra_edificio]', '$_POST[numero_departamento]', '$_POST[email]', '$_POST[telefono]', '$_POST[password]')";

    if (mysqli_query($conexion, $insertar)) {
        header("location: ./registroExitoso.php");
    } else {
        header("location: ./errorRegistro.php");
    }
    exit;
}
?>