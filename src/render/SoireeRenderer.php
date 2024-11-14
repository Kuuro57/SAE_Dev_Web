<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\render;

use iutnc\sae_dev_web\festival\Soiree;
use iutnc\sae_dev_web\festival\Spectacle;

class SoireeRenderer implements Renderer {

    // Attribut
    private Soiree $soiree;



    /**
     * Constructeur de la classe
     */
    public function __construct(Soiree $soiree) {
        $this->soiree = $soiree;
    }

    /**
     * Méthode qui permet d'afficher la soirée de manière détaillée
     * Affichage du détail d’une soirée : nom de la soirée, thématique, date et horaire, lieu,
     * tarifs, ainsi que la liste des spectacles : titre, artistes, description, style de musique, vidéo,
     * @param int $selector
     * @return string
     */


    public function render(int $selector = Renderer::COMPACT): string
    {
        switch ($selector) {
            case Renderer::COMPACT:
                return $this->renderCompact();
            case Renderer::LONG:
                return $this->renderLong();
            default:
                return "Mode d'affichage non reconnu";
        }

    }

    /**
     * Méthode renderCompact qui permet d'afficher en format HTML compact
     *
     * @return string Un texte en format HTML
     */
    public function renderCompact(): string {
        $nom = $this->soiree->getNom();
        $theme = $this->soiree->getThematique()->getNom();
        $date = $this->soiree->getDate();
        $heureD = $this->soiree->calculHeureDebut();
        $heureF = $this->soiree->calculHeureFin();
        $horaire = $heureD . "-" . $heureF;
        $lieu = $this->soiree->getLieu()->getNom();
        $tarif = $this->soiree->getTarif();
        $spectaclesListe = $this->soiree->getListeSpectacle();

        // Création des liens pour chaque spectacle
        if (count($spectaclesListe) > 0) {
            $spectacles = "";
            foreach ($spectaclesListe as $spectacle) {
                $id = (string)$spectacle->getId();
                $href = "?action=display-soiree-liste-spectacles&id=$id";  // Lien avec l'action et l'id du spectacle
                $lien = "<a href='$href'>" . $spectacle->getNom() . "</a>";
                $spectacles .= $lien . "<br>";
            }
        } else {
            $spectacles = "<p>Aucun spectacle pour cette soirée</p>";
        }

        // Retourne la soirée avec les spectacles sous forme de liens
        return "<div id='soiree'>
                <p><strong>$nom</strong> <br>
                <strong>Thématique</strong> - $theme <br>
                <strong>Date</strong> - $date <br>
                <strong>Horaire</strong> - $horaire <br>
                <strong>Lieu</strong> - $lieu <br>
                <strong>Tarif</strong> - $tarif <br>
                <strong>Spectacles</strong> - $spectacles <br>
            </div>";
    }


    /**
     * Méthode renderLong qui permet d'afficher en format HTML long
     * Affichage du détail d’une soirée : nom de la soirée, thématique, date et horaire, lieu,
     * tarifs, ainsi que la liste des spectacles : titre, artistes, description, style de musique, vidéo,
     * @return string Un texte en format HTML
     * @throws \DateMalformedStringException
     */

    public function renderLong() : string {
        $nom = $this->soiree->getNom();
        $theme = $this->soiree->getThematique()->getNom();
        $date = $this->soiree->getDate();
        $heureD = $this->soiree->calculHeureDebut();
        $heureF = $this->soiree->calculHeureFin();
        $horaire = $heureD . "-" . $heureF;
        $lieu = $this->soiree->getLieu()->getNom();
        $tarif = $this->soiree->getTarif();
        $spectaclesListe = $this->soiree->getListeSpectacle();

        if (count($spectaclesListe) > 0) {
            $spectacles = "";
            foreach ($spectaclesListe as $spectacle) {
                $renderer = new SpectacleRenderer($spectacle);
                $spectacles .= $renderer->render(Renderer::LONG);
            }
        }
        else {
            $spectacles = "<p>Aucun spectacle pour cette soirée</p>";
        }
        return "<div id='soiree'>
                        <p><strong>$nom</strong> <br>
                        <strong>Thématique</strong> - $theme <br>
                        <strong>Date</strong> - $date <br>
                        <strong>Horraire</strong> - $horaire <br>
                        <strong>Lieu</strong> - $lieu <br>
                        <strong>Tarif</strong> - $tarif <br>
                        <strong>Spectacles</strong> - $spectacles <br>
                      </div>";
    }
}