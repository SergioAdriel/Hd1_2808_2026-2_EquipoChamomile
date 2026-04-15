<?php
session_start();
require __DIR__ . "/../Controlador/conexion.php";
require __DIR__ . '/../musica.php';
require __DIR__ . "/../header.php";

// 🔒 validar sesión
if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../Controlador/login.php");
    exit;
}

$id_usuario = $_SESSION['trainer_id'];
$rival = $_POST['rival'] ?? null;

// 🔒 validar rival
if (!$rival || $rival == $id_usuario) {
    header("Location: ./combate.php");
    exit;
}

// Obtener equipos para mostrar en la animación
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

// Obtener nombres
$sql = "SELECT nombre FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$nombre_user = $stmt->get_result()->fetch_assoc()['nombre'];

$stmt->bind_param("i", $rival);
$stmt->execute();
$nombre_rival = $stmt->get_result()->fetch_assoc()['nombre'];

// Variable para controlar la animación
$mostrar_animacion = !isset($_GET['resultado']);
?>

<?php if ($mostrar_animacion): ?>
<!-- PANTALLA DE CARGA CON HEADER Y FOOTER -->
<div class="container center-align" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="battle-container" style="width: 100%; max-width: 1200px;">
        <div class="vs-central">
            <div class="vs-text">⚔️ VS ⚔️</div>
        </div>

        <div class="row">
            <div class="col s12 m5">
                <div class="trainer-card">
                    <i class="material-icons medium">person</i>
                    <h5 id="nombreUsuario"><?php echo htmlspecialchars($nombre_user); ?></h5>
                    <div class="pokemon-battle" id="pokemonUsuario">
                        <?php foreach ($equipo1 as $poke): ?>
                            <img class="pokemon-sprite"
                                src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col s12 m2"></div>

            <div class="col s12 m5">
                <div class="trainer-card">
                    <i class="material-icons medium">sports_mma</i>
                    <h5 id="nombreRival"><?php echo htmlspecialchars($nombre_rival); ?></h5>
                    <div class="pokemon-battle" id="pokemonRival">
                        <?php foreach ($equipo2 as $poke): ?>
                            <img class="pokemon-sprite"
                                src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="power-bar-container">
            <div class="power-label">
                <span>⚔️ CARGANDO BATALLA ⚔️</span>
                <span id="poderPorcentaje">0%</span>
            </div>
            <div class="power-bar-bg">
                <div class="power-bar" id="powerBar"></div>
            </div>
        </div>

        <div class="battle-messages" id="battleMessages">
            <div class="battle-message">🎮 ¡Preparando la batalla Pokémon!</div>
        </div>

        <div class="center-align">
            <div class="preloader-wrapper small active">
                <div class="spinner-layer spinner-red-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="./css/batalla.css">
<script src="./js/batalla.js"></script>
<input type="hidden" id="rivalId" value="<?php echo $rival; ?>">

<?php require __DIR__ . "/../footer.php"; ?>

