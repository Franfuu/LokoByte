<?php 
// filepath: c:\xampp\htdocs\ProyectoTienda\Views\Product\index.php
require __DIR__ . '/../layout/header.php'; 
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<h1>Listado de productos</h1>

<?php if ($isAdmin): ?>
<p>
    <a href="index.php?c=product&a=create" class="button">Nuevo producto</a>
</p>
<?php endif; ?>

<?php if (empty($products)): ?>
    <p>No hay productos todavía.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <?php if ($isAdmin): ?>
                <th>ID</th>
                <?php endif; ?>
                <th>Nombre</th>
                <th>Versión</th>
                <th>Precio (€)</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <?php if ($isAdmin): ?>
                <th>Creado</th>
                <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $pdo = getPdo();
            foreach ($products as $p): 
                $stmt = $pdo->prepare('SELECT name FROM product_types WHERE id = :type_id');
                $stmt->execute([':type_id' => $p['type_id']]);
                $typeName = $stmt->fetchColumn() ?: 'Sin tipo';
            ?>
                <tr>
                    <?php if ($isAdmin): ?>
                    <td><?= htmlspecialchars((string)$p['id']) ?></td>
                    <?php endif; ?>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['version']) ?></td>
                    <td><?= number_format((float)$p['price'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($p['description']) ?></td>
                    <td><?= htmlspecialchars($typeName) ?></td>
                    <?php if ($isAdmin): ?>
                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                    <td>
                        <a href="index.php?c=product&a=edit&id=<?= urlencode((string)$p['id']) ?>">Editar</a>
                        <a href="index.php?c=product&a=delete&id=<?= urlencode((string)$p['id']) ?>"
                            onclick="return confirm('¿Seguro que quieres eliminar este producto?');">
                            Eliminar
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>