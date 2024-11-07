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
        
    }



    /**
     * Méthode qui renvoie un spectacle dans la BDD
     * @param int $id Id du spectacle
     * @return Spectacle Un objet de type spectacle
     */
    public function getSpectacle(int $id) : Spectacle {
        // TODO
    }



    /**
     * Méthode qui regarde si l'email est déjà présent dans la BDD
     * @param string $email L'email à tester
     * @return array Liste qui contient le mot de passe (hashé) et le role de l'utilisateur si il est déjà
     *               présente dans la BDD, une liste vide si l'utilisateur n'existe pas
     */
    public function findExistingEmail(string $email) : array {
        // TODO
    }


}