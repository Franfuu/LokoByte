<?php

declare(strict_types=1);

class AuthController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Muestra el formulario de login.
     */
    public function login(): void
    {
        $error = $_GET['error'] ?? null;
        require __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Procesa el login.
     */
    public function doLogin(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByUsername($username);

        if (!$user || !password_verify($password, $user->password_hash)) {
            $_SESSION['error'] = 'Credenciales incorrectas';
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;

        // Redirigir al index principal
        header('Location: index.php');
        exit;
    }

    public function register(): void
    {
        ensureSession();
        require __DIR__ . '/../Views/Auth/register.php';
    }

    public function doRegister(): void
    {
        ensureSession();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $errors = [];

        // Validar username
        if ($username === '') {
            $errors[] = 'El nombre de usuario es obligatorio.';
        } elseif (strlen($username) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres.';
        } elseif (strlen($username) > 50) {
            $errors[] = 'El nombre de usuario no puede tener más de 50 caracteres.';
        }

        // Validar contraseña
        if ($password === '') {
            $errors[] = 'La contraseña es obligatoria.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        // Validar confirmación de contraseña
        if ($password !== $passwordConfirm) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        // Verificar si el usuario ya existe
        if (empty($errors)) {
            $user = User::findByUsername($username);
            if ($user) {
                $errors[] = 'El nombre de usuario ya está en uso.';
            }
        }

        // Si hay errores, volver al formulario
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_username'] = $username;
            header('Location: index.php?c=auth&a=register');
            exit;
        }

        // Crear usuario
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $pdo = getPdo();

        $stmt = $pdo->prepare('
        INSERT INTO users (username, password_hash, role)
        VALUES (:username, :password_hash, :role)
    ');

        $stmt->execute([
            ':username' => $username,
            ':password_hash' => $passwordHash,
            ':role' => 'cliente'
        ]);

        $_SESSION['success'] = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
        header('Location: index.php?c=auth&a=login');
        exit;
    }

    /**
     * Cierra la sesión.
     */
    public function logout(): void
    {
        ensureSession();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        header('Location: index.php?c=auth&a=login');
        exit;
    }
}
