<?php
declare(strict_types=1);

namespace App\Core;

/** Base des contrôleurs : accès à la requête, au rendu des vues et aux redirections. */
abstract class Controller
{
    public function __construct(
        protected readonly Request $requete,
        protected readonly View $vue,
        protected readonly Router $routeur,
    ) {
    }

    /** @param array<string, mixed> $donnees */
    protected function afficher(string $gabarit, array $donnees = [], int $statut = 200): Response
    {
        return new Response($this->vue->rendre($gabarit, $donnees), $statut);
    }

    /** @param array<string, mixed> $params */
    protected function rediriger(string $route, array $params = []): Response
    {
        return Response::redirection($this->routeur->url($route, $params));
    }

    protected function flash(string $type, string $message): void
    {
        Session::flash($type, $message);
    }
}
