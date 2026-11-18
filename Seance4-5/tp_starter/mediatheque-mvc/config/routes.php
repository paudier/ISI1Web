<?php
declare(strict_types=1);

use App\Core\Router;

// Table de routage : nom de la route, chemin, [classe du contrôleur, méthode]
// À COMPLÉTER (exercices 9 à 11). Exemple de syntaxe :
//     $r->get('accueil', '/', [LivreController::class, 'index']);
//     $r->get('livre_detail', '/livres/{id:\d+}', [LivreController::class, 'show']);
//     $r->post('livre_emprunter', '/livres/{id:\d+}/emprunter', [LivreController::class, 'emprunter']);
return static function (Router $r): void {
};
