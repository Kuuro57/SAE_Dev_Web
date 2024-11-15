<?php

namespace iutnc\sae_dev_web\action\tri;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\render\Renderer;
use iutnc\sae_dev_web\render\SoireeRenderer;
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
     * @throws \DateMalformedStringException
     */

    public function execute(): string
    {

        // Récupère le mode de rendu depuis l'URL (compact par défaut)

        if (isset($_GET['renderMode']) && $_GET['renderMode'] === 'long') {
            $renderMode = Renderer::LONG;
        } else {
            $renderMode = Renderer::COMPACT;
        }

        // On récupère le nom de la classe qui appel cette méthode
        $nomClasseAppelee = debug_backtrace()[1]['class'];
        $nomClasse = strrchr($nomClasseAppelee, '\\');
        $nomClasse = substr($nomClasse, 1);

        // Si la classe est DispatcherAffichageSoirees
        if ($nomClasse === "DispatcherAffichageSoirees") {

            // Récupération des soirees
            $r = SelectRepository::getInstance();
            $listeSoirees = $r->getSoirees(null);


            // On affiche la liste des soirees
            $res = "";
            foreach ($listeSoirees as $soiree) {
                $renderer = new SoireeRenderer($soiree);
                $res .= $renderer->render(2);
            }

        }



        // Sinon si la classe est DispatcherAffichageSpectacles
        elseif ($nomClasse === "DispatcherAffichageSpectacles") {

            // Récupération des spectacles
            $r = SelectRepository::getInstance();
            $listeSpectacle = $r->getSpectacles(null);

            $taille = count($listeSpectacle);

            // Tri par insertion
            for ($i = 1; $i < $taille; $i++) {
                $spectacleCourant = $listeSpectacle[$i];
                $dateCourante = $r->getDateSpectacle($spectacleCourant->getId());

                $j = $i - 1;

                // On Compare et déplace les éléments plus grands vers la droite
                while ($j >= 0 && $this->comparerDates($r->getDateSpectacle($listeSpectacle[$j]->getId()), $dateCourante) > 0) {
                    $listeSpectacle[$j + 1] = $listeSpectacle[$j];
                    $j--;
                }

                $listeSpectacle[$j + 1] = $spectacleCourant;
            }


            // On affiche la liste des spectacles
            $res = "";
            foreach ($listeSpectacle as $spectacle) {
                $renderer = new SpectacleRenderer($spectacle);
                $res .= $renderer->render($renderMode);
            }


        }

        return $res;
    }



    /**
     * Compare deux dates sous forme de chaînes (format YYYY-MM-DD HH:MM:SS).
     *
     * @param string|null $date1 La première date.
     * @param string|null $date2 La deuxième date.
     * @return int Retourne -1 si $date1 < $date2, 0 si elles sont égales, 1 si $date1 > $date2.
     */
    function comparerDates(?string $date1, ?string $date2): int {

        // Si la date1 ou date2 est null
        if (is_null($date1) || is_null($date2)) {
            return -1;
        }

        // Comparaison lexicographique fonctionne pour le format YYYY-MM-DD HH:MM:SS
        if ($date1 < $date2) {
            return -1;
        } elseif ($date1 > $date2) {
            return 1;
        } else {
            return 0;
        }

    }

}