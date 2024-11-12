<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;



/**
 * Classe qui représente une image
 */
class Image {

    // Attribut
    private ?int $idImage; // Id de l'image
    private int $idSpectacle; // Id du spectacle auquel appartient l'image
    private string $nomFichierImage; // Nom du fichier de l'image



    /**
     * Constructeur de la classe
     * @param int|null $idI Id de l'image
     * @param int $idS Id du spectacle
     * @param string $nomFichImg Nom du fichier de l'image
     */
    public function __construct(?int $idI, int $idS, string $nomFichImg) {
        $this->idImage = $idI;
        $this->idSpectacle = $idS;
        $this->nomFichierImage = $nomFichImg;
    }



    public function getIdImage(): ?int { return $this->idImage; }
    public function setIdImage(int $idImage): void { $this->idImage = $idImage; }

    public function getIdSpectacle(): int { return $this->idSpectacle; }

    public function getNomFichierImage(): string { return $this->nomFichierImage; }

}