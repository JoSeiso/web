<?php
session_start();
include("../conexion.php");

if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: ../login.html");
    exit();
}

$total = 0.0;
$productos_carrito = [];

if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
        $id_producto = intval($id_producto);
        $cantidad = intval($cantidad);

        $stmt = $conexion->prepare("SELECT nombre, precio, imagen FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $producto = $resultado->fetch_assoc();
            $subtotal = $producto['precio'] * $cantidad;
            $total += $subtotal;

            
                $productos_carrito[] = [
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                    'imagen' => $producto['imagen']
                ];
                
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proceso de Pago - BMW Forum</title>
    <link rel="stylesheet" href="../css/pago.css">
</head>
<body>
    <div class="contenedor-pago">
    <h2>Resumen del Pedido</h2>
<table>
    <thead>
        <tr>
        <tr>
    <th>Imagen</th>
    <th>Producto</th>
    <th>Cantidad</th>
    <th>Precio Unitario</th>
    <th>Subtotal</th>
</tr>

        </tr>
    </thead>
    <tbody>
    <?php foreach ($productos_carrito as $producto): ?>
<tr>
    <td><img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del producto" style="width: 60px; height: auto; border-radius: 6px;"></td>
    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
    <td><?php echo $producto['cantidad']; ?></td>
    <td><?php echo number_format($producto['precio'], 2); ?> €</td>
    <td><?php echo number_format($producto['subtotal'], 2); ?> €</td>
</tr>
<?php endforeach; ?>

    </tbody>
</table>

<h3>Total: <?php echo number_format($total, 2); ?> €</h3>

        <h2>Datos del Cliente</h2>
        <form action="procesar_pago.php" method="POST">
            <label for="nombre">Nombre completo:</label>
            <input type="text" name="nombre" id="nombre" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" required>

            <label for="direccion">Dirección de envío:</label>
            <textarea name="direccion" id="direccion" required></textarea>

            <button type="submit">Confirmar Pedido</button>
        </form>
    </div>
</body>
</html>

<?php $conexion->close(); ?>
