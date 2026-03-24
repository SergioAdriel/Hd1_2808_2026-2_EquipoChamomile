<?php require "./header.php"; ?>

<?php
// Iniciar la sesión para poder acceder a las variables de sesión
session_start();
// Obtener el número de teléfono del usuario desde la variable de sesión 'username'
$telefono = $_SESSION['username']; 

// Verificar si la variable de sesión 'username' está definida
if (!isset($telefono)) {
    // Si no está definida, redirigir al usuario a la página de inicio (index.php)
    header("location: ./index.php");
} else {
    ?>
    <div class="container" style="max-width: 400px; margin-top: 50px;">
        <h4 class="center-align red-text text-darken-2">Eliminar Usuario</h4>
        
        <!-- Formulario para eliminar el usuario -->
        <form method="POST" action="./Controlador/deleteUsuario.php" style="margin-top: 30px;">
            <!-- Campo para ingresar el número de teléfono -->
            <div class="input-field">
                <label for="telefono">Número de Teléfono</label>
                <input type="text" name="telefono" placeholder="Ingresa el número de teléfono" required>
            </div>
            
            <!-- Botón para enviar el formulario y eliminar el usuario -->
            <div class="center-align" style="margin-top: 20px;">
                <button type="submit" class="btn waves-effect waves-light red lighten-1">Eliminar Usuario</button>
            </div>
        </form>

        <!-- Enlace para regresar a la página principal -->
        <div class="center-align" style="margin-top: 20px;">
            <a href="principal.php" class="btn waves-effect waves-light teal lighten-1">Regresar</a>
        </div>
    </div>
<?php
} 
?>

<?php require "./footer.php"; ?>
