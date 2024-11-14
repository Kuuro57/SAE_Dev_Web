<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Lieu;
use iutnc\sae_dev_web\repository\InsertRepository;
use PDOException;


/**
 * Classe qui représente l'action d'ajouter un lieu
 */
class AddLieuAction extends Action {

    // formulaire statique : il sera toujours le même
    // method="post" : submit le formulaire changera le HTML en mode POST
    // tout les champs en required : impossible de submit sans tout avoir rempli
    private string $formulaire = '<form method="post" action="?action=add-lieu">
                        <input type="text" name="FnomLieu" placeholder="Nom" class="input-field" required autofocus>
                        <input type="text" name="Fadresse" placeholder="Adresse" class="input-field" required>
                        <input type="number" name="FplacesAssises" placeholder="Capacité Assise" class="input-field" required>
                        <input type="number" name="FplacesDebout" placeholder="Capacité Debout" class="input-field" required>
                        <input type="submit" name="connex" value="Ajouter" class="button">
                    </form>';

    public function execute(): string {
        // Si la méthode est GET, on affiche le titre et le formulaire
        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter un lieu</h1>' . $this->formulaire;
        } else { // sinon (donc POST)
            // On récupère les 4 valeurs saisies dans le formulaires, filtrées et castées si nécessaire
            $nomLieu = filter_var($_POST['FnomLieu'],  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $adresse = filter_var($_POST['Fadresse'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $placesAssises = (int) filter_var($_POST['FplacesAssises'], FILTER_SANITIZE_NUMBER_INT);
            $placesDebout = (int) filter_var($_POST['FplacesDebout'], FILTER_SANITIZE_NUMBER_INT);

            // On crée un objet lieu à partir des valeurs saisies
            $lieu = new Lieu(null, $nomLieu, $adresse, $placesAssises, $placesDebout);

            // recup du repository d'insertion
            $db = InsertRepository::getInstance();
            try {
                // La BD choisira elle-même l'ID du lieu (auto incrément)
                $db->ajouterLieu($lieu);
                $res = '<h1>Lieu ajouté</h1>';
            } catch (PDOException $e) { // Le repo peut lever une exception seulement si ATTR_ERRMODE vaux PDO::ERRMODE_EXCEPTION
                $res = '<h1>Erreur lors de l\'ajout du lieu</h1>';
                echo $e->getMessage();
            }
        }
        return $res;
    }
}