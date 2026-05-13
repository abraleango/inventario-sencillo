<?php
require 'conexion.php';

// recogemos el id de la URL
$id = trim($_GET['id'] ?? '');

if (empty($id)) {
    header("Location: index.php");
    exit;
}

// cargamos los datos actuales del producto para rellenar el formulario
$stmt_buscar = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt_buscar->execute([$id]);
$producto = $stmt_buscar->fetch(PDO::FETCH_ASSOC);

// si no existe ese id, pa fuera
if (!$producto) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // por si acaso meten espacios raros o se les va el dedo
    $nombre = trim($_POST['nombre']);
    $cantidad = (int)$_POST['cantidad'];
    $precio = (float)$_POST['precio'];

    // si no manda nada, que no haga nada
    if (empty($nombre) || $cantidad <= 0 || $precio <= 0) {
        header("Location: index.php");
        exit;
    }

    // update, editamos el existente, no creamos uno nuevo
    $query_editar = "UPDATE productos SET nombre = ?, cantidad = ?, precio = ? WHERE id = ?";
    $stmt = $pdo->prepare($query_editar);
    $stmt->execute([$nombre, $cantidad, $precio, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- formulario para editar un producto existente -->
    <div class="container form-container">
        <h2>Editar Producto</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre del producto</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>
            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" name="cantidad" value="<?= $producto['cantidad'] ?>" required>
            </div>
            <div class="form-group">
                <label>Precio unitario ($)</label>
                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn">Guardar cambios</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

</body>
</html>