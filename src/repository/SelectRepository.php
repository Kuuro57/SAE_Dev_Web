<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;

/**
 * Classe qui récupère des informations auprès de la BDD
 */
class SelectRepository extends Repository {

    // Attribut
    private static ?SelectRepository $instance = null; // Instance unique de la classe SelectRepository


    /**
     * Méthode getInstance qui retourne une instance de SelectRepository
     * @return SelectRepository Une instance de la classe
     */
    public static function getInstance(): SelectRepository {

        if(self::$instance === null) {
            self::$instance = new SelectRepository(self::$config);
        }
        return self::$instance;

    }


    /**
     * Méthode qui renvoie une liste de tout les spectacles de la BDD
     * @param string $filtre Filtre qui permet de savoir dans quel ordre afficher les spectacles
     * @return Spectacle[] La liste de tout les spectacles dans le bon ordre d'affichage
     */

    public function getSpectacles(string $filtre) : array {  //default affiche ordre date | date ordre date |lieu ordre lieu| style ordre style
        // Requête SQL en fonction du filtre
        switch ($filtre){
            case "date":
                $querySQL = "SELECT Spectacle.idSpectacle, Spectacle.idStyle, Spectacle.idArtiste, descSpectacle, Soiree.dateSoiree
                             FROM Spectacle INNER JOIN Programme ON Spectacle.idSpectacle = Programme.idSpectacle 
                             INNER JOIN Soiree ON Programme.idSoiree = Soiree.idSoiree ORDER BY dateSoiree";
                break;
            case "lieu":
                $querySQL = "SELECT Spectacle.idSpectacle, Spectacle.idStyle, Spectacle.idArtiste, descSpectacle, Soiree.nomLieu
                             FROM Spectacle INNER JOIN Programme ON Spectacle.idSpectacle = Programme.idSpectacle 
                             INNER JOIN Soiree ON Programme.idSoiree = Soiree.idSoiree 
                             INNER JOIN Lieu ON Soiree.idLieu = Lieu.idLieu ORDER BY nomLieu";
                break;
            case "style":
                $querySQL = "SELECT Spectacle.idSpectacle, Spectacle.idStyle, Spectacle.idArtiste, descSpectacle, Style.nomStyle 
                             FROM Spectacle INNER JOIN Style ON Spectacle.idStyle = Style.idStyle ORDER BY nomStyle";
                break;
            default:
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
            $spectacle = new Spectacle($this->getSpectacle($data['idSpectacle']));
            $res[] = $spectacle;
        }
        return $res;
    }


    /**
     * Méthode qui renvoie un spectacle dans la BDD
     * @param int $id Id du spectacle
     * @return Spectacle Un objet de type spectacle
     */
    public function getSpectacle(int $id) : Spectacle {
        // Requête SQL qui récupère l'id du spectacle
        $querySQL = "SELECT Spectacle.idSpectacle, Spectacle.idStyle, Spectacle.idArtiste, descSpectacle, 
                     FROM spectacle WHERE idSpectacle = ?";
        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);
        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        $spectacle = new Spectacle($data['idSpectacle'],
            $data['nomSpectacle'], $data['idStyle'],
            $data['idArtiste'], $data['descSpectacle']);

        return $spectacle;
    }

    /**
     * Méthode qui regarde si l'email est déjà présent dans la BDD
     * @param string $email L'email à tester
     * @return array Liste qui contient le mot de passe (hashé) et le role de l'utilisateur si il est déjà
     *               présente dans la BDD, une liste vide si l'utilisateur n'existe pas
     */
    public function findExistingEmail(string $email) : array {
        // Requête SQL
        $querySQL = "SELECT mdp, role FROM utilisateur WHERE email = ? ";
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
        }
        //Sinon
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

    // TODO public function getDebutSpectacle

    // TODO getArtistes()

    // TODO getStyles()

    // TODO getLieux() -> id + nom | ou tout
}