<?php
session_start();
include("../conexion.php");
$buscar = isset($_GET['buscar']) ? $conexion->real_escape_string($_GET['buscar']) : '';

$condicion_busqueda = "";
if (!empty($buscar)) {
    $condicion_busqueda = "WHERE t.titulo LIKE '%$buscar%' 
                           OR u.nombre_usuario LIKE '%$buscar%' 
                           OR m.nombre_modelo LIKE '%$buscar%'";
}

// Consulta para obtener los temas con el nombre del usuario y los modelos de BMW asociados
$sql = "SELECT 
            t.id_tema, 
            t.titulo, 
            t.fecha_creacion, 
            u.nombre_usuario, 
            GROUP_CONCAT(m.nombre_modelo SEPARATOR ', ') AS modelos
        FROM temas t
        INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
        LEFT JOIN tema_modelo_bmw tm ON t.id_tema = tm.id_tema
        LEFT JOIN modelo_bmw m ON tm.id_modelo = m.id_modelo
        $condicion_busqueda
        GROUP BY t.id_tema, t.titulo, t.fecha_creacion, u.nombre_usuario
        ORDER BY t.fecha_creacion DESC";

$result = $conexion->query($sql);

// Generar HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/listar_temas.css">
    <title>Lista de Temas</title>
</head>
<body>
    <h1>Temas del Foro</h1>
    <div id="nuevo_tema">
        <a href="crear_tema.php"><h3>Crear Tema</h3></a>
    </div>

<input type="text" id="buscador" placeholder="Buscar temas..." style="padding:8px; width: 250px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; margin-bottom: 20px;">
<div id="resultados">
    <!-- Aquí se cargarán los temas dinámicamente -->
</div>

<?php $conexion->close(); ?>
<div id="index">
    <a href="../index.php">Volver al Inicio</a>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const buscador = document.getElementById("buscador");
    const resultados = document.getElementById("resultados");

    function buscarTemas() {
        const valor = buscador.value;

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "buscar_temas.php?buscar=" + encodeURIComponent(valor), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                resultados.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    buscador.addEventListener("keyup", buscarTemas);

    // Ejecutar búsqueda inicial para mostrar todo
    buscarTemas();
});
</script>

</body>
</html>
