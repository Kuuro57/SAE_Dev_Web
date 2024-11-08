-- ### Script de création des tables de la BDD ### --

CREATE TABLE Lieu (
                      idLieu INT(4) PRIMARY KEY AUTO_INCREMENT,
                      nomLieu VARCHAR(50) NOT NULL,
                      adresse VARCHAR(50) NOT NULL,
                      nbPlacesAssises INT(5) NOT NULL,
                      nbPlacesDebout INT(5) NOT NULL
);

CREATE TABLE Artiste (
                         idArtiste INT(4) PRIMARY KEY AUTO_INCREMENT,
                         nomArtiste VARCHAR(50) NOT NULL
);

CREATE TABLE Style (
                       idStyle INT(4) PRIMARY KEY AUTO_INCREMENT,
                       nomStyle VARCHAR(50) NOT NULL
);

CREATE TABLE Spectacle (
                           idSpectacle INT(4) PRIMARY KEY AUTO_INCREMENT,
                           nomSpectacle VARCHAR(50) NOT NULL,
                           idStyle INT(4) NOT NULL,
                           idArtiste INT(4) NOT NULL,
                           duree INT(4),
                           heureD TIME NOT NULL,
                           descSpectacle VARCHAR(50),
                           FOREIGN KEY (idArtiste) REFERENCES Artiste(idArtiste),
                           FOREIGN KEY (idStyle) REFERENCES Style(idStyle)
);

CREATE TABLE ImageSpectacle (
                                idImage INT(4),
                                idSpectacle INT(4),
                                nomFichierImage VARCHAR(50) NOT NULL,
                                PRIMARY KEY (idImage, idSpectacle),
                                FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);

CREATE TABLE AudioSpectacle (
                                idAudio INT(4),
                                idSpectacle INT(4),
                                nomFichierAudio VARCHAR(50) NOT NULL,
                                PRIMARY KEY (idAudio, idSpectacle),
                                FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);

CREATE TABLE VideoSpectacle (
                                idVideo INT(4),
                                idSpectacle INT(4),
                                nomFichierVideo VARCHAR(50) NOT NULL,
                                PRIMARY KEY (idVideo, idSpectacle),
                                FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);

CREATE TABLE ThematiqueSoiree (
                                  idThematique INT(4),
                                  nomThematique VARCHAR(50) NOT NULL,
                                  PRIMARY KEY (idThematique)
);

CREATE TABLE ImageLieu (
                           idLieu INT(4),
                           nomFichierImage VARCHAR(50) NOT NULL,
                           PRIMARY KEY (idLieu, nomFichierImage),
                           FOREIGN KEY (idLieu) REFERENCES Lieu(idLieu)
);

CREATE TABLE Soiree (
                        idSoiree INT(4) PRIMARY KEY AUTO_INCREMENT,
                        nomSoiree VARCHAR(50) NOT NULL,
                        tarif FLOAT(5, 2) NOT NULL,
                        idLieu INT(4),
                        idThematique INT(4),
                        estAnnule BOOLEAN NOT NULL DEFAULT FALSE,
                        dateSoiree DATE NOT NULL,
                        FOREIGN KEY (idLieu) REFERENCES Lieu(idLieu),
                        FOREIGN KEY (idThematique) REFERENCES ThematiqueSoiree(idThematique)
);

CREATE TABLE Programme (
                           idSoiree INT(4),
                           idSpectacle INT(4),
                           PRIMARY KEY (idSoiree, idSpectacle),
                           FOREIGN KEY (idSoiree) REFERENCES Soiree(idSoiree),
                           FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);

CREATE TABLE Utilisateur (
                             idUtilisateur INT(4) PRIMARY KEY AUTO_INCREMENT,
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


INSERT INTO ThematiqueSoiree (idThematique, nomThematique)
VALUES
    (1, 'Concert Rock'),
    (2, 'Electro Night'),
    (3, 'Rap Battle'),
    (4, 'British Rock'),
    (5, 'Hip-Hop Extravaganza');


INSERT INTO Soiree (idSoiree, nomSoiree, idLieu, tarif, idThematique, dateSoiree)
VALUES
    (1, 'Soiree Grande Bulle', 3, 5.50, 2, '2024-11-06'),
    (2, 'Soiree Mente', 4, 10.5, 5, '2024-11-06'),
    (3, 'Soiree Rock Paper Scicor', 1, 2.0, 1, '2024-11-12'),
    (4, 'Soiree Rock-Embolesque', 3, 1.0, 1, '2024-11-13'),
    (5, 'Soiree Big Snap', 5, 6.2, 3, '2024-11-19');


INSERT INTO Artiste (idArtiste, nomArtiste)
VALUES
    (1, 'Indochine'),
    (2, 'Daft Punk'),
    (3, 'PNL'),
    (4, 'The Beatles'),
    (5, 'Naps');


INSERT INTO Style (idStyle, nomStyle)
VALUES
    (1, 'Rock'),
    (2, 'Electro'),
    (3, 'Rap');


INSERT INTO Spectacle (idSpectacle, nomSpectacle, idStyle, idArtiste, heureD, duree, descSpectacle)
VALUES
    (1, 'Indochine', 1, 1, '19:00', 2, 'Un super spectacle !'),
    (2, 'Daft Punk', 2, 2, '19:30', 3, 'Un super spectacle !'),
    (3, 'PNL', 3, 3, '23:00', 2, 'Un super spectacle !'),
    (4, 'The Beatles', 1, 4, '20:00', 2, 'Un super spectacle !'),
    (5, 'Naps', 3, 4, '20:00', 3, 'Une super spectacle !');


INSERT INTO ImageSpectacle (idSpectacle, nomFichierImage)
VALUES
    (1, 'indochine2024.png'),
    (2, 'daft_punk2024.png'),
    (3, 'pnl2024.png'),
    (4, 'the_beatles2024.png'),
    (5, 'naps2024.png');


INSERT INTO AudioSpectacle (idAudio, idSpectacle, nomFichierAudio)
VALUES
    (1, 1, 'indochine2024_audio.mp3'),
    (2, 2, 'daft_punk2024_audio.mp3'),
    (3, 3, 'pnl2024_audio.mp3'),
    (4, 4, 'the_beatles2024_audio.mp3'),
    (5, 5, 'naps2024_audio.mp3');


INSERT INTO VideoSpectacle (idVideo, idSpectacle, nomFichierVideo)
VALUES
    (1, 1, 'indochine2024_video.mp4'),
    (2, 2, 'daft_punk2024_video.mp4'),
    (3, 3, 'pnl2024_video.mp4'),
    (4, 4, 'the_beatles2024_video.mp4'),
    (5, 5, 'naps2024_video.mp4');


INSERT INTO Programme (idSoiree, idSpectacle)
VALUES
    (1, 2),
    (1, 3),
    (2, 1),
    (3, 4),
    (3, 1),
    (4, 5),
    (5, 5);


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
