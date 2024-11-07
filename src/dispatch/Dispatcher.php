<?php

namespace iutnc\sae_dev_web\dispatch;



use iutnc\sae_dev_web\action\AddSpectacleAction;

/**
 * Classe qui représente le dispatcher
 */
class Dispatcher {

    /**
     * Méthode qui lance le dispatcher
     */
    public function run() : void {

        // On regarde (avec un switch) quelle action faire en récupérant l'action
        switch ($_GET['action']) {

            case "TODO" :
                // TODO
                break;

            default :
                $class = new AddSpectacleAction();
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

            <html lang="fr">
                $html
            </html>

        END;


    }


}