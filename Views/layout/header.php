<?php
// filepath: c:\xampp\htdocs\ProyectoTienda\Views\layout\header.php
// Leer de sesión primero, luego de cookie, por defecto 'light'
$theme = $_SESSION['theme'] ?? $_COOKIE['theme'] ?? 'light';

// DEBUG: Descomentar para ver qué tema se está usando
// echo "<!-- Tema actual: $theme -->";
?>
<!DOCTYPE html>
<html lang="es" data-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Productos LokoByte</title>
    <!-- Añadir timestamp para evitar cache -->
    <link rel="stylesheet" href="public/css/styles.css?v=<?= time() ?>">
</head>
<body>
<nav class="nav">
    <div class="nav-left">
        <span class="logo">Proyecto Tienda LokoByte</span>
    </div>
    <div class="nav-right">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="nav-user">Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="index.php?c=theme&a=toggle" class="theme-toggle">
                <?= $theme === 'light' ? '🌙 Modo Oscuro' : '☀️ Modo Claro' ?>
            </a>
            <a href="index.php?c=auth&a=logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="index.php?c=auth&a=login">Login</a>
            <a href="index.php?c=auth&a=register">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>
<main class="container">