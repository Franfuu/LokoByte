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
    public function doLogin(): void {
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
