<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;



/**
 * Classe qui récupère des informations auprès de la BDD
 */
class SelectRepository extends Repository {

    // Attribut
    private static ?SelectRepository $instance = null; // Instance unique de la classe SelectRepository



    public static function getInstance(): SelectRepository
    {
        // TODO
    }

}