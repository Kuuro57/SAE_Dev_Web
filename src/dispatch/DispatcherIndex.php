<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\dispatch;



use iutnc\sae_dev_web\action\AddArtisteAction;
use iutnc\sae_dev_web\action\AddLieuAction;
use iutnc\sae_dev_web\action\AddSoireeAction;
use iutnc\sae_dev_web\action\AddSpectacleAction;
use iutnc\sae_dev_web\action\AddStaffAction;
use iutnc\sae_dev_web\action\AddStyleAction;
use iutnc\sae_dev_web\action\AddThematiqueAction;
use iutnc\sae_dev_web\action\AddUtilisateurAction;
use iutnc\sae_dev_web\action\AnnulerSpectacleAction;
use iutnc\sae_dev_web\action\ModifierInfoSpectacleAction;
use iutnc\sae_dev_web\action\RemplirSoireeAction;
use iutnc\sae_dev_web\action\DefaultAction;
use iutnc\sae_dev_web\action\SeConnecterAction;
use iutnc\sae_dev_web\action\SeDeconnecterAction;
use iutnc\sae_dev_web\action\tri\TriDateAction;
use iutnc\sae_dev_web\action\tri\TriLieuAction;
use iutnc\sae_dev_web\action\tri\TriStyleAction;

/**
 * Classe qui représente le dispatcher
 */
class DispatcherIndex {

    /**
     * Méthode qui lance le dispatcher
     */
    public function run() : void {

        if (!isset($_GET['action'])) {
            $_GET['action'] = '';
        }

        switch ($_GET['action']) {

            case "add-lieu" :
                $class = new AddLieuAction();
                break;

            case "add-style" :
                $class = new AddStyleAction();
                break;

            case "add-soiree" :
                $class = new AddSoireeAction();
                break;

            case "add-spectacle" :
                $class = new AddSpectacleAction();
                break;

            case "add-artiste" :
                $class = new AddArtisteAction();
                break;

            case "remplir-soiree" :
                $class = new RemplirSoireeAction();
                break;

            case "add-utilisateur" :
                $class = new AddUtilisateurAction();
                break;

            case "add-staff" :
                $class = new AddStaffAction();
                break;

            case "add-theme" :
                $class = new AddThematiqueAction();
                break;

            case "se-connecter" :
                $class = new SeConnecterAction();
                break;

            case "se-deconnecter" :
                $class = new SeDeconnecterAction();
                break;

            case "tri-date" :
                $class = new TriDateAction();
                break;

            case "tri-lieu" :
                $class = new TriLieuAction();
                break;

            case "tri-style" :
                $class = new TriStyleAction();
                break;
            case "annuler-spectacle" :
                $class = new AnnulerSpectacleAction();
                break;
            case "modifier-spectacle" :
                $class = new ModifierInfoSpectacleAction();
                break;

            default :
                $class = new DefaultAction();
                break;

        }

        // On affiche la page en executant la méthode execute d'une classe Action
        $this->renderPage($class->execute());

    }



