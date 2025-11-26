<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Iniciar sesión. CRUD de Francisco</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="post" action="index.php?c=auth&a=doLogin" class="form">
    <label>
        Usuario
        <input type="text" name="username" required>
    </label>
    <label>
        Contraseña
        <input type="password" name="password" required>
    </label>
    <button type="submit">Entrar</button>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>