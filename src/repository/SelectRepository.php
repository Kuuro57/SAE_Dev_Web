<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;



use Couchbase\IndexFailureException;

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
    //null TODO switch sur le filtre qui renvoie une requête SQL différente en fonction du filtre
    public function getSpectacles(string $filtre) : array {
        return [];
        //TODO
    }



    /**
     * Méthode qui renvoie un spectacle dans la BDD
     * @param int $id Id du spectacle
     * @return Spectacle Un objet de type spectacle
     */
    public function getSpectacle(int $id) : Spectacle {
        // Requête SQL qui récupère l'id du spectacle
        $querySQL = "SELECT idSpectale FROM spectacle WHERE idSpectacle = ?";
        // Préparation de la requête
        $statement = $this->pdo->prepare($querySQL);
        $statement->bindParam(1, $id);
        // Execution de la requête
        $statement->execute();

        // On récupère les données sorties par la requête
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        $spectacle = $this->findSpectacle((int) $data['idSpectacle']);

        return $spectacle;
    }

    public function findPlaylist(int $id) : Playlist {
        $querySQL1 = 'SELECT id, nom FROM playlist WHERE id = :id';

        $statement1 = $this->pdo->prepare($querySQL1);
        $statement1->execute(['id' => $id]);

        $row = $statement1->fetch();
        $pl = new Playlist($row['nom'], []);
        $pl->setId((int)$row['id']); // associe l'id à la playlist récupérée



        $querySQL2 = 'SELECT * FROM playlist2track INNER JOIN track ON playlist2track.id_track = track.id
                      WHERE id_pl = :id';

        $statement2 = $this->pdo->prepare($querySQL2);
        $statement2->execute(['id' => $id]);


        foreach ($statement2->fetchAll() as $row) {
            if ($row['type'] === 'P') {
                $podcastTrack = new PodcastTrack($row['titre'], $row['filename']);
                $podcastTrack->setId((int)$row['id']);
                $podcastTrack->setGenre($row['genre']);
                $podcastTrack->setDuree((int)$row['duree']);
                $podcastTrack->setAuteur($row['auteur_podcast']);
                $podcastTrack->setDate($row['date_posdcast']);

                $pl->addTrack($podcastTrack);
            }
            else if ($row['type'] === 'A') {
                $albumTrack = new AlbumTrack($row['titre'], $row['filename'], $row['titre_album'], (int)$row['id']);
                $albumTrack->setArtiste($row['artiste_album']);
                $albumTrack->setAnnee((int)$row['annee_album']);
                $albumTrack->setGenre($row['genre']);
                $albumTrack->setDuree((int)$row['duree']);

                $pl->addTrack($albumTrack);
            }

        }

        return $pl;
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
}