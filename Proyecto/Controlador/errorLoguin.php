<?php require __DIR__ . "/../header.php"; ?>

<?php
// Obtener el tipo de error desde la URL
$mensaje = $_GET['msg'] ?? '';
?>
<?php require __DIR__ . '/../musica.php'; ?> 

<div class="row">
    <div class="col s12 m5 offset-m3">
        <div class="card">
            <div class="card-content center-align">

                <span class="card-title red-text text-darken-2">
                    Error de Login
                </span>

                <?php if ($mensaje === 'usuario') { ?>
                    <p>❌ El usuario o teléfono no existe.</p>

                <?php } elseif ($mensaje === 'clave') { ?>
                    <p>❌ La contraseña es incorrecta.</p>

                <?php } else { ?>
                    <p>❌ Ocurrió un error al iniciar sesión.</p>
                <?php } ?>

                <p class="grey-text">Intenta nuevamente con tus datos correctos.</p>

                <div style="margin-top: 20px;">
                    <a href="./login.php" class="btn waves-effect waves-light blue">
                        Regresar
                    </a>
                </div>

                <div style="margin-top: 15px;">
                    <p>¿No tienes cuenta?</p>
                    <a href="./registroVista.php" class="btn green">
                        Registrarse
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . "/../footer.php"; ?>