<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;

class Lieu {

    // Attributs
    private ?int $id; // Id du lieu
    private string $nom; // Nom du lieu
    private string $adresse; // Adresse du lieu
    private int $nbPlacesAssises; // Nombre de places assises du lieu
    private int $nbPlacesDebout; // Nombre de places debout du lieu



    /**
     * Constructeur de la classe
     * @param int|null $i Id du lieu
     * @param string $n Nom du lieu
     * @param string $a Adresse du lieu
     * @param int $nbPlAs Nombre de places assises
     * @param int $nbPlDeb Nombre de places debout
     */
    public function __construct(?int $i, string $n, string $a, int $nbPlAs, int $nbPlDeb) {
        $this->id = $i;
        $this->nom = $n;
        $this->adresse = $a;
        $this->nbPlacesAssises = $nbPlAs;
        $this->nbPlacesDebout = $nbPlDeb;
    }

    public function getId() : int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNom() : string { return $this->nom; }

    public function getAdresse() : string { return $this->adresse; }

    public function getNbPlacesAssises() : int { return $this->nbPlacesAssises; }

    public function getNbPlacesDebout() : int { return $this->nbPlacesDebout; }

}