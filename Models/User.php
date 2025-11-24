<?php
declare(strict_types=1);

class User{
    private int $id;
    private string $username;
    private string $passwordHash;

    public static function findByUsername(PDO $pdo, string $username): ?User
    {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $user = new User();
        $user->id = (int)$row['id'];
        $user->username = $row['username'];
        $user->passwordHash = $row['password_hash'];

        return $user;
    }
}