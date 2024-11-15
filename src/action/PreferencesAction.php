<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\render\SpectacleRenderer;
use iutnc\sae_dev_web\repository\SelectRepository;

class PreferencesAction extends Action {

    private array $spec;
    public function execute(): string {

        $res = "";

        if($_SESSION['user']['email'] == null){

            if (isset($_COOKIE["Favoris"])) {

                $vals = $_COOKIE["Favoris"];
                $ids = ToggleFavori::toArray($vals);

                $select = SelectRepository::getInstance();

                foreach ($ids as $id) {

                    $spec[] = $select->getSpectacle($id);

                }

                foreach ($spec as $spectacle) {

                    $renderer = new SpectacleRenderer($spectacle);
                    $res .= $renderer->render(1); //Render court
                }

            } else {

                $res = "Aucune préférence dans votre liste.";

            }

        } else {

            $select = SelectRepository::getInstance();

            $idUser = $select->getIdFromEmail($_SESSION['user']['email']);

            $hasFavs = $select->hasPrefs($idUser);

            $data = [];

            if($hasFavs){

                $data = $select->getPrefs($idUser);

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