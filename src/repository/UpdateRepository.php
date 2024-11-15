<?php

namespace iutnc\sae_dev_web\repository;

use iutnc\sae_dev_web\festival\Spectacle;

/**
 * Classe qui update des données dans la BDD
 */
class UpdateRepository extends Repository {

// Attribut
    /**
     * @var UpdateRepository|null
     */
    private static ?UpdateRepository $instance = null; // Instance unique de la classe SelectRepository


    /**
     * Méthode getInstance qui retourne une instance de SelectRepository
     * @return UpdateRepository Une instance de la classe
     */
    public static function getInstance(): UpdateRepository {

        //Si l'instance n'existe pas, on la crée
        if (self::$instance === null) {
            self::$instance = new UpdateRepository(self::$config);
        }
        return self::$instance;

    }


    /**
     * Méthode qui met à jour un spectacle dans la BDD
     * @param Spectacle $spectacle Le spectacle à mettre à jour
     * @return Spectacle Le spectacle mis à jour
     */

    public function updateSpectacle(Spectacle $spectacle): Spectacle {

        // Requête SQL qui modifie un spectacle donné dans la BDD
        $req = 'UPDATE Spectacle SET nomSpectacle = ?, idStyle = ?, idArtiste = ?, heureD = ?, duree = ?, descSpectacle = ? WHERE idSpectacle = ?';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        // Execution de la requête
        $stmt->execute([$spectacle->getNom(), $spectacle->getStyle()->getNom(), $spectacle->getArtiste()->getNom(), $spectacle->getHeureDebut(), $spectacle->getDuree(), $spectacle->getDescription(), $spectacle->getId()]);
        return $spectacle;
    }

    /**
     * Méthode annulerSpectacle qui annule un spectacle dans la BDD
     * @param Spectacle $spectacle
     * @return Spectacle
     */

    public function annulerSpectacle(Spectacle $spectacle): Spectacle {

        // Requête SQL qui modfie l'état d'annulation d'un spectacle donné dans la BDD
        $req = 'UPDATE Spectacle SET estAnnule = 1 WHERE idSpectacle = ?';

        // Préparation de la requête
        $stmt = $this->pdo->prepare($req);

        // Execution de la requête
        $stmt->execute([$spectacle->getId()]);
        return $spectacle;
    }

}