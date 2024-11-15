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

    /** Formulaire dynamique qui va dépendre des lieux et thématique en BD -> méthode getForm()
     * @return string Le formulaire pour ajouter une soiree
     */
    public function getForm(): string {

        $listeLieu = SelectRepository::getInstance()->getLieux(); // array des lieux en BD
        $listeThematique = SelectRepository::getInstance()->getThematiques(); // array des thematiques en BD

        // comboBox (liste déroulante) des lieux
        $listeDeroulanteLieux = '<select name="listeLieux"> <option value=""> -- Choisissez un lieu -- </option>';
        // pour chaque lieu dans l'array des lieux en BD, on ajoute une option à la liste déroulante
        // Les noms sont associés à des ID, mais seul le nom est affiché, et sa valeur est l'ID
        foreach ($listeLieu as $lieu) {
            $listeDeroulanteLieux .= '<option value="' . $lieu->getId() . '">' . $lieu->getNom() . '</option>';
        }
        $listeDeroulanteLieux .= '</select>';

        // comboBox (liste déroulante) des thematiques
        $listeDeroulanteThematiques = '<select name="listeThematiques"> <option value=""> -- Choisissez une thématique -- </option>';
        // pour chaque thematique dans l'array des thematiques en BD, on ajoute une option à la liste déroulante
        // Les noms sont associés à des ID, mais seul le nom est affiché, et sa valeur est l'ID
        foreach ($listeThematique as $thematique) {
            $listeDeroulanteThematiques .= '<option value="' . $thematique->getId() . '">' . $thematique->getNom() . '</option>';
        }
        $listeDeroulanteThematiques .= '</select>';

        // création du formulaire en soi
        // method="post" : submit le formulaire changera le HTML en mode POST
        // tout les champs en required : impossible de submit sans tout avoir rempli
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

        // Si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user'])) {
            // On renvoie un message comme quoi il n'a pas les permissions
            return '<p> Vous n\'avez pas les permissions requises ! Connectez-vous à un compte STAFF ou ADMIN </p>';

        }
        // Sinon
        else {
            // Si le compte à les permissions STANDARD
            if ((int)$_SESSION['user']['role'] === 1) {
                // On renvoie un message comme quoi il n'a pas les permissions
                return '<p> Vous n\'avez pas les permissions requises ! Connectez-vous à un compte STAFF ou ADMIN </p>';
            }
        }



        // Si la méthode est GET, on affiche le titre et le formulaire (donc getForm() ici)
        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter une soirée</h1>' . $this->getForm();
        } else { // sinon (post a priori)
            // On récupère les 5 valeurs saisies dans le formulaire, filtrées et castées si nécessaire
            $nomSoiree = filter_input(INPUT_POST, 'nomSoiree', FILTER_SANITIZE_SPECIAL_CHARS);
            $lieu = (int) filter_input(INPUT_POST, 'listeLieux', FILTER_SANITIZE_SPECIAL_CHARS);
            $thematique = (int) filter_input(INPUT_POST, 'listeThematiques', FILTER_SANITIZE_SPECIAL_CHARS);
            $tarif = (float) filter_input(INPUT_POST, 'tarif', FILTER_SANITIZE_SPECIAL_CHARS);
            $dateSoiree = filter_input(INPUT_POST, 'dateSoiree', FILTER_SANITIZE_SPECIAL_CHARS);

            // On récupère les objets correspondants aux ID selectionnés dans les comboBox
            $lieuOb = SelectRepository::getInstance()->getLieu($lieu);
            $thematiqueOb = SelectRepository::getInstance()->getThematique($thematique);

            // création de l'objet Soiree à insérer en BD
            $soiree = new Soiree(null, $nomSoiree, $tarif, $lieuOb, $thematiqueOb, [], $dateSoiree); // l'array vide est la liste des spectacles

            try {
                // La BD ajoutera elle-même l'ID de la soirée (auto incrément)
                InsertRepository::getInstance()->ajouterSoiree($soiree);
                $res = '<p> La soirée a bien été ajoutée ! </p>';
            } catch (PDOException $e) { // Le repo peut lever une exception seulement si ATTR_ERRMODE vaux PDO::ERRMODE_EXCEPTION
                $res = '<p> La soirée n\'a pas pu être ajoutée ! </p>';
                echo $e->getMessage();}
        }
        return $res;
    }
}