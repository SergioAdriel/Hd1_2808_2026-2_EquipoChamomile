<?php require "./header.php"; ?>
 <!-- Incluye el archivo de cabecera -->

<?php
session_start();  // Inicia la sesión PHP
$telefono = $_SESSION['username'];  // Asigna el valor del usuario (número de teléfono) a la variable $telefono

if (!isset($telefono)) {  // Si no está definido el teléfono (usuario no logueado)
    header("location: ./index.php");  // Redirige a la página de inicio (login)
} else{
?>

<!-- Formulario de registro de usuario -->
<div class="container" style="max-width: 600px; margin-top: 20px;">
    <h4 class="center-align blue-text text-darken-2">Registro de Usuario</h4>
    
    <!-- Formulario para enviar los datos del nuevo usuario -->
    <form action="./Controlador/enviarRegistro.php" method="post" style="margin-top: 20px;">
        
        <!-- Campo para ingresar el nombre de usuario -->
        <div class="input-field">
            <input type="text" id="nombre_usuario" name="nombre_usuario" required maxlength="100" placeholder="Ingresa tu Nombre">
            <label for="nombre_usuario">Nombre de Usuario:</label>
        </div>
        
        <!-- Campo para seleccionar el edificio -->
        <div class="input-field">
            <select name="letra_edificio" id="letra_edificio" required>
                <option value="" disabled selected>Selecciona un edificio</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
            <label for="letra_edificio">Edificio:</label>
        </div>

        <!-- Campo para seleccionar el número de departamento -->
        <div class="input-field">
            <select name="numero_departamento" id="numero_departamento" required>
                <option value="" disabled selected>Selecciona un número de departamento</option>
                <option value="101">101</option>
                <option value="102">102</option>
                <option value="201">201</option>
                <option value="202">202</option>
            </select>
            <label for="numero_departamento">Número de Departamento:</label>
        </div>
        
        <!-- Campo para ingresar el correo electrónico -->
        <div class="input-field">
            <input type="email" id="email" name="email" required maxlength="255" placeholder="Ingresa tu correo">
            <label for="email">Correo:</label>
        </div>
        
        <!-- Campo para ingresar el teléfono -->
        <div class="input-field">
            <input type="text" id="telefono" name="telefono" required maxlength="10" placeholder="Ingresa tu teléfono">
            <label for="telefono">Teléfono:</label>
        </div>
        
        <!-- Campo para ingresar la contraseña -->
        <div class="input-field">
            <input type="password" id="password" name="password" minlength="6" maxlength="10" placeholder="Ingresa tu password">
            <label for="password">Contraseña:</label>
        </div>
        
        <!-- Botón para enviar el formulario -->
        <div class="center-align" style="margin-top: 20px;">
            <button type="submit" name="submit" class="btn waves-effect waves-light blue lighten-1">Enviar Registro</button>
        </div>
    </form>

    <!-- Enlace adicional para regresar a la página principal -->
    <div class="center-align" style="margin-top: 20px;">
        <a href="principal.php" class="btn waves-effect waves-light teal lighten-1">Regresar</a>
    </div>
</div>

<?php
}  // Fin del bloque else
?>
<?php require "./footer.php"; ?>  <!-- Incluye el archivo de pie de página -->

<!-- Script para inicializar los componentes de Materialize -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        M.AutoInit(); // Inicializa todos los componentes Materialize, como los select
    });
</script>
