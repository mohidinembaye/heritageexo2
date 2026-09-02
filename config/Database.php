<?php


namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $env = chargerEnv(dirname(__DIR__) . '/.env');

            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $env['DB_HOST'],
                $env['DB_PORT'],
                $env['DB_NAME']
            );

            try {
            $pdo = new PDO(
                    $dsn,
                    $env['DB_USER'],
                    $env['DB_PASSWORD'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException('Erreur de connexion à la base : ' . $e->getMessage());
            }
    }

    return $pdo;
}

function chargerEnv(string $path): array
{
        if (!is_file($path)) {
            throw new RuntimeException("Fichier .env introuvable : $path");
        }

        $env = [];
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }

        return $env;
}
