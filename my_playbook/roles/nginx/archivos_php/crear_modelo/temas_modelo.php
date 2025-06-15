<?php
session_start();

// Incluir archivo de conexión a la base de datos
include("../conexion.php");

// Verificar si se ha pasado el parámetro 'id_modelo'
if (!isset($_GET['id_modelo'])) {
    echo "Modelo no encontrado.";
    exit();
}

$id_modelo = $_GET['id_modelo'];

// Obtener los temas relacionados con el modelo seleccionado
$sql_temas = "SELECT t.ID_TEMA, t.TITULO, t.DESCRIPCION, t.FECHA_CREACION 
              FROM temas t
              INNER JOIN tema_modelo_bmw tm ON t.ID_TEMA = tm.ID_TEMA
              WHERE tm.ID_MODELO = ?";
$stmt_temas = $conexion->prepare($sql_temas);
$stmt_temas->bind_param("i", $id_modelo);
$stmt_temas->execute();
$result_temas = $stmt_temas->get_result();

// Obtener información del modelo
$sql_modelo = "SELECT NOMBRE_MODELO, AÑO_INICIO_PRODUCCION, AÑO_FIN_PRODUCCION 
               FROM modelo_bmw WHERE ID_MODELO = ?";
$stmt_modelo = $conexion->prepare($sql_modelo);
$stmt_modelo->bind_param("i", $id_modelo);
$stmt_modelo->execute();
$result_modelo = $stmt_modelo->get_result();
$modelo = $result_modelo->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temas de Modelo BMW</title>
    <link rel="stylesheet" href="../css/temas_modelo.css">
</head>
<body>
    <h1>Temas del Modelo <?php echo htmlspecialchars($modelo['NOMBRE_MODELO']); ?> 
    (<?php echo $modelo['AÑO_INICIO_PRODUCCION']; ?> - <?php echo $modelo['AÑO_FIN_PRODUCCION'] ? $modelo['AÑO_FIN_PRODUCCION'] : "Actualmente en producción"; ?>)
    </h1>

    <?php
    // Verificar si hay temas disponibles
    if ($result_temas->num_rows > 0) {
        // Mostrar los temas relacionados con este modelo
        while ($tema = $result_temas->fetch_assoc()) {
            echo "<p><strong><a class='titulo-link' href='../crear_tema/tema_comentario.php?id_tema=" . $tema['ID_TEMA'] . "'>" . 
                 htmlspecialchars($tema['TITULO']) . "</a></strong><br>" . 
                 htmlspecialchars($tema['DESCRIPCION']) . "<small>Creado el: " . $tema['FECHA_CREACION'] . "</small></p>";
        }
    } else {
        echo "<p class='no-temas'>No hay temas relacionados con este modelo.</p>";
    }

    // Cerrar la conexión
    $conexion->close();
    ?>

    <a class='volver-a-lista' href="pagina_modelos.php">Volver a la lista de modelos</a>
</body>
</html>
