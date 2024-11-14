<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\dispatch;



use iutnc\sae_dev_web\action\AddUtilisateurAction;
use iutnc\sae_dev_web\action\DisplaySoireeListeSpectacle;
use iutnc\sae_dev_web\action\SeConnecterAction;
use iutnc\sae_dev_web\action\SeDeconnecterAction;
use iutnc\sae_dev_web\action\tri\TriDateAction;
use iutnc\sae_dev_web\action\tri\TriLieuAction;
use iutnc\sae_dev_web\action\tri\TriStyleAction;

/**
 * Classe qui représente le dispatcher de la page qui affiche les soirées
 */
class DispatcherAffichageSoirees {

    /**
     * Méthode qui lance le dispatcher
     */
    public function run() : void {
        
        if (!isset($_GET['action'])) {
            $_GET['action'] = '';
        }

        switch ($_GET['action']) {

            case "se-connecter" :
                $class = new SeConnecterAction();
                break;

            case "se-deconnecter" :
                $class = new SeDeconnecterAction();
                break;

            case "add-utilisateur" :
                $class = new AddUtilisateurAction();
                break;

            case "tri-lieu" :
                $class = new TriLieuAction();
                break;

            case "tri-style" :
                $class = new TriStyleAction();
                break;
            case "display-soiree-liste-spectacles" :
                $class = new DisplaySoireeListeSpectacle();
                break;

            default : // Tri par date
                $class = new TriDateAction();
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
        $email = '';
        $role = '';

        // Si l'utilisateur est connecté
        if (isset($_SESSION['user'])) {
            // On créé son email et son rôle (ADMIN ou STANDARD)
            $email = 'Connecté au compte : ' . $_SESSION['user']['email'];
            if ((int) $_SESSION['user']['role'] === 1) { $role = 'Vos permissions : STANDARD'; }
            elseif ((int) $_SESSION['user']['role'] === 100) { $role = 'Vos permissions : ADMIN'; }
            else { $role = 'Vos permissions : Undefined'; }

            // On crée le bouton de déconnexion
            $btnDeconnexion = '<button name="action" value="se-deconnecter"> Se déconnecter </button>';
        }
        // Sinon
        else {
            // On crée le bouton de connexion
            $btnConnexion = '<button name="action" value="se-connecter"> Connexion </button>';
            // On crée le bouton de création d'un compte
            $btnCreationCompte = '<button name="action" value="add-utilisateur"> Créer son compte </button>';
        }

        // On affiche sur la page son contenu
        echo <<<END
    
        <!DOCTYPE html >
        <html lang="fr">
        
        <head>
            <meta charset="utf-8">
            <title> NRV Festival - Programme </title>
            <link href="./css/style.css" rel="stylesheet">
        </head>
        
        <body>
            
                <h1 id="mainTitle">NRV - Nancy Rock Vibration</h1>
                <h2 id="secondTitle"> Notre Programme / Soirees </h2>
                
                <form method="get">
                    $email
                    $role
                
                    $btnConnexion
                    $btnDeconnexion
                    $btnCreationCompte
                </form>
                
                <nav>
                        <a href="index.php?action=default">Accueil</a>
                        <a href="?action=display-soiree-liste-spectacles">Soirées</a>
                        <a href="?action=tri-date">Trier par date</a>
                        <a href="?action=tri-lieu">Trier par lieu</a>
                        <a href="?action=tri-style">Trier par thematique </a>
                        
                </nav>
            
                <div class="container">
                    $html
                </div>
                <br>
                
            <footer>
                <p>@ALLART Noah, ARMBRUSTER Loup, DE WASCH Clement, DENIS Oscar, MANGIN Adrien</p>
                <p>S3B 2024 - S3-02 SAE_Dev_Web</p>
            </footer>
            
        </body>
        
        </html>

END;
    }


}
