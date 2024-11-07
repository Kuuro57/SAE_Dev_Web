<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;

class Artiste {

    // Attributs
    private string $id; // L'id de l'artiste
    private string $nom; // Nom de l'artiste



    /**
     * Constructeur de la classe
     * @param string $i Id de l'artiste
     * @param string $n Nom de l'artiste
     */
    public function __construct(string $i, string $n) {
        $this->id = $i;
        $this->nom = $n;
    }

    public function getId() : string { return $this->id; }
    public function setId(string $id) : void { $this->id = $id; }

    public function getNom() : string { return $this->nom; }
    public function setNom(string $nom) : void { $this->nom = $nom; }


}