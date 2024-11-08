<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;

class Thematique {

    // Attributs
    private ?int $id; // Id de la thématique
    private string $nom; // Nom de la thématique



    /**
     * Constructeur de la classe
     * @param int|null $i Id de la thématique
     * @param string $n Nom de la thématique
     */
    public function __construct(?int $i, string $n) {
        $this->id = $i;
        $this->nom = $n;
    }

    public function getId() : int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNom() : string { return $this->nom; }

}