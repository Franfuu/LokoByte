<?php 
// filepath: c:\xampp\htdocs\ProyectoTienda\Views\Product\index.php
require __DIR__ . '/../layout/header.php'; 
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>

<h1>Listado de productos</h1>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<!-- CABECERA DE TABLA -->
<div class="product-header">
    <?php if ($isAdmin): ?>
    <a href="index.php?c=product&a=create" class="button">Nuevo producto</a>
    <?php endif; ?>
    
    <div class="product-info">
        Mostrando <?= count($products) ?> de <?= $totalProductos ?> productos (Página <?= $paginaActual ?> de <?= $totalPaginas ?>)
    </div>
</div>

<!-- TABLA DE PRODUCTOS -->
<?php if (empty($products)): ?>
    <p>No hay productos en esta página.</p>
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
                <th>Precio + IVA</th>
                <th>Stock</th>
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
                
                // Calcular valores
                $precio = (float)$p['price'];
                $stock = (int)($p['stock'] ?? 0);
                $precioIVA = round($precio * 1.21, 2); // IVA 21%
                
                // Determinar clase de stock
                $stockClass = '';
                if ($stock < 10) {
                    $stockClass = 'stock-bajo';
                } elseif ($stock < 50) {
                    $stockClass = 'stock-medio';
                } else {
                    $stockClass = 'stock-alto';
                }
            ?>
                <tr>
                    <?php if ($isAdmin): ?>
                    <td><?= htmlspecialchars((string)$p['id']) ?></td>
                    <?php endif; ?>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['version']) ?></td>
                    <td><?= number_format($precio, 2, ',', '.') ?> €</td>
                    <td><strong><?= number_format($precioIVA, 2, ',', '.') ?> €</strong></td>
                    <td class="<?= $stockClass ?>">
                        <strong><?= $stock ?></strong> uds.
                    </td>
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

<!-- PAGINACIÓN -->
<?php if ($totalPaginas > 1): ?>
    <div class="pagination">
        <?php if ($paginaActual > 1): ?>
            <a href="index.php?c=product&a=index&page=1" class="page-link" title="Primera página">
                &laquo;&laquo;
            </a>
        <?php endif; ?>
        
        <?php if ($paginaActual > 1): ?>
            <a href="index.php?c=product&a=index&page=<?= $paginaActual - 1 ?>" class="page-link" title="Página anterior">
                &laquo;
            </a>
        <?php endif; ?>
        
        <?php
        $rango = 2;
        $inicio = max(1, $paginaActual - $rango);
        $fin = min($totalPaginas, $paginaActual + $rango);
        
        if ($inicio > 1): ?>
            <a href="index.php?c=product&a=index&page=1" class="page-link">1</a>
            <?php if ($inicio > 2): ?>
                <span class="page-dots">...</span>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php for ($i = $inicio; $i <= $fin; $i++): ?>
            <a href="index.php?c=product&a=index&page=<?= $i ?>" 
               class="page-link <?= $i === $paginaActual ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($fin < $totalPaginas): ?>
            <?php if ($fin < $totalPaginas - 1): ?>
                <span class="page-dots">...</span>
            <?php endif; ?>
            <a href="index.php?c=product&a=index&page=<?= $totalPaginas ?>" class="page-link"><?= $totalPaginas ?></a>
        <?php endif; ?>
        
        <?php if ($paginaActual < $totalPaginas): ?>
            <a href="index.php?c=product&a=index&page=<?= $paginaActual + 1 ?>" class="page-link" title="Página siguiente">
                &raquo;
            </a>
        <?php endif; ?>
        
        <?php if ($paginaActual < $totalPaginas): ?>
            <a href="index.php?c=product&a=index&page=<?= $totalPaginas ?>" class="page-link" title="Última página">
                &raquo;&raquo;
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>