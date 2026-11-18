<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Table de routage : associe (méthode, chemin) à (classe contrôleur, méthode).
 * Les segments {nom} ou {nom:regex} capturent des paramètres : '/livres/{id:\d+}'.
 */
final class Router
{
    /** @var array<string, array{methode: string, motif: string, regex: string, action: array{0: class-string, 1: string}}> */
    private array $routes = [];

    public function __construct(private readonly string $base = '')
    {
    }

    /** @param array{0: class-string, 1: string} $action */
    public function get(string $nom, string $motif, array $action): void
    {
        $this->ajouter($nom, 'GET', $motif, $action);
    }

    /** @param array{0: class-string, 1: string} $action */
    public function post(string $nom, string $motif, array $action): void
    {
        $this->ajouter($nom, 'POST', $motif, $action);
    }

    /** @param array{0: class-string, 1: string} $action */
    private function ajouter(string $nom, string $methode, string $motif, array $action): void
    {
        $regex = preg_replace_callback(
            '/\{(\w+)(?::([^}]+))?\}/',
            static fn(array $m): string => '(?P<' . $m[1] . '>' . ($m[2] ?? '[^/]+') . ')',
            $motif
        );
        $this->routes[$nom] = ['methode' => $methode, 'motif' => $motif, 'regex' => '#^' . $regex . '$#', 'action' => $action];
    }

    /**
     * @return array{action: array{0: class-string, 1: string}, params: array<string, string>}
     * @throws HttpException 404 si aucun chemin ne correspond, 405 si le chemin existe mais pas avec cette méthode
     */
    public function resoudre(Request $requete): array
    {
        $methodesAutorisees = [];
        foreach ($this->routes as $route) {
            if (preg_match($route['regex'], $requete->chemin(), $m) !== 1) {
                continue;
            }
            if ($route['methode'] !== $requete->methode()) {
                $methodesAutorisees[] = $route['methode'];
                continue;
            }
            $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);

            return ['action' => $route['action'], 'params' => $params];
        }
        if ($methodesAutorisees !== []) {
            throw new HttpException(405);
        }
        throw new HttpException(404);
    }

    /** Fabrique une URL à partir du NOM de la route : url('livre_detail', ['id' => 3]). */
    public function url(string $nom, array $params = [], array $query = []): string
    {
        if (!isset($this->routes[$nom])) {
            throw new \InvalidArgumentException("Route inconnue : « $nom »");
        }
        $chemin = preg_replace_callback(
            '/\{(\w+)(?::[^}]+)?\}/',
            static function (array $m) use ($params, $nom): string {
                if (!array_key_exists($m[1], $params)) {
                    throw new \InvalidArgumentException("Paramètre « {$m[1]} » manquant pour la route « $nom »");
                }

                return rawurlencode((string) $params[$m[1]]);
            },
            $this->routes[$nom]['motif']
        );
        $url = $this->base . ($chemin === '/' ? '/' : $chemin);

        return $query === [] ? $url : $url . '?' . http_build_query($query);
    }
}
