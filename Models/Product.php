<?php
declare(strict_types=1);

class Product
{
    public int $id;
    public string $name;
    public string $description;
    public string $version;
    public float $price;
    public int $type_id;
    public string $created_at;

    /**
     * Obtiene todos los productos.
     */
    public static function all(PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un producto por ID.
     */
    public static function find(PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Crea un nuevo producto.
     */
    public static function create(PDO $pdo, string $name, string $description, string $version, float $price, int $type_id): void
    {
        $stmt = $pdo->prepare('
            INSERT INTO products (name, description, version, price, type_id, created_at)
            VALUES (:name, :description, :version, :price, :type_id, :created_at)
        ');
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':version' => $version,
            ':price' => $price,
            ':type_id' => $type_id,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Actualiza un producto existente.
     */
    public static function update(PDO $pdo, int $id, string $name, string $description, string $version, float $price, int $type_id): void
    {
        $stmt = $pdo->prepare('
            UPDATE products
            SET name = :name, description = :description, version = :version, price = :price, type_id = :type_id
            WHERE id = :id
        ');
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':version' => $version,
            ':price' => $price,
            ':type_id' => $type_id,
            ':id' => $id,
        ]);
    }

    /**
     * Elimina un producto.
     */
    public static function delete(PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}