<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\dispatch;



use iutnc\sae_dev_web\action\AddUtilisateurAction;
use iutnc\sae_dev_web\action\FiltreSpectacleAction;
use iutnc\sae_dev_web\action\SeConnecterAction;
use iutnc\sae_dev_web\action\SeDeconnecterAction;
use iutnc\sae_dev_web\action\tri\TriDateAction;
use iutnc\sae_dev_web\action\tri\TriLieuAction;
use iutnc\sae_dev_web\action\tri\TriStyleAction;
use iutnc\sae_dev_web\repository\SelectRepository;

/**
 * Classe qui représente le dispatcher de la page qui affiche les spectacles
 */
class DispatcherAffichageSpectacles {

    /**
     * Méthode qui lance le dispatcher
     */
    public function run() : void {
        
        if (!isset($_GET['action'])) {
            $_GET['action'] = '';
        }

        // On initialise la variable de mode de rendu, compact par défaut
        $renderMode = $_GET['renderMode'] ?? 'compact';

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

            case "add-favs" :
                $class = new AddFavoriAction();
                break;

            case "tri-style" :
                $class = new TriStyleAction();
                break;

            case "filtre" :
                $class = new FiltreSpectacleAction();
                break;

            default :
                $class = new TriDateAction();
                break;

        }

