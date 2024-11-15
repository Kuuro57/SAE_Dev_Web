<?php declare (strict_types=1);
namespace iutnc\sae_dev_web\action;



use iutnc\sae_dev_web\auth\Auth;

/**
 * Classe qui représente l'action de connexion à un compte
 */
class SeConnecterAction extends Action {

    // Attributs
    private string $formulaire = '
                    <form method="post" action="?action=se-connecter">
                        <input type="email" name="email" placeholder="Email" class="input-field" required autofocus>
                        <input type="password" name="mdp" placeholder="Mot de passe" class="input-field" required>
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
     * @return string Un message au format HTML contenant un formulaire (lorsque méthode GET utilisée)
     *                Un message indiquant que la connexion s'est bien déroulée
     */
    public function execute() : string{

        // Si la méthode utilisée est de type GET
        if ($this->http_method == "GET") {

            // On affiche le formulaire
            return '<h1>Connexion</h1>' . $this->formulaire;

        }
        // Sinon
        else {
            // On récupère l'email et le mot de passe
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p = $_POST['mdp'];
            $bool = false;

            // On vérifie que l'utilisateur a bien rempli les champs
            $bool = Auth::authenticate($e, $p);
            // Si l'authentification n'a pas réussi
            if (!$bool) {
                // On retourne un message indiquant que la connexion n'a pas réussie
                $res = "<p>Identifiant ou mot de passe invalide</p>";
            }
            // Sinon
            else {
                // On retourne un message comme quoi la connexion s'est bien déroulé
                $res= "<h3>Connexion réussie pour $e</h3>";
            }

        }

        // On retourne le résultat
        return $res;
    }
}