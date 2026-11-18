# Médiathèque – architecture MVC (séance 4)

Application d'exemple du cours MVC : catalogue de livres, détail, emprunt / retour.

## Installation (XAMPP)

1. Copier le dossier dans `htdocs/` (ex. `C:\xampp\htdocs\mediatheque-mvc`).
2. Dans un terminal, à la racine du projet : `composer install`
3. Démarrer Apache et MariaDB (panneau XAMPP), ouvrir phpMyAdmin > **Importer** : `db/schema.sql` puis `db/seed.sql`.
4. Créer sa configuration locale : copier `config/local.example.php` en `config/local.php` et l'adapter.
5. Ouvrir <http://localhost/mediatheque-mvc/public/>

Sans Apache : `php -S localhost:8000 -t public` puis <http://localhost:8000/>.

## Organisation

| Dossier | Rôle |
|---|---|
| `public/` | seul dossier accessible par le navigateur : `index.php` (contrôleur frontal), `assets/` |
| `config/` | `app.php` (versionné), `local.php` (non versionné), `routes.php` (table de routage) |
| `src/Core/` | noyau réutilisable : Application, Router, Request, Response, Controller, View, Config, Database, Logger, Session, Csrf |
| `src/Controller/` | contrôleurs (un par ressource) |
| `src/Model/` | entités et dépôts (accès SQL) |
| `src/Service/` | règles métier |
| `templates/` | vues Twig |
| `db/` | scripts SQL |
| `storage/` | journaux et cache (non versionnés) |

## Configuration

`config/app.php` (versionné) contient les valeurs par défaut **sans secret**. `config/local.php` (ignoré par Git)
est fusionné par-dessus : c'est là que vont le mot de passe de la base et le mode `debug`.

