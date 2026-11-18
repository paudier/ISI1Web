<?php
declare(strict_types=1);

// MODÈLE de configuration locale. Étapes :
//   1. copiez ce fichier en config/local.php  (cp config/local.example.php config/local.php)
//   2. adaptez les valeurs à votre machine
// config/local.php est ignoré par Git : vos mots de passe ne seront jamais publiés.
// Seules les clés à modifier sont nécessaires : le reste vient de config/app.php.
return [
    'debug' => true,
    'db' => [
        'user' => 'root',
        'pass' => '',
    ],
];
