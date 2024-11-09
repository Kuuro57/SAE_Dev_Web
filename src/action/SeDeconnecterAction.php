<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;



/**
 * Classe qui représente l'action de déconnexion à un compte
 */
class SeDeconnecterAction extends Action {

    /**
     * Constructeur de la classe
     */
    public function __construct(){
        parent::__construct();
    }



    /**
     * Méthode qui execute l'action
     * @return string Message indiquant que la déconnexion s'est bien déroulée
     */
    public function execute() : string {

        // On enlève l'email et le role stocké en session
        $_SESSION['user'] = null;

        // On retourne un message disant que la déconnexion s'est bien effectuée
        return '<h3> Vous êtes déconnecté </h3>';

    }
}