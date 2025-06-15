<?php
include('conexion.php');

// Información del usuario administrador
$nombre_usuario = 'admin';
$email = 'admin@admin.com';
$password = 'admin';  // La contraseña del administrador (en texto claro)
$rol = 'ADMIN';  // Rol del administrador

// Hashear la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insertar el usuario en la base de datos
$sql = "INSERT INTO USUARIOS (NOMBRE_USUARIO, EMAIL, PASSWORD, ROL) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssss", $nombre_usuario, $email, $hashed_password, $rol);

if ($stmt->execute()) {
    echo "Administrador creado con éxito.";
} else {
    echo "Error al crear el administrador: " . $conexion->error;
}

?>
