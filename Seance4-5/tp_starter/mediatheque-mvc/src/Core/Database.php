<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/** Singleton : une seule connexion PDO pour toute la requête. */
final class Database
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $c = Config::get('db', []);
        $pilote = $c['driver'] ?? 'mysql';

        if ($pilote === 'sqlite') {
            $chemin = (string) ($c['path'] ?? ':memory:');
            if ($chemin !== ':memory:' && !str_starts_with($chemin, '/')) {
                $chemin = Config::racine() . '/' . $chemin;
            }
            $dsn = 'sqlite:' . $chemin;
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $c['host'] ?? 'localhost',
                $c['port'] ?? 3306,
                $c['name'] ?? '',
                $c['charset'] ?? 'utf8mb4'
            );
        }

        try {
            $this->pdo = new PDO($dsn, $c['user'] ?? null, $c['pass'] ?? null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // On ne propage pas le message brut : il pourrait contenir des informations de connexion.
            throw new \RuntimeException('Connexion à la base de données impossible (voir config/local.php).', 0, $e);
        }
        if ($pilote === 'sqlite') {
            $this->pdo->exec('PRAGMA foreign_keys = ON');
        }
    }

    private function __clone()
    {
    }

    public function __wakeup(): void
    {
        throw new \LogicException('Un singleton ne se désérialise pas.');
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    /** Oublie la connexion (tests). */
    public static function reset(): void
    {
        self::$instance = null;
    }
}
