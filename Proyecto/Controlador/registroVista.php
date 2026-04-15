<?php 
session_start();
if (isset($_SESSION['trainer_id'])) {
    header("Location: ./principal.php");
    exit;
}

require __DIR__ . "/../header.php"; 
require __DIR__ . '/../musica.php';
?>

<div class="container center-align" style="margin-top: 40px;">

    <h4 class="red-text">Registro de Entrenador</h4>

    <!-- 🔥 MENSAJES -->
    <?php if (isset($_GET['error'])): ?>
        <div class="card red lighten-4" style="padding:10px; margin-bottom:20px;">
            <span class="red-text text-darken-2">
                <?php
                if ($_GET['error'] == 'telefono_invalido') {
                    echo "El teléfono debe tener exactamente 10 dígitos";
                } else {
                    echo "El usuario ya está registrado";
                }
                ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['ok'])): ?>
        <div class="card green lighten-4" style="padding:10px; margin-bottom:20px;">
            <span class="green-text text-darken-2">
                Registro exitoso
            </span>
        </div>
    <?php endif; ?>


    <!-- Formulario -->
    <div class="nes-container is-rounded" style="max-width: 500px; margin:auto;">

        <form action="./enviarRegistro.php" method="POST">
            
            <!-- 👤 NOMBRE -->
            <div class="input-field">
                <input type="text" name="nombre" required>
                <label>Nombre de entrenador</label>
            </div>

            <!-- 📱 TELÉFONO -->
            <div class="input-field">
                <input type="text" name="telefono" required
                       pattern="[0-9]{10}"
                       maxlength="10"
                       title="Debe contener exactamente 10 dígitos">
                <label>Teléfono (10 dígitos)</label>
            </div>

            <!-- 🔑 CONTRASEÑA -->
            <div class="input-field">
                <input type="password" name="clave" required>
                <label>Contraseña</label>
            </div>

            <br>

            <button class="btn green waves-effect waves-light">
                Registrarse
            </button>

        </form>

    </div>

    <br><br>

    <div class="center-align" style="margin-top: 25px;">
        <p>¿Ya tienes cuenta?</p>
        <a href="./login.php" class="btn waves-effect waves-light blue lighten-1">
            Iniciar sesion
        </a>
    </div>

    <br><br>
    
    <!-- 🔥 BOTÓN REGRESAR -->
    <a href="../index.php" class="btn blue">
        Volver a la pantalla principal
    </a>
</div>

<?php require __DIR__ . "/../footer.php"; ?>