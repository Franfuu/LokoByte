<?php
// filepath: c:\xampp\htdocs\ProyectoTienda\index.php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/ThemeController.php';

ensureSession();

$pdo = getPdo();

//si no se ha creado la base de datos, la creamos
if (!file_exists(__DIR__ . '/data/app.sqlite')) {
    require_once __DIR__ . '/create_db.php';
}

$controllerName = $_GET['c'] ?? null;
$action = $_GET['a'] ?? null;

// Si no hay usuario autenticado, permitir login y registro
if (!isset($_SESSION['user_id']) && !($controllerName === 'auth' && in_array($action, ['login', 'doLogin', 'register', 'doRegister'], true))) {
    $controllerName = 'auth';
    $action = 'login';
}

// Valores por defecto
if ($controllerName === null) {
    $controllerName = isset($_SESSION['user_id']) ? 'product' : 'auth';
}
if ($action === null) {
    $action = ($controllerName === 'auth') ? 'login' : 'index';
}


//Creamos la instancia del controlador correspondiente y llamamos a la acción
$authController = new AuthController($pdo);
$productController = new ProductController($pdo);
$themeController = new ThemeController();

switch ($controllerName) {
    case 'auth':
        switch ($action) {
            case 'login':
                $authController->login();
                break;
            case 'doLogin':
                $authController->doLogin();
                break;
            case 'register':
                $authController->register();
                break;
            case 'doRegister':
                $authController->doRegister();
                break;
            case 'logout':
                $authController->logout();
                break;
            default:
                http_response_code(404);
                echo 'Acción de autenticación no encontrada';
        }
        break;

    case 'product':
        switch ($action) {
            case 'index':
                $productController->index();
                break;
            case 'create':
                $productController->create();
                break;
            case 'store':
                $productController->store();
                break;
            case 'edit':
                $productController->edit();
                break;
            case 'update':
                $productController->update();
                break;
            case 'delete':
                $productController->delete();
                break;
            default:
                http_response_code(404);
                echo 'Acción de productos no encontrada';
        }
        break;

    case 'theme':
        switch ($action) {
            case 'toggle':
                $themeController->toggle();
                break;
            default:
                http_response_code(404);
                echo 'Acción de tema no encontrada';
        }
        break;

    default:
        http_response_code(404);
        echo 'Controlador no encontrado';
}