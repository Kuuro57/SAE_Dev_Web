-- ### Script de création des tables de la BDD ### --

CREATE TABLE Lieu (
    idLieu INT(4) PRIMARY KEY,
    nomLieu VARCHAR(50) NOT NULL
);

CREATE TABLE Spectacle (
    idSpectacle INT(4) PRIMARY KEY,
    nomSpectacle VARCHAR(50) NOT NULL,
    style VARCHAR(50) NOT NULL
);

CREATE TABLE Soiree (
    idSoiree INT(4) PRIMARY KEY,
    nomSoiree VARCHAR(50) NOT NULL,
    idLieu INT(4),
    estAnnule BOOLEAN NOT NULL DEFAULT FALSE,
    dateSoiree DATE NOT NULL,
    FOREIGN KEY (idLieu) REFERENCES Lieu(idLieu)
);

CREATE TABLE Programme (
    idSoiree INT(4),
    idSpectacle INT(4),
    heureD TIME NOT NULL,
    heureF TIME NOT NULL,
    PRIMARY KEY (idSoiree, idSpectacle),
    FOREIGN KEY (idSoiree) REFERENCES Soiree(idSoiree),
    FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);

CREATE TABLE Utilisateur (
    idUtilisateur INT(4) PRIMARY KEY,
    email VARCHAR(50) UNIQUE NOT NULL,
    mdp VARCHAR(256) NOT NULL,
    role INT(3) NOT NULL
);


-- ### Script d'ajout de données dans la BDD ### --

INSERT INTO Lieu (idLieu, nomLieu) 
VALUES 
    (1, 'Parc de la Pépinière'),
    (2, 'Place Stanislas'),
    (3, 'Place Carnot'),
    (4, 'L autre Canal'),
    (5, 'Zénith de Nancy');


INSERT INTO Soiree (idSoiree, nomSoiree, idLieu, dateSoiree) 
VALUES 
    (1, 'Soiree Grande Bulle', 3, '2024-11-06'),
    (2, 'Soiree Mente', 4, '2024-11-06'),
    (3, 'Soiree Rock Paper Scicor', 1, '2024-11-12'),
    (4, 'Soiree Rock-Embolesque', 3, '2024-11-13'),
    (5, 'Soiree Big Snap', 5, '2024-11-19');


INSERT INTO Spectacle (idSpectacle, nomSpectacle, style) 
VALUES 
    (1, 'Indochine', 'Rock'), 
    (2, 'Daft Punk', 'Electro'),
    (3, 'PNL', 'Rap'),
    (4, 'The Beatles', 'Rock'),
    (5, 'Naps', 'Rap');


INSERT INTO Programme (idSoiree, idSpectacle, heureD, heureF) 
VALUES 
    (1, 2, '19:00', '22:00'), 
    (1, 3, '23:00', '01:00'),
    (2, 1, '19:00', '21:00'),
    (3, 4, '20:00', '23:00'),
    (3, 1, '17:00', '19:00'),
    (4, 5, '20:00', '23:00'),
    (5, 5, '20:00', '23:00');


INSERT INTO Utilisateur (idUtilisateur, email, mdp, role) 
VALUES 
    (1,    'user1@mail.com',    '$2y$12$e9DCiDKOGpVs9s.9u2ENEOiq7wGvx7sngyhPvKXo2mUbI3ulGWOdC',    1),
    (2,    'user2@mail.com',    '$2y$12$4EuAiwZCaMouBpquSVoiaOnQTQTconCP9rEev6DMiugDmqivxJ3AG',    1),
    (3,    'user3@mail.com',    '$2y$12$5dDqgRbmCN35XzhniJPJ1ejM5GIpBMzRizP730IDEHsSNAu24850S',    1),
    (4,    'user4@mail.com',    '$2y$12$ltC0A0zZkD87pZ8K0e6TYOJPJeN/GcTSkUbpqq0kBvx6XdpFqzzqq',    1),
    (5,    'admin@mail.com',    '$2y$12$JtV1W6MOy/kGILbNwGR2lOqBn8PAO3Z6MupGhXpmkeCXUPQ/wzD8a',    100); -- mdp = admin

