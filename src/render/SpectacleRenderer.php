<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\render;

use DateInterval;
use DateTime;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\repository\SelectRepository;

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



        $heureD = SelectRepository::getInstance()->getDebutSpectacle($this->spectacle->getId());
        $heureF =  $heureD + $this->spectacle->getDuree();
        $lieu = $this->spectacle->getLieu();
        $date = SelectRepository::getInstance()->getDateSpectacle($this->spectacle->getId());



        $imagestab = $this->spectacle->getListeImages();
        $images = "";
        if (count($imagestab) > 0) {
            // pour chaque image du tab images on affiche l'image balise img avec la src de l'image
            foreach ($imagestab as $image) {
                $images.="<img src='{$image}' alt='Image du spectacle'>";

            }}
        else {
            $images = "<p>Image non disponible</p>";
        }


        return "
            <div id='spectacle'>
                <p><strong>{$this->spectacle->getNom()}</strong> <br>
                <strong>Date</strong> - $date </p> <br>
                <p>
                    <strong>Heure</strong> - $heureD. ' - ' . $heureF <br>
                    <strong>Lieu</strong> - $lieu <br>
                    // pour chaque image du tab images on affiche l'image
                  
                </p>
                $images;
            </div>
        ";}


    /**
     * méthode renderLong qui permet d'afficher en format HTML long pour chaque spectacle,
     *
     * Affichage détaillé d’un spectacle : titre, artistes, description, style, durée, image(s),
     * extrait audio/vidéo,
     * @throws \DateMalformedStringException
     */

    public function renderLong() {
        // Récupération de l'heure de début sous forme de DateTime
        $heureD = SelectRepository::getInstance()->getHeureDebutSpectacle($this->spectacle->getId());
        $heureD = new DateTime($heureD);

        // Supposons que $this->spectacle->getDuree() retourne la durée en minutes
        $dureeMinutes = (int) $this->spectacle->getDuree();

        // Calcul des heures et minutes
        $heures = intdiv($dureeMinutes, 60);  // Nombre d'heures
        $minutes = $dureeMinutes % 60;              // Nombre de minutes restantes

        // Création d'un intervalle de temps pour la durée
        $dureeInterval = new DateInterval("PT{$heures}H{$minutes}M");

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

        $date = SelectRepository::getInstance()->getDateSpectacle($this->spectacle->getId());
        // Si la date n'est pas null
        if (is_null($date)) {
            $date = "Non définie";
        }

        $duree = $this->spectacle->getDuree();

        $imagestab = $this->spectacle->getListeImages();
        $images = "";
        if (count($imagestab) > 0) {
            // pour chaque image du tab images on affiche l'image balise img avec la src de l'image
            foreach ($imagestab as $image) {
                $images.="<img src='{$image->getNomFichierImage()}' alt='Image du spectacle'>";

            }}
        else {
            $images = "<p>Image non disponible</p>";
        }

        // array des audio
        $audio = $this->spectacle->getListeAudios();
        // array des video
        $video = $this->spectacle->getListeVideos();
        $artiste = $this->spectacle->getArtiste()->getNom();
        $style = $this->spectacle->getStyle()->getNom();

        $audioListe = "";
        $videoListe = "";

        // pour chaque audio du tab audio on affiche l'audio balise audio avec la src de l'audio
        foreach ($audio as $aud) {
            $audioListe.="<audio controls>
                <source src='{$aud->getNomFichierAudio()}' type='audio/mpeg'>
                Your browser does not support the audio element.
            </audio>";
        }

        // pour chaque video du tab video on affiche la video balise video avec la src de la video
        foreach ($video as $vid) {
            $videoListe.="<video width='320' height='240' controls>
                <source src='{$vid->getUrl()}' type='video/mp4'>
                Your browser does not support the video tag.
            </video>";
        }


        return "
            <div id='spectacle'>
                <p><strong>{$this->spectacle->getNom()}</strong> <br>
                <strong>Date</strong> - $date <br>
                <strong>Heure</strong> - {$heureD->format('H:i')} / {$heureF->format('H:i')} <br>
                <strong>Lieu</strong> - $nomLieu <br>
                <strong>Artistes</strong> - $artiste <br>
                <strong>Description</strong> - $description <br>
                <strong>Style</strong> - $style <br>
                <strong>Durée</strong> - $duree <br>
                <strong>Images</strong> - $images <br>
                <strong>Audio</strong> - $audioListe <br>
                <strong>Video</strong> - $videoListe <br>
            </div>
        ";




    }


}