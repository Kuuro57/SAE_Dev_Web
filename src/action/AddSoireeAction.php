<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Soiree;
use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;
use PDOException;


/**
 * Classe qui représente l'action d'ajouter un lieu
 */
class AddSoireeAction extends Action {

    public function getForm(): string {

        $listeLieu = SelectRepository::getInstance()->getLieux();
        $listeThematique = SelectRepository::getInstance()->getThematiques();

        $listeDeroulanteLieux = '<select name="listeLieux"> <option value=""> -- Choisissez un lieu -- </option>';
        foreach ($listeLieu as $lieu) {
            $listeDeroulanteLieux .= '<option value="' . $lieu->getId() . '">' . $lieu->getNom() . '</option>';
        }
        $listeDeroulanteLieux .= '</select>';

        $listeDeroulanteThematiques = '<select name="listeThematiques"> <option value=""> -- Choisissez une thématique -- </option>';
        foreach ($listeThematique as $thematique) {
            $listeDeroulanteThematiques .= '<option value="' . $thematique->getId() . '">' . $thematique->getNom() . '</option>';
        }
        $listeDeroulanteThematiques .= '</select>';

        return <<<END
            <form method="post" name="" action="?action=add-soiree" enctype="multipart/form-data">
                <input type="text" name="nomSoiree" placeholder="Nom de la soirée" required> <br>
                $listeDeroulanteLieux <br>
                $listeDeroulanteThematiques<br>
                <input type="number" name="tarif" placeholder="Tarif" required> <br>
                <input type ="date" name="dateSoiree" placeholder="Date de la soirée" required> <br>
                <button type="submit" name="valider" class="button"> Valider </button>
            </form>
            END;
        // TODO : Checkbox pour l'ajouter directement annulée ?
    }



    public function execute(): string {
        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter une soirée</h1>' . $this->getForm();

        } else {
            $nomSoiree = filter_input(INPUT_POST, 'nomSoiree', FILTER_SANITIZE_SPECIAL_CHARS);
            $lieu = (int) filter_input(INPUT_POST, 'listeLieux', FILTER_SANITIZE_SPECIAL_CHARS);
            $thematique = (int) filter_input(INPUT_POST, 'listeThematiques', FILTER_SANITIZE_SPECIAL_CHARS);
            $tarif = (float) filter_input(INPUT_POST, 'tarif', FILTER_SANITIZE_SPECIAL_CHARS);
            $dateSoiree = filter_input(INPUT_POST, 'dateSoiree', FILTER_SANITIZE_SPECIAL_CHARS);

            $lieuOb = SelectRepository::getInstance()->getLieu($lieu);
            $thematiqueOb = SelectRepository::getInstance()->getThematique($thematique);

            $soiree = new Soiree(null, $nomSoiree, $tarif, $lieuOb, $thematiqueOb, [], $dateSoiree);

            // try {
                InsertRepository::getInstance()->ajouterSoiree($soiree);
                $res = '<p> La soirée a bien été ajoutée ! </p>';
            //} catch (PDOException $e) {
            //    $res = '<p> La soirée n\'a pas pu être ajoutée ! </p>';
            //}
        }

        return $res;

    }
}