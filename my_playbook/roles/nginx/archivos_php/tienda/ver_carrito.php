<?php
session_start();
include("../conexion.php");

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de compras</title>
    <link rel="stylesheet" href="../css/ver_carrito.css">
</head>
<body>
<main>
    <h1>Carrito de compras</h1>

    <?php if (!empty($_SESSION['carrito'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($_SESSION['carrito'] as $id_producto => $cantidad):
                $stmt = $conexion->prepare("SELECT nombre, precio, imagen FROM productos WHERE id_producto = ?");
                $stmt->bind_param("i", $id_producto);
                $stmt->execute();
                $resultado = $stmt->get_result();
                if ($resultado->num_rows > 0):
                    $producto = $resultado->fetch_assoc();
                    $subtotal = $producto['precio'] * $cantidad;
                    $total += $subtotal;
            ?>
                <tr>
                    <td data-label="Producto"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td data-label="Imagen"><img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen"></td>
                    <td data-label="Precio"><?php echo number_format($producto['precio'], 2); ?> €</td>
                    <td data-label="Cantidad"><?php echo $cantidad; ?></td>
                    <td data-label="Subtotal"><?php echo number_format($subtotal, 2); ?> €</td>
                </tr>
            <?php
                endif;
                $stmt->close();
            endforeach;
            ?>
            </tbody>
        </table>

        <h3>Total: <?php echo number_format($total, 2); ?> €</h3>

        <div class="actions">
        <?php if (!isset($_SESSION['ID_USUARIO'])): ?>
            <p>⚠️ Debes <a href="../login.html">iniciar sesión</a> para continuar con el pago.</p>
        <?php else: ?>
            <form action="pago.php" method="POST">
                <button type="submit">Proceder al pago</button>
            </form>
        <?php endif; ?>
            <a class="link-button" href="tienda.php">🛍️ Seguir comprando</a>
        </div>
    <?php else: ?>
        <p>Tu carrito está vacío.</p>
        <a class="link-button" href="tienda.php">Ir a la tienda</a>
    <?php endif; ?>
</main>
</body>
</html>

<?php $conexion->close(); ?>
