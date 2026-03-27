<?php
session_start();
require "./Controlador/conexion.php";

$id_usuario = $_SESSION['trainer_id'];
$rival = $_GET['rival'];

// obtener equipos
function getEquipo($conexion, $id) {
    $sql = "SELECT id_pokemon FROM equipo WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    $pokemons = [];
    while ($row = $res->fetch_assoc()) {
        $pokemons[] = $row['id_pokemon'];
    }
    return $pokemons;
}

$equipo1 = getEquipo($conexion, $id_usuario);
$equipo2 = getEquipo($conexion, $rival);

$ganadas1 = 0;
$ganadas2 = 0;

// 🔥 pelea 1 vs 1
for ($i = 0; $i < 6; $i++) {

    $p1 = json_decode(file_get_contents("https://pokeapi.co/api/v2/pokemon/".$equipo1[$i]), true);
    $p2 = json_decode(file_get_contents("https://pokeapi.co/api/v2/pokemon/".$equipo2[$i]), true);

    $stats1 = array_sum(array_column($p1['stats'], 'base_stat'));
    $stats2 = array_sum(array_column($p2['stats'], 'base_stat'));

    if ($stats1 > $stats2) {
        $ganadas1++;
    } else {
        $ganadas2++;
    }
}

$ganador = ($ganadas1 > $ganadas2) ? $id_usuario : $rival;

// actualizar BD
if ($ganador == $id_usuario) {
    $conexion->query("UPDATE combates SET victorias = victorias + 1 WHERE id_usuario = $id_usuario");
    $conexion->query("UPDATE combates SET derrotas = derrotas + 1 WHERE id_usuario = $rival");
} else {
    $conexion->query("UPDATE combates SET derrotas = derrotas + 1 WHERE id_usuario = $id_usuario");
    $conexion->query("UPDATE combates SET victorias = victorias + 1 WHERE id_usuario = $rival");
}
?>

<?php require "./header.php"; ?>

<div class="container center-align">
    <h4>🔥 Resultado de la batalla</h4>

    <p>Tu equipo ganó: <?php echo $ganadas1; ?></p>
    <p>Rival ganó: <?php echo $ganadas2; ?></p>

    <h5>
        <?php echo ($ganador == $id_usuario) ? "🏆 GANASTE" : "💀 PERDISTE"; ?>
    </h5>

    <a href="combate.php" class="btn blue">Volver</a>
</div>

<?php require "./footer.php"; ?>