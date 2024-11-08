<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Style;
use iutnc\sae_dev_web\repository\InsertRepository;
use PDOException;

/**
 * Classe qui représente l'action d'ajouter un style
 */
class AddStyleAction extends Action
{

    // Attribut
    private string $formulaire = '<form method="post" action="?action=add-style">
                        <input type="text" name="FnomStyle" placeholder="Nom du style" class="input-field" required autofocus>
                        <input type="submit" name="connex" value="Ajouter" class="button">
                        </form>';

    public function execute(): string {
        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter un style</h1>' . $this->formulaire;
        } else {
            $nomStyle = filter_var($_POST['FnomStyle'],  FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $style = new Style(null, $nomStyle);

            $db = InsertRepository::getInstance();
            try {
                $db->ajouterStyle($style);
                $res = '<h1>Style ajouté</h1>';
            } catch (PDOException $e) {
                $res = '<h1>Erreur lors de l\'ajout du style</h1>';
                echo $e->getMessage();
            }
        }
        return $res;
    }
}