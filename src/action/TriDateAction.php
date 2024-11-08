<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\render\SpectacleRenderer;
use iutnc\sae_dev_web\repository\SelectRepository;

class TriDateAction extends Action
{


    /**
     * Méthode qui execute une action
     * récupère la liste des spectacles triés par date
     *
     * render chaque spectacle de la liste
     * @return string
     */

    public function execute(): string
    {
        // Récupération des spectacles
        $r = SelectRepository::getInstance();
        $listeSpectacle = $r->getSpectacles("date");


        // On affiche la liste des spectacles
        $res = "";
        /** @var Spectacle $spectacle */
        foreach ($listeSpectacle as $spectacle) {
            $renderer = new SpectacleRenderer($spectacle);
            $res .= $renderer->render(2);
        }
        return $res;


    }
}