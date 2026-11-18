<?php
declare(strict_types=1);

namespace App\Core;

/** Jeton anti-CSRF : un secret par session, à glisser dans chaque formulaire POST. */
final class Csrf
{
    public static function token(): string
    {
        return $_SESSION['_csrf'] ??= bin2hex(random_bytes(32));
    }

    public static function valide(mixed $recu): bool
    {
        return is_string($recu) && isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $recu);
    }

    /** @throws HttpException 403 */
    public static function exiger(Request $requete): void
    {
        if (!self::valide($requete->post('_csrf'))) {
            Logger::getInstance()->warning('Jeton CSRF invalide', ['chemin' => $requete->chemin()]);
            throw new HttpException(403, 'Jeton de sécurité invalide ou expiré. Rechargez la page et recommencez.');
        }
    }
}
