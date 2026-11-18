<?php
declare(strict_types=1);

namespace App\Core;

/** Le contrôleur frontal : reçoit la requête, choisit le contrôleur, gère les erreurs. */
final class Application
{
    private Router $routeur;
    private View $vue;

    public function __construct(private readonly Request $requete)
    {
        $this->routeur = new Router($requete->base());
        (require Config::racine() . '/config/routes.php')($this->routeur);
        $this->vue = new View($this->routeur, $requete->base());
    }

    public function traiter(): Response
    {
        try {
            $route = $this->routeur->resoudre($this->requete);
            [$classe, $methode] = $route['action'];
            $controleur = new $classe($this->requete, $this->vue, $this->routeur);

            return $controleur->$methode(...$route['params']);
        } catch (HttpException $e) {
            if ($e->statut() >= 500) {
                Logger::getInstance()->error($e->getMessage());
            }

            return $this->pageErreur($e->statut(), $e->getMessage());
        } catch (\Throwable $e) {
            Logger::getInstance()->error($e->getMessage(), ['fichier' => $e->getFile(), 'ligne' => $e->getLine()]);
            $message = Config::get('debug', false)
                ? $e::class . ' : ' . $e->getMessage() . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')'
                : 'Une erreur est survenue. Réessayez plus tard.';

            return $this->pageErreur(500, $message);
        }
    }

    private function pageErreur(int $statut, string $message): Response
    {
        try {
            return new Response($this->vue->rendre('erreur.html.twig', ['code' => $statut, 'message' => $message]), $statut);
        } catch (\Throwable) {
            return new Response('<h1>Erreur ' . $statut . '</h1>', $statut); // dernier recours si Twig est cassé
        }
    }
}
