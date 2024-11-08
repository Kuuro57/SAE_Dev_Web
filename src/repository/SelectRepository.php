<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;



use iutnc\sae_dev_web\festival\Artiste;
use iutnc\sae_dev_web\festival\Audio;
use iutnc\sae_dev_web\festival\Lieu;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\festival\Style;
use iutnc\sae_dev_web\festival\Image;
use iutnc\sae_dev_web\festival\Thematique;
use iutnc\sae_dev_web\festival\Video;
use PDO;



/**
 * Classe qui récupère des informations auprès de la BDD
 */
class SelectRepository extends Repository
{

    // Attribut
    private static ?SelectRepository $instance = null; // Instance unique de la classe SelectRepository



    /**
     * Méthode getInstance qui retourne une instance de SelectRepository
     * @return SelectRepository Une instance de la classe
     */
    public static function getInstance(): SelectRepository
    {

        if (self::$instance === null) {
            self::$instance = new SelectRepository(self::$config);
        }
        return self::$instance;

    }



    /**
     * Méthode qui renvoie une liste de tous les spectacles de la BDD
     * @param string $filtre Filtre qui permet de savoir dans quel ordre afficher les spectacles
     * @return Spectacle[] La liste de tous les spectacles dans le bon ordre d'affichage
     */
    public function getSpectacles(string $filtre): array
    {  //default affiche ordre date | date ordre date |lieu ordre lieu| style ordre style
        // Requête SQL en fonction du filtre
        switch ($filtre) {
            case "lieu":
                $querySQL = "SELECT Spectacle.idSpectacle, Spectacle.idStyle, Spectacle.idArtiste, descSpectacle, Lieu.nomLieu
                             FROM Spectacle INNER JOIN Programme ON Spectacle.idSpectacle = Programme.idSpectacle 
                             INNER JOIN Soiree ON Programme.idSoiree = Soiree.idSoiree 
                             INNER JOIN Lieu ON Soiree.idLieu = Lieu.idLieu ORDER BY nomLieu";
                break;
            case "style":
                $querySQL = "SELECT Spectacle.idSpectacle, Spectacle.idStyle, Spectacle.idArtiste, descSpectacle, Style.nomStyle 
                             FROM Spectacle INNER JOIN Style ON Spectacle.idStyle = Style.idStyle ORDER BY nomStyle";
                break;
            default: // tri par date
                $querySQL = "SELECT Spectacle.idSpectacle, Spectacle.idStyle, Spectacle.idArtiste, descSpectacle, Soiree.dateSoiree
                             FROM Spectacle INNER JOIN Programme ON Spectacle.idSpectacle = Programme.idSpectacle 
                             INNER JOIN Soiree ON Programme.idSoiree = Soiree.idSoiree ORDER BY dateSoiree";
                break;
        }

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);

        // Execution de la requête
        $statement->execute();

