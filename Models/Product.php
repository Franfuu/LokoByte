<?php
// filepath: c:\xampp\htdocs\ProyectoTienda\models\Product.php
declare(strict_types=1);

class Product
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM products ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(string $name, string $description, string $version, float $price, int $stock, int $typeId): bool
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO products (name, description, version, price, stock, type_id, created_at)
            VALUES (:name, :description, :version, :price, :stock, :type_id, :created_at)
        ');
        
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':version' => $version,
            ':price' => $price,
            ':stock' => $stock,
            ':type_id' => $typeId,
            ':created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function update(int $id, string $name, string $description, string $version, float $price, int $stock, int $typeId): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE products 
            SET name = :name, 
                description = :description, 
                version = :version, 
                price = :price,
                stock = :stock,
                type_id = :type_id
            WHERE id = :id
        ');
        
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':version' => $version,
            ':price' => $price,
            ':stock' => $stock,
            ':type_id' => $typeId
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}