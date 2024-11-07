<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

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
                        <input type="submit" name="connex" value="Connexion" class="button">
                    </form>';

    public function execute(): string {
        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter un lieu</h1>' . $this->formulaire;
        } else {
            $nomLieu = filter_var($_POST['FnomLieu'], FILTER_SANITIZE_STRING);
            $adresse = filter_var($_POST['Fadresse'], FILTER_SANITIZE_STRING);
            $placesAssises = filter_var($_POST['FplacesAssises'], FILTER_SANITIZE_NUMBER_INT);
            $placesDebout = filter_var($_POST['FplacesDebout'], FILTER_SANITIZE_NUMBER_INT);

            $db = InsertRepository::getInstance();
            try {
                $db->ajouterLieu($nomLieu, $adresse, $placesAssises, $placesDebout);
                $res = '<h1>Lieu ajouté</hh1>';
            } catch (PDOException $e) {
                $res = '<h1>Erreur lors de lajout du lieu</h1>';
            }
        }
        return $res;
    }
}