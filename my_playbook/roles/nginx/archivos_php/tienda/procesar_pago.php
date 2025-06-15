<?php
session_start();
include("../conexion.php");

// Verificar que el usuario esté logueado (por seguridad)
if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: ../login.html");
    exit;
}

// Validar que el carrito no esté vacío
if (empty($_SESSION['carrito'])) {
    echo "Tu carrito está vacío. No se puede procesar el pedido.";
    exit;
}

// Validar datos enviados desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($direccion)) {
        echo "Por favor completa todos los campos requeridos.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no es válido.";
        exit;
    }

    // Guardar el pedido en la tabla pedidos
    $id_usuario = $_SESSION['ID_USUARIO'];
    $fecha_pedido = date('Y-m-d H:i:s');

    $stmt = $conexion->prepare("INSERT INTO pedidos (id_usuario, fecha_pedido, nombre_cliente, email_cliente, direccion_envio) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error en prepare INSERT INTO pedidos: " . $conexion->error);
    }
    $stmt->bind_param("issss", $id_usuario, $fecha_pedido, $nombre, $email, $direccion);

    if (!$stmt->execute()) {
        echo "Error al guardar el pedido: " . $stmt->error;
        exit;
    }

    $id_pedido = $stmt->insert_id;
    $stmt->close();

    // Insertar detalles del pedido y actualizar stock
    foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
        $id_producto = intval($id_producto);
        $cantidad = intval($cantidad);

        // Obtener precio y stock actual del producto
        $stmt = $conexion->prepare("SELECT precio, stock FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($producto = $resultado->fetch_assoc()) {
            $precio_unitario = $producto['precio'];
            $stock_actual = $producto['stock'];

            // Comprobar que hay stock suficiente
            if ($stock_actual < $cantidad) {
                echo "No hay suficiente stock para el producto ID $id_producto.";
                exit;
            }

            // Insertar detalle pedido
            $stmt_detalle = $conexion->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            $stmt_detalle->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio_unitario);

            if (!$stmt_detalle->execute()) {
                echo "Error al guardar el detalle del pedido: " . $stmt_detalle->error;
                exit;
            }
            $stmt_detalle->close();

            // Actualizar stock en productos
            $nuevo_stock = $stock_actual - $cantidad;
            $stmt_update = $conexion->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
            if (!$stmt_update) {
                die("Error en prepare UPDATE productos: " . $conexion->error);
            }
            $stmt_update->bind_param("ii", $nuevo_stock, $id_producto);
            $stmt_update->execute();
            $stmt_update->close();

        } else {
            echo "Producto no encontrado: ID " . $id_producto;
            exit;
        }
        $stmt->close();
    }

    // Vaciar carrito
    unset($_SESSION['carrito']);

    // Mostrar mensaje de éxito con estructura HTML
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Procesar Pago</title>
        <link rel="stylesheet" href="../css/procesar_pago.css"> 
    </head>
    <body>
        <div class="container">
            <h2>Gracias por tu compra, <?php echo htmlspecialchars($nombre); ?></h2>
            <p>Tu pedido ha sido procesado correctamente.</p>
            <p><a href="tienda.php">Volver a la tienda</a></p>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Método no permitido.";
}

$conexion->close();
?>
