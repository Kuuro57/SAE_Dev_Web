CREATE TABLE Soiree (
    idSoiree INT(4) PRIMARY KEY,
    idLieu INT(4),
    estAnnule BOOLEAN DEFAULT FALSE,
    dateSoiree DATE,
    FOREIGN KEY (idLieu) REFERENCES Lieu(idLieu)
);

CREATE TABLE Lieu (
    idLieu INT(4) PRIMARY KEY,
    nomLieu VARCHAR(50) NOT NULL
);


CREATE TABLE Spectacle (
    idSpectacle INT(4) PRIMARY KEY,
    style VARCHAR(50) NOT NULL
);

CREATE TABLE Programme (
    idSoiree INT(4),
    idSpectacle INT(4),
    heureD DATE,
    heureF DATE,
    PRIMARY KEY (idSoiree, idSpectacle),
    FOREIGN KEY (idSoiree) REFERENCES Soiree(idSoiree),
    FOREIGN KEY (idSpectacle) REFERENCES Spectacle(idSpectacle)
);

CREATE TABLE Utilisateur (
    idUtilisateur INT(4) PRIMARY KEY,
    email VARCHAR(50),
    mdp VARCHAR(256),
    role INT(3)
);
