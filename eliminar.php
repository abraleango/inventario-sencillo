<?php
require 'conexion.php';

// limpio el id pero sin convertirlo a número, que aquí son strings tipo "XKQM-65F3A2B1C4D5"
$id = trim($_GET['id']);

if (empty($id)) {
    header("Location: index.php");
    exit;
}

// compruebo que el producto existe antes de borrar
$stmt_check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
$stmt_check->execute([$id]);

if (!$stmt_check->fetch()) {
    header("Location: index.php");
    exit;
}

// todo bien, borramos
$query_borrar = "DELETE FROM productos WHERE id = ?";
$stmt = $pdo->prepare($query_borrar);
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>