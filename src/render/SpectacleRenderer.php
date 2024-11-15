<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\render;

use DateInterval;
use DateTime;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\repository\SelectRepository;


/**
 * Classe qui permet de rendre un spectacle
 */
class SpectacleRenderer implements Renderer {

    // Attribut
    private Spectacle $spectacle;



    /**
     * Constructeur de la classe
     */
    public function __construct(Spectacle $spectacle) {

        $this->spectacle = $spectacle;
    }


    /**
     * Méthode qui permet d'afficher en format HTML un objet
     * @param int $selector Entier qui correspond au mode d'affichage
     * @return string Un texte en format HTML
     * @throws \DateMalformedStringException
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
     * Méthode renderCompact qui permet d'afficher en format HTML compact pour chaque spectacle, on affiche le titre, la date et
     * l’horaire, une image,
     * @return string Un texte en format HTML
     */

    public function renderCompact() : string {
        $heureD = SelectRepository::getInstance()->getHeureDebutSpectacle($this->spectacle->getId());
        $heureD = new DateTime($heureD);
        // Calcul des heures et minutes
        $dureeMinutes = $this->spectacle->getDuree();
        $heures = intdiv($dureeMinutes, 60);  // Nombre d'heures
        $minutes = $dureeMinutes % 60;              // Nombre de minutes restantes
        // Création d'un intervalle de temps pour la durée
        $dureeInterval = new DateInterval("PT{$heures}H{$minutes}M");
        // Si les minutes = 0
        if ($minutes === 0) {
            // On affiche pas les minutes
            $minutes = '';
        }
        // Ajout de la durée à l'heure de début pour obtenir l'heure de fin
        $heureF = (clone $heureD)->add($dureeInterval);
        $lieu = SelectRepository::getInstance()->getLieuSpectacle($this->spectacle->getId());
        if (!is_null($lieu)) {
            $lieu = $lieu->getNom();
        }
        else {
            $lieu = "Non défini";
        }
        $date = SelectRepository::getInstance()->getDateSpectacle($this->spectacle->getId());
        if (is_null($date)) {
            $date = "Non définie";
        }
        $imagestab = $this->spectacle->getListeImages();
        $images = "";
        if (count($imagestab) > 0) {
            // pour chaque image du tab images on affiche l'image balise img avec la src de l'image
            foreach ($imagestab as $image) {
                $images.="<img src='image/{$image->getNomFichierImage()}' alt='Image du spectacle'>";
            }
        }
        else {
            $images = "<p>Image non disponible</p>";
        }

        $annule = $this->isCancelled();

        return "
            <div id='spectacle'>
                <p>
                    <strong>{$this->spectacle->getNom()}</strong> <br>
                    <strong>Date</strong> - $date 
                </p>
                <p>
                    <strong>Heure</strong> - {$heureD->format('H:i')} / {$heureF->format('H:i')} <br>
                    <strong>Lieu</strong> - $lieu <br>
                    $annule
                </p>
                
                $images
            </div>";
    }



    /**
     * Méthode renderLong qui permet d'afficher en format HTML long pour chaque spectacle,
     *
     * Affichage détaillé d’un spectacle : titre, artistes, description, style, durée, image(s),
     * extrait audio/vidéo,
     * @throws \DateMalformedStringException
     */
    public function renderLong(): string
    {
        // Récupération de l'heure de début sous forme de DateTime
        $heureD = SelectRepository::getInstance()->getHeureDebutSpectacle($this->spectacle->getId());
        $heureD = new DateTime($heureD);
        // Calcul des heures et minutes
        $dureeMinutes = $this->spectacle->getDuree();
        $heures = intdiv($dureeMinutes, 60);  // Nombre d'heures
        $minutes = $dureeMinutes % 60;              // Nombre de minutes restantes
        // Création d'un intervalle de temps pour la durée
        $dureeInterval = new DateInterval("PT{$heures}H{$minutes}M");

        // Si les minutes = 0
        if ($minutes === 0) {
            // On affiche pas les minutes
            $minutes = '';
        }

        // Ajout de la durée à l'heure de début pour obtenir l'heure de fin
        $heureF = (clone $heureD)->add($dureeInterval);

        // le lieu est obtenu en utilisant la méthode getLieuSpectacle de la classe SelectRepository jointure avec Lieu, Soiree et Spectacle
        $lieu = SelectRepository::getInstance()->getLieuSpectacle($this->spectacle->getId());
        // Si le lieu n'est pas null
        if (!is_null($lieu)) {
            $nomLieu = $lieu->getNom();
        }
        // Sinon
        else {
            $nomLieu = "Non défini";
        }

        $artistes = $this->spectacle->getArtiste();
        $description = $this->spectacle->getDescription();

        $style = $this->spectacle->getStyle();
        if (!is_null($style)) {
            $style = $style->getNom();
        }
        else {
            $style = "Non défini";
        }


        $date = SelectRepository::getInstance()->getDateSpectacle($this->spectacle->getId());
        // Si la date n'est pas null
        if (is_null($date)) {
            $date = "Non définie";
        }

        $imagestab = $this->spectacle->getListeImages();
        $images = "";
        if (count($imagestab) > 0) {
            // pour chaque image du tab images on affiche l'image balise img avec la src de l'image
            foreach ($imagestab as $image) {
                $images.="<img src='image/{$image->getNomFichierImage()}' alt='Image du spectacle'>";

            }}
        else {
            $images = "<p>Image non disponible</p>";
        }

        // array des audio
        $audio = $this->spectacle->getListeAudios();
        // array des video
        $video = $this->spectacle->getListeVideos();
        $artiste = $this->spectacle->getArtiste()->getNom();

        $audioListe = "";
        $videoListe = "";

        // pour chaque audio du tab audio on affiche l'audio balise audio avec la src de l'audio
        foreach ($audio as $aud) {
            $audioListe.="<audio controls>
                <source src='audio/{$aud->getNomFichierAudio()}' type='audio/mpeg'>
                Your browser does not support the audio element.
            </audio>";
        }

        // pour chaque video du tab video on affiche la video balise video avec la src de la video
        foreach ($video as $vid) {
            $videoListe.="<video width='320' height='240' controls>
                <source src='video/{$vid->getUrl()}' type='video/mp4'>
                Your browser does not support the video tag.
            </video>";
        }

        $annule = $this->isCancelled();
        return "
            <div id='spectacle'>
                <p><strong>{$this->spectacle->getNom()}</strong> <br>
                <strong>Date</strong> - $date <br>
                <strong>Heure</strong> - {$heureD->format('H:i')} / {$heureF->format('H:i')} <br>
                <strong>Lieu</strong> - $nomLieu <br>
                <strong>Artistes</strong> - $artiste <br>
                <strong>Description</strong> - $description <br>
                <strong>Style</strong> - $style <br>
                <strong>Durée</strong> - {$heures}h{$minutes} <br>
                <strong>Images</strong> - $images <br>
                <strong>Audio</strong> - $audioListe <br>
                <strong>Video</strong> - $videoListe <br>
                $annule
            </div>
        ";
    }


    /**
     * Méthode qui permet de verifier si le spectacle est annulé
     */
    public function isCancelled() : string {
        $html = "";
        $estAnnule =  SelectRepository::getInstance()->getEstAnnuleSpectacle($this->spectacle->getId());
        if ($estAnnule === 1) {
            $html = "<p>Le spectacle est annulé</p>";
        }
        return $html;
    }


}