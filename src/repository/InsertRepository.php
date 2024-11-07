<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\sae_dev_web\festival\Spectacle;


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



    /**
     * Méthode qui insert un spectacle à la BDD
     * @param Spectacle $spectacle Le spectacle à ajouter
     */
    public function ajouterSpectacle(Spectacle $spectacle): void {
        // TODO
    }



    /**
     * Méthode qui insert un spectacle à la BDD
     * @param Soiree $soiree La soiree à ajouter
     */
    public function ajouterSoiree(Soiree $soiree): void {
        // TODO
    }



    /**
     * Méthode qui ajoute un spectacle à une soirée
     * @param int $idSpectacle L'id du spectacle à ajouter
     * @param int $idSoiree L'id de la soirée à ajouter
     */
    public function ajouterSpectacleToSoiree(int $idSpectacle, int $idSoiree): void {
        // TODO
    }



    /**
     * Méthode qui ajoute une nouveau lieu
     * @param string $nomLieu Nom du lieu à ajouter
     */
    public function ajouterLieu(string $nomLieu): void {
        // TODO
    }



    /**
     * Méthode qui ajoute un utilisateur à la BDD
     * @param string $email L'email de l'utilisateur
     * @param string $mdp Le mot de passe de l'utilisateur
     * @param int $role Le role de l'utilisateur
     */
    public function ajouterUtilisateur(string $email, string $mdp, int $role) : void {
        // TODO
    }




}