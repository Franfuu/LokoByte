<?php
// filepath: c:\xampp\htdocs\ProyectoTienda\Views\auth\login.php
require __DIR__ . '/../layout/header.php';

// Primero verificar si existe la cookie, LUEGO usarla
$username = $_COOKIE['remember_user'] ?? '';

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>

<h1>Iniciar sesión</h1>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="post" action="index.php?c=auth&a=doLogin" class="form">
    <label>
        Usuario
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required autofocus>
    </label>

    <label>
        Contraseña
        <input type="password" name="password" required>
    </label>

    <label>
        <input type="checkbox" name="remember"> Recordarme durante 30 días
    </label>

    <button type="submit">Entrar</button>

    <p>
        ¿No tienes cuenta? <a href="index.php?c=auth&a=register">Regístrate aquí</a>
    </p>
</form>



<?php require __DIR__ . '/../layout/footer.php'; ?>