<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\render\Renderer;
use iutnc\sae_dev_web\render\SpectacleRenderer;


/**
 * Classe qui représente le filtrage des spectacles
 */
class FiltreSpectacleAction extends Action
{

    public function __construct() {
        parent::__construct();
    }



    public function execute(): string {

        // On récupère les variables du formulaire
        if (isset($_POST["heuresD"]) && isset($_POST["styles"]) && isset($_POST["lieux"]) && isset($_POST['dates'])) {
            $heureD = filter_var($_POST["heuresD"], FILTER_SANITIZE_SPECIAL_CHARS);
            $style = filter_var($_POST["styles"], FILTER_SANITIZE_SPECIAL_CHARS);
            $lieu = filter_var($_POST["lieux"], FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_var($_POST["dates"], FILTER_SANITIZE_SPECIAL_CHARS);
        }
        else {
            $heureD = null;
            $style = null;
            $lieu = null;
            $date = null;
        }


        if ($heureD === "0") {
            $heureD = null;
        }

        if ($style === "0") {
            $style = null;
        }

        if ($lieu === "0") {
            $lieu = null;
        }

        if ($date === "0") {
            $date = null;
        }

        // On appel la méthode qui récupère les spectacles filtrées
        $listeSpectacle = $this->getSpectaclesFiltre($heureD, $date, $style, $lieu);

        // On récupère le mode d'affichage
        if (isset($_GET['renderMode']) && $_GET['renderMode'] === 'court') {
            $renderMode = Renderer::COMPACT;
        } else {
            $renderMode = Renderer::LONG;
        }

        // On affiche la liste des spectacles
        $res = "";
        foreach ($listeSpectacle as $spectacle) {
            // Si le spectacle n'est pas null
            if (!is_null($spectacle)) {
                $renderer = new SpectacleRenderer($spectacle);
                $res .= $renderer->render($renderMode);
            }

        }

        // On retourne le résultat
        return $res;

    }


    /**
     * Méthode qui retourne une liste des spectacles filtré
     *
     * @param string|null $heureD
     * @param string|null $date
     * @param string|null $style
     * @param string|null $lieu
     * @return Spectacle[]
     */
    public function getSpectaclesFiltre(?string $heureD, ?string $date, ?string $style, ?string $lieu) : array {

        // On récupère tous les spectacles
        $listeSpectacles = $this->selectRepo->getSpectacles(null);


        // Si l'utilisateur veut filtrer sur la date
        if (!is_null($heureD)) {

            // On prend dans la liste des spectacles seulement les spectacles qui ont la même heure
            $i = 0;
            foreach ($listeSpectacles as $spectacle) {

                if (!($spectacle->getHeureDebut() === $heureD)) {
                    $listeSpectacles[$i] = null;
                }
                $i++;
            }
        }

        // Si l'utilisateur veut filtrer sur la date
        if (!is_null($date)) {

            // On prend dans la liste des spectacles seulement les spectacles qui ont la même date
            $i = 0;
            foreach ($listeSpectacles as $spectacle) {

                if (!is_null($spectacle)) {
                    if (!($this->selectRepo->getDateSpectacle($spectacle->getId()) === $date)) {
                        $listeSpectacles[$i] = null;
                    }
                }
                $i++;
            }
        }

        // Si l'utilisateur veut filtrer sur le style
        if (!is_null($style)) {

            // On prend dans la liste des spectacles seulement les spectacles qui ont le même style
            $i = 0;
            foreach ($listeSpectacles as $spectacle) {

                if (!is_null($spectacle)) {
                    if (!($spectacle->getStyle()->getNom() === $style)) {
                        $listeSpectacles[$i] = null;
                    }
                }
                $i++;
            }
        }

        // Si l'utilisateur veut filtrer sur le lieu
        if (!is_null($lieu)) {

            // On prend dans la liste des spectacles seulement les spectacles qui ont le même lieu
            $i = 0;
            foreach ($listeSpectacles as $spectacle) {

                // Si le spectacle n'est pas null
                if (!is_null($spectacle)) {
                    // On prend le lieu à tester
                    $lieuTest = $this->selectRepo->getLieuSpectacle($spectacle->getId());
                    // Si ce lieu n'est pas null
                    if (!is_null($lieuTest)) {
                        // Si le nom du lieu est le même que le nom du lieu à tester
                        if (!($lieuTest->getNom() === $lieu)) {
                            // On le met à null dans la liste
                            $listeSpectacles[$i] = null;
                        }
                    }
                    // Sinon
                    else {
                        // On le met à null dans la liste
                        $listeSpectacles[$i] = null;
                    }
                }
                // On incrémente le compteur
                $i++;
            }
        }

        // On renvoie la liste des spectacles
        return $listeSpectacles;

    }

}