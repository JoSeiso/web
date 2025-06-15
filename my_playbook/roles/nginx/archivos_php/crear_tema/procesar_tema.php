<?php
session_start();

// Comprobamos que el usuario está logueado
if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir archivo de conexión a la base de datos
include("../conexion.php");

// Obtener los datos del formulario
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$id_modelo = $_POST['id_modelo'];
$id_usuario = $_SESSION['ID_USUARIO'];

// Crear el nuevo tema
$sql_tema = "INSERT INTO temas (titulo, descripcion, id_usuario) VALUES (?, ?, ?)";
$stmt_tema = $conexion->prepare($sql_tema);
$stmt_tema->bind_param("ssi", $titulo, $descripcion, $id_usuario);

if ($stmt_tema->execute()) {
    // Obtener el ID del tema recién creado
    $id_tema = $stmt_tema->insert_id;

    // Relacionar el tema con el modelo seleccionado
    $sql_relacion = "INSERT INTO tema_modelo_bmw (id_tema, id_modelo) VALUES (?, ?)";
    $stmt_relacion = $conexion->prepare($sql_relacion);
    $stmt_relacion->bind_param("ii", $id_tema, $id_modelo);

    if ($stmt_relacion->execute()) {
        header("Location: listar_temas.php");
        exit();
    } else {
        echo "Error al asociar el tema con el modelo.";
    }
} else {
    echo "Error al crear el tema.";
}

$conexion->close();
?>
