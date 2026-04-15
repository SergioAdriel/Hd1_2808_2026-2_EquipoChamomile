<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ./login.php");
    exit;
}

require __DIR__ . "/../header.php";
require __DIR__ . "/conexion.php";
require __DIR__ . '/../musica.php';

$id_usuario = $_SESSION['trainer_id'];
$nombre = $_SESSION['trainer_name'];
?>

<link rel="stylesheet" href="../Vista/css/PokeCSS.css">

<?php
// 🔥 stats del usuario
$sql = "SELECT victorias, derrotas FROM combates WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

$victorias = $stats['victorias'] ?? 0;
$derrotas = $stats['derrotas'] ?? 0;
?>

<div class="container">

<!-- ========================= -->
<!-- 🧑 PANEL USUARIO -->
<!-- ========================= -->

<?php
$sql = "SELECT id_pokemon FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$pokemons = [];

while ($row = $result->fetch_assoc()) {
    $pokemons[] = $row['id_pokemon'];
}
?>

<div class="card center-align" style="padding:20px; margin-top:20px;">
    <h5>Bienvenid@, <?php echo $nombre; ?>.</h5>
    <br>

    <h6 class="center-align">Estadísticas.</h6>
    <p>🏆 <?php echo $victorias; ?> | 💀 <?php echo $derrotas; ?></p>

        <h6 class="center-align">Tu equipo actual.</h6>

        <div class="poke-card center-align">

            <?php if (!empty($pokemons)): ?>
                <?php foreach ($pokemons as $poke): ?>
                    <img class="poke-img"
                    src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sin Pokémon</p>
            <?php endif; ?>
        </div>

        <br>

        <a href="../Vista/equipo.php" class="btn green">
            Editar equipo
        </a>
        <a href="../Vista/combate.php" class="btn red">
            Consultar oponentes
        </a>

        <br><br>

        <a href="./configuracion.php" class="btn blue">
            Configuración de cuenta
        </a>
        <br><br>

</div>

<!-- ========================= -->
<!-- ⚔️ ACCIONES -->
<!-- ========================= -->

<div class="center-align" style="margin-top:40px;">
    
</div>

</div>

<?php require __DIR__ . "/../footer.php"; ?>