    /**
     * Méthode qui ajoute le morceau de page à la page complète
     */
    private function renderPage(string $html) : void {

        // Initialisation des variables contenants les boutons et données au format HTML
        $btnConnexion = '';
        $btnDeconnexion = '';
        $btnCreationCompte = '';
        $btnCreationCompteStaff = '';
        $liensAjout = '';
        $email = '';
        $role = '';

        // Si l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // On crée le bouton de déconnexion
            $btnDeconnexion = '<button name="action" value="se-deconnecter" id="btn_connexion"> Se déconnecter </button>';

            // Si l'utilisateur est connecté en tant que STANDARD
            if ((int) $_SESSION['user']['role'] === 1) {
                // On affiche son email et son rôle
                $email = 'Compte : ' . $_SESSION['user']['email'] . "<br>";
                $role = 'Permissions : STANDARD';
            }

            // Sinon si l'utilisateur est connecté en tant que STAFF
            else if ((int) $_SESSION['user']['role'] === 90) {
                // On affiche son email et son rôle
                $email = 'Compte : ' . $_SESSION['user']['email'] . "<br>";
                $role = 'Permissions : STAFF';
                // On affiche les lien cliquable pour ajouter
                $liensAjout = <<<END
                    <a href="?action=add-soiree">Ajouter une soirée</a>
                    <a href="?action=add-spectacle">Ajouter un spectacle</a>
                    <a href="?action=remplir-soiree">Programmer une soirée</a>
                    <a href="?action=add-artiste">Ajouter un artiste</a>
                    <a href="?action=add-lieu">Ajouter un lieu</a>
                    <a href="?action=add-style">Ajouter un style</a>
                    <a href="?action=add-theme">Ajouter une thématique</a>
                    <a href="?action=annuler-spectacle">Annuler un spectacle</a>
                    <a href="?action=modifier-spectacle">Modifier un spectacle</a>
                END;
            }

            // Sinon si l'utilisateur est connecté en tant que ADMIN
            else if ((int) $_SESSION['user']['role'] === 100) {
                // On affiche son email et son rôle
                $email = 'Compte : ' . $_SESSION['user']['email'] . "<br>";
                $role = 'Permissions : ADMIN';
                // On affiche les lien cliquable pour ajouter
                $liensAjout = <<<END
                    <a href="?action=add-soiree">Ajouter une soirée</a>
                    <a href="?action=add-spectacle">Ajouter un spectacle</a>
                    <a href="?action=remplir-soiree">Programmer une soirée</a>
                    <a href="?action=add-artiste">Ajouter un artiste</a>
                    <a href="?action=add-lieu">Ajouter un lieu</a>
                    <a href="?action=add-style">Ajouter un style</a>
                    <a href="?action=add-theme">Ajouter une thématique</a>
                    <a href="?action=annuler-spectacle">Annuler un spectacle</a>
                    <a href="?action=modifier-spectacle">Modifier un spectacle</a>
                END;
                // On affiche le bouton pour créer un compte STAFF
                $btnCreationCompteStaff = '<button name="action" value="add-staff" id="btn_connexion"> Créer compte STAFF </button>';
            }

        }
        // Sinon
        else {
            // On met dans la zone de l'email que l'utilisateur n'est pas connecté
            $email = 'Vous n\'êtes pas connecté !';
            // On crée le bouton de connexion
            $btnConnexion = '<button name="action" value="se-connecter" id="btn_connexion"> Connexion </button>';
            // On crée le bouton de création d'un compte
            $btnCreationCompte = '<button name="action" value="add-utilisateur" id="btn_connexion"> Inscription </button>';
        }





        
        // On affiche sur la page son contenu
        echo <<<END
    
        <!DOCTYPE html>
            <html lang="fr">
            
            <head>
                <meta charset="utf-8">
                <title> NRV Festival - Accueil </title>
                <link href="./css/style_index.css" rel="stylesheet">
            </head>
            
            <body>
            
                <h1 id="mainTitle"> NRV - Nancy Rock Vibration </h1>
                <h2 id="secondTitle"> Accueil </h2>
            
                <form method="get" class="form-container">
                    <div class="user-info">
                        $email <br>
                        $role
                    </div>
                    <div class="btn_connexion_container">
                        $btnConnexion
                        $btnDeconnexion
                        $btnCreationCompte
                        $btnCreationCompteStaff
                    </div>
                </form>
                
                <br>
            
                <nav>
                    <a href="afficherSoirees.php?action=default">Notre programme</a>
                    <a href="afficherSpectacles.php?action=default">Nos spectacles</a>
                    $liensAjout
                </nav>
            
                <div class="container">
                    $html
                </div>
            
                <footer>
                    <p>@ALLART Noah, ARMBRUSTER Loup, DE WASCH Clement, DENIS Oscar, MANGIN Adrien</p>
                    <p>S3B 2024 - S3-02 SAE_Dev_Web</p>
                </footer>
            
            </body>
        </html>


END;
    }


}
