<?php
session_start();
include("../conexion.php");

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['ID_USUARIO']) || $_SESSION['ROL'] !== 'ADMIN') {
    header("Location: login.php");
    exit();
}

// Verificar si se ha recibido el id del tema
if (isset($_GET['id_tema'])) {
    $id_tema = $_GET['id_tema'];

    // Eliminar el tema de la base de datos
    $sql = "DELETE FROM temas WHERE ID_TEMA = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_tema);

    if ($stmt->execute()) {
        // Redirigir a la página de lista de temas después de borrar
        header("Location: listar_temas.php");
        exit();
    } else {
        echo "Error al eliminar el tema: " . $conexion->error;
    }
} else {
    echo "No se ha especificado el ID del tema.";
}

$conexion->close();
?>
