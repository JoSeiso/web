<?php
session_start();
include("../conexion.php");

// Verificar si el usuario está logueado
if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del comentario y el ID del tema
$id_comentario = $_GET['id_comentario'];
$id_tema = $_GET['id_tema'];

// Verificar si el comentario existe en la base de datos
$sql = "SELECT * FROM comentarios WHERE ID_COMENTARIO = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_comentario);
$stmt->execute();
$result = $stmt->get_result();
$comentario = $result->fetch_assoc();

// Si el comentario no existe, redirigir o mostrar error
if (!$comentario) {
    echo "Comentario no encontrado.";
    exit();
}

// Si el comentario es editado (cuando se recibe el formulario POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'])) {
    $nuevo_comentario = $_POST['comentario'];
    $fecha_edicion = date("Y-m-d H:i:s"); // Obtener la fecha actual para la edición

    // Actualizar el comentario y la fecha de edición
    $sql_update = "UPDATE comentarios SET CONTENIDO = ?, FECHA_EDICION = ? WHERE ID_COMENTARIO = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("ssi", $nuevo_comentario, $fecha_edicion, $id_comentario);

    if ($stmt_update->execute()) {
        header("Location: ../crear_tema/tema_comentario.php?id_tema=" . $id_tema); // Redirigir después de la edición
        exit();
    } else {
        echo "Error al actualizar el comentario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/editar_comentario.css">
    <title>Editar Comentario</title>
</head>
<body>
    <div id="contenedor">
        <h1>Editar Comentario</h1>

        <form action="editar_comentario.php?id_comentario=<?php echo $id_comentario; ?>&id_tema=<?php echo $id_tema; ?>" method="POST">
            <textarea name="comentario" rows="4" cols="50" required><?php echo htmlspecialchars($comentario['CONTENIDO']); ?></textarea><br>
            <input type="submit" value="Actualizar Comentario">
        </form>

        <a href="../crear_tema/tema_comentario.php?id_tema=<?php echo $id_tema; ?>">Cancelar</a>
    </div>
</body>
</html>
