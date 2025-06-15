<?php
session_start();
include("../conexion.php");

// Obtener productos de la base de datos
$sql = "SELECT id_producto, nombre, descripcion, precio, imagen FROM productos";
$result = $conexion->query($sql);

// Comprobar si hay productos en el carrito (ejemplo con session)
$carrito_vacio = true;
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    $carrito_vacio = false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda - BMW Forum</title>
  <link rel="stylesheet" href="../css/tienda.css">
</head>
<body>
  <main class="tienda-wrapper">
    <header class="tienda-header">
      <h1>Tienda Oficial BMW Forum</h1>
      <?php if (!$carrito_vacio): ?>
        <a href="ver_carrito.php" class="btn-carrito">Ver Carrito</a>
      <?php endif; ?>
    </header>

    <section class="productos-grid">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($producto = $result->fetch_assoc()): ?>    
          <div class="producto-card">
            <a href="producto.php?id=<?php echo $producto['id_producto']; ?>">
              <img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
              <div class="producto-info">
                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <p><strong>Precio:</strong> <?php echo number_format($producto['precio'], 2); ?> €</p>
              </div>
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="sin-productos">No hay productos disponibles.</p>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
<?php
$conexion->close();
?>
