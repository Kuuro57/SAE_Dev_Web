<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\festival\Audio;
use iutnc\sae_dev_web\festival\Image;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\festival\Video;
use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;
use iutnc\sae_dev_web\repository\UpdateRepository;

/**
 * Classe qui représente l'action d'ajouter ou modifier un spectacle
 */
class ModifierInfoSpectacleAction extends Action {

    public function execute(): string {
        $updateRepo = UpdateRepository::getInstance();

        // Si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user'])) {
            return '<p>Vous n\'avez pas les permissions requises ! Connectez-vous à un compte STAFF ou ADMIN.</p>';
        }

        // Vérifier les permissions
        if ((int)$_SESSION['user']['role'] === 1) {
            return '<p>Vous n\'avez pas les permissions requises ! Connectez-vous à un compte STAFF ou ADMIN.</p>';
        }

        // Afficher le formulaire si la méthode est GET
        if ($this->http_method === 'GET') {
            return '<h1> Modifier un spectacle </h1>' . $this->getFormulaire();
        }
        // Traiter les données si la méthode est POST
        else if ($this->http_method === 'POST') {
            $id = filter_var($_POST['listeSpectacles'], FILTER_SANITIZE_SPECIAL_CHARS);

            // Charger le spectacle existant
            $spectacleExistant = SelectRepository::getInstance()->getSpectacle((int)$id);
            if (!$spectacleExistant) {
                return "<p>Erreur : Spectacle introuvable</p>";
            }

            // Récupérer les champs du formulaire ou utiliser les valeurs existantes
            $nomSpec = empty($_POST['nomSpec']) ? $spectacleExistant->getNom() : filter_var($_POST['nomSpec'], FILTER_SANITIZE_SPECIAL_CHARS);
            $style = empty($_POST['style']) ? $spectacleExistant->getStyle()->getId() : filter_var($_POST['style'], FILTER_SANITIZE_SPECIAL_CHARS);
            $artiste = empty($_POST['artiste']) ? $spectacleExistant->getArtiste()->getId() : filter_var($_POST['artiste'], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = empty($_POST['duree']) ? $spectacleExistant->getDuree() : filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT);
            $heureD = empty($_POST['heureD']) ? $spectacleExistant->getHeureDebut() : filter_var($_POST['heureD'], FILTER_SANITIZE_SPECIAL_CHARS);
            $description = empty($_POST['descSpec']) ? $spectacleExistant->getDescription() : filter_var($_POST['descSpec'], FILTER_SANITIZE_SPECIAL_CHARS);

            // Vérifier et traiter les fichiers envoyés
            $nomFichierVideo = isset($_FILES['fichierVideo']['tmp_name']) && is_uploaded_file($_FILES['fichierVideo']['tmp_name'])
                ? uniqid()
                : null;

            $nomFichierAudio = isset($_FILES['fichierAudio']['tmp_name']) && is_uploaded_file($_FILES['fichierAudio']['tmp_name'])
                ? uniqid()
                : null;

            $nomFichierImage = isset($_FILES['fichierImage']['tmp_name']) && is_uploaded_file($_FILES['fichierImage']['tmp_name'])
                ? uniqid()
                : null;



            // Création des objets correspondants si un fichier a été ajouté
            $videos = $nomFichierVideo ? [new Video(null, (int)$id, $nomFichierVideo)] : $spectacleExistant->getListeVideos();
            $audios = $nomFichierAudio ? [new Audio(null, (int)$id, $nomFichierAudio)] : $spectacleExistant->getListeAudios();
            $images = $nomFichierImage ? [new Image(null, (int)$id, $nomFichierImage)] : $spectacleExistant->getListeImages();

            // Construire l'objet Spectacle avec les nouvelles données
            $spectacle = new Spectacle(
                (int)$id,
                $nomSpec,
                SelectRepository::getInstance()->getStyle((int)$style),
                SelectRepository::getInstance()->getArtiste((int)$artiste),
                (int)$duree,
                $heureD,
                $description,
                $videos,
                $audios,
                $images
            );

            // Mettre à jour les données du spectacle
            $updateRepo->updateSpectacle($spectacle);
            return '<p>Le spectacle a bien été modifié !</p>';
        }

        return '<p>Erreur lors de la modification du spectacle.</p>';
    }





    private function getFormulaire(): string {
        $listeSpectacle = SelectRepository::getInstance()->getSpectacles(null);
        $listeDeroulanteSpectacles = '<select name="listeSpectacles" class="input-field"> <option value="">-- Choisissez un spectacle --</option>';
        foreach ($listeSpectacle as $spectacle) {
            $listeDeroulanteSpectacles .= '<option value="' . $spectacle->getId() . '">' . $spectacle->getNom() . '</option>';
        }
        $listeDeroulanteSpectacles .= '</select>';

        $listeArtistes = SelectRepository::getInstance()->getArtistes();
        $listeStyles = SelectRepository::getInstance()->getStyles();
        $listeDeroulanteArtistes = '<select name="artiste" class="input-field"><option value="0">-- Choisissez un artiste --</option>';
        foreach ($listeArtistes as $artiste) {
            $listeDeroulanteArtistes .= "<option value='{$artiste->getId()}'>{$artiste->getNom()}</option>";
        }
        $listeDeroulanteArtistes .= '</select>';
        $listeDeroulanteStyle = '<select name="style" class="input-field"><option value="0">-- Choisissez un style --</option>';
        foreach ($listeStyles as $style) {
            $listeDeroulanteStyle .= "<option value='{$style->getId()}'>{$style->getNom()}</option>";
        }
        $listeDeroulanteStyle .= '</select>';

        return <<<END
            <form method="post" action="?action=modifier-info-spectacle" enctype="multipart/form-data">
                <input type="text" name="nomSpec" placeholder="Nom du spectacle" class="input-field">
                $listeDeroulanteSpectacles
                $listeDeroulanteArtistes
                $listeDeroulanteStyle
                <input type="time" name="heureD" class="input-field">
                <input type="number" name="duree" min="0" placeholder="Durée en minutes" class="input-field">
                <input type="text" name="descSpec" placeholder="Description" class="input-field">
                <button type="submit" id="btn_connexion">Valider</button>
            </form>
        END;
    }
}
