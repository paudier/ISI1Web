<?php
declare(strict_types=1);

namespace App\Core;

/** Session PHP + messages « flash » (affichés une seule fois, après une redirection). */
final class Session
{
    public static function demarrer(): void
    {
        if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
            session_set_cookie_params([
                'httponly' => true,       // inaccessible au JavaScript
                'samesite' => 'Lax',      // limite l'envoi du cookie depuis un autre site
                'secure'   => !empty($_SERVER['HTTPS']),
            ]);
            session_start();
        }
        $_SESSION ??= [];
    }

    public static function get(string $cle, mixed $defaut = null): mixed
    {
        return $_SESSION[$cle] ?? $defaut;
    }

    public static function set(string $cle, mixed $valeur): void
    {
        $_SESSION[$cle] = $valeur;
    }

    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
    }

    /** @return list<array{type: string, message: string}> lit puis efface */
    public static function lireFlash(): array
    {
        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);

        return $messages;
    }
}
