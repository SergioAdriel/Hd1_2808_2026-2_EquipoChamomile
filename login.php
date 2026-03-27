<?php require "./header.php"; ?>

<?php
// Mensaje opcional (ej: registro exitoso)
$mensaje = $_GET['msg'] ?? '';
?>

<div class="row">
    <div class="col s12 m5 offset-m3">
        <div class="card">
            <div class="card-content">

                <span class="card-title center-align teal-text text-darken-2">
                    Iniciar Sesión
                </span>

                <!-- Mensaje de éxito -->
                <?php if ($mensaje === 'registrado') { ?>
                    <p class="green-text center-align">
                        ✅ Registro exitoso, ahora inicia sesión
                    </p>
                <?php } ?>

                <!-- FORMULARIO LOGIN -->
                <form method="POST" action="./Controlador/loguear.php">

                    <!-- Usuario (teléfono o nombre) -->
                    <div class="input-field">
                        <input type="text" name="usuario" id="usuario" required />
                        <label for="usuario">Teléfono o Nombre</label>
                    </div>

                    <!-- Contraseña -->
                    <div class="input-field">
                        <input type="password" name="clave" id="clave" required />
                        <label for="clave">Contraseña</label>
                    </div>

                    <!-- Botón -->
                    <div class="center-align">
                        <button type="submit" class="btn-large waves-effect waves-light teal lighten-1">
                            Iniciar Sesión
                        </button>
                    </div>

                </form>

                <!-- REGISTRO -->
                <div class="center-align" style="margin-top: 25px;">
                    <p>¿No tienes cuenta?</p>
                    <a href="registroVista.php" class="btn waves-effect waves-light blue lighten-1">
                        Registrarse
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require "./footer.php"; ?>