<?php include("conexion.php"); ?>
<?php
    // Recojo los datos del formulario
    $usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $contraseña = $_POST['password'];

    // Cifro la contraseña antes de almacenarla
    $password_hash = password_hash($contraseña, PASSWORD_BCRYPT);

    // Imagen de perfil predeterminada
    $imagen_perfil = "uploads/default.jpg";

    // Verifico que la carpeta 'uploads/' existe
    if (!is_dir("uploads")) {
        mkdir("uploads", 0755, true); // Crear la carpeta si no existe
    }

    // Verifico si se subió una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen'];
        $nombre_temp = $archivo['tmp_name'];
        $nombre_final = uniqid() . "_" . basename($archivo['name']);
        $ruta_destino = "uploads/" . $nombre_final;

        // Verifico el tipo de archivo
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($archivo['type'], $permitidos)) {
            // Verifico el tamaño máximo del archivo (2 MB)
            if ($archivo['size'] <= 2 * 1024 * 1024) {
                // Mover la imagen al directorio de destino
                if (move_uploaded_file($nombre_temp, $ruta_destino)) {
                    $imagen_perfil = $ruta_destino; // Guardar la ruta completa
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

    // Preparo la consulta
    $stmt = $conexion->prepare("INSERT INTO usuarios (NOMBRE_USUARIO, EMAIL, PASSWORD, IMAGEN_PERFIL) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $usuario, $email, $password_hash, $imagen_perfil);

    // Ejecuto la consulta y verifico
    if ($stmt->execute()) {
        header("Location: index.php");
        exit(); // Asegúrate de usar exit después de redirigir
    } else {
        echo "Fallo al intentar insertar los datos en la tabla usuarios: " . $stmt->error;
    }

    // Cierro la conexión
    $stmt->close();
    $conexion->close();
?>
<html>
    <body>
        <a href="altausuario.php">
            <button type="button">Volver a alta</button>
        </a>
    </body>
</html>
