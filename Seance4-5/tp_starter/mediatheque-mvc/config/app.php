<?php
declare(strict_types=1);

// Valeurs PAR DÉFAUT de l'application. Ce fichier est versionné : il ne contient AUCUN secret
// et ses valeurs par défaut sont sûres pour la production (debug désactivé).
// Les réglages propres à votre machine vont dans config/local.php (non versionné).
return [
    'nom'           => 'Médiathèque',
    'debug'         => false,
    'bootstrap_cdn' => false,   // true : Bootstrap chargé depuis le CDN jsDelivr

    'db' => [
        'driver'  => 'mysql',
        'host'    => 'localhost',
        'port'    => 3306,
        'name'    => 'mediatheque',
        'user'    => 'root',
        'pass'    => '',          // ne jamais écrire un vrai mot de passe ici
        'charset' => 'utf8mb4',
    ],

    'log' => [
        'file' => 'storage/logs/app.log',
        'db'   => false,
    ],
];
