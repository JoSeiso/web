<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION['ID_USUARIO'];
$nombre = $_POST['nombre_usuario'];

// Consulta para obtener el email actual (por si decides no permitir cambiarlo)
$sql_email = "SELECT EMAIL, IMAGEN_PERFIL FROM usuarios WHERE ID_USUARIO = ?";
$stmt_email = $conexion->prepare($sql_email);
$stmt_email->bind_param("i", $id_usuario);
$stmt_email->execute();
$result = $stmt_email->get_result();
$datos_usuario = $result->fetch_assoc();
$stmt_email->close();

$email = $datos_usuario['EMAIL']; // por ahora mantenemos el email igual
$imagen_perfil = $datos_usuario['IMAGEN_PERFIL']; // la imagen anterior, en caso de no subir una nueva

// Verifico si se subió una nueva imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $archivo = $_FILES['imagen'];
    $nombre_temp = $archivo['tmp_name'];
    $nombre_final = uniqid() . "_" . basename($archivo['name']);
    $ruta_destino = "uploads/" . $nombre_final;

    $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($archivo['type'], $permitidos)) {
        if ($archivo['size'] <= 2 * 1024 * 1024) {
            if (move_uploaded_file($nombre_temp, $ruta_destino)) {
                $imagen_perfil = $ruta_destino;
            } else {
                echo "Error al mover la imagen.";
                exit();
            }
        } else {
            echo "El archivo es demasiado grande. Máximo permitido: 2 MB.";
            exit();
        }
    } else {
        echo "Tipo de archivo no permitido.";
        exit();
    }
}

// Actualizo el usuario
$sql_update = "UPDATE usuarios SET NOMBRE_USUARIO = ?, IMAGEN_PERFIL = ? WHERE ID_USUARIO = ?";
$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("ssi", $nombre, $imagen_perfil, $id_usuario);

if ($stmt_update->execute()) {
    header("Location: editar_perfil.php");
    exit();
} else {
    echo "Error al actualizar el perfil: " . $conexion->error;
}

$stmt_update->close();
$conexion->close();
?>
