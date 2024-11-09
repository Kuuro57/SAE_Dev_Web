<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action\tri;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\render\SoireeRenderer;
use iutnc\sae_dev_web\render\SpectacleRenderer;
use iutnc\sae_dev_web\repository\SelectRepository;

class TriStyleAction extends Action {


    /**
     * Méthode qui execute une action
     * @return string
     */
    public function execute(): string {
        // On récupère le nom de la classe qui appel cette méthode
        $nomClasseAppelee = debug_backtrace()[1]['class'];
        $nomClasse = strrchr($nomClasseAppelee, '\\');
        $nomClasse = substr($nomClasse, 1);

        // Si la classe est DispatcherAffichageSpectacles
        if ($nomClasse === "DispatcherAffichageSpectacles") {

            // Récupération des spectacles
            $r = SelectRepository::getInstance();
            $listeSpectacle = $r->getSpectacles('style');


            // On affiche la liste des spectacles
            $res = "";
            foreach ($listeSpectacle as $spectacle) {
                $renderer = new SpectacleRenderer($spectacle);
                $res .= $renderer->render(2);
            }

        }

        // Sinon si la classe est DispatcherAffichageSoiree
        elseif ($nomClasse === "DispatcherAffichageSoirees") {

            // Récupération des soirees
            $r = SelectRepository::getInstance();
            $listeSoiree = $r->getSoirees('thematique');


            // On affiche la liste des spectacles
            $res = "";
            foreach ($listeSoiree as $soiree) {
                $renderer = new SoireeRenderer($soiree);
                $res .= $renderer->render(2);
            }

        }

        // On retourne le résultat
        return $res;
    }

}