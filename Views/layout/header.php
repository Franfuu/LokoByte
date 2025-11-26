<?php
ensureSession();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Productos LokoByte</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
<nav class="nav">
    <div class="nav-left">
        <span class="logo">Proyecto Tienda LokoByte</span>
    </div>
    <div class="nav-right">
        <?php if ($user): ?>
            <span class="nav-user">Hola, <?= htmlspecialchars($user['username']) ?></span>
            <a href="index.php?c=product&a=index">Productos</a>
            <a href="index.php?c=auth&a=logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="index.php?c=auth&a=login">Login</a>
        <?php endif; ?>
    </div>
</nav>
<main class="container">