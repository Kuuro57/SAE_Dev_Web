-- ### Script de création des tables de la BDD ### --

CREATE TABLE Lieu (
    idLieu INT(4) PRIMARY KEY,
    nomLieu VARCHAR(50) NOT NULL,
    adresse VARCHAR(50) NOT NULL,
    nbPlacesAssises INT(5) NOT NULL,
    nbPlacesDebout INT(5) NOT NULL
);

CREATE TABLE ImageLieu (
    idLieu INT(4),
    nomFichierImage VARCHAR(50) NOT NULL,
    PRIMARY KEY (idLieu, nomFichierImage),
    FOREIGN KEY (idLieu) REFERENCES Lieu(idLieu)
);

CREATE TABLE Spectacle (
    idSpectacle INT(4) PRIMARY KEY,
    nomSpectacle VARCHAR(50) NOT NULL,
    style VARCHAR(50) NOT NULL,
    artiste VARCHAR(50) NOT NULL,
    duree INT(4),
    descSpectacle VARCHAR(50),
    nomFichierVideo VARCHAR(50),
    nomFichierAudio VARCHAR(50)
);

CREATE TABLE ImageSpectacle (
    idSpectacle INT(4),
    nomFichierImage VARCHAR(50) NOT NULL,
    PRIMARY KEY (idSpectacle, nomFichierImage),
    FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);

CREATE TABLE Soiree (
    idSoiree INT(4) PRIMARY KEY,
    nomSoiree VARCHAR(50) NOT NULL,
    idLieu INT(4),
    estAnnule BOOLEAN NOT NULL DEFAULT FALSE,
    dateSoiree DATE NOT NULL,
    heureD TIME NOT NULL,
    heureF TIME NOT NULL,
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

CREATE TABLE ListePreference (
  idUtilisateur INT(4),
  idSpectacle INT(4),
  PRIMARY KEY (idUtilisateur, idSpectacle),
  FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
  FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);





-- ### Script d'ajout de données dans la BDD ### --

INSERT INTO Lieu (idLieu, nomLieu, adresse, nbPlacesAssises, nbPlacesDebout) 
VALUES 
    (1, 'Parc de la Pépinière', 'Boulevard du XXVI régiment d infenterie', 0, 1000),
    (2, 'Place Stanislas', 'Place Stanislas', 0, 1000),
    (3, 'Place Carnot', 'Place Carnot', 0, 500),
    (4, 'L autre Canal', '45 Boulevard d Austrasie', 350, 1300),
    (5, 'Zénith de Nancy', 'Rue du Zénith', 8000, 25000);


INSERT INTO ImageLieu (idLieu, nomFichierImage)
VALUES
    (1, 'parc_pepiniere.png'),
    (2, 'place_stan.png'),
    (3, 'place_carnot.png'),
    (4, 'autre_canal.png'),
    (5, 'zenith_ncy.png');


INSERT INTO Soiree (idSoiree, nomSoiree, idLieu, dateSoiree) 
VALUES 
    (1, 'Soiree Grande Bulle', 3, '2024-11-06'),
    (2, 'Soiree Mente', 4, '2024-11-06'),
    (3, 'Soiree Rock Paper Scicor', 1, '2024-11-12'),
    (4, 'Soiree Rock-Embolesque', 3, '2024-11-13'),
    (5, 'Soiree Big Snap', 5, '2024-11-19');


-- L'attribut duree est complété quand le spectacle est ajouté dans la table Programme (en récupérant l'heure de début et l'heure de fin)
-- grâce à un trigger
INSERT INTO Spectacle (idSpectacle, nomSpectacle, style, artiste, descSpectacle, nomFichierVideo, nomFichierAudio) 
VALUES 
    (1, 'Indochine', 'Rock', 'Indochine', 'Un super spectacle !', 'indochine2024.mp4', 'indochine2024.mp3'), 
    (2, 'Daft Punk', 'Electro', 'Daft Punk', 'Un super spectacle !', 'daft_punk2024.mp4', 'daft_punk2024.mp3'),
    (3, 'PNL', 'Rap', 'PNL', 'Un super spectacle !', 'pnl2024.mp4', 'pnl2024.mp3'),
    (4, 'The Beatles', 'Rock', 'The Beatles', 'Un super spectacle !', 'the_beatles2024.mp4', 'the_beatles2024.mp3'),
    (5, 'Naps', 'Rap', 'Naps', 'Une super spectacle !', 'naps2024.mp4', 'naps2024.mp3');


INSERT INTO ImageSpectacle (idSpectacle, nomFichierImage)
VALUES
    (1, 'indochine2024.png'),
    (2, 'daft_punk2024.png'),
    (3, 'pnl2024.png'),
    (4, 'the_beatles2024.png'),
    (5, 'naps2024.png');


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
    
INSERT INTO ListePreference (idUtilisateur, idSpectacle)
VALUES
    (1, 1),
    (1, 3),
    (2, 2),
    (2, 5),
    (4, 2);
