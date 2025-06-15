<?php
session_start();
include("../conexion.php");

if (!isset($_GET['id'])) {
    header("Location: tienda.php");
    exit();
}

$id_producto = intval($_GET['id']);
$sql = "SELECT nombre, descripcion, precio, imagen, stock, id_producto FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Producto no encontrado.";
    exit();
}

$producto = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($producto['nombre']); ?> - BMW Forum</title>
  <link rel="stylesheet" href="../css/producto.css">
</head>
<body>
  <main class="producto-contenedor">
    <div class="producto-imagen">
      <img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
    </div>

    <div class="producto-info">
      <h1 class="titulo-producto"><?php echo htmlspecialchars($producto['nombre']); ?></h1>

      <div class="producto-descripcion">
        <h2>Descripción</h2>
        <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
      </div>

      <div class="producto-precio">
        <span><?php echo number_format($producto['precio'], 2); ?> €</span>
      </div>

      <form action="añadir_carrito.php" method="POST" id="form-carrito">
        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
        <label for="cantidad">Cantidad:</label>
        <div class="cantidad-box">
          <button type="button" id="menos">−</button>
          <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>">
          <button type="button" id="mas">+</button>
        </div>
        <button type="submit" class="btn-agregar">Añadir al carrito</button>
      </form>

      <div id="mensaje-toast" class="toast oculto">Producto añadido al carrito</div>

      <div class="volver">
        <a href="tienda.php">← Volver a la tienda</a>
      </div>
    </div>
  </main>

  <script>
    const menos = document.getElementById("menos");
    const mas = document.getElementById("mas");
    const cantidad = document.getElementById("cantidad");
    const max = <?php echo $producto['stock']; ?>;

    menos.onclick = () => {
      if (cantidad.value > 1) cantidad.value--;
    };

    mas.onclick = () => {
      if (parseInt(cantidad.value) < max) cantidad.value++;
    };

    document.getElementById("form-carrito").addEventListener("submit", (e) => {
      const toast = document.getElementById("mensaje-toast");
      toast.classList.remove("oculto");
      setTimeout(() => toast.classList.add("oculto"), 2000);
    });
  </script>
</body>
</html>
<?php
$stmt->close();
$conexion->close();
?>
