<?php require "./header.php"; ?>

<!-- Estructura de la página de inicio de sesión -->
<div class="row">
    <div class="col s12 m5 offset-m3">
        <div class="card">
            <div class="card-content">
                <!-- Título que indica al usuario que ingrese sus datos -->
                <span class="card-title center-align teal-text text-darken-2">Ingresa tus Datos</span>
                
                <!-- Formulario para ingresar el número de teléfono y la contraseña -->
                <form method="POST" action="Controlador/loguear.php">
                    <!-- Campo para ingresar el número de teléfono -->
                    <div class="input-field">
                        <input type="text" name="telefono" id="telefono" placeholder="Número de Teléfono" required />
                        <label for="telefono">Número de Teléfono</label>
                    </div>
                    
                    <!-- Campo para ingresar la contraseña -->
                    <div class="input-field">
                        <input type="password" name="clave" id="clave" placeholder="Contraseña" required />
                        <label for="clave">Contraseña</label>
                    </div>
                    
                    <!-- Botón de envío centrado -->
                    <div class="center-align">
                        <button type="submit" class="btn-large waves-effect waves-light teal lighten-1" style="margin-right: 10px;">Iniciar Sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require "./footer.php"; ?>