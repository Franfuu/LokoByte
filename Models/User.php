<?php
declare(strict_types=1);
class User {
    public int $id;
    public string $username;
    public string $password_hash;
    public string $role;

    public static function findByUsername(string $username): ?User {
        $pdo = getPdo();
        $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        $user = new User();
        $user->id = (int)$row['id'];
        $user->username = $row['username'];
        $user->password_hash = $row['password_hash'];
        $user->role = $row['role'];
        
        return $user;
    }
}
