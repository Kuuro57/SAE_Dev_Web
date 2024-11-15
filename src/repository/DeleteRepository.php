<?php

namespace iutnc\sae_dev_web\repository;

use iutnc\sae_dev_web\festival\Spectacle;

/**
 * Classe qui update des données dans la BDD
 */
class DeleteRepository extends Repository {

// Attribut
    /**
     * @var UpdateRepository|null
     */
    private static ?DeleteRepository $instance = null; // Instance unique de la classe SelectRepository


    /**
     * Méthode getInstance qui retourne une instance de SelectRepository
     * @return DeleteRepository Une instance de la classe
     */
    public static function getInstance(): DeleteRepository {

        //Si l'instance n'existe pas, on la crée
        if (self::$instance === null) {
            self::$instance = new DeleteRepository(self::$config);
        }
        return self::$instance;

    }



    /**
     * Méthode qui supprimer un spectacle de la liste des préférences d'un utilisateur
     * @param int $idUser Id de l'utilisateur
     * @param int $idSpec Id du spectacle à supprimer
     */
    public function supPref(int $idUser, int $idSpec){

        $req = 'DELETE FROM Listepreference WHERE idUtilisateur = ? AND idSpectacle = ?;';

        $stmt = $this->pdo->prepare($req);
        $stmt->bindParam(1, $idUser);
        $stmt->bindParam(2, $idSpec);

        $stmt->execute();

    }

}