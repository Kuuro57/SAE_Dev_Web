<?php

namespace iutnc\sae_dev_web\repository;

use iutnc\sae_dev_web\festival\Spectacle;

class UpdateRepository extends Repository
{
// Attribut
    private static ?UpdateRepository $instance = null; // Instance unique de la classe SelectRepository


    /**
     * Méthode getInstance qui retourne une instance de SelectRepository
     * @return UpdateRepository Une instance de la classe
     */
    public static function getInstance(): UpdateRepository
    {

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

    public function updateSpectacle(Spectacle $spectacle): Spectacle
    {
        $req = 'UPDATE Spectacle SET nomSpectacle = ?, idStyle = ?, idArtiste = ?, heureD = ?, duree = ?, descSpectacle = ? WHERE idSpectacle = ?';
        $stmt = $this->pdo->prepare($req);
        $stmt->execute([$spectacle->getNom(), $spectacle->getStyle()->getNom(), $spectacle->getArtiste()->getNom(), $spectacle->getHeureDebut(), $spectacle->getDuree(), $spectacle->getDescription(), $spectacle->getId()]);
        return $spectacle;
    }

    /**
     * Méthode annulerSpectacle qui annule un spectacle dans la BDD
     * @param Spectacle
     * @return Spectacle
     */

    public function annulerSpectacle(Spectacle $spectacle): Spectacle {
        $req = 'UPDATE Spectacle SET estAnnule = 1 WHERE idSpectacle = ?';
        $stmt = $this->pdo->prepare($req);
        $stmt->execute([$spectacle->getId()]);
        return $spectacle;
    }


}