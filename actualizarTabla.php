<?php
// Incluir el archivo de conexión a la base de datos
require "./Controlador/conexion.php";
// Establecer el conjunto de caracteres de la conexión a UTF-8
mysqli_set_charset($conexion, 'utf8');

// Definir la consulta SQL para obtener todos los registros de la tabla 'pokemon'
$consulta_sql = "SELECT * FROM pokemon";

// Ejecutar la consulta SQL y almacenar el resultado
$resultado = $conexion->query($consulta_sql);

// Verificar si hay registros en la consulta
if (mysqli_num_rows($resultado) > 0) {
    // Si hay registros, recorrer cada fila del resultado
    while ($row = mysqli_fetch_assoc($resultado)) {
        // Mostrar cada registro dentro de una fila de la tabla HTML
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
        echo "</tr>";
    }
} else {
    // Si no hay registros, mostrar un mensaje indicando que no hay datos
    echo "<tr><td colspan='3'>Sin registros</td></tr>";
}
?>