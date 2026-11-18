<?php
declare(strict_types=1);

namespace App\Core;

/** La réponse HTTP : un statut, des en-têtes, un corps. */
final class Response
{
    /** @param array<string, string> $entetes */
    public function __construct(
        private readonly string $corps = '',
        private readonly int $statut = 200,
        private readonly array $entetes = ['Content-Type' => 'text/html; charset=UTF-8'],
    ) {
    }

    /** Redirection après POST : code 303 (« voir ailleurs », toujours en GET). */
    public static function redirection(string $url, int $statut = 303): self
    {
        return new self('', $statut, ['Location' => $url]);
    }

    public function statut(): int
    {
        return $this->statut;
    }

    public function corps(): string
    {
        return $this->corps;
    }

    public function entete(string $nom): ?string
    {
        return $this->entetes[$nom] ?? null;
    }

    public function envoyer(): void
    {
        http_response_code($this->statut);
        foreach ($this->entetes as $nom => $valeur) {
            header($nom . ': ' . $valeur);
        }
        echo $this->corps;
    }
}
