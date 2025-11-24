<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Crear carpeta data si no existe
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$pdo = getPdo();

//TABLA: users
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'cliente'
    )
");

//TABLA: product_types
$pdo->exec("
    CREATE TABLE IF NOT EXISTS product_types (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )
");

//TABLA: products
$pdo->exec("
    CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        version TEXT,
        price REAL NOT NULL,
        type_id INTEGER,
        created_at TEXT NOT NULL,
        
        FOREIGN KEY (type_id) REFERENCES product_types(id)
    )
");

echo "Base de datos creada con éxito." . PHP_EOL;

//CREAR USUARIO ADMIN POR DEFECTO
$stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :u');
$stmt->execute([':u' => 'admin']);
$exists = (int)$stmt->fetchColumn();

if ($exists === 0) {
    $passwordHash = password_hash('admin', PASSWORD_DEFAULT);
    $insert = $pdo->prepare('
        INSERT INTO users (username, password_hash, role)
        VALUES (:u, :p, :r)
    ');
    $insert->execute([
        ':u' => 'admin',
        ':p' => $passwordHash,
        ':r' => 'admin',
    ]);
    echo "Base de datos creada. Usuario 'admin' (admin) generado." . PHP_EOL;
} else {
    echo "Base de datos ya existente. Usuario 'admin' ya creado." . PHP_EOL;
}