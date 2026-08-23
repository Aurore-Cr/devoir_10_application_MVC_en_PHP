

CREATE TABLE agence (
    id_agence     INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_agence    VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE utilisateur (
    id_utilisateur  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             VARCHAR(80)  NOT NULL,
    prenom          VARCHAR(80)  NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    telephone       VARCHAR(20)  NOT NULL,
    mot_de_passe    VARCHAR(255) NOT NULL,
    role            VARCHAR(20) NOT NULL DEFAULT 'utilisateur'
);

CREATE TABLE trajet (
    id_trajet               INTEGER PRIMARY KEY AUTOINCREMENT,
    id_agence_depart        INTEGER NOT NULL REFERENCES agence(id_agence),
    id_agence_arrivee       INTEGER NOT NULL REFERENCES agence(id_agence),
    date_heure_depart       DATETIME NOT NULL,
    date_heure_arrivee      DATETIME NOT NULL,
    nb_places_total         INTEGER NOT NULL,
    nb_places_disponibles   INTEGER NOT NULL,
    id_utilisateur          INTEGER NOT NULL REFERENCES utilisateur(id_utilisateur)
);
