<?php

namespace iutnc\sae_dev_web\action\tri;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\render\Renderer;
use iutnc\sae_dev_web\render\SoireeRenderer;
use iutnc\sae_dev_web\render\SpectacleRenderer;
use iutnc\sae_dev_web\repository\SelectRepository;

class TriStyleAction extends Action
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
            $listeSoirees = $r->getSoirees('style');


            // On affiche la liste des soirees
            $res = "";
            foreach ($listeSoirees as $soiree) {
                $renderer = new SoireeRenderer($soiree);
                $res .= $renderer->render($renderMode);
            }

            return $res;
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
                $styleCourant = $listeSpectacle[$i]->getStyle()->getNom();

                $j = $i - 1;

                // On compare et déplace les éléments plus grands vers la droite
                while ($j >= 0 && $this->comparerStyles($listeSpectacle[$j]->getStyle()->getNom(), $styleCourant) > 0) {
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
     * Compare deux styles sous forme de chaînes
     *
     * @param string|null $style1 Le premier style.
     * @param string|null $style2 Le deuxième style.
     * @return int Retourne -1 si $style1 < $styl2, 0 si elles sont égales, 1 si $styl1 > $style2.
     */
    function comparerStyles(?string $style1, ?string $style2): int {

        // Si le style1 ou style2 est null
        if (is_null($style1) || is_null($style2)) {
            return -1;
        }

        // Comparaison lexicographique
        if ($style1 < $style2) {
            return -1;
        } elseif ($style1 > $style2) {
            return 1;
        } else {
            return 0;
        }

    }
}