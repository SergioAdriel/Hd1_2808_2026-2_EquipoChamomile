<?php
require 'conexion.php';
session_start();

$id = $_SESSION['trainer_id'];
$pokemon = $_POST['pokemon'];

// 🔍 verificar si ya existe
$sql_check = "SELECT * FROM equipo WHERE id_usuario = ? AND id_pokemon = ?";
$stmt = $conexion->prepare($sql_check);
$stmt->bind_param("ii", $id, $pokemon);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: ../principal.php?error=repetido");
    exit;
}

// insertar si no existe
$sql = "INSERT INTO equipo (id_usuario, id_pokemon) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id, $pokemon);
$stmt->execute();

header("Location: ../principal.php?ok=1");