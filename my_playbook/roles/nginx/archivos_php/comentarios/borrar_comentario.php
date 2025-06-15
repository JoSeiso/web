<?php
session_start();
include("../conexion.php");

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['ID_USUARIO']) || $_SESSION['ROL'] !== 'ADMIN') {
    // Si no está logueado o no es administrador, redirigir a login
    header("Location: ../login.php");
    exit();
}

// Verificar si se ha pasado el ID del comentario
if (isset($_GET['id_comentario']) && is_numeric($_GET['id_comentario'])) {
    $id_comentario = $_GET['id_comentario'];
    $id_tema = $_GET['id_tema'];

    // Eliminar el comentario de la base de datos
    $sql = "DELETE FROM comentarios WHERE ID_COMENTARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_comentario);

    if ($stmt->execute()) {
        // Redirigir al tema después de eliminar el comentario
        header("Location: ../crear_tema/tema_comentario.php?id_tema=" . $id_tema);
        exit();
    } else {
        echo "Error al eliminar el comentario";
    }
} else {
    echo "Comentario no válido";
}

$conexion->close();
?>
