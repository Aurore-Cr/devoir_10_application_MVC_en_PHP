/**
 * Touche pas au klaxon - Script de creation de la base de donnees
 * Ce script SQL cree la base de donnees "touche_pas_au_klaxon" et toutes
 * les tables necessaires pour l'application.

 */

DROP DATABASE IF EXISTS touche_pas_au_klaxon;
CREATE DATABASE touche_pas_au_klaxon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE touche_pas_au_klaxon;

/** 
* Table : agence
* Villes / implantations de l'entreprise. Modifiable uniquement par
l'administrateur.
*/



CREATE TABLE agence (
    id_agence     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom_agence    VARCHAR(100) NOT NULL UNIQUE
) ENGINE = InnoDB;

/** 
* Table : utilisateur
* Employes de l'entreprise, extraits du systeme RH. Le champ role
* distingue les employes classiques de l'administrateur.
* ---------------------------------------------------------------------
*/

CREATE TABLE utilisateur (
    id_utilisateur  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(80)  NOT NULL,
    prenom          VARCHAR(80)  NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    telephone       VARCHAR(20)  NOT NULL,
    mot_de_passe    VARCHAR(255) NOT NULL,
    role            ENUM('utilisateur', 'admin') NOT NULL DEFAULT 'utilisateur'
) ENGINE = InnoDB;

/** 
* Table : trajet
* Un trajet inter-site propose par un utilisateur (le "contact").
* ---------------------------------------------------------------------
*/

CREATE TABLE trajet (
    id_trajet               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_agence_depart        INT UNSIGNED NOT NULL,
    id_agence_arrivee       INT UNSIGNED NOT NULL,
    date_heure_depart       DATETIME NOT NULL,
    date_heure_arrivee      DATETIME NOT NULL,
    nb_places_total         TINYINT UNSIGNED NOT NULL,
    nb_places_disponibles   TINYINT UNSIGNED NOT NULL,
    id_utilisateur          INT UNSIGNED NOT NULL,

    CONSTRAINT fk_trajet_agence_depart
        FOREIGN KEY (id_agence_depart) REFERENCES agence(id_agence)
        ON DELETE RESTRICT ON UPDATE RESTRICT,

    CONSTRAINT fk_trajet_agence_arrivee
        FOREIGN KEY (id_agence_arrivee) REFERENCES agence(id_agence)
        ON DELETE RESTRICT ON UPDATE RESTRICT,

    CONSTRAINT fk_trajet_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT chk_agences_differentes
        CHECK (id_agence_depart <> id_agence_arrivee),

    CONSTRAINT chk_dates_coherentes
        CHECK (date_heure_arrivee > date_heure_depart),

    CONSTRAINT chk_places_disponibles
        CHECK (nb_places_disponibles <= nb_places_total)
) ENGINE = InnoDB;

CREATE INDEX idx_trajet_date_depart ON trajet(date_heure_depart);
