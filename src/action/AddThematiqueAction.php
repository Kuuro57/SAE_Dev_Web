<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Thematique;
use iutnc\sae_dev_web\repository\InsertRepository;

class AddThematiqueAction extends Action {

    /*
     * Formulaire d'ajout d'une thématique
     */
    private string $formulaire = '<form method="post" action="?action=add-lieu">
                        <input type="text" name="nomT" placeholder="Nom de la thématique" class="input-field" required autofocus>
                        <input type="submit" name="connex" value="Ajouter" class="button">
                    </form>';

    public function execute(): string {

        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter une thématique</h1>' . $this->formulaire;
        } else {
            //  On récupère le nom passé dans le form
            $nomTheme = filter_var($_POST['nomT'],  FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $theme = new Thematique(null, $nomTheme);

            $db = InsertRepository::getInstance();
            try {
                $db->ajouterThematique($theme);
                $res = '<h1>Thématique ajoutée</h1>';
            } catch (\PDOException $e) {
                $res = '<h1>Erreur lors de lajout de la thématique</h1>';
            }
        }
        return $res;

    }
}