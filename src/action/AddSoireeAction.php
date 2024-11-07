<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\repository\InsertRepository;
use PDOException;


/**
 * Classe qui représente l'action d'ajouter un lieu
 */
class AddSoireeAction extends Action {

    private string $formulaire =
        '<form method="post" action="?action=add-soiree">
        <input type="text" name="FnomSoiree" placeholder="Nom" class="input-field" required autofocus>' .
        // ComboBox DropDown de touts les lieux
        '<input type="checkbox" name="FestAnnule" placeholder="Est annulé ?" required>
        <input type="date" name="FdateSoiree" placeholder="date soirée">
        <input type="time" name="FheureDebut" placeholder="Heure début" class="input-field" required>
        <input type="submit" name="connex" value="Ajouter" class="button">
        </form>';

    public function execute(): string {
        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter une soirée</h1>' . $this->formulaire;
        } else {
            $nomSoiree = filter_var($_POST['FnomSoiree'], FILTER_SANITIZE_STRING);
            // TODO $idLieu = ??
            $estAnnule = filter_var($_POST['FestAnnule'], FILTER_SANITIZE_NUMBER_INT); // FILTRER BOOL ?
            $dateSoiree = filter_var($_POST['FdateSoiree'], FILTER_SANITIZE_STRING); // FILTRER DATE ?
            $heureDebut = filter_var($_POST['FheureDebut'], FILTER_SANITIZE_STRING); // FILTRER HEURE ?



            $db = InsertRepository::getInstance();
            try {
                $db->ajouterSoiree($nomSoiree, $idLieu, $estAnnule, $dateSoiree, $heureDebut);
                $res = '<h1>Soirée ajouté</hh1>';
            } catch (PDOException $e) {
                $res = '<h1>Erreur lors de lajout de la soiree</h1>';
            }
        }
        return $res;
    }
}