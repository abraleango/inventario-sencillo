<?php
require 'conexion.php';

// si no viene id o accion, pa fuera
if (!isset($_GET['id']) || !isset($_GET['accion'])) {
    header("Location: index.php");
    exit;
}

$id = trim($_GET['id']);
$accion = $_GET['accion'];

// por si acaso meten una accion que no existe
if ($accion !== 'restar' && $accion !== 'sumar') {
    header("Location: index.php");
    exit;
}

// compruebo que el producto existe antes de tocar algo
$stmt_check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
$stmt_check->execute([$id]);

if (!$stmt_check->fetch()) {
    header("Location: index.php");
    exit;
}

if ($accion == 'restar') {
    // el AND cantidad > 0 evita que se vaya a negativos
    $query_stock = "UPDATE productos SET cantidad = cantidad - 1 WHERE id = ? AND cantidad > 0";
} 
elseif ($accion == 'sumar') {
    $query_stock = "UPDATE productos SET cantidad = cantidad + 1 WHERE id = ?";
}

$stmt = $pdo->prepare($query_stock);
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>