<?php
declare(strict_types=1);

class ProductController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function requireLogin(): void
    {
        ensureSession();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
    }

    public function index(): void
    {
        $this->requireLogin();
        $products = Product::all($this->pdo);
        require __DIR__ . '/../Views/Product/index.php';
    }

    public function create(): void
    {
        $this->requireLogin();
        $product = ['id' => null, 'name' => '', 'price' => '', 'description' => '', 'version' => '', 'type_id' => ''];
        $action = 'store';
        require __DIR__ . '/../Views/Product/form.php';
    }

    public function store(): void
    {
        $this->requireLogin();

        $name = trim($_POST['name'] ?? '');
        $priceStr = trim($_POST['price'] ?? '');
        $description = trim($_POST['description'] ?? '');   
        $version = trim($_POST['version'] ?? '');
        $type_id = isset($_POST['type_id']) ? (int)$_POST['type_id'] : 0;

        if ($name === '' || $priceStr === '' || $description === '' || $version === '' || $type_id === 0) {
            $error = 'Todos los campos son obligatorios.';
            $product = [
                'id' => null, 
                'name' => $name, 
                'price' => $priceStr, 
                'description' => $description, 
                'version' => $version,
                'type_id' => $type_id
            ];
            $action = 'store';
            require __DIR__ . '/../Views/Product/form.php';
            return;
        }

        $price = (float)$priceStr;

        Product::create($this->pdo, $name, $description, $version, $price, $type_id);

        header('Location: index.php?c=product&a=index');
        exit;
    }

    public function edit(): void
    {
        $this->requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = Product::find($this->pdo, $id);

        if (!$product) {
            http_response_code(404);
            echo 'Producto no encontrado';
            return;
        }

        $action = 'update';
        require __DIR__ . '/../Views/Product/form.php';
    }

    public function update(): void
    {
        $this->requireLogin();

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name = trim($_POST['name'] ?? '');
        $priceStr = trim($_POST['price'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $version = trim($_POST['version'] ?? '');
        $type_id = isset($_POST['type_id']) ? (int)$_POST['type_id'] : 0;

        if ($name === '' || $priceStr === '' || $description === '' || $version === '' || $type_id === 0) {
            $error = 'Todos los campos son obligatorios.';
            $product = [
                'id' => $id, 
                'name' => $name, 
                'price' => $priceStr,
                'description' => $description,
                'version' => $version,
                'type_id' => $type_id
            ];
            $action = 'update';
            require __DIR__ . '/../Views/Product/form.php';
            return;
        }

        $price = (float)$priceStr;

        Product::update($this->pdo, $id, $name, $description, $version, $price, $type_id);

        header('Location: index.php?c=product&a=index');
        exit;
    }

    public function delete(): void
    {
        $this->requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            Product::delete($this->pdo, $id);
        }

        header('Location: index.php?c=product&a=index');
        exit;
    }
}