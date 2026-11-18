<?php
declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/** Le « V » : fabrique l'environnement Twig et rend les gabarits. */
final class View
{
    private Environment $twig;

    public function __construct(Router $routeur, string $base)
    {
        $debug = (bool) Config::get('debug', false);
        $racine = Config::racine();

        $this->twig = new Environment(new FilesystemLoader($racine . '/templates'), [
            'cache'            => $racine . '/storage/cache/twig',
            'debug'            => $debug,
            'auto_reload'      => $debug,
            'strict_variables' => $debug,
            'autoescape'       => 'html',
        ]);
        if ($debug) {
            $this->twig->addExtension(new DebugExtension());
        }

        $this->twig->addGlobal('app_nom', Config::get('nom', 'Application'));
        $this->twig->addGlobal('bootstrap_cdn', (bool) Config::get('bootstrap_cdn', false));
        $this->twig->addFunction(new TwigFunction('path', $routeur->url(...)));
        $this->twig->addFunction(new TwigFunction('asset', static fn(string $f): string => $base . '/assets/' . ltrim($f, '/')));
        $this->twig->addFunction(new TwigFunction('csrf_token', Csrf::token(...)));
        $this->twig->addFunction(new TwigFunction('flashes', Session::lireFlash(...)));
    }

    /** @param array<string, mixed> $donnees */
    public function rendre(string $gabarit, array $donnees = []): string
    {
        return $this->twig->render($gabarit, $donnees);
    }
}
