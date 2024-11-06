CREATE TABLE Lieu (
    idLieu INT(4) PRIMARY KEY,
    nomLieu VARCHAR(50) NOT NULL
);

CREATE TABLE Soiree (
    idSoiree INT(4) PRIMARY KEY,
    idLieu INT(4),
    estAnnule BOOLEAN DEFAULT FALSE,
    dateSoiree DATE NOT NULL,
    FOREIGN KEY (idLieu) REFERENCES Lieu(idLieu) ON DELETE SET NULL
);

CREATE TABLE Spectacle (
    idSpectacle INT(4) PRIMARY KEY,
    style VARCHAR(50) NOT NULL
);

CREATE TABLE Programme (
    idSoiree INT(4),
    idSpectacle INT(4),
    heureD DATE NOT NULL,
    heureF DATE NOT NULL,
    PRIMARY KEY (idSoiree, idSpectacle),
    FOREIGN KEY (idSoiree) REFERENCES Soiree(idSoiree) ON DELETE CASCADE,
    FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle) ON DELETE CASCADE
);

CREATE TABLE Utilisateur (
    idUtilisateur INT(4) PRIMARY KEY,
    email VARCHAR(50) UNIQUE NOT NULL,
    mdp VARCHAR(256) NOT NULL,
    role INT(3) NOT NULL
);
