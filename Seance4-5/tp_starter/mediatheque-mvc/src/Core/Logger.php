<?php
declare(strict_types=1);

namespace App\Core;

/** Singleton : un seul journal, niveaux INFO / WARNING / ERROR, horodaté. */
final class Logger
{
    public const INFO = 'INFO';
    public const WARNING = 'WARNING';
    public const ERROR = 'ERROR';

    private static ?self $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    /** @param array<string, mixed> $contexte */
    public function info(string $message, array $contexte = []): void
    {
        $this->log(self::INFO, $message, $contexte);
    }

    /** @param array<string, mixed> $contexte */
    public function warning(string $message, array $contexte = []): void
    {
        $this->log(self::WARNING, $message, $contexte);
    }

    /** @param array<string, mixed> $contexte */
    public function error(string $message, array $contexte = []): void
    {
        $this->log(self::ERROR, $message, $contexte);
    }

    /** @param array<string, mixed> $contexte */
    public function log(string $niveau, string $message, array $contexte = []): void
    {
        $horodatage = date('Y-m-d H:i:s');
        $json = $contexte === [] ? '' : json_encode($contexte, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // 1. Fichier (toujours)
        $fichier = (string) Config::get('log.file', 'storage/logs/app.log');
        if (!str_starts_with($fichier, '/')) {
            $fichier = Config::racine() . '/' . $fichier;
        }
        is_dir(dirname($fichier)) || @mkdir(dirname($fichier), 0775, true);
        $ligne = sprintf("[%s] %s %s%s\n", $horodatage, $niveau, str_replace(["\r", "\n"], ' ', $message), $json !== '' ? ' ' . $json : '');
        @file_put_contents($fichier, $ligne, FILE_APPEND | LOCK_EX);

        // 2. Base de données (si activée) : un échec de journalisation ne doit jamais casser la page
        if (Config::get('log.db', false)) {
            try {
                Database::getInstance()->pdo()
                    ->prepare('INSERT INTO logs (level, message, context, created_at) VALUES (?, ?, ?, ?)')
                    ->execute([$niveau, $message, $json !== '' ? $json : null, $horodatage]);
            } catch (\Throwable) {
                // déjà écrit dans le fichier
            }
        }
    }
}
