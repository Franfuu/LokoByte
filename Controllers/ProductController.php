<?php
// filepath: c:\xampp\htdocs\ProyectoTienda\Controllers\ProductController.php
declare(strict_types=1);

class ProductController
{
    private PDO $pdo;
    private const IVA = 0.21; // 21% de IVA

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        ensureSession();
        
        // Configuración de paginación
        $productosPorPagina = 5;
        $paginaActual = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($paginaActual - 1) * $productosPorPagina;
        
        // Obtener productos con límite y offset (para mostrar)
        $stmt = $this->pdo->prepare('
            SELECT * FROM products 
            ORDER BY id DESC 
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':limit', $productosPorPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Contar total de productos para calcular páginas
        $totalProductos = (int)$this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $totalPaginas = (int)ceil($totalProductos / $productosPorPagina);
        
        require __DIR__ . '/../Views/Product/index.php';
    }

    public function create(): void
    {
        ensureSession();
        require __DIR__ . '/../Views/Product/form.php';
    }

    public function store(): void
    {
        ensureSession();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=product&a=index');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $version = trim($_POST['version'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $typeId = (int)($_POST['type_id'] ?? 0);
        
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'El nombre es obligatorio.';
        }
        if ($price <= 0) {
            $errors[] = 'El precio debe ser mayor que 0.';
        }
        if ($stock < 0) {
            $errors[] = 'El stock no puede ser negativo.';
        }
        if ($typeId <= 0) {
            $errors[] = 'Debe seleccionar un tipo de producto.';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: index.php?c=product&a=create');
            exit;
        }
        
        $productModel = new Product($this->pdo);
        
        if ($productModel->create($name, $description, $version, $price, $stock, $typeId)) {
            $_SESSION['success'] = 'Producto creado correctamente.';
        } else {
            $_SESSION['errors'] = ['Error al crear el producto.'];
        }
        
        header('Location: index.php?c=product&a=index');
        exit;
    }

    public function edit(): void
    {
        ensureSession();
        
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?c=product&a=index');
            exit;
        }
        
        $productModel = new Product($this->pdo);
        $product = $productModel->getById($id);
        
        if (!$product) {
            header('Location: index.php?c=product&a=index');
            exit;
        }
        
        require __DIR__ . '/../Views/Product/form.php';
    }

    public function update(): void
    {
        ensureSession();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=product&a=index');
            exit;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $version = trim($_POST['version'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $typeId = (int)($_POST['type_id'] ?? 0);
        
        $errors = [];
        
        if ($id <= 0) {
            $errors[] = 'ID de producto inválido.';
        }
        if (empty($name)) {
            $errors[] = 'El nombre es obligatorio.';
        }
        if ($price <= 0) {
            $errors[] = 'El precio debe ser mayor que 0.';
        }
        if ($stock < 0) {
            $errors[] = 'El stock no puede ser negativo.';
        }
        if ($typeId <= 0) {
            $errors[] = 'Debe seleccionar un tipo de producto.';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?c=product&a=edit&id=$id");
            exit;
        }
        
        $productModel = new Product($this->pdo);
        
        if ($productModel->update($id, $name, $description, $version, $price, $stock, $typeId)) {
            $_SESSION['success'] = 'Producto actualizado correctamente.';
        } else {
            $_SESSION['errors'] = ['Error al actualizar el producto.'];
        }
        
        header('Location: index.php?c=product&a=index');
        exit;
    }

    public function delete(): void
    {
        ensureSession();
        
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?c=product&a=index');
            exit;
        }
        
        $productModel = new Product($this->pdo);
        
        if ($productModel->delete($id)) {
            $_SESSION['success'] = 'Producto eliminado correctamente.';
        } else {
            $_SESSION['errors'] = ['Error al eliminar el producto.'];
        }
        
        header('Location: index.php?c=product&a=index');
        exit;
    }

    /**
     * Calcula el precio con IVA
     * @param float $precio Precio sin IVA
     * @return float Precio con IVA incluido
     */
    private function calcularPrecioConIVA(float $precio): float
    {
        return round($precio * (1 + self::IVA), 2);
    }

    /**
     * Formatea un precio para mostrar
     * @param float $precio
     * @return string
     */
    private function formatearPrecio(float $precio): string
    {
        return number_format($precio, 2, ',', '.') . ' €';
    }
}