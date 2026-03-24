<?php
// Incluir el archivo de conexión a la base de datos
require "./Controlador/conexion.php";
// Establecer el conjunto de caracteres de la conexión a UTF-8
mysqli_set_charset($conexion, 'utf8');

// Definir la consulta SQL para obtener todos los registros de la tabla 'residente'
$consulta_sql = "SELECT * FROM residente";

// Ejecutar la consulta SQL y almacenar el resultado
$resultado = $conexion->query($consulta_sql);

// Verificar si hay registros en la consulta
if (mysqli_num_rows($resultado) > 0) {
    // Si hay registros, recorrer cada fila del resultado
    while ($row = mysqli_fetch_assoc($resultado)) {
        // Mostrar cada registro dentro de una fila de la tabla HTML
        echo "<tr>";
        // Mostrar el nombre del usuario
        echo "<td>" . htmlspecialchars($row['nombre_usuario']) . "</td>";
        // Mostrar el teléfono del usuario
        echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
        // Mostrar la letra del edificio
        echo "<td>" . htmlspecialchars($row['letra_edificio']) . "</td>"; 
        // Mostrar el número del departamento
        echo "<td>" . htmlspecialchars($row['numero_departamento']) . "</td>"; 
        // Mostrar el email del usuario
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        // Mostrar la contraseña del usuario
        echo "<td>" . htmlspecialchars($row['password']) . "</td>"; 
        // Mostrar la fecha de registro
        echo "<td>" . htmlspecialchars($row['fecha_registro']) . "</td>";
        // Mostrar los permisos del usuario
        echo "<td>" . htmlspecialchars($row['permisos']) . "</td>";
        // Cerrar la fila de la tabla
        echo "</tr>";
    }
} else {
    // Si no hay registros, mostrar un mensaje indicando que no hay datos
    echo "<tr><td colspan='8'>Sin registros</td></tr>";
}
?>