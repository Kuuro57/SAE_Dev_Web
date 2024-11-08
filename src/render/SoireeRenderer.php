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
     * @param Soiree $soiree La soiree à afficher
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
     * cette méthode n'est pas utilisé
     * @return string Un texte en format HTML
     */
    public function renderCompact() : string {
        return "";
    }

    /**
     * Méthode renderLong qui permet d'afficher en format HTML long
     * Affichage du détail d’une soirée : nom de la soirée, thématique, date et horaire, lieu,
     * tarifs, ainsi que la liste des spectacles : titre, artistes, description, style de musique, vidéo,
     * @return string Un texte en format HTML
     */

    public function renderLong() : string {
        $nom = $this->soiree->getNom();
        $theme = $this->soiree->getThematique();
        $date = $this->soiree->getDate();
        $heureD = $this->soiree->getHeureDebut();
        $heureF = $this->soiree->getHeureFin();
        $horraire = $heureD . "-" . $heureF;
        $lieu = $this->soiree->getLieu();
        $tarif = $this->soiree->getTarif();
        $spectaclesListe = $this->soiree->getListeSpectacle();

        if (count($spectaclesListe) > 0) {
            $spectacles = "";
            foreach ($spectaclesListe as $spectacle) {
                $spectacles .= $spectacle->render(Renderer::LONG);
            }
        }
        else {
            $spectacles = "<p>Aucun spectacle pour cette soirée</p>";
        }




        $affichage = "<div id='soiree'>
                        <p><strong>$nom</strong> <br>
                        <strong>Thématique</strong> - $theme <br>
                        <strong>Date</strong> - $date <br>
                        <strong>Horraire</strong> - $horraire <br>
                        <strong>Lieu</strong> - $lieu <br>
                        <strong>Tarif</strong> - $tarif <br>
                        <strong>Spectacles</strong> - $spectacles <br>";



        return $affichage;

    }


}