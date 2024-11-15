<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;

use DateTime;

/**
 * Classe qui représente une soiree
 */
class Soiree {

    // Attributs
    private ?int $id; // Id de la soirée
    private string $nom; // Nom de la soirée
    private float $tarif; // Tarif de la soirée
    private Lieu $lieu; // Lieu où se déroule la soirée
    private Thematique $thematique; // Thématique de la soirée
    private array $listeSpectacle; // Liste qui contient tous les spectacles présents dans la soirée
    private bool $estAnnulee; // Vrai si la soirée est annulée, faux sinon
    private string $date; // Date à laquelle se déroule la soirée



    /**
     * Constructeur de la classe
     * @param int|null $i Id de la soirée
     * @param string $n Nom de la soirée
     * @param float $ta Tarif de la soirée
     * @param Lieu $l Lieu où se déroule la soirée
     * @param Thematique $t Thématique de la soirée
     * @param Spectacle[] $lS Liste qui contient tous les spectacles présents dans la soirée
     * @param string $d Date à laquelle se déroule la soirée
     * @param bool $eA Booléen qui détermine si la soirée est annulée ou non
     */
    public function __construct(?int $i, string $n, float $ta, Lieu $l, Thematique $t, array $lS, string $d, bool $eA = false) {
        $this->id = $i;
        $this->nom = $n;
        $this->tarif = $ta;
        $this->lieu = $l;
        $this->thematique = $t;
        $this->listeSpectacle = $lS;
        $this->date = $d;
        $this->estAnnulee = $eA;
    }


    /**
     * Méthode qui calcul l'heure de début de la soirée
     * @return string|null Heure la plus tôt de tous les spectacles
     * @throws \DateMalformedStringException
     */
    public function calculHeureDebut() : ?string {

        // On initialise l'heure minimale avec une valeur très grande
        $heureMin = new DateTime('23:59:59');

        // Si il n'y a aucun spectacles dans cette soirée
        if (empty($this->listeSpectacle)) {
            // On renvoie un message disant qu'il n'y a pas d'heures de début
            return 'N/A';
        }
        // Parcours de la liste des spectacles pour trouver l'heure la plus tôt
        foreach ($this->listeSpectacle as $spectacle) {
            $heure = new DateTime($spectacle->getHeureDebut());
            if ($heure < $heureMin) {
                $heureMin = $heure;
            }
        }

        // Retourne l'heure de début
        return $heureMin->format('H:i');
    }


    /**
     * Méthode qui calcul l'heure de fin de la soirée en ajoutant la durée du spectacle
     * @return string Heure la plus tard de tous les spectacles
     * @throws \DateMalformedStringException
     */
    public function calculHeureFin() : string {

        // On initialise l'heure maximale avec une valeur très basse
        $heureMax = new DateTime('00:00:00');
        $dureeMax = 0;

        // Si il n'y a aucun spectacles dans cette soirée
        if (empty($this->listeSpectacle)) {
            // On renvoie un message disant qu'il n'y a pas d'heures de fin
            return 'N/A';
        }

        // Parcours de la liste des spectacles pour trouver l'heure la plus tard et sa durée
        foreach ($this->listeSpectacle as $spectacle) {
            $heure = new DateTime($spectacle->getHeureDebut());
            if ($heure > $heureMax) {
                $heureMax = $heure;
                $dureeMax = $spectacle->getDuree(); // Durée en minutes
            }
        }

        // Ajoute la durée du spectacle le plus tardif
        $heureMax->modify("+{$dureeMax} minutes");

        // Retourne l'heure de fin
        return $heureMax->format('H:i');
    }



    public function getId() : int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNom() : string { return $this->nom; }

    public function getTarif(): float { return $this->tarif; }

    public function getLieu(): Lieu { return $this->lieu; }

    public function getThematique(): Thematique { return $this->thematique; }

    public function getListeSpectacle(): ?array { return $this->listeSpectacle; }

    public function getDate() : string { return $this->date; }

    public function getEstAnnule() : bool { return $this->estAnnulee; }

}