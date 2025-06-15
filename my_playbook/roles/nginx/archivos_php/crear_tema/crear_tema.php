<?php 
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: ../login.html");
    exit();
}

// Incluir archivo de conexión a la base de datos
include("../conexion.php");

// Obtener los modelos de BMW disponibles
$sql_modelos = "SELECT * FROM modelo_bmw";
$result_modelos = $conexion->query($sql_modelos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../css/crear_tema.css" />
    <title>Crear Temas</title>
</head>
<body>
    <form action="procesar_tema.php" method="POST">
        <label for="titulo">Título del tema:</label>
        <input type="text" name="titulo" required />

        <label for="descripcion">Descripción del tema:</label>
        <textarea name="descripcion" required></textarea>

        <label for="id_modelo">Selecciona el modelo de BMW:</label>
        <select name="id_modelo" required>
            <option value="">Selecciona un modelo</option>
            <?php
            if ($result_modelos->num_rows > 0) {
                while ($modelo = $result_modelos->fetch_assoc()) {
                    echo "<option value='" . $modelo['ID_MODELO'] . "'>" . htmlspecialchars($modelo['NOMBRE_MODELO']) . "</option>";
                }
            } else {
                echo "<option value=''>No hay modelos disponibles</option>";
            }
            ?>
        </select>

        <input type="submit" value="Crear Tema" />
    </form>

<?php
$conexion->close();
?>
</body>
</html>
