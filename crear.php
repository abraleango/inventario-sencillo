<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Recibimos los datos del formulario (le quitamos espacios en blanco por si las moscas)
    $nombre = trim($_POST['nombre']);
    $cantidad = (int)$_POST['cantidad'];
    $precio = (float)$_POST['precio'];

    //Buscamos en la base de datos si ya existe un producto con ese  nombre

    $sql_check = "SELECT * FROM productos WHERE nombre = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$nombre]);
    $producto_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($producto_existente) {
       
        $nueva_cantidad = $producto_existente['cantidad'] + $cantidad;
        
        
        $sql_update = "UPDATE productos SET cantidad = ?, precio = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$nueva_cantidad, $precio, $producto_existente['id']]);
        
    } else {
       
        $prefijo = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
        $id_aleatorio = $prefijo . '-' . strtoupper(uniqid()); 
        
        $sql_insert = "INSERT INTO productos (id, nombre, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$id_aleatorio, $nombre, $cantidad, $precio]);
    }
    
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container form-container">
        <h2>Añadir Producto / Stock</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre del producto</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Cantidad (De ya existir, se sumará al stock actual)</label>
                <input type="number" name="cantidad" required>
            </div>
            <div class="form-group">
                <label>Precio unitario ($)</label>
                <input type="number" step="0.01" name="precio" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn">Guardar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>