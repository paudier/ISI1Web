<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Accès à la configuration.
 *
 *   config/app.php    : valeurs par défaut, SANS secret      -> versionné (commité)
 *   config/local.php  : réglages de CETTE machine + secrets  -> jamais commité (.gitignore)
 *
 * local.php est fusionné par-dessus app.php (clé par clé, y compris dans les sous-tableaux).
 */
final class Config
{
    /** @var array<string, mixed>|null */
    private static ?array $valeurs = null;

    public static function racine(): string
    {
        return dirname(__DIR__, 2);
    }

    /** Lecture avec notation pointée : Config::get('db.host', 'localhost'). */
    public static function get(string $cle, mixed $defaut = null): mixed
    {
        $courant = self::charger();
        foreach (explode('.', $cle) as $segment) {
            if (!is_array($courant) || !array_key_exists($segment, $courant)) {
                return $defaut;
            }
            $courant = $courant[$segment];
        }

        return $courant;
    }

    /** Surcharge en mémoire (tests uniquement). */
    public static function set(string $cle, mixed $valeur): void
    {
        $valeurs = self::charger();
        $ref = &$valeurs;
        foreach (explode('.', $cle) as $segment) {
            if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }
        $ref = $valeur;
        self::$valeurs = $valeurs;
    }

    public static function reset(): void
    {
        self::$valeurs = null;
    }

    /** @return array<string, mixed> */
    private static function charger(): array
    {
        if (self::$valeurs === null) {
            $dossier = self::racine() . '/config';
            $valeurs = require $dossier . '/app.php';
            if (is_file($dossier . '/local.php')) {
                $valeurs = array_replace_recursive($valeurs, require $dossier . '/local.php');
            }
            self::$valeurs = $valeurs;
        }

        return self::$valeurs;
    }
}
