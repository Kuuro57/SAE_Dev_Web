<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;



/**
 * Classe qui représente un audio
 */
class Audio {

    // Attribut
    private ?int $idAudio; // Id de l'audio
    private int $idSpectacle; // Id du spectacle auquel appartient l'audio
    private string $nomFichierAudio; // Nom du fichier de l'audio



    /**
     * Constructeur de la classe
     * @param int|null $idA Id de l'audio
     * @param int $idS Id du spectacle
     * @param string $nomFichAudio Nom du fichier de l'audio
     */
    public function __construct(?int $idA, int $idS, string $nomFichAudio) {
        $this->idAudio = $idA;
        $this->idSpectacle = $idS;
        $this->nomFichierAudio = $nomFichAudio;
    }



    public function getIdAudio(): ?int { return $this->idAudio; }
    public function setIdAudio(?int $idAudio): void { $this->idAudio = $idAudio; }

    public function getIdSpectacle(): int { return $this->idSpectacle; }

    public function getNomFichierAudio(): string { return $this->nomFichierAudio; }

}