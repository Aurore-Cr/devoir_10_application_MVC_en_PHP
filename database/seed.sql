/**
 * Touche pas au klaxon - Jeu d'essai (donnees d'alimentation)
 * Genere a partir des annexes RH fournies (users.txt / agences.txt)
 */

USE touche_pas_au_klaxon_db;

/**
* Agences
*/

INSERT INTO agence (nom_agence) VALUES ('Paris');
INSERT INTO agence (nom_agence) VALUES ('Lyon');
INSERT INTO agence (nom_agence) VALUES ('Marseille');
INSERT INTO agence (nom_agence) VALUES ('Toulouse');
INSERT INTO agence (nom_agence) VALUES ('Nice');
INSERT INTO agence (nom_agence) VALUES ('Nantes');
INSERT INTO agence (nom_agence) VALUES ('Strasbourg');
INSERT INTO agence (nom_agence) VALUES ('Montpellier');
INSERT INTO agence (nom_agence) VALUES ('Bordeaux');
INSERT INTO agence (nom_agence) VALUES ('Lille');
INSERT INTO agence (nom_agence) VALUES ('Rennes');
INSERT INTO agence (nom_agence) VALUES ('Reims');

/**
* Employes et Mot de passe en clair pour tous les employes de demonstration en hashage bcrypt : Admin123!
* Compte administrateur dedie : admin@touchepasauklaxon.fr / Admin123!
*/

INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role) VALUES
('Admin', 'Klaxon', 'admin@touchepasauklaxon.fr', '0102030405', '$2y$10$rUeXnhtaKrCufjwzf9YH8ef2OO0fDwaJhnXV4DD0rBZkfqqPEoLqG', 'admin');

INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role) VALUES
('Martin', 'Alexandre', 'alexandre.martin@email.fr', '0612345678', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Dubois', 'Sophie', 'sophie.dubois@email.fr', '0698765432', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Bernard', 'Julien', 'julien.bernard@email.fr', '0622446688', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Moreau', 'Camille', 'camille.moreau@email.fr', '0611223344', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Lefèvre', 'Lucie', 'lucie.lefevre@email.fr', '0777889900', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Leroy', 'Thomas', 'thomas.leroy@email.fr', '0655443322', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Roux', 'Chloé', 'chloe.roux@email.fr', '0633221199', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Petit', 'Maxime', 'maxime.petit@email.fr', '0766778899', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Garnier', 'Laura', 'laura.garnier@email.fr', '0688776655', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Dupuis', 'Antoine', 'antoine.dupuis@email.fr', '0744556677', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Lefebvre', 'Emma', 'emma.lefebvre@email.fr', '0699887766', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Fontaine', 'Louis', 'louis.fontaine@email.fr', '0655667788', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Chevalier', 'Clara', 'clara.chevalier@email.fr', '0788990011', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Robin', 'Nicolas', 'nicolas.robin@email.fr', '0644332211', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Gauthier', 'Marine', 'marine.gauthier@email.fr', '0677889922', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Fournier', 'Pierre', 'pierre.fournier@email.fr', '0722334455', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Girard', 'Sarah', 'sarah.girard@email.fr', '0688665544', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Lambert', 'Hugo', 'hugo.lambert@email.fr', '0611223366', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Masson', 'Julie', 'julie.masson@email.fr', '0733445566', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur'),
('Henry', 'Arthur', 'arthur.henry@email.fr', '0666554433', '$2y$10$Hwroz0InIb.SEHsXNDir/.KnI9q/OJW9iqUZt7d.24hzG09yQXM5G', 'utilisateur');

/**
* Trajets (jeu d'essai)
* id_utilisateur : 1 = admin, 2..21 = employes (ordre du fichier users.txt)
*/

INSERT INTO trajet (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee, nb_places_total, nb_places_disponibles, id_utilisateur) VALUES
(1, 2, '2026-08-18 08:00:00', '2026-08-18 11:00:00', 3, 0, 2),
(2, 1, '2026-08-17 09:00:00', '2026-08-17 12:00:00', 4, 3, 3),
(3, 4, '2026-08-16 10:00:00', '2026-08-16 13:00:00', 5, 3, 4),
(4, 5, '2026-08-17 11:00:00', '2026-08-17 14:00:00', 3, 3, 5),
(5, 6, '2026-08-18 12:00:00', '2026-08-18 15:00:00', 4, 0, 6),
(6, 3, '2026-08-19 13:00:00', '2026-08-19 16:00:00', 5, 5, 7),
(7, 8, '2026-08-20 08:00:00', '2026-08-20 11:00:00', 3, 3, 8),
(8, 9, '2026-08-21 09:00:00', '2026-08-21 12:00:00', 4, 1, 9),
(9, 10, '2026-08-22 10:00:00', '2026-08-22 13:00:00', 5, 0, 10),
(10, 7, '2026-08-23 11:00:00', '2026-08-23 14:00:00', 3, 3, 11),
(11, 12, '2026-08-24 12:00:00', '2026-08-24 15:00:00', 4, 2, 12),
(12, 1, '2026-08-25 13:00:00', '2026-08-25 16:00:00', 5, 4, 13),
(1, 5, '2026-08-26 08:00:00', '2026-08-26 11:00:00', 3, 0, 14),
(2, 8, '2026-08-27 09:00:00', '2026-08-27 12:00:00', 4, 3, 15),
(3, 9, '2026-08-28 10:00:00', '2026-08-28 13:00:00', 5, 1, 16);
