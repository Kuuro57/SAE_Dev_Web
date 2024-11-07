<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;



/**
 * Classe qui représente un spectacle
 */
class Spectacle {

    // Attributs
    private ?int $id; // L'id du spectacle
    private string $nom; // Nom du spectacle
    private string $style; // Style du spectacle
    private string $artiste; // Nom de l'artiste qui se représente pendant ce spectacle
    private string $description; // Description du spectacle
    private string $nomFichierVideo; // Nom du fichier vidéo
    private string $nomFichierAudio; // Nom du fichier audio
    private string $nomFichierImage; // Nom du fichier de l'image qui représente le spectacle



    /**
     * @param int|null $i L'id du spectacle (null si le spectacle est créé pour la première fois)
     * @param string $n Nom du spectacle
     * @param string $s Style du spectacle
     * @param string $a Artiste qui se représente pendant ce spectacle
     * @param string $des Description du spectacle
     * @param string $nFichVid Nom du fichier vidéo
     * @param string $nFichAud Nom du fichier audio
     * @param string $img Nom du fichier de l'image
     */
    public function __construct(?int $i, string $n, string $s, string $a, string $des, string $nFichVid, string $nFichAud, string $img) {
        $this->id = $i;
        $this->nom = $n;
        $this->style = $s;
        $this->artiste = $a;
        $this->description = $des;
        $this->nomFichierVideo = $nFichVid;
        $this->nomFichierAudio = $nFichAud;
        $this->nomFichierImage = $img;
    }



    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): void { $this->nom = $nom; }

    public function getStyle(): string { return $this->style; }
    public function setStyle(string $style): void { $this->style = $style; }

    public function getArtiste(): string { return $this->artiste; }
    public function setArtiste(string $artiste): void { $this->artiste = $artiste; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }

    public function getNomFichierVideo(): string { return $this->nomFichierVideo; }
    public function setNomFichierVideo(string $nomFichierVideo): void { $this->nomFichierVideo = $nomFichierVideo; }

    public function getNomFichierAudio(): string { return $this->nomFichierAudio; }
    public function setNomFichierAudio(string $nomFichierAudio): void { $this->nomFichierAudio = $nomFichierAudio; }

    public function getNomFichierImage(): string { return $this->nomFichierImage; }
    public function setNomFichierImage(string $nomFichierImage): void { $this->nomFichierImage = $nomFichierImage; }


}