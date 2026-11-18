USE mediatheque;

INSERT INTO livres (titre, auteur, annee, resume, disponible) VALUES
('Les Misérables',      'Victor Hugo',               1862, 'Le destin de Jean Valjean dans la France du XIXe siècle.', 1),
('Orgueil & Préjugés',  'Jane Austen',               1813, NULL, 0),
('Le Petit Prince',     'Antoine de Saint-Exupéry',  1943, 'Un aviateur rencontre un petit garçon venu d''une autre planète.', 1),
('Fondation',           'Isaac Asimov',              1951, NULL, 1),
('Clean Code',          'Robert C. Martin',          2008, 'Écrire du code lisible et maintenable.', 1),
('Dune',                'Frank Herbert',             1965, NULL, 0);
