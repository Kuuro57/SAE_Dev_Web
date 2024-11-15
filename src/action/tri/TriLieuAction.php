<?php

namespace iutnc\sae_dev_web\action\tri;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\render\Renderer;
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
            $listeSoirees = $r->getSoirees('lieu');


            // On affiche la liste des soirees
            $res = "";
            foreach ($listeSoirees as $soiree) {
                $renderer = new SoireeRenderer($soiree);
                $res .= $renderer->render(2);
            };
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
                $lieuCourant = $r->getLieu($spectacleCourant->getId());

                $j = $i - 1;

                // On compare et déplace les éléments plus grands vers la droite en vérifiant que le lieu n'est
                // pas null
                while ( $j >= 0 && ( $lieuPrecedent = $r->getLieu($listeSpectacle[$j]->getId())) !== null
                        && $this->comparerLieux($lieuPrecedent->getNom(), $lieuCourant->getNom()) > 0) {
                    $listeSpectacle[$j + 1] = $listeSpectacle[$j];
                    $j--;
                }

                // On ajoute le spectacle au bon endroit
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
     * Compare deux lieux sous forme de chaînes
     *
     * @param string|null $lieu1 Le premier lieu.
     * @param string|null $lieu2 Le deuxieme lieu.
     * @return int Retourne -1 si $lieu1 < $lieu2, 0 si elles sont égales, 1 si $lieu1 > $lieu2.
     */
    function comparerLieux(?string $lieu1, ?string $lieu2): int {

        // Si la date1 ou date2 est null
        if (is_null($lieu1) || is_null($lieu2)) {
            return -1;
        }

        // Comparaison lexicographique
        if ($lieu1 < $lieu2) {
            return -1;
        } elseif ($lieu1 > $lieu2) {
            return 1;
        } else {
            return 0;
        }

    }

}