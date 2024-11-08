<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;
use PDOException;


/**
 * Classe qui représente l'action d'ajouter un lieu
 */
class AddSoireeAction extends Action {

    private string $formulaire;

    public function getForm(): string {
        $lieuOptions = $this->getLieuOptions();
        return '<form method="post" action="?action=add-soiree">
            <input type="text" name="FnomSoiree" placeholder="Nom" class="input-field" required autofocus>
            <select name="FidLieu" class="input-field" required>' . $lieuOptions . '</select>
            <input type="checkbox" name="FestAnnule" placeholder="Est annulé ?" required>
            <input type="date" name="FdateSoiree" placeholder="date soirée" required>
            <input type="time" name="FheureDebut" placeholder="Heure début" class="input-field" required>
            <input type="submit" name="connex" value="Ajouter" class="button">
        </form>';
    }

    private function getLieuOptions(): string {
        $repo = SelectRepository::getInstance();
        $lieux = $repo->getLieux();
        $options = '';
        foreach ($lieux as $lieu) {
            $options .= '<option value="' . htmlspecialchars($lieu['id']) . '">' . htmlspecialchars($lieu['nom']) . '</option>';
        }
        return $options;
    }

    public function execute(): string {
        if ($this->http_method == "GET") {
            $formulaires = $this->getForm();

            $res = '<h1>Ajouter une soirée</h1>' . $this->formulaire;
        } else {
            $nomSoiree = filter_var($_POST['FnomSoiree'], FILTER_SANITIZE_STRING);
            $idLieu = filter_var($_POST['FidLieu'], FILTER_SANITIZE_NUMBER_INT);
            $dateSoiree = filter_var($_POST['FdateSoiree'], FILTER_SANITIZE_STRING); // FILTRER DATE ?
            $heureDebut = filter_var($_POST['FheureDebut'], FILTER_SANITIZE_STRING); // FILTRER HEURE ?
            $estAnnule = filter_var($_POST['FestAnnule'], FILTER_SANITIZE_NUMBER_INT); // FILTRER BOOL ?



            $db = InsertRepository::getInstance();
            try {
                $db->ajouterSoiree($nomSoiree, $idLieu, $dateSoiree, $estAnnule);
                $res = '<h1>Soirée ajouté</hh1>';
            } catch (PDOException $e) {
                $res = '<h1>Erreur lors de lajout de la soiree</h1>';
            }
        }
        return $res;
    }
}