<?php else: 
    // ⚔️ Calcular la batalla
    $ganadas1 = 0;
    $ganadas2 = 0;
    $detalle_batallas = [];

    for ($i = 0; $i < 6; $i++) {
        $p1 = json_decode(file_get_contents("https://pokeapi.co/api/v2/pokemon/".$equipo1[$i]), true);
        $p2 = json_decode(file_get_contents("https://pokeapi.co/api/v2/pokemon/".$equipo2[$i]), true);

        $stats1 = array_sum(array_column($p1['stats'], 'base_stat'));
        $stats2 = array_sum(array_column($p2['stats'], 'base_stat'));
        
        $nombre_p1 = ucfirst($p1['name']);
        $nombre_p2 = ucfirst($p2['name']);
        $img_p1 = $equipo1[$i];
        $img_p2 = $equipo2[$i];

        if ($stats1 > $stats2) {
            $ganadas1++;
            $detalle_batallas[] = [
                'resultado' => 'victoria',
                'texto' => "✓ $nombre_p1 vs $nombre_p2 → ¡VICTORIA!",
                'pokemon_ganador' => $nombre_p1,
                'pokemon_perdedor' => $nombre_p2,
                'img_ganador' => $img_p1,
                'img_perdedor' => $img_p2
            ];
        } else {
            $ganadas2++;
            $detalle_batallas[] = [
                'resultado' => 'derrota',
                'texto' => "✗ $nombre_p1 vs $nombre_p2 → DERROTA",
                'pokemon_ganador' => $nombre_p2,
                'pokemon_perdedor' => $nombre_p1,
                'img_ganador' => $img_p2,
                'img_perdedor' => $img_p1
            ];
        }
    }

    $ganador = ($ganadas1 > $ganadas2) ? $id_usuario : $rival;

    // 🔥 definir equipo ganador
    if ($ganador == $id_usuario) {
        $nombre_ganador = $nombre_user;
        $equipo_ganador = $equipo1;
        $resultado_final = "victoria";
    } else {
        $nombre_ganador = $nombre_rival;
        $equipo_ganador = $equipo2;
        $resultado_final = "derrota";
    }

    // 🔥 actualizar BD
    $check_user = $conexion->prepare("SELECT victorias FROM combates WHERE id_usuario = ?");
    $check_user->bind_param("i", $id_usuario);
    $check_user->execute();
    $existe_user = $check_user->get_result()->num_rows > 0;

    $check_rival = $conexion->prepare("SELECT victorias FROM combates WHERE id_usuario = ?");
    $check_rival->bind_param("i", $rival);
    $check_rival->execute();
    $existe_rival = $check_rival->get_result()->num_rows > 0;

    if ($ganador == $id_usuario) {
        if ($existe_user) {
            $stmt = $conexion->prepare("UPDATE combates SET victorias = victorias + 1 WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
        } else {
            $stmt = $conexion->prepare("INSERT INTO combates (id_usuario, victorias, derrotas) VALUES (?, 1, 0)");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
        }
        
        if ($existe_rival) {
            $stmt = $conexion->prepare("UPDATE combates SET derrotas = derrotas + 1 WHERE id_usuario = ?");
            $stmt->bind_param("i", $rival);
            $stmt->execute();
        } else {
            $stmt = $conexion->prepare("INSERT INTO combates (id_usuario, victorias, derrotas) VALUES (?, 0, 1)");
            $stmt->bind_param("i", $rival);
            $stmt->execute();
        }
    } else {
        if ($existe_user) {
            $stmt = $conexion->prepare("UPDATE combates SET derrotas = derrotas + 1 WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
        } else {
            $stmt = $conexion->prepare("INSERT INTO combates (id_usuario, victorias, derrotas) VALUES (?, 0, 1)");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
        }
        
        if ($existe_rival) {
            $stmt = $conexion->prepare("UPDATE combates SET victorias = victorias + 1 WHERE id_usuario = ?");
            $stmt->bind_param("i", $rival);
            $stmt->execute();
        } else {
            $stmt = $conexion->prepare("INSERT INTO combates (id_usuario, victorias, derrotas) VALUES (?, 1, 0)");
            $stmt->bind_param("i", $rival);
            $stmt->execute();
        }
    }
?>

<!-- RESULTADOS CON HEADER Y FOOTER -->
<div class="container center-align resultado-card">
    <h4>⚔️ Resultado del combate ⚔️</h4>
    <br>

    <div class="row">
        <div class="col s12 m6">
            <div class="card blue-grey darken-1">
                <div class="card-content white-text center-align">
                    <span class="card-title"><?php echo htmlspecialchars($nombre_user); ?></span>
                    <div class="marcador <?php echo ($ganadas1 > $ganadas2) ? 'victoria' : 'derrota'; ?>">
                        <?php echo $ganadas1; ?>
                    </div>
                    <p>VICTORIAS EN ESTA BATALLA</p>
                </div>
            </div>
        </div>
        
        <div class="col s12 m6">
            <div class="card blue-grey darken-1">
                <div class="card-content white-text center-align">
                    <span class="card-title"><?php echo htmlspecialchars($nombre_rival); ?></span>
                    <div class="marcador <?php echo ($ganadas2 > $ganadas1) ? 'victoria' : 'derrota'; ?>">
                        <?php echo $ganadas2; ?>
                    </div>
                    <p>VICTORIAS EN ESTA BATALLA</p>
                </div>
            </div>
        </div>
    </div>

    <?php
    $stmt = $conexion->prepare("SELECT victorias, derrotas FROM combates WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stats_user_total = $stmt->get_result()->fetch_assoc();
    if (!$stats_user_total) $stats_user_total = ['victorias' => 0, 'derrotas' => 0];

    $stmt = $conexion->prepare("SELECT victorias, derrotas FROM combates WHERE id_usuario = ?");
    $stmt->bind_param("i", $rival);
    $stmt->execute();
    $stats_rival_total = $stmt->get_result()->fetch_assoc();
    if (!$stats_rival_total) $stats_rival_total = ['victorias' => 0, 'derrotas' => 0];
    ?>

    <div class="card-panel grey lighten-4" style="margin-top: 20px;">
        <h6 class="center-align">📊 HISTORIAL TOTAL DE COMBATES</h6>
        <div class="row">
            <div class="col s12 m6">
                <div class="center-align">
                    <p><strong><?php echo htmlspecialchars($nombre_user); ?></strong></p>
                    <p>🏆 Victorias totales: <strong class="green-text"><?php echo $stats_user_total['victorias']; ?></strong></p>
                    <p>💔 Derrotas totales: <strong class="red-text"><?php echo $stats_user_total['derrotas']; ?></strong></p>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="center-align">
                    <p><strong><?php echo htmlspecialchars($nombre_rival); ?></strong></p>
                    <p>🏆 Victorias totales: <strong class="green-text"><?php echo $stats_rival_total['victorias']; ?></strong></p>
                    <p>💔 Derrotas totales: <strong class="red-text"><?php echo $stats_rival_total['derrotas']; ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card resultado-card" style="padding:20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h6>🏆 EQUIPO GANADOR 🏆</h6>
        <h5 class="<?php echo $resultado_final; ?>">
            <?php 
            if ($ganadas1 > $ganadas2) {
                echo "✨ " . htmlspecialchars($nombre_ganador) . " ✨";
                echo "<br><small>¡FELICIDADES! Has ganado la batalla</small>";
            } elseif ($ganadas2 > $ganadas1) {
                echo "💔 " . htmlspecialchars($nombre_ganador) . " 💔";
                echo "<br><small>¡Oh no! Has perdido la batalla</small>";
            } else {
                echo "🤝 ¡EMPATE! 🤝";
                echo "<br><small>Ha sido un combate muy reñido</small>";
            }
            ?>
        </h5>
        <div>
            <?php foreach ($equipo_ganador as $poke): ?>
                <img class="poke-img"
                    src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png"
                    style="animation: bounce 0.5s ease;">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card" style="margin-top: 20px;">
        <div class="card-content">
            <span class="card-title center-align">📊 Detalle de la batalla</span>
            <div class="row">
                <?php foreach ($detalle_batallas as $index => $detalle): ?>
                    <div class="col s12 m6 l4">
                        <div class="detalle-item <?php echo $detalle['resultado'] == 'victoria' ? 'green lighten-4' : 'red lighten-4'; ?>" 
                             style="padding: 12px; margin: 8px; border-radius: 10px; text-align: center;">
                            <strong>Pelea <?php echo $index + 1; ?></strong>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin: 10px 0;">
                                <div style="text-align: center;">
                                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $detalle['img_ganador']; ?>.png" 
                                         style="width: 50px;">
                                    <br>
                                    <small class="green-text"><?php echo $detalle['pokemon_ganador']; ?></small>
                                </div>
                                <div class="vs-small" style="font-weight: bold; color: #f44336;">VS</div>
                                <div style="text-align: center;">
                                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $detalle['img_perdedor']; ?>.png" 
                                         style="width: 50px; opacity: 0.6;">
                                    <br>
                                    <small class="red-text"><?php echo $detalle['pokemon_perdedor']; ?></small>
                                </div>
                            </div>
                            <div class="resultado-badge <?php echo $detalle['resultado']; ?>">
                                <?php echo $detalle['resultado'] == 'victoria' ? '🏆 VICTORIA' : '💔 DERROTA'; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <br>
    <a href="./combate.php" class="btn waves-effect waves-light blue">
        <i class="material-icons left">arrow_back</i>
        Volver al mapa de combate
    </a>
</div>

<link rel="stylesheet" href="./css/batalla.css">

<?php require __DIR__ . "/../footer.php"; ?>
<?php endif; ?>