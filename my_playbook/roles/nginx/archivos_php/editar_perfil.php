<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION['ID_USUARIO'];

$sql = "SELECT nombre_usuario, imagen_perfil FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$imagen = (!empty($usuario['imagen_perfil']) && file_exists($usuario['imagen_perfil']))
    ? $usuario['imagen_perfil']
    : "uploads/default.jpg";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/editar_perfil.css">
</head>
<body>
<div id="perfil-container">
    <h1>Editar Perfil</h1>

    <div class="form-image-wrapper">
        <!-- Formulario -->
        <form action="procesar_imagen.php" method="POST" enctype="multipart/form-data" class="perfil-form">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required>

            <label for="imagen">Subir Imagen de Perfil:</label>
            <input type="file" name="imagen" accept="image/*">

            <button type="submit">Guardar Cambios</button>
        </form>

        <!-- Imagen actual -->
        <div class="profile-preview">
            <p>Imagen Actual:</p>
            <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Imagen de Perfil">
        </div>
    </div>

    <a href="index.php">Volver al Inicio</a>
</div>

</body>
</html>