        // On affiche la page en executant la méthode execute d'une classe Action
        $this->renderPage($class->execute(), $renderMode);

    }



    /**
     * Méthode qui ajoute le morceau de page à la page complète
     */
    private function renderPage(string $html, string $renderMode) : void {

        // Initialisation des variables contenants les boutons et données au format HTML
        $btnConnexion = '';
        $btnDeconnexion = '';
        $btnCreationCompte = '';
        $email = '';
        $role = '';

        // Si l'utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // On crée le bouton de déconnexion
            $btnDeconnexion = '<button name="action" value="se-deconnecter" id="btn_connexion"> Se déconnecter </button>';

            // Si l'utilisateur est connecté en tant que STANDARD
            if ((int) $_SESSION['user']['role'] === 1) {
                // On affiche son email et son rôle
                $email = 'Connecté au compte : ' . $_SESSION['user']['email'] . "<br>";
                $role = 'Vos permissions : STANDARD';
            }

            // Sinon si l'utilisateur est connecté en tant que STAFF
            else if ((int) $_SESSION['user']['role'] === 90) {
                // On affiche son email et son rôle
                $email = 'Connecté au compte : ' . $_SESSION['user']['email'] . "<br>";
                $role = 'Vos permissions : STAFF';
            }

            // Sinon si l'utilisateur est connecté en tant que ADMIN
            else if ((int) $_SESSION['user']['role'] === 100) {
                // On affiche son email et son rôle
                $email = 'Connecté au compte : ' . $_SESSION['user']['email'] . "<br>";
                $role = 'Vos permissions : ADMIN';
            }

        }
        // Sinon
        else {
            // On crée le bouton de connexion
            $btnConnexion = '<button name="action" value="se-connecter" id="btn_connexion"> Connexion </button>';
            // On crée le bouton de création d'un compte
            $btnCreationCompte = '<button name="action" value="add-utilisateur" id="btn_connexion"> Inscription </button>';
        }



        // Gestion de l'URL pour changer le mode d'affichage
        $renderModeChecked = $renderMode === 'long' ? 'checked' : '';
        $nvRenderMode = $renderMode === 'long' ? 'compact' : 'long';



        // Si le mode d'affichage utilise le filtre
        if ($_GET['action'] === 'filtre') {
            // On affiche pas le bouton détaille
            $btnDetaille = '';
        }
        // Sinon
        else {
            // On l'affiche
            $btnDetaille = <<<END
            <label>Detaillé <input type="checkbox" name="checkBoxDetail"
            onchange="window.location.href='?action={$_GET['action']}&renderMode={$nvRenderMode}';" 
            {$renderModeChecked}></label>
        END;

        }

        $formulaire = $this->getFormulaire();



        // On affiche sur la page son contenu
        echo <<<END
    
        <!DOCTYPE html>
            <html lang="fr">
            
            <head>
                <meta charset="utf-8">
                <title> NRV Festival - Spectacles </title>
                <link href="./css/style_spectacles.css" rel="stylesheet">
            </head>
            
            <body>
            
                <h1 id="mainTitle"> NRV - Nancy Rock Vibration </h1>
                <h2 id="secondTitle"> Spectacles </h2>
            
                <form method="get">
                    <div class="user-info">
                        $email <br>
                        $role
                    </div>
                    <div class="btn_connexion_container">
                        $btnConnexion
                        $btnDeconnexion
                        $btnCreationCompte         
                    </div>
                </form>
                
                <br>
                
                <nav>
                        <a href="index.php?action=default">Accueil</a>
                        <a href="?action=tri-date&renderMode={$renderMode}">Trier par date</a>
                        <a href="?action=tri-lieu&renderMode={$renderMode}">Trier par lieu</a>
                        <a href="?action=tri-style&renderMode={$renderMode}">Trier par style</a>
                        $btnDetaille
                </nav>
                
                 $formulaire
                
                 <br>
            
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


    /**
     * Méthode qui créé le formulaire de filtrage
     *
     * @return string Le formulaire
     */
    private function getFormulaire() : string {

        // On récupère une instance du SelectRepository
        $selectRepo = SelectRepository::getInstance();

        // On récupère la liste des id de tout les spectacles
        $listeSpectacles = $selectRepo->getSpectacles(null);

        // Liste qui contient toutes les horaires
        $listeHoraires = [];



        // On créé la liste déroulante des heures de début des spectacles (= soirées)
        foreach ($listeSpectacles as $spectacles) {
            $listeHeureSpectacles[] = $selectRepo->getSpectacle($spectacles->getId());
        }
        $listeDeroulanteHoraire = '<select name="heuresD" class="input-field"> <option value="0"> -- Choisissez un horaire -- </option>';
        foreach ($listeHeureSpectacles as $spectacle) {
            // Si l'horaire n'est pas déjà présent
            if (!key_exists($spectacle->getHeureDebut(), $listeHoraires)) {
                // On ajoute l'horaire à la liste déroulante
                $listeDeroulanteHoraire .= "<option value='{$spectacle->getHeureDebut()}'> {$spectacle->getHeureDebut()} </option>";
                // On ajoute l'horaire à la liste des horaires
                $listeHoraires[$spectacle->getHeureDebut()] = $spectacle->getHeureDebut();
            }
        }
        $listeDeroulanteHoraire .= "</select>";



        // On créé la liste déroulante des dates des soirées
        $listeDates = [];
        foreach ($listeSpectacles as $spectacle) {
            $listeDatesSpectacles[] = $selectRepo->getDateSpectacle($spectacle->getId());
        }
        $listeDeroulanteDate = '<select name="dates" class="input-field"> <option value="0"> -- Choisissez une date -- </option>';
        foreach ($listeDatesSpectacles as $date) {
            // Si la date n'est pas déjà présent
            if (!key_exists($date, $listeDates)) {
                // On ajoute la date à la liste déroulante
                $listeDeroulanteDate .= "<option value='{$date}'> {$date} </option>";
                // On ajoute la date à la liste des horaires
                $listeHoraires[$date] = $date;
            }
        }
        $listeDeroulanteDate .= "</select>";



        // On créé la liste déroulant des style des spectacles
        $listeStylesSpectacles = $selectRepo->getStyles();
        $listeDeroulanteStyles = '<select name="styles" class="input-field"> <option value="0"> -- Choisissez un style -- </option>';
        foreach ($listeStylesSpectacles as $style) {
            $listeDeroulanteStyles .= "<option value='{$style->getNom()}'> {$style->getNom()} </option>";
        }
        $listeDeroulanteStyles .= "</select>";



        // On créé la liste déroulant des lieu des spectacles (= soirées)
        $listeLieuxSpectacles = $selectRepo->getLieux();
        $listeDeroulanteLieux = '<select name="lieux" class="input-field"> <option value="0"> -- Choisissez un lieu -- </option>';
        foreach ($listeLieuxSpectacles as $lieu) {
            $listeDeroulanteLieux .= "<option value='{$lieu->getNom()}'> {$lieu->getNom()} </option>";
        }
        $listeDeroulanteLieux .= "</select>";



        // On renvoie le formulaire complet
        return <<<END
            
            <form method="post" name="" action="?action=filtre">
                $listeDeroulanteHoraire
                $listeDeroulanteDate
                $listeDeroulanteStyles
                $listeDeroulanteLieux
                <button type="submit" name="valider" id="btn_connexion"> Valider </button>
            </form>
            
        END;


    }

}
