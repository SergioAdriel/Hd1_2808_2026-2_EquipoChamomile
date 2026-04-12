<?php
require __DIR__ . '/conexion.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no permitido");
}

$usuario = trim($_POST['usuario'] ?? '');
$clave = trim($_POST['clave'] ?? '');

if (empty($usuario) || empty($clave)) {
    header("Location: ./errorLoguin.php?msg=campos");
    exit;
}

$sql = "SELECT id_usuario, nombre, contrasena 
        FROM usuarios 
        WHERE telefono = ? OR nombre = ?";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error SQL: " . $conexion->error);
}

$stmt->bind_param("ss", $usuario, $usuario);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {

    $row = $resultado->fetch_assoc();

    if ($clave === $row['contrasena']) {

        $_SESSION['trainer_id'] = $row['id_usuario'];
        $_SESSION['trainer_name'] = $row['nombre'];

        header("Location: ./principal.php");
        exit;

    } else {
        header("Location: ./errorLoguin.php?msg=clave");
        exit;
    }

} else {
    header("Location: ./errorLoguin.php?msg=usuario");
    exit;
}

$stmt->close();
$conexion->close();
?>