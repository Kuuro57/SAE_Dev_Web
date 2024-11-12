<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;

/**
 * Classe qui représente un artiste
 */
class Artiste {

    // Attributs
    private int $id; // L'id de l'artiste
    private string $nom; // Nom de l'artiste



    /**
     * Constructeur de la classe
     * @param int $i Id de l'artiste
     * @param string $n Nom de l'artiste
     */
    public function __construct(int $i, string $n) {
        $this->id = $i;
        $this->nom = $n;
    }

    public function getId() : int { return $this->id; }

    public function getNom() : string { return $this->nom; }

}