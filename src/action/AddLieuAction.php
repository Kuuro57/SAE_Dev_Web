<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Artiste;
use iutnc\sae_dev_web\festival\Lieu;
use iutnc\sae_dev_web\repository\InsertRepository;
use PDOException;


/**
 * Classe qui représente l'action d'ajouter un lieu
 */
class AddLieuAction extends Action {

    private string $formulaire = '<form method="post" action="?action=add-lieu">
                        <input type="text" name="FnomLieu" placeholder="Nom" class="input-field" required autofocus>
                        <input type="text" name="Fadresse" placeholder="Adresse" class="input-field" required>
                        <input type="number" name="FplacesAssises" placeholder="Capacité Assise" class="input-field" required>
                        <input type="number" name="FplacesDebout" placeholder="Capacité Debout" class="input-field" required>
                        <input type="submit" name="connex" value="Ajouter" class="button">
                    </form>';

    public function execute(): string {
        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter un lieu</h1>' . $this->formulaire;
        } else {
            $nomLieu = filter_var($_POST['FnomLieu'],  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $adresse = filter_var($_POST['Fadresse'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $placesAssises = (int) filter_var($_POST['FplacesAssises'], FILTER_SANITIZE_NUMBER_INT);
            $placesDebout = (int) filter_var($_POST['FplacesDebout'], FILTER_SANITIZE_NUMBER_INT);

            $lieu = new Lieu(null, $nomLieu, $adresse, $placesAssises, $placesDebout);

            $db = InsertRepository::getInstance();
            try {
                $db->ajouterLieu($lieu);
                $res = '<h1>Lieu ajouté</h1>';
            } catch (PDOException $e) {
                $res = '<h1>Erreur lors de l\'ajout du lieu</h1>';
                echo $e->getMessage();
            }
        }
        return $res;
    }
}