<?php



namespace App\Repository;

final class Database
{
    private static ?\PDO $pdo = null;

    public static function connect(): \PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $requiredKeys = [
            'DB_HOST',
            'DB_PORT',
            'DB_NAME',
            'DB_USER',
            'DB_PASSWORD',
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($_ENV[$key]) || trim((string) $_ENV[$key]) === '') {
                throw new \RuntimeException("Variable d'environnement manquante : {$key}");
            }
        }

        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_NAME'],
        );

        try {
            self::$pdo = new \PDO(
                $dsn,
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ],
            );
        } catch (\PDOException $exception) {
            throw new \PDOException(
                'Erreur de connexion à la base : ' . $exception->getMessage(),
                (int) $exception->getCode(),
                $exception,
            );
        }

        return self::$pdo;
    }

    public static function getPdo(): \PDO
    {
        return self::connect();
    }

    public static function disconnect(): void
    {
        self::$pdo = null;
    }
}
