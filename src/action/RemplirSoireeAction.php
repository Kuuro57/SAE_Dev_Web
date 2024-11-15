<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;
use PDOException;

class RemplirSoireeAction extends Action {

    public function execute(): string {
        // Si la méthode est GET, on affiche le titre et le formulaire (donc getForm() ici)
        if ($this->http_method == "GET") {
            $res = '<h1> Ajouter un spectacle à une soirée </h1>' . $this->getForm();
        } else { // sinon (POST à priori)
            // on récupère les ID du spectacle et de la soiree, filtrés et castés
            $spectacleID = (int) filter_input(INPUT_POST, 'listeSpectacles', FILTER_SANITIZE_SPECIAL_CHARS);
            $soireeID = (int) filter_input(INPUT_POST, 'listeSoirees', FILTER_SANITIZE_SPECIAL_CHARS);

            // On récupère les objets correspondants aux ID obtenus dans le formulaire
            $spectacleOb = SelectRepository::getInstance()->getSpectacle($spectacleID);
            $soireeOb = SelectRepository::getInstance()->getSoiree($soireeID);

            try {
                InsertRepository::getInstance()->ajouterSpectacleToSoiree($soireeOb, $spectacleOb);
                $res = '<p> Le spectacle à bien été ajouté à la soirée </p>';
            } catch (PDOException $e) { // Le repo peut lever une exception seulement si ATTR_ERRMODE vaux PDO::ERRMODE_EXCEPTION
                $res = '<p> Erreur lors de l\'ajout du spectacle à la soirée </p>';
                echo $e->getMessage();
            }
        }
        return $res;
    }

    private function getForm() : string {
        $listeSpectacle = SelectRepository::getInstance()->getSpectacles(null);
        $listeSoiree = SelectRepository::getInstance()->getSoirees(null);

        // comboBox (liste déroulante) des Spectacles
        $listeDeroulanteSpectacles = '<select name="listeSpectacles" class="input-field"> <option value=""> -- Choisissez un spectacle -- </option>';
        // pour chaque spectacle dans l'array des specs en BD, on ajoute une option à la liste déroulante
        // les noms sont associés à des ID, mais seul le nom est affiché, et sa valeur est l'ID
        foreach ($listeSpectacle as $spectacle) {
            $listeDeroulanteSpectacles .= '<option value="' . $spectacle->getId() . '">' . $spectacle->getNom() . '</option>';
        }
        $listeDeroulanteSpectacles .= '</select>';


        // comboBox (liste déroulante) des Spectacles
        $listeDeroulanteSoirees = '<select name="listeSoirees" class="input-field"> <option value=""> -- Choisissez une soiree -- </option>';
        // pour chaque soiree dans l'array des soirees en BD, on ajoute une option à la liste déroulante
        // les noms sont associés à des ID, mais seul le nom est affiché, et sa valeur est l'ID
        foreach ($listeSoiree as $soiree) {
            $listeDeroulanteSoirees .= '<option value="' . $soiree->getId() . '">' . $soiree->getNom() . '</option>';
        }
        $listeDeroulanteSoirees .= '</select>';

        // création du formulaire en soi
        // method="post" : submit le formulaire changera le HTML en mode POST
        // enctype="multipart/form-data" pour supporter les comboBox
        return <<<END
            <form method="post" name="" action="?action=remplir-soiree" enctype="multipart/form-data">
                $listeDeroulanteSpectacles <br>
                $listeDeroulanteSoirees<br>
                <button type="submit" name="valider" id="btn_connexion"> Valider </button>
            </form>
            END;

    }
}