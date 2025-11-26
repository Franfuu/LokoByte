<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Listado de productos</h1>

<p>
    <a href="index.php?c=product&a=create" class="button">Nuevo producto</a>
</p>

<?php if (empty($products)): ?>
    <p>No hay productos todavía.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio (€)</th>
                <th>Descripción</th>
                <th>Versión</th>
                <th>Tipo</th>
                <th>Creado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $pdo = getPdo();
            foreach ($products as $p): 
                // Obtener el nombre del tipo de producto
                $stmt = $pdo->prepare('SELECT name FROM product_types WHERE id = :type_id');
                $stmt->execute([':type_id' => $p['type_id']]);
                $typeName = $stmt->fetchColumn() ?: 'Sin tipo';
            ?>
                <tr>
                    <td><?= htmlspecialchars((string)$p['id']) ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= number_format((float)$p['price'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($p['description']) ?></td>
                    <td><?= htmlspecialchars($p['version']) ?></td>
                    <td><?= htmlspecialchars($typeName) ?></td>
                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                    <td>
                        <a href="index.php?c=product&a=edit&id=<?= urlencode((string)$p['id']) ?>">Editar</a>
                        <a href="index.php?c=product&a=delete&id=<?= urlencode((string)$p['id']) ?>"
                            onclick="return confirm('¿Seguro que quieres eliminar este producto?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>