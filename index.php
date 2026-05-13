<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'conexion.php';

$stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Inventario de Stock</h2>
            <h6>Estudiantes: Abraham Angulo y Eric Méndez</h6>
            <a href="crear.php" class="btn">Añadir Producto</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($productos as $producto): ?>
                <tr>
                    <td><?= $producto['id'] ?></td>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td class="cantidad-celda">
    <a href="stock.php?id=<?= $producto['id'] ?>&accion=restar" class="btn-stock btn-restar">-</a>
    <span class="numero"><?= $producto['cantidad'] ?></span>
    <a href="stock.php?id=<?= $producto['id'] ?>&accion=sumar" class="btn-stock btn-sumar">+</a>
</td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                    <td class="actions">
                        <a href="editar.php?id=<?= $producto['id'] ?>" class="link-edit">Editar</a>
                        <a href="eliminar.php?id=<?= $producto['id'] ?>" class="link-delete" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($productos)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No hay productos en el inventario.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>