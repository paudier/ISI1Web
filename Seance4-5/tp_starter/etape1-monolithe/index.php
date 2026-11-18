<?php
// Étape 1 : tout dans un seul fichier (accès aux données + HTML mélangés)
$bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$livres = $bdd->query('SELECT id, titre, auteur, annee, disponible FROM livres ORDER BY titre');
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Médiathèque</title>
</head>
<body>
    <header><h1>Médiathèque</h1></header>
    <?php foreach ($livres as $livre): ?>
        <article>
            <h2><?= htmlspecialchars($livre['titre']) ?></h2>
            <p><?= htmlspecialchars($livre['auteur']) ?> (<?= $livre['annee'] ?>)</p>
            <p class="<?= $livre['disponible'] ? 'dispo' : 'emprunte' ?>"><?= $livre['disponible'] ? 'Disponible' : 'Emprunté' ?></p>
        </article>
    <?php endforeach; ?>
    <footer>Réalisé avec PHP</footer>
</body>
</html>
