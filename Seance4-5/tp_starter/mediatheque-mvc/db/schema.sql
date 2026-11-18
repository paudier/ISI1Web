-- Médiathèque : schéma MariaDB 10.4+ (XAMPP)
CREATE DATABASE IF NOT EXISTS mediatheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mediatheque;

DROP TABLE IF EXISTS livres;
CREATE TABLE livres (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre       VARCHAR(150) NOT NULL,
    auteur      VARCHAR(100) NOT NULL,
    annee       SMALLINT     NOT NULL,
    resume      TEXT         NULL,
    disponible  TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
