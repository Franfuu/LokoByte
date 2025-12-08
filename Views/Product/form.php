<?php
// filepath: c:\xampp\htdocs\ProyectoTienda\Views\Product\form.php
require __DIR__ . '/../layout/header.php';

$errors = $_SESSION['errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);

$pdo = getPdo();
$stmt = $pdo->query('SELECT * FROM product_types ORDER BY name');
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Determinar si es edición o creación
$isEdit = isset($product);
$title = $isEdit ? 'Editar Producto' : 'Crear Producto';
$action = $isEdit ? 'update' : 'store';
$buttonText = $isEdit ? 'Actualizar Producto' : 'Crear Producto';
?>

<h1><?= $title ?></h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="index.php?c=product&a=<?= $action ?>" class="form">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars((string)$product['id']) ?>">
    <?php endif; ?>

    <label>
        Nombre del producto
        <input type="text" name="name" 
               value="<?= htmlspecialchars($isEdit ? $product['name'] : ($oldInput['name'] ?? '')) ?>" 
               required>
    </label>

    <label>
        Versión
        <input type="text" name="version" 
               value="<?= htmlspecialchars($isEdit ? $product['version'] : ($oldInput['version'] ?? '')) ?>" 
               required>
    </label>

    <label>
        Precio (€)
        <input type="number" step="0.01" name="price" 
               value="<?= htmlspecialchars($isEdit ? (string)$product['price'] : ($oldInput['price'] ?? '')) ?>" 
               required min="0">
    </label>

    <label>
        Stock (unidades)
        <input type="number" name="stock" 
               value="<?= htmlspecialchars($isEdit ? (string)($product['stock'] ?? 0) : ($oldInput['stock'] ?? '0')) ?>" 
               required min="0">
        <small>Cantidad disponible en inventario</small>
    </label>

    <label>
        Tipo de producto
        <select name="type_id" required>
            <option value="">-- Seleccionar tipo --</option>
            <?php 
            $selectedTypeId = $isEdit ? $product['type_id'] : ($oldInput['type_id'] ?? '');
            foreach ($types as $type): 
            ?>
                <option value="<?= $type['id'] ?>" 
                    <?= ($selectedTypeId == $type['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($type['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        Descripción
        <textarea name="description" rows="4"><?= htmlspecialchars($isEdit ? $product['description'] : ($oldInput['description'] ?? '')) ?></textarea>
    </label>

    <button type="submit"><?= $buttonText ?></button>
    <a href="index.php?c=product&a=index" class="button button-secondary">Cancelar</a>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>