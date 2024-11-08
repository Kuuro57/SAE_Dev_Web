<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;

class Soiree {

    // Attributs
    private ?int $id; // Id de la soirée
    private string $nom; // Nom de la soirée
    private Lieu $lieu; // Lieu où se déroule la soirée
    private Thematique  $thematique; // Thématique de la soirée
    private bool $estAnulee; // Vrai si la soirée est annulée, faux sinon
    private string $date; // Date à laquelle se déroule la soirée
    private string $heureDebut; // Heure de début de la soirée
    private string $heureFin; // Heure de fin de la soirée



    /**
     * Constructeur de la classe
     * @param int|null $i Id de la soirée
     * @param string $n Nom de la soirée
     * @param Lieu $l Lieu où se déroule la soirée
     * @param Thematique $t Thématique de la soirée
     * @param bool $eA Booléen qui détermine si la soirée est annulée ou non
     * @param string $d Date à laquelle se déroule la soirée
     * @param string $hD Heure de début
     * @param string $hF Heure de fin
     */
    public function __construct(?int $i, string $n, Lieu $l, Thematique $t, bool $eA = false, string $d, string $hD, string $hF) {
        $this->id = $i;
        $this->nom = $n;
        $this->lieu = $l;
        $this->thematique = $t;
        $this->estAnulee = $eA;
        $this->date = $d;
        $this->heureDebut = $hD;
        $this->heureFin = $hF;
    }

    public function getId() : int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNom() : string { return $this->nom; }

    public function getLieu(): Lieu { return $this->lieu; }

    public function getThematique(): Thematique { return $this->thematique; }

    public function getEstAnulee() : bool { return $this->estAnulee; }

    public function getDate() : string { return $this->date; }

    public function getHeureDebut() : string { return $this->heureDebut; }

    public function getHeureFin() : string { return $this->heureFin; }

}