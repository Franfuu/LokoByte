<?php 

require __DIR__ . '/../layout/header.php'; 

$errors = $_SESSION['errors'] ?? [];
$oldUsername = $_SESSION['old_username'] ?? '';
unset($_SESSION['errors'], $_SESSION['old_username']);
?>

<h1>Registro de Usuario</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="index.php?c=auth&a=doRegister" class="form">
    <label>
        Nombre de usuario
        <input type="text" name="username" value="<?= htmlspecialchars($oldUsername) ?>" required autofocus>
    </label>

    <label>
        Contraseña
        <input type="password" name="password" required>
        <small>Mínimo 6 caracteres</small>
    </label>

    <label>
        Confirmar contraseña
        <input type="password" name="password_confirm" required>
    </label>

    <button type="submit">Registrarse</button>
    
    <p>
        ¿Ya tienes cuenta? <a href="index.php?c=auth&a=login">Inicia sesión aquí</a>
    </p>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>