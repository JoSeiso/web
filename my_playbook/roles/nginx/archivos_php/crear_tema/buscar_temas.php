<?php
session_start(); // Necesario para acceder a $_SESSION
include("../conexion.php");

$buscar = isset($_GET['buscar']) ? $conexion->real_escape_string($_GET['buscar']) : '';

$sql = "SELECT 
            t.ID_TEMA, 
            t.TITULO, 
            t.FECHA_CREACION, 
            u.NOMBRE_USUARIO, 
            GROUP_CONCAT(m.NOMBRE_MODELO SEPARATOR ', ') AS MODELOS
        FROM temas t
        INNER JOIN usuarios u ON t.ID_USUARIO = u.ID_USUARIO
        LEFT JOIN tema_modelo_bmw tm ON t.ID_TEMA = tm.ID_TEMA
        LEFT JOIN modelo_bmw m ON tm.ID_MODELO = m.ID_MODELO
        WHERE t.TITULO LIKE '%$buscar%' 
            OR u.NOMBRE_USUARIO LIKE '%$buscar%' 
            OR m.NOMBRE_MODELO LIKE '%$buscar%'
        GROUP BY t.ID_TEMA, t.TITULO, t.FECHA_CREACION, u.NOMBRE_USUARIO
        ORDER BY t.FECHA_CREACION DESC";

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha de Creación</th>
                    <th>Creador</th>
                    <th>Modelos Asociados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";
    while ($tema = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($tema['TITULO']) . "</td>
                <td>" . htmlspecialchars($tema['FECHA_CREACION']) . "</td>
                <td>" . htmlspecialchars($tema['NOMBRE_USUARIO']) . "</td>
                <td>" . htmlspecialchars($tema['MODELOS'] ?: 'Ninguno') . "</td>
                <td>
                    <a href='tema_comentario.php?id_tema=" . $tema['ID_TEMA'] . "'>Ver Tema</a>";

        // Mostrar botón de borrar solo si es admin
        if (isset($_SESSION['ROL']) && $_SESSION['ROL'] === 'ADMIN') {
            echo " | <a href='borrar_tema.php?id_tema=" . $tema['ID_TEMA'] . "' 
                      onclick=\"return confirm('¿Estás seguro de que deseas eliminar este tema?');\" 
                      style='color:red;'>Borrar</a>";
        }

        echo "</td></tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No hay resultados que coincidan con tu búsqueda.</p>";
}

$conexion->close();
?>
