<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\auth\Auth;



/**
 * Classe qui représente l'action de créer un nouveau compte
 */
class AddStaffAction extends Action {

    // Attribut
    private string $formulaire = '<form method="post" action="?action=add-staff">
                                        <input type="email" name="email" placeholder="Email" class="input-field" required autofocus>
                                        <input type="password" name="passwd1" placeholder="Mot de passe" class="input-field" required>
                                        <input type="password" name="passwd2" placeholder="Confirmez le mot de passe" class="input-field" required>
                                        <input type="submit" name="connex" value="Connexion" id="btn_connexion">
                                  </form>';



    /**
     * Constructeur de la classe
     */
    public function __construct(){
        parent::__construct();
    }



    /**
     * Méthode qui execute l'action
     * @return string
     */
    public function execute() : string {

        // Si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user'])) {
            // On renvoie un message comme quoi il n'a pas les permissions
            return '<p> Vous n\'avez pas les permissions requises ! Connectez-vous à un compte ADMIN </p>';

        }
        // Sinon
        else {
            // Si le compte à les permissions STANDARD ou STAFF
            if ((int)$_SESSION['user']['role'] === 1 || (int)$_SESSION['user']['role'] === 90) {
                // On renvoie un message comme quoi il n'a pas les permissions
                return '<p> Vous n\'avez pas les permissions requises ! Connectez-vous à un compte ADMIN </p>';
            }
        }



        // Si la méthode utilisée est de type GET
        if ($this->http_method == "GET") {
            // On renvoie le formulaire
            $res = "
                    <h1>Créer un compte STAFF</h1>
                    $this->formulaire
                    ";
        }
        // Sinon
        else {
            // On récupère l'email en le filtrant
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            // On récupère les mots de passe
            $p1= $_POST['passwd1'];
            $p2 = $_POST['passwd2'];
            $p1 = filter_var($p1, FILTER_SANITIZE_SPECIAL_CHARS);
            $p2 = filter_var($p2, FILTER_SANITIZE_SPECIAL_CHARS);

            // Si les deux mots de passe sont identiques
            if ($p1 === $p2) {
                // Si le mot de passe est trop faible
                if(!Auth::checkPasswordStrength($p1, 8)){
                    return $res = "
                        <h1>Créer un compte</h1>
                        <h3> Erreur : mot de passe trop faible ! </h3>
                        <br>
                        $this->formulaire
                        ";
                }
                // Sinon
                else {
                // On enregistre le nouveau compte / utilisateur dans la BDD
                $res = "<p>" . Auth::registerStaff($e, $p1) . "</p>";}
            }
            // Sinon
            else {
                // On réaffiche le formulaire
                $res = "
                        <h1>Créer un compte</h1>
                        <h3> Erreur : mot de passe différent ! </h3>
                        <br>
                        $this->formulaire
                        ";
            }
        }
        // On retourne le résultat
        return $res;

    }
}