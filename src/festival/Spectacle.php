<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;



/**
 * Classe qui représente un spectacle
 */
class Spectacle {

    // Attributs
    private int $id; // L'id du spectacle
    private string $nom; // Nom du spectacle
    private string $style; // Style du spectacle
    private string $artiste; // Nom de l'artiste qui se représente pendant ce spectacle
    private int $duree; // Durée du spectacle
    private string $description; // Description du spectacle
    private string $nomFichierVideo; // Nom du fichier vidéo
    private string $nomFichierAudio; // Nom du fichier audio



    /**
     * @param int|null $i L'id du spectacle (null si le spectacle est créé pour la première fois)
     * @param string $n Nom du spectacle
     * @param string $s Style du spectacle
     * @param string $a Artiste qui se représente pendant ce spectacle
     * @param int|null $d Durée du spectacle
     * @param string $des Description du spectacle
     * @param string $nFichVid Nom du fichier vidéo
     * @param string $nFichAud Nom du fichier audio
     */
    public function __construct(?int $i, string $n, string $s, string $a, ?int $d, string $des, string $nFichVid, string $nFichAud) {
        $this->id = $i;
        $this->nom = $n;
        $this->style = $s;
        $this->artiste = $a;
        $this->duree = $d;
        $this->description = $des;
        $this->nomFichierVideo = $nFichVid;
        $this->nomFichierAudio = $nFichAud;
    }




}