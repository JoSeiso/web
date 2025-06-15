<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_producto']);
    $cantidad = isset($_POST['cantidad']) ? max(1, intval($_POST['cantidad'])) : 1;

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Asegurarse de que el valor previo sea un número
    if (isset($_SESSION['carrito'][$id]) && is_numeric($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] = $_SESSION['carrito'][$id] + $cantidad;
    } else {
        $_SESSION['carrito'][$id] = $cantidad;
    }

    header("Location: ver_carrito.php");
    exit();
}
?>
