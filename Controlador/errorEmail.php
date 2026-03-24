<?php require "../header.php"; ?>

<!-- Estructura principal del contenido de la página de error de registro -->
<div class="row">
    <div class="col s12 m5 offset-m3">
        <div class="card">
            <div class="card-content">
                <div class="center-align">
                    <!-- Título del mensaje de error de registro -->
                    <span class="card-title red-text text-darken-2">Error de Registro</span>
                    <!-- Mensaje que indica que el email ya está registrado -->
                    <p>El correo electrónico ya está registrado. Por favor, utiliza otro o verifica tus datos.</p>
                    <!-- Botón para regresar al formulario de registro -->
                    <a href="../registro.php" class="btn waves-effect waves-light blue">Volver al formulario</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "../footer.php"; ?>