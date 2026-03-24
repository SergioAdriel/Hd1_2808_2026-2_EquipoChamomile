<?php require "./header.php"; ?>
 <!-- Incluye el archivo de cabecera -->

<?php
session_start();  // Inicia una sesión PHP
$telefono = $_SESSION['username'];  // Asigna el valor del usuario (número de teléfono) a la variable $telefono

if (!isset($telefono)) {  // Si no está definido el teléfono (usuario no logueado)
    header("location: ./index.php");  // Redirige a la página de inicio (login)
} else {  
    // Mensaje de bienvenida con estilos
    echo "<div class='container center-align' style='margin-top: 20px;'>
            <h4 class='blue-text text-darken-2'>¡Bienvenido!</h4>
            <h5>Tu número de teléfono es <span class='teal-text'>$telefono</span></h5>
          </div>";

    // Botón de salir que redirige al controlador para cerrar sesión
    echo "<div class='container center-align' style='margin-top: 20px;'>
            <a href='Controlador/salir.php' class='btn waves-effect waves-light teal lighten-1'>Salir</a>
          </div>";

    // Conexión a la base de datos
    require "./Controlador/conexion.php";  
    mysqli_set_charset($conexion, 'utf8');  // Establece la codificación de caracteres a UTF-8

    // Consulta SQL para obtener todos los registros de la tabla 'residente'
    $consulta_sql = "SELECT * FROM residente";  
    $resultado = $conexion->query($consulta_sql);  // Ejecuta la consulta SQL

    $count = mysqli_num_rows($resultado);  // Cuenta la cantidad de filas obtenidas en el resultado

    // Muestra una tabla con los registros de los residentes
    echo "<div class='container' style='overflow-x:auto; margin-top: 20px;'>";
    echo "<table class='highlight bordered responsive-table centered'>
            <thead>
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Teléfono</th>
                    <th>Edificio</th>
                    <th>Número de Departamento</th>
                    <th>Correo Electrónico</th>
                    <th>Contraseña</th>
                    <th>Fecha de Registro</th>
                    <th>Permisos</th>
                </tr>
            </thead>
            <tbody>";

    if ($count > 0) {  // Si hay registros en la base de datos
        // Muestra cada registro en una fila de la tabla
        while ($row = mysqli_fetch_assoc($resultado)) {  
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nombre_usuario']) . "</td>";  // Muestra el nombre de usuario
            echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";  // Muestra el teléfono
            echo "<td>" . htmlspecialchars($row['letra_edificio']) . "</td>";  // Muestra la letra del edificio
            echo "<td>" . htmlspecialchars($row['numero_departamento']) . "</td>";  // Muestra el número de departamento
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";  // Muestra el correo electrónico
            echo "<td>" . htmlspecialchars($row['password']) . "</td>";  // Muestra la contraseña
            echo "<td>" . htmlspecialchars($row['fecha_registro']) . "</td>";  // Muestra la fecha de registro
            echo "<td>" . htmlspecialchars($row['permisos']) . "</td>";  // Muestra los permisos
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        // Si no hay registros, muestra un mensaje de "Sin ningún registro"
        echo "<h5 class='center-align red-text'>Sin ningún registro</h5>";
    }

    echo "</div>"; 

    // Botones para eliminar usuario o registrar uno nuevo
    echo "<div class='container center-align' style='margin-top: 20px;'>
            <a href='eliminarUsuario.php' class='btn waves-effect waves-light red lighten-1' style='margin-right: 10px;'>Eliminar Usuario</a>
            <a href='registro.php' class='btn waves-effect waves-light blue lighten-1'>Registro</a>
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
