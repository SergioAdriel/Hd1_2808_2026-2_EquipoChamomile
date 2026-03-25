<?php require "./header.php"; ?>

<?php
// Iniciar la sesión para poder acceder a las variables de sesión
session_start();
// Obtener el ID del entrenador desde la variable de sesión 'trainer_id'
$trainerId = $_SESSION['trainer_id'];

// Verificar si el ID está definido
if (!isset($trainerId)) {
    header("location: ./index.php");
    exit;
} else {
    ?>
    <div class="container" style="max-width: 400px; margin-top: 50px;">
        <h4 class="center-align red-text text-darken-2">Eliminar Pokémon</h4>
        
        <!-- Formulario para eliminar el Pokémon -->
        <form method="POST" action="./Controlador/deleteUsuario.php" style="margin-top: 30px;">
            <!-- Campo para ingresar el ID del Pokémon -->
            <div class="input-field">
                <label for="id">ID de Pokémon</label>
                <input type="number" name="id" placeholder="Ingresa el ID del Pokémon" required>
            </div>
            
            <!-- Botón para enviar el formulario y eliminar el Pokémon -->
            <div class="center-align" style="margin-top: 20px;">
                <button type="submit" class="btn waves-effect waves-light red lighten-1">Eliminar Pokémon</button>
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
