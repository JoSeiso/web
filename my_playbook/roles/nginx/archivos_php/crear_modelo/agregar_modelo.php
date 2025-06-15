<?php
session_start();
if ($_SESSION['ROL'] !== 'ADMIN') {
    header("Location: ../login.html"); // Redirigir si no es admin
    exit();
}

include("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_modelo'], $_POST['año_inicio'])) {
    $nombre_modelo = $_POST['nombre_modelo'];
    $año_inicio = $_POST['año_inicio'];
    $año_fin = isset($_POST['año_fin']) ? $_POST['año_fin'] : NULL;

    // Verificar que los valores se pasan correctamente
    echo "Modelo: " . $nombre_modelo . "<br>";
    echo "Año Inicio: " . $año_inicio . "<br>";
    echo "Año Fin: " . $año_fin . "<br>";

    $sql = "INSERT INTO modelo_bmw (NOMBRE_MODELO, AÑO_INICIO_PRODUCCION, AÑO_FIN_PRODUCCION) 
            VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    // Verifica si la preparación fue exitosa
    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $conexion->error;
    } else {
        $stmt->bind_param("sis", $nombre_modelo, $año_inicio, $año_fin);

        if ($stmt->execute()) {
            echo "Modelo BMW añadido correctamente.";
        } else {
            echo "Error al añadir el modelo: " . $conexion->error;
        }
    }
}

$conexion->close();
?>

<!-- Formulario para añadir un modelo -->
<form action="agregar_modelo.php" method="POST">
    <label for="nombre_modelo">Nombre del Modelo:</label>
    <input type="text" name="nombre_modelo" required>
    <br>

    <label for="año_inicio">Año de Inicio de Producción:</label>
    <input type="number" name="año_inicio" required>
    <br>

    <label for="año_fin">Año de Fin de Producción (opcional):</label>
    <input type="number" name="año_fin">
    <br>

    <input type="submit" value="Añadir Modelo">
</form>
