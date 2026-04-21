<?php
require __DIR__ . "/conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../index.php");
    exit;
}

$id_usuario = $_SESSION['trainer_id'];

$sql = "DELETE FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error en prepare: " . $conexion->error);
}

$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {

    $_SESSION = [];
    session_destroy();

    header("Location: ../index.php?msg=cuenta_eliminada");
    exit;

} else {
    header("Location: ../Vista/principal.php?error=eliminar");
    exit;
}

$stmt->close();
$conexion->close();
?>
