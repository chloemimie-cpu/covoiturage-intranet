-- Base utilisée uniquement par les tests PHPUnit.
-- Même structure que la base principale (01_creation.sql) : à garder synchronisées.

DROP DATABASE IF EXISTS covoiturage_test;
CREATE DATABASE covoiturage_test
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Même utilisateur que l'application, mêmes droits limités
GRANT SELECT, INSERT, UPDATE, DELETE ON covoiturage_test.* TO 'covoiturage_app'@'localhost';
FLUSH PRIVILEGES;

USE covoiturage_test;

CREATE TABLE employes (
    id_employe    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom           VARCHAR(50)  NOT NULL,
    prenom        VARCHAR(50)  NOT NULL,
    telephone     VARCHAR(10)  NOT NULL,
    email         VARCHAR(100) NOT NULL,
    mot_de_passe  VARCHAR(255) NOT NULL,
    role          ENUM('employe', 'admin') NOT NULL DEFAULT 'employe',
    PRIMARY KEY (id_employe),
    UNIQUE KEY uq_employes_email (email)
) ENGINE=InnoDB;

CREATE TABLE agences (
    id_agence  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom        VARCHAR(50)  NOT NULL,
    PRIMARY KEY (id_agence),
    UNIQUE KEY uq_agences_nom (nom)
) ENGINE=InnoDB;

CREATE TABLE trajets (
    id_trajet           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    date_depart         DATETIME     NOT NULL,
    date_arrivee        DATETIME     NOT NULL,
    places_total        TINYINT UNSIGNED NOT NULL,
    places_disponibles  TINYINT UNSIGNED NOT NULL,
    id_employe          INT UNSIGNED NOT NULL,
    id_agence_depart    INT UNSIGNED NOT NULL,
    id_agence_arrivee   INT UNSIGNED NOT NULL,
    PRIMARY KEY (id_trajet),
    CONSTRAINT fk_trajets_employe
        FOREIGN KEY (id_employe) REFERENCES employes (id_employe),
    CONSTRAINT fk_trajets_depart
        FOREIGN KEY (id_agence_depart) REFERENCES agences (id_agence),
    CONSTRAINT fk_trajets_arrivee
        FOREIGN KEY (id_agence_arrivee) REFERENCES agences (id_agence),
    CONSTRAINT ck_trajets_agences    CHECK (id_agence_depart <> id_agence_arrivee),
    CONSTRAINT ck_trajets_dates      CHECK (date_arrivee > date_depart),
    CONSTRAINT ck_trajets_places     CHECK (places_total >= 1),
    CONSTRAINT ck_trajets_dispo      CHECK (places_disponibles <= places_total)
) ENGINE=InnoDB;