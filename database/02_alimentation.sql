USE covoiturage;

-- Empreinte du mot de passe de test, commune à tous les comptes
SET @mdp = 'COLLE_ICI_LEMPREINTE';

-- Agences (ids 1 à 12 dans l'ordre)
INSERT INTO agences (nom) VALUES
('Paris'), ('Lyon'), ('Marseille'), ('Toulouse'), ('Nice'), ('Nantes'),
('Strasbourg'), ('Montpellier'), ('Bordeaux'), ('Lille'), ('Rennes'), ('Reims');

-- Employés (ids 1 à 20 dans l'ordre) : le premier est l'administrateur
INSERT INTO employes (nom, prenom, telephone, email, mot_de_passe, role) VALUES
('Martin',    'Alexandre', '0612345678', 'alexandre.martin@email.fr',   @mdp, 'admin'),
('Dubois',    'Sophie',    '0698765432', 'sophie.dubois@email.fr',      @mdp, 'employe'),
('Bernard',   'Julien',    '0622446688', 'julien.bernard@email.fr',     @mdp, 'employe'),
('Moreau',    'Camille',   '0611223344', 'camille.moreau@email.fr',     @mdp, 'employe'),
('Lefèvre',   'Lucie',     '0777889900', 'lucie.lefevre@email.fr',      @mdp, 'employe'),
('Leroy',     'Thomas',    '0655443322', 'thomas.leroy@email.fr',       @mdp, 'employe'),
('Roux',      'Chloé',     '0633221199', 'chloe.roux@email.fr',         @mdp, 'employe'),
('Petit',     'Maxime',    '0766778899', 'maxime.petit@email.fr',       @mdp, 'employe'),
('Garnier',   'Laura',     '0688776655', 'laura.garnier@email.fr',      @mdp, 'employe'),
('Dupuis',    'Antoine',   '0744556677', 'antoine.dupuis@email.fr',     @mdp, 'employe'),
('Lefebvre',  'Emma',      '0699887766', 'emma.lefebvre@email.fr',      @mdp, 'employe'),
('Fontaine',  'Louis',     '0655667788', 'louis.fontaine@email.fr',     @mdp, 'employe'),
('Chevalier', 'Clara',     '0788990011', 'clara.chevalier@email.fr',    @mdp, 'employe'),
('Robin',     'Nicolas',   '0644332211', 'nicolas.robin@email.fr',      @mdp, 'employe'),
('Gauthier',  'Marine',    '0677889922', 'marine.gauthier@email.fr',    @mdp, 'employe'),
('Fournier',  'Pierre',    '0722334455', 'pierre.fournier@email.fr',    @mdp, 'employe'),
('Girard',    'Sarah',     '0688665544', 'sarah.girard@email.fr',       @mdp, 'employe'),
('Lambert',   'Hugo',      '0611223366', 'hugo.lambert@email.fr',       @mdp, 'employe'),
('Masson',    'Julie',     '0733445566', 'julie.masson@email.fr',       @mdp, 'employe'),
('Henry',     'Arthur',    '0666554433', 'arthur.henry@email.fr',       @mdp, 'employe');

-- Trajets : (départ, arrivée, places totales, places dispo, id employé, id agence départ, id agence arrivée)
INSERT INTO trajets (date_depart, date_arrivee, places_total, places_disponibles, id_employe, id_agence_depart, id_agence_arrivee) VALUES
('2026-12-02 08:00:00', '2026-12-02 12:00:00', 4, 3,  2,  1,  2),  -- Paris -> Lyon
('2026-12-02 14:00:00', '2026-12-02 17:30:00', 3, 2,  3,  2,  3),  -- Lyon -> Marseille
('2026-12-02 08:30:00', '2026-12-02 12:30:00', 3, 0,  4,  1,  2),  -- Paris -> Lyon, COMPLET
('2026-12-03 07:30:00', '2026-12-03 10:30:00', 4, 4,  5,  4,  9),  -- Toulouse -> Bordeaux
('2026-12-04 09:00:00', '2026-12-04 10:30:00', 3, 1,  6,  6, 11),  -- Nantes -> Rennes
('2026-12-05 13:00:00', '2026-12-05 15:30:00', 4, 2,  7, 10, 12),  -- Lille -> Reims
('2026-12-08 06:45:00', '2026-12-08 11:00:00', 4, 3,  8,  7,  1),  -- Strasbourg -> Paris
('2026-12-09 10:00:00', '2026-12-09 12:30:00', 2, 1,  9,  5,  3),  -- Nice -> Marseille
('2026-12-10 15:00:00', '2026-12-10 17:30:00', 3, 3,  2,  8,  4),  -- Montpellier -> Toulouse
('2026-12-11 08:00:00', '2026-12-11 12:00:00', 4, 2, 10,  9,  6),  -- Bordeaux -> Nantes
('2026-11-10 08:00:00', '2026-11-10 10:00:00', 3, 2, 11,  1, 12),  -- trajet PASSÉ
('2026-11-15 09:00:00', '2026-11-15 14:00:00', 4, 1, 12,  2,  5);  -- trajet PASSÉ