<?php
declare(strict_types=1);

namespace App\Core;

/** Erreur HTTP « attendue » (404, 403, 405…) : le noyau affiche la page d'erreur correspondante. */
final class HttpException extends \RuntimeException
{
    public function __construct(private readonly int $statut, string $message = '')
    {
        parent::__construct($message !== '' ? $message : self::libelle($statut), $statut);
    }

    public function statut(): int
    {
        return $this->statut;
    }

    private static function libelle(int $statut): string
    {
        return match ($statut) {
            403 => 'Action interdite.',
            404 => 'Page introuvable.',
            405 => 'Méthode non autorisée.',
            default => 'Erreur.',
        };
    }
}
