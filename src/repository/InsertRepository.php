<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;



use iutnc\deefy\repository\DeefyRepository;

/**
 * Classe qui insert des données dans la BDD
 */
class InsertRepository extends Repository {

    // Attribut
    private static ?InsertRepository $instance = null; // Instance unique de la classe InsertRepository



    public static function getInstance(): InsertRepository
    {
        // TODO
    }

}