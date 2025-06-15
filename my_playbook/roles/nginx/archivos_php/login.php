<?php
session_start(); // Iniciar sesión
include('conexion.php');

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el email existe en la base de datos
    $sql = "SELECT ID_USUARIO, NOMBRE_USUARIO, PASSWORD, ROL, IMAGEN_PERFIL FROM usuarios WHERE EMAIL = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($password, $user['PASSWORD'])) {
            // Procesar la imagen si el usuario sube una nueva
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $archivo = $_FILES['imagen'];
                $nombre_temp = $archivo['tmp_name'];
                $nombre_final = uniqid() . "_" . basename($archivo['name']);
                $ruta_destino = "uploads/" . $nombre_final;

                // Validar tipo de archivo
                $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($archivo['type'], $permitidos)) {
                    // Mover la imagen al directorio
                    if (move_uploaded_file($nombre_temp, $ruta_destino)) {
                        // Actualizar la imagen en la base de datos
                        $sql_actualizar = "UPDATE usuarios SET IMAGEN_PERFIL = ? WHERE ID_USUARIO = ?";
                        $stmt_actualizar = $conexion->prepare($sql_actualizar);
                        $stmt_actualizar->bind_param("si", $ruta_destino, $user['ID_USUARIO']);
                        $stmt_actualizar->execute();
                        $stmt_actualizar->close();

                        // Actualizar la ruta en la sesión
                        $_SESSION['IMAGEN_PERFIL'] = $ruta_destino;
                    } else {
                        echo "Error al mover la imagen.";
                        exit();
                    }
                } else {
                    echo "Tipo de archivo no permitido.";
                    exit();
                }
            } else {
                // Si no se sube una nueva imagen, usar la existente
                $_SESSION['IMAGEN_PERFIL'] = $user['IMAGEN_PERFIL'];
            }

            // Guardar datos en sesión
            $_SESSION['ID_USUARIO'] = $user['ID_USUARIO'];
            $_SESSION['NOMBRE_USUARIO'] = $user['NOMBRE_USUARIO'];
            $_SESSION['ROL'] = $user['ROL'];

            // Redirigir al panel de usuario
            header("Location: index.php");
            exit();
        } else {
            // Contraseña incorrecta
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: login.html");
            exit();
        }
    } else {
        // Usuario no encontrado
        $_SESSION['error'] = "No se encontró un usuario con ese email.";
        header("Location: login.html");
        exit();
    }
}
?>
