<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;



/**
 * Classe qui représente un spectacle
 */
class Spectacle {

    // Attributs
    private ?int $id; // L'id du spectacle
    private string $nom; // Nom du spectacle
    private Style $style; // Style du spectacle
    private Artiste $artiste; // Nom de l'artiste qui se représente pendant ce spectacle
    private ?int $duree; // Duree du spectacle (en minutes)
    private string $description; // Description du spectacle
    private array $listeVideos; // Liste qui contient les URLs des vidéos
    private array $listeAudios; // Liste qui contient le nom des fichiers audios
    private array $listeImages; // Liste qui contient les noms des fichiers des images qui représentent
                                // le spectacle



    /**
     * Constructeur de la classe
     * @param int|null $i Id du spectacle
     * @param string $n Nom du spectacle
     * @param Style $s Style du spectacle
     * @param Artiste $a Artiste qui se réprésente dans ce spectacle
     * @param int|null $du Durée du spectacle
     * @param string $de Description du spectacle
     * @param Video[] $lV Liste qui contient les URLs des vidéos du spectacle
     * @param Audio[] $lA Liste qui contient les noms des fichiers audio du spectacle
     * @param Image[] $lI Liste qui contient les noms des fichiers des images du spectacle
     */
    public function __construct(?int $i, string $n, Style $s, Artiste $a, ?int $du, string $de, array $lV, array $lA, array $lI) {
        $this->id = $i;
        $this->nom = $n;
        $this->style = $s;
        $this->artiste = $a;
        $this->duree = $du;
        $this->description = $de;
        $this->listeVideos = $lV;
        $this->listeAudios = $lA;
        $this->listeImages = $lI;
    }




    public function getId(): int { return $this->id; }

    public function getNom(): string { return $this->nom; }

    public function getStyle(): Style { return $this->style; }

    public function getArtiste(): Artiste { return $this->artiste; }

    public function getDuree(): int { return $this->duree; }
    public function setDuree(int $duree): void { $this->duree = $duree; }

    public function getDescription(): string { return $this->description; }

    public function getListeVideos(): array { return $this->listeVideos; }

    public function getListeAudios(): array { return $this->listeAudios; }

    public function getListeImages(): array { return $this->listeImages; }


}