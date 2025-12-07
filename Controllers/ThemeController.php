<?php
// filepath: c:\xampp\htdocs\ProyectoTienda\Controllers\ThemeController.php
declare(strict_types=1);

class ThemeController
{
    public function toggle(): void
    {
        ensureSession();
        
        $currentTheme = $_COOKIE['theme'] ?? 'light';
        $newTheme = ($currentTheme === 'light') ? 'dark' : 'light';
        
        // Guardar en cookie y sesión
        setcookie('theme', $newTheme, time() + (86400 * 30), "/");
        $_SESSION['theme'] = $newTheme;
        
        // Volver a productos
        header("Location: index.php?c=product&a=index");
        exit;
    }
}