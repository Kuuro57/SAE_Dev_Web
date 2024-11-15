<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\render\SpectacleRenderer;
use iutnc\sae_dev_web\repository\SelectRepository;

class PreferencesAction extends Action {


    public function execute(): string {

        $res = "";

        // On vérifie si un utilisateur est connecté
        if(!isset($_SESSION['user']['email'])){

            if (isset($_COOKIE["Favoris"])) {

                $vals = $_COOKIE["Favoris"];
                $ids = ToggleFavori::toArray($vals);

                $select = SelectRepository::getInstance();

                //on récupère tout les spectacles dans une liste sous la forme de leur objet
                foreach ($ids as $id) {

                    $spec[] = $select->getSpectacle($id);

                }

                //on affiche tout les spectacles de la liste de préferences en cookie sur la page
                foreach ($spec as $spectacle) {

                    $renderer = new SpectacleRenderer($spectacle);
                    $res .= $renderer->render(1); //Render court
                }

            } else {

                $res = "Aucune préférence dans votre liste.";

            }

        } else {

            $select = SelectRepository::getInstance();

            //on récupère l'id user avec le mail
            $idUser = $select->getIdFromEmail($_SESSION['user']['email']);

            //on vérifie si l'utilisateur a des préférences
            $hasFavs = $select->hasPrefs($idUser);

            $data = [];

            if($hasFavs){

                $data = $select->getPrefs($idUser);


                //on affiche tout les spectacles de la liste de préferences en BDD sur la page
                foreach ($data as $spectacle) {

                    $renderer = new SpectacleRenderer($spectacle);
                    $res .= $renderer->render(1); //Render court
                }

            } else {

                $res = "Aucune préférence dans votre liste.";

            }

        }

        return $res;

    }
}