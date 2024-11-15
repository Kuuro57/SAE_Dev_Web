<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Thematique;
use iutnc\sae_dev_web\repository\InsertRepository;

class AddThematiqueAction extends Action {

    /*
     * Formulaire d'ajout d'une thématique
     */
    private string $formulaire = '<form method="post" action="?action=add-theme">
                        <input type="text" name="nomT" placeholder="Nom de la thématique" class="input-field" required autofocus>
                        <input type="submit" name="connex" value="Ajouter" id="btn_connexion">
                    </form>';

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



        if ($this->http_method == "GET") {
            $res = '<h1>Ajouter une thématique</h1>' . $this->formulaire;
        } else {
            //  On récupère le nom passé dans le form
            $nomTheme = filter_var($_POST['nomT'],  FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $db = InsertRepository::getInstance();

            $theme = new Thematique(null, $nomTheme);

            try {
                $db->ajouterThematique($theme);
                $res = '<h1>Thématique ajoutée</h1>';
            } catch (\PDOException $e) {
                $res = '<h1>Erreur lors de lajout de la thématique</h1>' . $e->getMessage();
            }
        }
        return $res;

    }
}