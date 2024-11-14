<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Spectacle;
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
        $heureD = filter_var($_POST["heuresD"], FILTER_SANITIZE_SPECIAL_CHARS);
        $style = filter_var($_POST["styles"], FILTER_SANITIZE_SPECIAL_CHARS);
        $lieu = filter_var($_POST["lieux"], FILTER_SANITIZE_SPECIAL_CHARS);

        if ($heureD === "0") {
            $heureD = null;
        }

        if ($style === "0") {
            $style = null;
        }

        if ($lieu === "0") {
            $lieu = null;
        }

        // On appel la méthode qui récupère les spectacles filtrées
        $listeSpectacle = $this->getSpectaclesFiltre($heureD, $style, $lieu);

        // On affiche la liste des spectacles
        $res = "";
        foreach ($listeSpectacle as $spectacle) {
            // Si le spectacle n'est pas null
            if (!is_null($spectacle)) {
                $renderer = new SpectacleRenderer($spectacle);
                $res .= $renderer->render(2);
            }

        }

        // On retourne le résultat
        return $res;

    }



    /**
     * Méthode qui retourne une liste des spectacles filtré
     *
     * @param string|null $heureD
     * @param string|null $style
     * @param string|null $lieu
     * @return Spectacle[]
     */
    public function getSpectaclesFiltre(?string $heureD, ?string $style, ?string $lieu) : array {

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