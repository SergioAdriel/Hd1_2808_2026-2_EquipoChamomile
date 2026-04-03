<?php
session_start();
require "./Controlador/conexion.php";

// 🔒 validar sesión
if (!isset($_SESSION['trainer_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['trainer_id'];
$rival = $_POST['rival'] ?? null;

// 🔒 validar rival
if (!$rival || $rival == $id_usuario) {
    header("Location: combate.php");
    exit;
}

// 🔍 obtener nombres
$sql = "SELECT nombre FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$nombre_user = $stmt->get_result()->fetch_assoc()['nombre'];

$stmt->bind_param("i", $rival);
$stmt->execute();
$nombre_rival = $stmt->get_result()->fetch_assoc()['nombre'];

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

// 🔒 validar equipos completos
if (count($equipo1) < 6 || count($equipo2) < 6) {
    header("Location: combate.php");
    exit;
}

$ganadas1 = 0;
$ganadas2 = 0;

// ⚔️ pelea 1 vs 1
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

// 🔥 definir equipo ganador
if ($ganador == $id_usuario) {
    $nombre_ganador = $nombre_user;
    $equipo_ganador = $equipo1;
} else {
    $nombre_ganador = $nombre_rival;
    $equipo_ganador = $equipo2;
}

// 🔥 actualizar BD
if ($ganador == $id_usuario) {

    $stmt = $conexion->prepare("UPDATE combates SET victorias = victorias + 1 WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $stmt = $conexion->prepare("UPDATE combates SET derrotas = derrotas + 1 WHERE id_usuario = ?");
    $stmt->bind_param("i", $rival);
    $stmt->execute();

} else {

    $stmt = $conexion->prepare("UPDATE combates SET derrotas = derrotas + 1 WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $stmt = $conexion->prepare("UPDATE combates SET victorias = victorias + 1 WHERE id_usuario = ?");
    $stmt->bind_param("i", $rival);
    $stmt->execute();
}
?>

<?php require "./header.php"; ?>

<style>
.poke-img {
    width: 80px;
}
</style>

<div class="container center-align">
    <h4>Resultado del combate</h4>

    <br>

    <p>Tu equipo ganó: <?php echo $ganadas1; ?></p>
    <p>Rival ganó: <?php echo $ganadas2; ?></p>

    

    <br>

    <!-- 🏆 EQUIPO GANADOR -->
    <div class="card poke-card center-align" style="padding:20px;">
        
        <h6>Ganador:</h6>
        <h5><?php echo $nombre_ganador; ?></h5>

        <div>
            <?php foreach ($equipo_ganador as $poke): ?>
                <img class="poke-img"
                src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
            <?php endforeach; ?>
        </div>

    </div>

    <br>

    <br>

    <a href="combate.php" class="btn blue">Volver</a>
</div>

<?php require "./footer.php"; ?>