<?php
session_start();
include("conexion.php");

// Comprobar si enviaron el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Imagen subida
    $imagen_perfil = "uploads/default_producto.jpg"; // Imagen por defecto
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen'];
        $nombre_temp = $archivo['tmp_name'];
        $nombre_final = uniqid() . "_" . basename($archivo['name']);
        $ruta_destino = "uploads/" . $nombre_final;

        // Comprobaciones de tipo y tamaño
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($archivo['type'], $permitidos) && $archivo['size'] <= 2 * 1024 * 1024) {
            if (move_uploaded_file($nombre_temp, $ruta_destino)) {
                $imagen_perfil = $ruta_destino;
            } else {
                echo "Error al mover la imagen.";
                exit;
            }
        }
    }

    // Insertar en la base de datos (todo en minúsculas)
    $stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen_perfil, $stock);

    if ($stmt->execute()) {
        echo "Producto añadido correctamente.";
    } else {
        echo "Error al añadir producto: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Producto</title>
</head>
<body>
    <h1>Añadir Producto</h1>
    <form action="añadir_producto.php" method="POST" enctype="multipart/form-data">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" rows="4" cols="40" required></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>

        <label>Imagen del producto:</label><br>
        <input type="file" name="imagen" accept="image/*"><br><br>

        <input type="submit" value="Añadir Producto">
    </form>

    <a href="../index.php">Volver al Inicio</a>
</body>
</html>
