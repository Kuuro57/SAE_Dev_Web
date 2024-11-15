<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;

use iutnc\sae_dev_web\festival\Artiste;
use iutnc\sae_dev_web\festival\Audio;
use iutnc\sae_dev_web\festival\Image;
use iutnc\sae_dev_web\festival\Lieu;
use iutnc\sae_dev_web\festival\Soiree;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\festival\Style;
use iutnc\sae_dev_web\festival\Thematique;
use iutnc\sae_dev_web\festival\Video;


/**
 * Classe qui insert des données dans la BDD
 */
class InsertRepository extends Repository {

    // Attribut
    /**
     * @var InsertRepository|null
     */
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

        //Si l'instance n'existe pas, on la crée
        if(self::$instance === null) {
            self::$instance = new self(self::$config);
        }
        return self::$instance;
    }



    /**
     * Méthode qui insert un spectacle à la BDD
     * @param Spectacle $spectacle Le spectacle à ajouter
     * @return Spectacle Nouvel objet spectacle avec son nouvel id
     */
    public function ajouterSpectacle(Spectacle $spectacle): Spectacle {

        // Requête SQL qui insère un spectacle donné dans la BDD
        $req = 'INSERT INTO Spectacle (nomSpectacle, idStyle, idArtiste, heureD, duree, descSpectacle) VALUES (?, ?, ?, ?, ?, ?)';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        $nom = $spectacle->getNom();
        $style = $spectacle->getStyle()->getId();
        $artiste = $spectacle->getArtiste()->getId();
        $heureD = $spectacle->getHeureDebut();
        $duree = $spectacle->getDuree();
        $desc = $spectacle->getDescription();

        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $style);
        $stmt->bindParam(3, $artiste);
        $stmt->bindParam(4, $heureD);
        $stmt->bindParam(5, $duree);
        $stmt->bindParam(6, $desc);
        // Execution de la requête
        $stmt->execute();

        // On ajoute le nouvel id à l'objet Spectacle et on le renvoie
        $spectacle->setId((int) $this->pdo->lastInsertId());
        return $spectacle;

    }



    /**
     * Méthode qui insert un spectacle à la BDD
     * @param Soiree $soiree La soiree à ajouter
     */
    public function ajouterSoiree(Soiree $soiree): void {

        // Requête SQL qui insère une soirée donnée dans la BDD
        $req = 'INSERT INTO Soiree (nomSoiree, idLieu, idThematique, tarif, dateSoiree, estAnnule) VALUES (?, ?, ?, ?, ?, ?)';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        $nom = $soiree->getNom();
        $idLieu = $soiree->getLieu()->getId();
        $idTheme = $soiree->getThematique()->getId();
        $tarif = $soiree->getTarif();
        $date = $soiree->getDate();
        $annulee = $soiree->getEstAnnule();

        // Si l'annulation de la soirée n'est pas définie, on la met à false
        if (!isset($annulee)) {
            $annulee = false;
        // Sinon on la met à true
        } else {
            $annulee = true;
        }

        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $idLieu);
        $stmt->bindParam(3, $idTheme);
        $stmt->bindParam(4, $tarif);
        $stmt->bindParam(5, $date);
        $stmt->bindParam(6, $annulee);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un spectacle à une soirée
     * @param Soiree $soiree L'id de la soirée à ajouter
     * @param Spectacle $spectacle L'id du spectacle à ajouter
     *
     */
    public function ajouterSpectacleToSoiree(Soiree $soiree, Spectacle $spectacle): void {

        // Requête SQL qui lie un Spectacle donnée à une Soirée donnée dans la BDD
        $req = 'INSERT INTO Programme (idSoiree, idSpectacle) VALUES(?, ?)';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        $idSoiree = $soiree->getId();
        $idSpectacle = $spectacle->getId();

        $stmt->bindParam(1, $idSoiree);
        $stmt->bindParam(2, $idSpectacle);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un nouveau lieu
     * @param Lieu $lieu Objet Lieu à ajouter
     */
    public function ajouterLieu(Lieu $lieu): void {

        // Requête SQL qui insère un lieu donné dans la BDD
        $req = 'INSERT INTO Lieu (nomLieu, adresse, nbPlacesAssises, nbPlacesDebout) VALUES (?, ?, ?, ?)';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        $nom = $lieu->getNom();
        $adresse = $lieu->getAdresse();
        $nbPlacesAssises = $lieu->getNbPlacesAssises();
        $nbPlacesDebout = $lieu->getNbPlacesDebout();

        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $adresse);
        $stmt->bindParam(3, $nbPlacesAssises);
        $stmt->bindParam(4, $nbPlacesDebout);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un nouveau style
     * @param Style $style Objet Style à ajouter
     */
    public function ajouterStyle(Style $style): void {

        // Requête SQL qui insère un style donné dans la BDD
        $req = 'INSERT INTO Style(nomStyle) VALUES (?)';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        $nom = $style->getNom();

        $stmt->bindParam(1, $nom);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute une image à la BDD
     * @param Image $image Objet image qui contient tout ce dont a besoin la BDD pour ajouter l'image
     */
    public function ajouterImage(Image $image) : void {

        // Requête SQL qui ajoute une image donnée (nom de l'image) à la BDD
        $querySQL = "INSERT INTO ImageSpectacle (idSpectacle, nomFichierImage) VALUES (?, ?)";

        // On prépare et execute la requête
        $stmt = $this->pdo->prepare($querySQL);

        $nomFichier = $image->getNomFichierImage() . ".png";
        $idSpectacle = $image->getIdSpectacle();

        $stmt->bindParam(1, $idSpectacle);
        $stmt->bindParam(2, $nomFichier);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un audio à la BDD
     * @param Audio $audio Objet audio qui contient tout ce dont a besoin la BDD pour ajouter l'audio
     */
    public function ajouterAudio(Audio $audio) : void {

        // Requête SQL qui ajoute un audio donné (nom de l'audio) à la BDD
        $querySQL = "INSERT INTO AudioSpectacle (idSpectacle, nomFichierAudio) VALUES (?, ?)";

        // On prépare et execute la requête
        $stmt = $this->pdo->prepare($querySQL);

        $nomFichier = $audio->getNomFichierAudio() . ".mp3";
        $idAudio = $audio->getIdSpectacle();

        $stmt->bindParam(1, $idAudio);
        $stmt->bindParam(2, $nomFichier);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute une vidéo à la BDD
     * @param Video $video Objet video qui contient tout ce dont a besoin la BDD pour ajouter la vidéo
     */
    public function ajouterVideo(Video $video) : void {

        // Requête SQL qui ajoute une video donnée (url de la vidéo) à la BDD
        $querySQL = "INSERT INTO VideoSpectacle (idSpectacle, nomFichierVideo) VALUES (?, ?)";

        // On prépare et execute la requête
        $stmt = $this->pdo->prepare($querySQL);

        $nomFichier = $video->getUrl() . ".mp4";
        $idVideo = $video->getIdSpectacle();

        $stmt->bindParam(1, $idVideo);
        $stmt->bindParam(2, $nomFichier);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un utilisateur à la BDD
     * @param string $email L'email de l'utilisateur
     * @param string $mdp Le mot de passe de l'utilisateur
     * @param int $role Le role de l'utilisateur
     */
    public function ajouterUtilisateur(string $email, string $mdp, int $role) : void {

        // Requête SQL qui ajoute un utilisateur (email, mot de passe et rôle) à la BDD
        $req = 'INSERT INTO Utilisateur(email, mdp, role) VALUES(?, ?, ?)';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $mdp);
        $stmt->bindParam(3, $role);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Ajout d'une thématique à la BDD
     * @param Thematique $theme Objet Thematique créé avec le nom de la thématique
     * @return void
     */
    public function ajouterThematique(Thematique $theme) : void {

        // Requête SQL qui ajoute une thématique donnée à la BDD
        $req = 'INSERT INTO Thematiquesoiree(nomThematique) VALUES (?)';

        // Préparation de la requête
        $nom = $theme->getNom();

        $stmt = $this->pdo->prepare($req);

        $stmt->bindParam(1, $nom);

        // Execution de la requête
        $stmt->execute();

    }



    /**
     * Méthode qui ajoute un artiste dans la BDD
     * @param Artiste $artiste Objet de type Artiste
     */
    public function ajouterArtiste(Artiste $artiste) : void {

        // Requête SQL qui ajoute un artiste donné à la BDD
        $req = 'INSERT INTO Artiste (nomArtiste) VALUES (?)';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        $nom = $artiste->getNom();

        $stmt->bindParam(1, $nom);

        // Execution de la requête
        $stmt->execute();
    }



    /**
     * Méthode qui ajoute un spectacle à la liste des préférences de l'utilisateur
     * @param int $idUser Id de l'utilisateur
     * @param int $idSpec Id du spectacle
     */
    public function ajouterPref(int $idUser, int $idSpec) : void {

        $req = 'INSERT INTO Listepreference(idUtilisateur, idSpectacle) VALUES(?, ?)';

        $stmt = $this->pdo->prepare($req);

        $stmt->bindParam(1, $idUser);
        $stmt->bindParam(2, $idSpec);

        $stmt->execute();

    }

}