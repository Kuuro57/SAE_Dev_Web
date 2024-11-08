<?php

namespace iutnc\sae_dev_web\dispatch;



use iutnc\sae_dev_web\action\AddLieuAction;
use iutnc\sae_dev_web\action\AddSoireeAction;
use iutnc\sae_dev_web\action\AddSpectacleAction;
use iutnc\sae_dev_web\action\AddUtilisateurAction;
use iutnc\sae_dev_web\action\SeConnecterAction;
use iutnc\sae_dev_web\action\SeDeconnecterAction;
use iutnc\sae_dev_web\action\TriDateAction;
use iutnc\sae_dev_web\action\TriLieuAction;
use iutnc\sae_dev_web\action\TriStyleAction;
use iutnc\sae_dev_web\action\DefaultAction;

/**
 * Classe qui représente le dispatcher
 */
class Dispatcher {

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

            case "add-soiree" :
                $class = new AddSoireeAction();
                break;

            case "add-spectacle" :
                $class = new AddSpectacleAction();
                break;

            case "add-utilisateur" :
                $class = new AddUtilisateurAction();
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

        echo <<<END
    
        <!DOCTYPE html>
        <head lang="fr">
        <meta charset="UTF-8">
        <head>
            <link rel="stylesheet" type="text/css" href="../ladéco/style.css"/>
        </head>
        <body>
            <header>
                <h1>NRV - Nancy Rock Vibration</h1>
                <nav>
                    <ul>
                        <li><a href="?action=default">Accueil</a></li>
                        <li><a href="?action=afficher-soirees">Soirées</a></li>
                        <li><a href="?action=choix-affichage-spectacles">Spectacles</a></li>
                        <li><a href="?action=add-soiree">Ajouter une soirée</a></li>
                        <li><a href="?action=add-spectacle">Ajouter un spectacle</a></li>
                        <li><a href="?action=add-lieu">Ajouter un lieu</a></li>
                        <li><a href="?action=add-utilisateur">Ajouter un utilisateur</a></li>
                        <li><a href="?action=se-connecter">Se connecter</a></li>
                        <li><a href="?action=se-deconnecter">Se déconnecter</a></li>
                        <li><a href="?action=tri-date">Trier par date</a></li>
                        <li><a href="?action=tri-lieu">Trier par lieu</a></li>
                        <li><a href="?action=tri-style">Trier par style</a></li>
                    </ul>
                </nav>
            </header>
            <main>
                $html
            </main>
            <br>
            <footer>
                <p>@ALLART Noah, ARMBRUSTER Loup, DE WASCH Clement, DENIS Oscar, MANGIN Adrien</p>
                <p>S3B 2024 - S3-02 SAE_Dev_Web</p>
            </footer>
        </body>

END;
    }


}
