<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Artiste;
use iutnc\sae_dev_web\repository\InsertRepository;
use PDOException;

class AddArtisteAction extends Action {

    // formulaire statique : il sera toujours le même
    // method="post" : submit le formulaire changera le HTML en mode POST
    // tout les champs en required : impossible de submit sans tout avoir rempli
    private string $formulaire = '<form method="post" action="?action=add-artiste">
                        <input type="text" name="FnomArtiste" placeholder="Nom" class="input-field" required autofocus>
                        <input type="submit" name="connex" value="Ajouter" id="btn_connexion">
                    </form>';


    public function execute(): string
    {

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
            $res = '<h1> Ajouter un artiste </h1>' . $this->formulaire;
        } else { // sinon (POST à priori)
            // on récupère les données du formulaire, filtrées et castées
            $nomArtiste = filter_input(INPUT_POST, 'FnomArtiste', FILTER_SANITIZE_SPECIAL_CHARS);

            // On crée un nouvel objet Artiste avec les données récupérées
            $artiste = new Artiste(null, $nomArtiste);

            try {
                // On ajoute l'artiste à la base de données
                InsertRepository::getInstance()->ajouterArtiste($artiste);
                $res = '<p> L\'artiste à bien été ajouté </p>';
            } catch (PDOException $e) { // Le repo peut lever une exception seulement si ATTR_ERRMODE vaux PDO::ERRMODE_EXCEPTION
                $res = '<p> Erreur lors de l\'ajout de l\'artiste </p>';
                echo $e->getMessage();
            }
        }
        return $res;
    }
}