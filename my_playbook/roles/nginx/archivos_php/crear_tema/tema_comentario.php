<?php
session_start();
include("../conexion.php");

if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: ../login.html");
    exit();
}

// Obtener el ID del tema
$id_tema = $_GET['id_tema'];

// Verificar si el ID del tema es válido
if (!isset($id_tema) || !is_numeric($id_tema)) {
    echo "Tema no válido.";
    exit();
}

$id_usuario = $_SESSION['ID_USUARIO'] ?? null; // Obtener el ID del usuario de la sesión de forma segura

// Procesar el comentario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'])) {
    $comentario = $_POST['comentario'];
    $id_usuario = $_SESSION['ID_USUARIO'];
    
    // Insertar comentario en la base de datos
    $sql_comentario = "INSERT INTO comentarios (id_tema, id_usuario, contenido) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql_comentario);
    $stmt->bind_param("iis", $id_tema, $id_usuario, $comentario);
    
    if ($stmt->execute()) {
        // Redirigir a la misma página para recargar el contenido
        header("Location: tema_comentario.php?id_tema=" . $id_tema);
        exit();
    } else {
        echo "Error al añadir comentario: " . $conexion->error;
    }
}

// Obtener los detalles del tema
$sql_tema = "SELECT t.titulo, t.descripcion, t.fecha_creacion, u.nombre_usuario
             FROM temas t
             INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
             WHERE t.id_tema = ?";
$stmt = $conexion->prepare($sql_tema);
$stmt->bind_param("i", $id_tema);
$stmt->execute();
$result_tema = $stmt->get_result();
$tema = $result_tema->fetch_assoc();

// Obtener los comentarios del tema
$sql_comentarios = "SELECT c.id_comentario, c.contenido, c.id_usuario, c.fecha_edicion, u.imagen_perfil, u.nombre_usuario, c.fecha_publicacion 
                    FROM comentarios c
                    INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                    WHERE c.id_tema = ?
                    ORDER BY c.fecha_publicacion DESC";
$stmt_comentarios = $conexion->prepare($sql_comentarios);

if (!$stmt_comentarios) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmt_comentarios->bind_param("i", $id_tema);
$stmt_comentarios->execute();
$result_comentarios = $stmt_comentarios->get_result();

// Obtener los datos del usuario logueado
$sql_usuario = "SELECT nombre_usuario, imagen_perfil FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = $conexion->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario_logueado = $result_usuario->fetch_assoc();
$stmt_usuario->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/tema_comentario.css">
    <title><?php echo htmlspecialchars($tema['titulo']); ?></title>
</head>
<body>
    <div id="container">
    <h1><?php echo htmlspecialchars($tema['titulo']); ?></h1>
    <h3>Cuestion del foro</h3>
    <p><strong><?php echo htmlspecialchars($tema['descripcion']); ?></strong></p>
    <p>Creado por: <strong><?php echo htmlspecialchars($tema['nombre_usuario']); ?></strong></p>
    <small>Fecha de creación: <?php echo $tema['fecha_creacion']; ?></small>
    <hr>

    <h2>Comentarios</h2>
<?php if ($result_comentarios->num_rows > 0): ?>
    <ul>
        <?php while ($comentario = $result_comentarios->fetch_assoc()): ?>
            <?php 
            // Manejo de la ruta de la imagen
            $ruta_imagen = "../" . htmlspecialchars($comentario['imagen_perfil']);
            if (!file_exists($ruta_imagen) || empty($comentario['imagen_perfil'])) {
                $ruta_imagen = "../uploads/default.png"; // Imagen predeterminada
            }
            ?>
            <li class="comment-item">
                <div class="comment-user">
                    <img src="<?php echo $ruta_imagen; ?>" alt="Imagen de Perfil" class="profile-pic">
                    <p><strong><?php echo htmlspecialchars($comentario['nombre_usuario']); ?></strong></p>
                </div>
                <div class="comment-content">
                    <p><?php echo htmlspecialchars($comentario['contenido']); ?></p>
                    <small>Fecha: <?php echo $comentario['fecha_publicacion']; ?></small>

                    <?php if (!is_null($comentario['fecha_edicion'])): ?>
                        <small>(Editado el: <?php echo $comentario['fecha_edicion']; ?>)</small>
                    <?php endif; ?>

                    <!-- Opciones de edición y eliminación -->
                    <?php if ((isset($_SESSION['ID_USUARIO']) && $_SESSION['ID_USUARIO'] === $comentario['id_usuario']) || (isset($_SESSION['ROL']) && $_SESSION['ROL'] === 'ADMIN')): ?>
                        <!--Los administradores pueden editar cualquier comentario-->
                        <a href="../comentarios/editar_comentario.php?id_comentario=<?php echo $comentario['id_comentario']; ?>&id_tema=<?php echo $id_tema; ?>">Editar</a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['ROL']) && $_SESSION['ROL'] === 'ADMIN'): ?>
                        <!-- Solo los administradores pueden borrar comentarios -->
                        <a href="../comentarios/borrar_comentario.php?id_comentario=<?php echo $comentario['id_comentario']; ?>&id_tema=<?php echo $id_tema; ?>" onclick="return confirm('¿Estás seguro de que deseas borrar este comentario?');">Borrar Comentario</a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>No hay comentarios aún.</p>
<?php endif; ?>

    <h3>Añadir un comentario:</h3>
    <form action="tema_comentario.php?id_tema=<?php echo $id_tema; ?>" method="POST">
    <textarea name="comentario" rows="4" cols="50" required></textarea>
    <input type="submit" value="Comentar">
</form>

<div class="volver-container">
    <a href="listar_temas.php" class="volver-link">Volver a la lista de temas</a>
</div>

    </div>
</body>
</html>

<?php
$conexion->close();
?>
