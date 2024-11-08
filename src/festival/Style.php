<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;


/**
 * Classe qui représente un style de spectacle
 */
class Style {

    // Attributs
    private ?int $id; // Id du style
    private string $nom; // Nom du style



    /**
     * Constructeur de la classe
     * @param int|null $i Id du style
     * @param string $n Nom du style
     */
    public function __construct(?int $i, string $n) {
        $this->id = $i;
        $this->nom = $n;
    }



    public function getId() : int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNom() : string { return $this->nom; }

}