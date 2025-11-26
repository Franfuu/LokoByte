<?php require __DIR__ . '/../layout/header.php'; ?>

<h1><?= $action === 'store' ? 'Nuevo producto' : 'Editar producto' ?></h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="post" action="index.php?c=product&a=<?= htmlspecialchars($action) ?>" class="form">
    <?php if (!empty($product['id'])): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars((string)$product['id']) ?>">
    <?php endif; ?>

    <label>
        Nombre
        <input type="text" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
    </label>

    <label>
        Precio (€)
        <input type="number" step="0.01" min="0" name="price" value="<?= htmlspecialchars((string)($product['price'] ?? '0')) ?>" required>
    </label>

    <label>
        Descripción
        <textarea name="description" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
    </label>

    <label>
        Versión
        <input type="text" name="version" value="<?= htmlspecialchars($product['version'] ?? '') ?>" required>
    </label>

    <label>
        Tipo de Producto
        <select name="type_id" required>
            <option value="">Seleccione un tipo</option>
            <?php
            $pdo = getPdo();
            $types = $pdo->query('SELECT id, name FROM product_types ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
            foreach ($types as $type): ?>
                <option value="<?= $type['id'] ?>" <?= (isset($product['type_id']) && $product['type_id'] == $type['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($type['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <button type="submit">
        <?= $action === 'store' ? 'Crear' : 'Actualizar' ?>
    </button>

    <a href="index.php?c=product&a=index" class="button button-secondary">Volver</a>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>