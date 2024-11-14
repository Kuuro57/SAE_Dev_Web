<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;
use iutnc\sae_dev_web\repository\UpdateRepository;
use PDOException;

class AnnulerSpectacleAction extends Action {

    public function execute(): string {
        // Si la méthode est GET, on affiche le titre et le formulaire (donc getForm() ici)
        if ($this->http_method == "GET") {
            $res = '<h1> Annuler un spectacle </h1>' . $this->getForm();
        } else { // sinon (POST à priori)
            // on récupère les ID du spectacle, filtrés et castés
            $spectacleID = (int) filter_input(INPUT_POST, 'listeSpectacles', FILTER_SANITIZE_SPECIAL_CHARS);

            // On récupère les objets correspondants aux ID obtenus dans le formulaire
            $spectacleOb = SelectRepository::getInstance()->getSpectacle($spectacleID);
            /** @var     $spectacleOb Spectacle */
            $nomSpectacle = $spectacleOb->getNom();
            try {
                UpdateRepository::getInstance()->annulerSpectacle($spectacleOb);

                $res = "<p> Le spectacle $nomSpectacle à bien été annulé </p>";
            } catch (PDOException $e) { // Le repo peut lever une exception seulement si ATTR_ERRMODE vaux PDO::ERRMODE_EXCEPTION
                $res = "<p> Erreur lors de l\'annulation du spectacle $nomSpectacle à la soirée </p>";
                echo $e->getMessage();
            }
        }
        return $res;
    }

    private function getForm() : string {
        $listeSpectacle = SelectRepository::getInstance()->getSpectacles(null);

        // comboBox (liste déroulante) des Spectacles
        $listeDeroulanteSpectacles = '<select name="listeSpectacles"> <option value=""> -- Choisissez un spectacle -- </option>';
        // pour chaque spectacle dans l'array des specs en BD, on ajoute une option à la liste déroulante
        // les noms sont associés à des ID, mais seul le nom est affiché, et sa valeur est l'ID
        foreach ($listeSpectacle as $spectacle) {
            $listeDeroulanteSpectacles .= '<option value="' . $spectacle->getId() . '">' . $spectacle->getNom() . '</option>';
        }
        $listeDeroulanteSpectacles .= '</select>';




        // création du formulaire en soi
        // method="post" : submit le formulaire changera le HTML en mode POST
        // enctype="multipart/form-data" pour supporter les comboBox
        return <<<END
            <form method="post" name="" action="?action=annuler-spectacle" enctype="multipart/form-data">
                $listeDeroulanteSpectacles <br>
                <button type="submit" name="valider" class="button"> Valider </button>
            </form>
            END;

    }
}