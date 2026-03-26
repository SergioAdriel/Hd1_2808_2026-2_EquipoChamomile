<?php require "./header.php"; ?>
 <!-- Incluye el archivo de cabecera -->

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$trainerId = $_SESSION['trainer_id'] ?? null;
$trainerName = $_SESSION['trainer_name'] ?? 'Entrenador';

if (!$trainerId) {
    header("location: ./index.php");
    exit;
} else {
    // Mensaje de bienvenida con estilos
    echo "<div class='container center-align' style='margin-top: 20px;'>
            <h4 class='blue-text text-darken-2'>¡Bienvenido, $trainerName!</h4>
            <h5>ID de Entrenador: <span class='teal-text'>$trainerId</span></h5>
          </div>";

    // Botón de salir que redirige al controlador para cerrar sesión
    echo "<div class='container center-align' style='margin-top: 20px;'>
            <a href='Controlador/salir.php' class='btn waves-effect waves-light teal lighten-1'>Salir</a>
          </div>";

    // Conexión a la base de datos
    require "./Controlador/conexion.php";  
    mysqli_set_charset($conexion, 'utf8');

    // Consulta SQL para obtener todos los registros de la Pokédex
    $consulta_sql = "SELECT * FROM pokemon";
    $resultado = $conexion->query($consulta_sql);  // Ejecuta la consulta SQL

    $count = mysqli_num_rows($resultado);  // Cuenta la cantidad de filas obtenidas en el resultado

    echo "<div class='container' style='overflow-x:auto; margin-top: 20px;'>";
    echo "<table class='highlight bordered responsive-table centered'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>";

    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<h5 class='center-align red-text'>Sin ningún Pokémon registrado</h5>";
    }

    echo "</div>";

    echo "<div class='container center-align' style='margin-top: 20px;'>
            <a href='eliminarUsuario.php' class='btn waves-effect waves-light red lighten-1' style='margin-right: 10px;'>Eliminar Pokémon</a>
            <a href='registro.php' class='btn waves-effect waves-light blue lighten-1'>Registrar Pokémon</a>
          </div>";
}
?>
<?php require "./footer.php"; ?>  <!-- Incluye el archivo de pie de página -->

<!-- Script para actualizar la tabla dinámicamente -->
<script>
function actualizarTabla() {
    fetch('actualizarTabla.php')  // Hace una solicitud al archivo 'actualizarTabla.php' para obtener los datos más recientes
        .then(response => response.text())  // Convierte la respuesta a texto
        .then(data => {
            document.querySelector('tbody').innerHTML = data;  // Reemplaza el contenido de la tabla con los nuevos datos
        })
        .catch(error => console.error('Error:', error));  // Muestra un error en consola si ocurre un problema
}

// Llamar a la función cada 5 segundos para mantener la tabla actualizada
setInterval(actualizarTabla, 5000);
</script>
</body>
</html>
