<?php 
session_start();
if (isset($_SESSION['trainer_id'])) {
    header("Location: ./principal.php");
    exit;
}

require __DIR__ . "/../header.php"; 
require __DIR__ . '/../musica.php';
?>

<?php
// Mensaje opcional (ej: registro exitoso)
$mensaje = $_GET['msg'] ?? '';
?>

<div class="container center-align" style="margin-top: 40px;">

    <h4 class="teal-text">Iniciar Sesión</h4>

    <?php if ($mensaje === 'registrado'): ?>
        <div class="card green lighten-4" style="padding:10px; margin-bottom:20px;">
            <span class="green-text text-darken-2">
                ✔ Registro exitoso, ahora inicia sesión 🎉
            </span>
        </div>
    <?php endif; ?>

    <!-- CONTENEDOR ESTILO RETRO -->
    <div class="nes-container is-rounded" style="max-width: 500px; margin:auto;">

        <form method="POST" action="./loguear.php">

            <div class="input-field">
                <input type="text" name="usuario" id="usuario" required />
                <label for="usuario">Teléfono o Nombre</label>
            </div>

            <div class="input-field">
                <input type="password" name="clave" id="clave" required />
                <label for="clave">Contraseña</label>
            </div>

            <br>

            <button type="submit" class="btn teal lighten-1 waves-effect waves-light">
                Iniciar Sesión
            </button>

        </form>

    </div>

    <br><br>

    <!-- REGISTRO -->
    <div class="center-align" style="margin-top: 25px;">
        <p>¿No tienes cuenta?</p>
        <a href="./registroVista.php" class="btn waves-effect waves-light blue lighten-1">
            Registrarse
        </a>
    </div>

    <br><br>

    <a href="../index.php" class="btn blue">
        Volver a la pantalla principal
    </a>

</div>

<?php require __DIR__ . "/../footer.php"; ?>
