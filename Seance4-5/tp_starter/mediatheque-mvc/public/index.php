<?php
declare(strict_types=1);

// Contrôleur frontal : TOUTES les requêtes passent par ce fichier.
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Application;
use App\Core\Request;
use App\Core\Session;

Session::demarrer();
(new Application(Request::fromGlobals()))->traiter()->envoyer();
