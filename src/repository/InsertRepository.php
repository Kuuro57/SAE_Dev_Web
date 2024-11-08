<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\sae_dev_web\festival\Lieu;
use iutnc\sae_dev_web\festival\Soiree;
use iutnc\sae_dev_web\festival\Spectacle;


/**
 * Classe qui insert des données dans la BDD
 */
class InsertRepository extends Repository {

    // Attribut
    private static ?InsertRepository $instance = null; // Instance unique de la classe InsertRepository

    /**
     * Constructeur de reprise du PDO parent
     */
    public function __construct(array $config) {

        parent::__construct($config);

    }


    /**
     * Méthode de récupèration de l'instance définie
     * @return InsertRepository l'instance
     */
    public static function getInstance(): InsertRepository {

        if(self::$instance === null) {

            self::$instance = new self(self::$config);

        }

        return self::$instance;
    }



    /**
     * Méthode qui insert un spectacle à la BDD
     * @param Spectacle $spectacle Le spectacle à ajouter
     */
    public function ajouterSpectacle(Spectacle $spectacle): void {

        $req = 'INSERT INTO spectacle(idSpectacle, nomSpectacle, idStyle, idArtiste, duree, descSpectacle) VALUES(?, ?, ?, ?, ?, ?)';

        $stmt = $this->pdo->prepare($req);

        $idSpec = $spectacle->getId();
        $nom = $spectacle->getNom();
        $style = $spectacle->getStyle()->getId();
        $artiste = $spectacle->getArtiste()->getId();
        $duree = $spectacle->getDuree();
        $desc = $spectacle->getDescription();

        $stmt->bindParam(1, $idSpec);
        $stmt->bindParam(2, $nom);
        $stmt->bindParam(3, $style);
        $stmt->bindParam(4, $artiste);
        $stmt->bindParam(5, $duree);
        $stmt->bindParam(6, $desc);

        $stmt->execute();

    }



    /**
     * Méthode qui insert un spectacle à la BDD
     * @param Soiree $soiree La soiree à ajouter
     */
    public function ajouterSoiree(Soiree $soiree): void {

        $req = 'INSERT INTO soiree(idSoiree, nomSoiree, idLieu, idThematique, estAnnule, dateSoiree)';

        $stmt = $this->pdo->prepare($req);

        $idSoiree = $soiree->getId();
        $nom = $soiree->getNom();
        $idlieu = $soiree->getLieu()->getId();
        $idTheme = $soiree->getThematique()->getId();
        $annulee = $soiree->getEstAnnule();
        $date = $soiree->getDate();

        $stmt->bindParam(1, $idSoiree);
        $stmt->bindParam(2, $nom);
        $stmt->bindParam(3, $idlieu);
        $stmt->bindParam(4, $idTheme);
        $stmt->bindParam(5, $annulee);
        $stmt->bindParam(6, $date);

        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un spectacle à une soirée
     * @param int $idSpectacle L'id du spectacle à ajouter
     * @param int $idSoiree L'id de la soirée à ajouter
     */
    public function ajouterSpectacleToSoiree(int $idSpectacle, int $idSoiree): void {

        $req = 'INSERT INTO programme(idSoiree, idSpectacle) VALUES(?, ?)';

        $stmt = $this->pdo->prepare($req);

        $stmt->bindParam(1, $idSoiree);
        $stmt->bindParam(2, $idSpectacle);

        $stmt->execute();

    }



    /**
     * Méthode qui ajoute une nouveau lieu
     * @param Lieu $lieu Objet Lieu à ajouter
     */
    public function ajouterLieu(Lieu $lieu): void {

        $req = 'INSERT INTO lieu(idLieu, nomLieu, adresse, nbPlacesAssises, nbPlacesDebout)';

        $stmt = $this->pdo->prepare($req);

        $idLieu = $lieu->getId();
        $nom = $lieu->getNom();
        $adresse = $lieu->getAdresse();
        $nbPlacesAssises = $lieu->getNbPlacesAssises();
        $nbPlacesDebout = $lieu->getNbPlacesDebout();

        $stmt->bindParam(1, $idLieu);
        $stmt->bindParam(2, $nom);
        $stmt->bindParam(3, $adresse);
        $stmt->bindParam(4, $nbPlacesAssises);
        $stmt->bindParam(5, $nbPlacesDebout);

        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un utilisateur à la BDD
     * @param string $email L'email de l'utilisateur
     * @param string $mdp Le mot de passe de l'utilisateur
     * @param int $role Le role de l'utilisateur
     */
    public function ajouterUtilisateur(string $email, string $mdp, int $role) : void {

        $req = 'INSERT INTO utilisateur(email, mdp, role) VALUES(?, ?, ?)';

        $stmt = $this->pdo->prepare($req);

        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $mdp);
        $stmt->bindParam(3, $role);

        $stmt->execute();

    }

}