INSERT INTO `matiere` (`id`, `titre`, `reference`) VALUES
(1, 'Programmation web avancée', 'PROG WEB AV'),
(2, 'Programmation embarquée', 'PROG EMB'),
(3, 'Programmation répartie', 'PROG REP'),
(4, 'Mathématiques', 'MATHS'),
(5, 'Anglais', 'ANGLAIS');

INSERT INTO `professeur` (`id`, `nom`, `prenom`, `email`) VALUES
(7, 'Loeb', 'Jacques', 'jacques.loeb@iut.fr'),
(8, 'Pasteur', 'Louis', 'louis.pasteur@iut.fr'),
(9, 'Alexandre', 'Fleming', 'alexandre.fleming@iut.com'),
(10, 'Charles', 'Darwin', 'charles.darwin@iut.fr'),
(11, 'Jacob', 'Francois', 'francois.jacob@iut.fr'),
(12, 'Monod', 'Théodore', 'thodore.monod@iut.fr'),
(13, 'Monod', 'Jacques', 'jacques.monod@iut.fr');

INSERT INTO `matiere_professeur` (`matiere_id`, `professeur_id`) VALUES
(1, 9),
(1, 10),
(2, 7),
(3, 8),
(4, 11),
(5, 12);

INSERT INTO `salle` (`id`, `numero`) VALUES
(4, 1),
(6, 2),
(7, 3),
(8, 4),
(12, 5),
(13, 6);

INSERT INTO `cours` (`id`, `matiere_id`, `professeur_id`, `salle_id`, `date_heure_debut`, `date_heure_fin`, `type`) VALUES
(22, 5, 12, 4, '2021-03-15 08:00:00', '2021-03-15 10:00:00', 'Cours'),
(23, 1, 9, 6, '2021-03-15 08:00:00', '2021-03-15 12:30:00', 'TP'),
(24, 4, 11, 4, '2021-03-15 10:00:00', '2021-03-15 12:30:00', 'TD'),
(25, 2, 7, 4, '2021-03-15 14:00:00', '2021-03-15 16:00:00', 'TP'),
(26, 3, 8, 12, '2021-03-15 10:30:00', '2021-03-15 12:30:00', 'TD'),
(27, 1, 10, 6, '2021-03-15 14:00:00', '2021-03-15 17:00:00', 'TP');

INSERT INTO `avis_cours` (`id`, `cours_id`, `note`, `commentaire`, `email_etudiant`) VALUES
(1, 22, 5, 'Je suis devenu bilingue grâce à ce cours !', 'etudiant1@iut.fr'),
(2, 27, 5, 'Je sais maintenant comment faire un site web très réactif et à la pointe de la technologie !', 'etudiant2@iut.fr');

INSERT INTO `avis` (`id`, `professeur_id`, `note`, `commentaire`, `email_etudiant`) VALUES
(1, 9, 5, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'etudiant7@iut.fr');