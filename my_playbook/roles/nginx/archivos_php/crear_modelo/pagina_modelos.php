<?php
session_start();

// Incluir archivo de conexión a la base de datos
include("../conexion.php");

// Obtener todos los modelos de BMW
$sql_modelos = "SELECT * FROM modelo_bmw";
$result_modelos = $conexion->query($sql_modelos);

?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pagina_modelos.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modelos BMW y Temas</title>
    </head>
    <body>
    <h1>Modelos BMW y Temas</h1>

    <?php
    // Verificar si hay modelos disponibles
    if ($result_modelos->num_rows > 0) {
        // Mostrar cada modelo
        while ($modelo = $result_modelos->fetch_assoc()) {
            // Crear un enlace al modelo con el ID_MODELO como parámetro
            echo "<h2><a href='temas_modelo.php?id_modelo=" . $modelo['ID_MODELO'] . "'>" . 
                 htmlspecialchars($modelo['NOMBRE_MODELO']) . " (" . $modelo['AÑO_INICIO_PRODUCCION'] . " - " . 
                 ($modelo['AÑO_FIN_PRODUCCION'] ? $modelo['AÑO_FIN_PRODUCCION'] : "Actualmente en producción") . 
                 ")</a></h2>";
        }
    } else {
        echo "<p>No hay modelos de BMW disponibles.</p>";
    }
    // Cerrar la conexión
    $conexion->close();
    ?>
    <div id="index">
        <a href="../index.php">Volver al Inicio</a>
    </div>
</body>
</html>
