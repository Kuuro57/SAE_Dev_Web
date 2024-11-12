<?php

namespace iutnc\sae_dev_web\action\tri;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\render\SoireeRenderer;
use iutnc\sae_dev_web\render\SpectacleRenderer;
use iutnc\sae_dev_web\repository\SelectRepository;

class TriLieuAction extends Action
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



        // On récupère le nom de la classe qui appel cette méthode
        $nomClasseAppelee = debug_backtrace()[1]['class'];
        $nomClasse = strrchr($nomClasseAppelee, '\\');
        $nomClasse = substr($nomClasse, 1);

        // Si la classe est DispatcherAffichageSoirees
        if ($nomClasse === "DispatcherAffichageSoirees") {

            // Récupération des soirees
            $r = SelectRepository::getInstance();
            $listeSoirees = $r->getSoirees('lieu');


            // On affiche la liste des soirees
            $res = "";
            foreach ($listeSoirees as $soiree) {
                $renderer = new SoireeRenderer($soiree);
                $res .= $renderer->render(2);
            }

            return $res;
        } // Sinon si la classe est DispatcherAffichageSpectacles
        elseif ($nomClasse === "DispatcherAffichageSpectacles") {




        // Récupération des spectacles
        $r = SelectRepository::getInstance();
        /** @var Spectacle[] $listeSpectacleAvecLieu */
        $listeSpectacleAvecLieu = $r->getSpectacles("lieu"); // On récupère les spectacles avec date triés par lieu
        /** @var Spectacle[] $listeTousSpectacles */
        $listeTousSpectacles = $r->getSpectacles(null); // On récupère tous les spectacles
        // On crée un tableau de Spectacle qui ne contiendra que les spectacles sans date, ceux qui restent
        $listeSpectaclesRestants = []; // Tableau de Spectacle qui contiendra les spectacles sans dates
        // pour vérifier si un spectacle est déjà dans le tableau on récupère l'id de chaque spectacle
        // si l'id est dans le tableau avec date on ne l'ajoute pas dans ListeSpectaclesRestants
        foreach ($listeTousSpectacles as $spectacle) {
            $id = $spectacle->getId();
            $trouve = false;
            foreach ($listeSpectacleAvecLieu as $spectacleAvecLieu) {
                if ($id == $spectacleAvecLieu->getId()) { // Si le spectacle est déjà dans le tableau avec date
                    $trouve = true; // On le signale
                    break; // On sort de la boucle
                }
            }
            if (!$trouve) { // Si le spectacle n'est pas dans le tableau avec date
                $listeSpectaclesRestants[] = $spectacle; // On ajoute le spectacle dans le tableau
            }
        }
        // on crée un tableau qui contiendra les spectacles triés par date plus les spectacles restants
        $listeSpectacle = array_merge($listeSpectacleAvecLieu, $listeSpectaclesRestants); // On fusionne les deux tableaux
        // On affiche la liste des spectacles
        $res = "";
        /** @var Spectacle $spectacle */
        foreach ($listeSpectacle as $spectacle) {
            $renderer = new SpectacleRenderer($spectacle);
            $res .= $renderer->render(2);
        }

    } return $res;
    }
}