<?php
session_start();
if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: login.php");
    exit();
}

include("../conexion.php");

// Recoger datos del formulario
$id_tema = $_POST['id_tema'];
$contenido = $_POST['contenido'];
$id_usuario = $_SESSION['ID_USUARIO'];

// Validar datos
if (empty($contenido) || !is_numeric($id_tema)) {
    echo "Datos inválidos.";
    exit();
}

// Insertar comentario en la base de datos
$sql = "INSERT INTO comentarios (CONTENIDO, FECHA_PUBLICACION, ID_USUARIO, ID_TEMA) VALUES (?, NOW(), ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sii", $contenido, $id_usuario, $id_tema);

if ($stmt->execute()) {
    header("Location: tema_comentario.php?id_tema=" . $id_tema);
    exit();
} else {
    echo "Error al guardar el comentario: " . $conexion->error;
}
?>