        $res = [];
        // on boucle en prenant l'id du spectacle et on crée un objet spectacle avec cet id dans l'ordre du filtre
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $spectacle = $this->getSpectacle($data['idSpectacle']);
            $res[] = $spectacle;
        }
        return $res;
    }



    /**
     * Méthode qui renvoie un spectacle dans la BDD
     * @param int $id Id du spectacle
     * @return Spectacle Un objet de type spectacle
     */
    public function getSpectacle(int $id): Spectacle
    {
        // Requête SQL qui récupère l'id du spectacle
        $querySQL = "SELECT idSpectacle, nomSpectacle, idStyle, idArtiste, duree, heureD, descSpectacle 
                     FROM Spectacle WHERE idSpectacle = ?";
        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);
        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        $spectacle = new Spectacle(
            $data['idSpectacle'],
            $data['nomSpectacle'],
            $this->getStyle($data['idStyle']),
            $this->getArtiste($data['idArtiste']),
            $data['duree'],
            $data['heureD'],
            $data['descSpectacle'],
            $this->getVideos($data['idSpectacle']),
            $this->getAudios($data['idSpectacle']),
            $this->getImages($data['idSpectacle'])
        );

        return $spectacle;
    }



    /**
     * Méthode qui regarde si l'email est déjà présent dans la BDD
     * @param string $email L'email à tester
     * @return array Liste qui contient le mot de passe (hashé) et le role de l'utilisateur s'il est déjà
     *               présente dans la BDD, une liste vide si l'utilisateur n'existe pas
     */
    public function findExistingEmail(string $email): array
    {
        // Requête SQL
        $querySQL = "SELECT mdp, role FROM Utilisateur WHERE email = ? ";
        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $email);
        // Execution de la requête
        $statement->execute();
        // On récupère les données sorties par la requête
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        // Si les données sont vides
        if (empty($data)) {
            // Le résultat est une liste vide
            $res = [];
        } //Sinon
        else {
            // Le résultat est une liste contenant le mot de passe hashé correspondant à l'email et son role
            $res = [
                'mdp' => $data['mdp'],
                'role' => $data['role']
            ];
        }
        // On retourne le résultat
        return $res;
    }


    /**
     * Méthode qui renvoie la liste de tous les artistes
     * @return array Liste d'artiste
     */
    public function getArtistes() : array {
        // Requête SQL qui récupère les attributs d'artistes
        $querySQL = "Select Artiste.idArtiste, Artiste.nomArtiste FROM Artiste";

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);

        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $res = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $artiste = $this->getArtiste($data['idArtiste']);
            $res[] = $artiste;
        }
        return $res;
    }

    /**
     * Méthode qui renvoie un artiste dans la BDD
     * @param int $id Id du spectacle
     * @return Artiste Un objet de type artiste
     */
    public function getArtiste(int $id): Artiste
    {
        // Requête SQL qui récupère l'id du spectacle
        $querySQL = "SELECT Artiste.idArtiste, Artiste.nomArtiste FROM Artiste WHERE idArtiste = ?";
        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);
        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        $artiste = new Artiste(
            $data['idArtiste'],
            $data['nomArtiste']
        );

        return $artiste;
    }

    /**
     * Méthode qui renvoie la liste de tous les styles
     * @return array Liste de styles
     */
    public function getStyles() : array {
        // Requête SQL qui récupère les attributs d'artistes
        $querySQL = "Select Style.idStyle, Style.nomStyle FROM Style";

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);

        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $res = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $style = $this->getStyle($data['idStyle']);
            $res[] = $style;
        }
        return $res;
    }

    /**
     * Méthode qui renvoie un style dans la BDD
     * @param int $id Id du style
     * @return Style Un objet de type style
     */
    public function getStyle(int $id): Style
    {
        // Requête SQL qui récupère l'id du spectacle
        $querySQL = "SELECT idStyle, nomStyle FROM Style WHERE idStyle = ?";
        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);
        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return new Style(
            $data['idStyle'],
            $data['nomStyle']
        );
    }

    /**
     * Méthode qui renvoie la liste de tous les lieux
     * @return array Liste de lieux
     */
    public function getLieux() : array {
        // Requête SQL qui récupère les attributs d'artistes
        $querySQL = "Select idLieu, nomLieu, adresse, nbPlacesAssises, nbPlacesDebout FROM Lieu";

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);

        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $res = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            //modifier par rapport au constructeur de style
            $lieux = new Lieu($data['idLieux'], $data['nomLieux'], $data['adresse'], $data['nbPlacesAssises'], $data['nbPlacesDebout']);
            $res[] = $lieux;
        }
        return $res;
    }



    /**
     * Méthode qui renvoie la liste des toutes les images d'un spectacle
     * @return array Liste d'images
     */
    public function getImages(int $id) : array {
        // Requête SQL qui récupère les attributs d'images du spectacle
        $querySQL = "Select idImage, idSpectacle, nomFichierImage FROM ImageSpectacle WHERE idSpectacle = ?";

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);

        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $res = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            //modifier par rapport au constructeur de style
            $image = new Image($data['idImage'], $data['idSpectacle'], $data['nomFichierImage']);
            $res[] = $image;
        }
        return $res;
    }



    /**
     * Méthode qui renvoie la liste des tous les audios d'un spectacle
     * @return array Liste d'audios
     */
    public function getAudios(int $id) : array {
        // Requête SQL qui récupère les attributs audios du spectacle
        $querySQL = "Select idAudio, idSpectacle, nomFichierAudio FROM AudioSpectacle WHERE idSpectacle = ?";

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);

        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $res = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            //modifier par rapport au constructeur de style
            $audio = new Audio($data['idAudio'], $data['idSpectacle'], $data['nomFichierAudio']);
            $res[] = $audio;
        }
        return $res;
    }



    /**
     * Méthode qui renvoie la liste des toutes les videos d'un spectacle
     * @return array Liste de videos
     */
    public function getVideos(int $id) : array {
        // Requête SQL qui récupère les attributs vidéos du spectacle
        $querySQL = "Select idVideo, idSpectacle, nomFichierVideo FROM VideoSpectacle WHERE idSpectacle = ?";

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);

        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $res = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            //modifier par rapport au constructeur de style
            $video = new Video($data['idVideo'], $data['idSpectacle'], $data['nomFichierVideo']);
            $res[] = $video;
        }
        return $res;
    }

    /**
     * Méthode qui renvoie la liste des toutes les thematiques
     * @return array Liste de thematique
     */
    public function getThematiques() : array {
        // Requête SQL qui récupère les attributs de thematique
        $querySQL = "Select idThematique, nomThematique FROM ThematiqueSoiree";

        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);

        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $res = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $thematique = new Thematique($data['idThematique'], $data['nomThematique']);
            $res[] = $thematique;
        }
        return $res;
    }

    /**
     * Méthode qui récupère l'heure de début d'un spectacle
     * @param int $id Id du spectacle
     * @return string Heure de début du spectacle (HH:MM:SS)
     */
    public function getHeureDebutSpectacle(int $id) : string {

        // Requête SQL qui récupère l'heure de début d'un spectacle
        $querySQL = "SELECT heureD FROM Spectacle WHERE idSpectacle = :id";

        // On prépare la requête et on l'exécute
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(":id", $id);
        $statement->execute();

        // On retourne l'heure de début
        return $statement->fetch()['heureD'];

    }
}
