<?php
include "./conexion.php";
mysqli_set_charset($conexion, 'utf8');

session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Verificar si el nombre de usuario ya está registrado
$buscarUsuario = "SELECT * FROM residente WHERE nombre_usuario = '$_POST[nombre_usuario]'";
$resultadoUsuario = mysqli_query($conexion, $buscarUsuario);

// Verificar si el teléfono ya está registrado
$buscarTelefono = "SELECT * FROM residente WHERE telefono = '$_POST[telefono]'";
$resultadoTelefono = mysqli_query($conexion, $buscarTelefono);

// Verificar si el correo electrónico ya está registrado
$buscarEmail = "SELECT * FROM residente WHERE email = '$_POST[email]'";
$resultadoEmail = mysqli_query($conexion, $buscarEmail);

// Redirección basada en los resultados de las consultas
if (mysqli_num_rows($resultadoUsuario) > 0) {
    header("location: ./errorNombreUsuario.php");
    exit;
} elseif (mysqli_num_rows($resultadoTelefono) > 0) {
    header("location: ./errorTelefono.php");
    exit;
} elseif (mysqli_num_rows($resultadoEmail) > 0) {
    header("location: ./errorEmail.php");
    exit;
} else {
    // Intentar registrar al usuario
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