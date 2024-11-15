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
 * Classe qui représente l'action d'ajouter un spectacle
 */
class ModifierSpectacleAction extends Action {



    public function execute(): string {
        $updateRepo =UpdateRepository::getInstance();

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



        // Si la méthode HTTP est de type GET
        if ($this->http_method === 'GET') {

            // Afficher un formulaire d'ajout d'un spectacle
            return $this->getFormulaire();

        }
        // Sinon si la méthode HTTP est de type POST
        else if ($this->http_method === 'POST') {

            $id = filter_var($_POST['listeSpectacles'], FILTER_SANITIZE_SPECIAL_CHARS);

            // Charger le spectacle existant pour obtenir ses valeurs actuelles
            $spectacleExistant = SelectRepository::getInstance()->getSpectacle((int) $id);

            if (!$spectacleExistant) {
                return "<p>Erreur : Spectacle introuvable</p>";
            }

            // Récupérer les champs du formulaire, ou conserver les valeurs existantes
            $nomSpec = empty($_POST['nomSpec']) ? $spectacleExistant->getNom() : filter_var($_POST['nomSpec'], FILTER_SANITIZE_SPECIAL_CHARS);
            $style = empty($_POST['style']) ? $spectacleExistant->getStyle()->getId() : filter_var($_POST['style'], FILTER_SANITIZE_SPECIAL_CHARS);
            $artiste = empty($_POST['artiste']) ? $spectacleExistant->getArtiste()->getId() : filter_var($_POST['artiste'], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = empty($_POST['duree']) ? $spectacleExistant->getDuree() : filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT);
            $heureD = empty($_POST['heureD']) ? $spectacleExistant->getHeureDebut() : filter_var($_POST['heureD'], FILTER_SANITIZE_SPECIAL_CHARS);
            $description = empty($_POST['descSpec']) ? $spectacleExistant->getDescription() : filter_var($_POST['descSpec'], FILTER_SANITIZE_SPECIAL_CHARS);

            // Vérifier les fichiers (si non envoyés, conserver les anciens noms de fichiers)
            $nomFichierVideo = isset($_FILES['fichierVideo']['tmp_name']) && is_uploaded_file($_FILES['fichierVideo']['tmp_name'])
                ? uniqid()
                : $spectacleExistant->getListeVideos()[0] ?? null;

            $nomFichierAudio = isset($_FILES['fichierAudio']['tmp_name']) && is_uploaded_file($_FILES['fichierAudio']['tmp_name'])
                ? uniqid()
                : $spectacleExistant->getListeAudios()[0] ?? null;

            $nomFichierImage = isset($_FILES['fichierImage']['tmp_name']) && is_uploaded_file($_FILES['fichierImage']['tmp_name'])
                ? uniqid()
                : $spectacleExistant->getListeImages()[0] ?? null;

            // Vérifier et déplacer les fichiers uniquement si de nouveaux fichiers ont été envoyés
            if ($nomFichierVideo && !$this->verifFichierVideo($nomFichierVideo)) {
                return '<p> Le fichier vidéo n\'est pas accepté ! </p>';
            }
            if ($nomFichierAudio && !$this->verifFichierAudio($nomFichierAudio)) {
                return '<p> Le fichier audio n\'est pas accepté ! </p>';
            }
            if ($nomFichierImage && !$this->verifFichierImage($nomFichierImage)) {
                return '<p> Le fichier image n\'est pas accepté ! </p>';
            }

            // Construire l'objet Spectacle mis à jour
            $spectacle = new Spectacle(
                (int)$id,
                $nomSpec,
                SelectRepository::getInstance()->getStyle((int) $style),
                SelectRepository::getInstance()->getArtiste((int) $artiste),
                (int) $duree,
                $heureD,
                $description,
                $nomFichierImage ? [$nomFichierImage] : $spectacleExistant->getListeImages(),
                $nomFichierAudio ? [$nomFichierAudio] : $spectacleExistant->getListeAudios(),
                $nomFichierVideo ? [$nomFichierVideo] : $spectacleExistant->getListeVideos()
            );

            // Mettre à jour les données du spectacle
            UpdateRepository::getInstance()->updateSpectacle($spectacle);




    }return '<p> Le spectacle a bien été modifié ! </p>';}



    /**
     * Méthode qui vérifie l'extension d'un fichier vidéo donné par l'utilisateur et qui ajoute ce fichier
     * à l'arborescence pour pouvoir le stocker
     * @param string $nomFichier Nom du fichier que l'on veut tester
     * @return bool Vrai si le fichier est bon et stocké dans l'arborescence, faux sinon
     */
    private function verifFichierVideo(string $nomFichier) : bool {
        // Si aucun fichier n'a été envoyé
        if (count($_FILES) === 0) {
            // On renvoie false
            return false;
        }
        // Si le fichier a été envoyé par la méthode POST
        if (is_uploaded_file($_FILES['fichierVideo']['tmp_name'])) {
            // Si l'extension du fichier n'est pas en .mp4
            if (!(str_ends_with($_FILES['fichierVideo']['name'], '.mp4'))) {
                // On renvoie false
                return false;
            }
            // On met comme type au fichier audio 'audio/mpeg-4'
            $_FILES['fichierVideo']['type'] = 'audio/mpeg-4';
            // On met ce fichier dans le répertoire /audio
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/SAE_Dev_Web/video/' . $nomFichier . '.mp4';
            move_uploaded_file($_FILES['fichierVideo']['tmp_name'], $dir);
            // On renvoie true (fichier correct)
            return true;
        }
        // Sinon
        else {
            // On renvoie false (fichier incorrect)
            return false;
        }
    }



    /**
     * Méthode qui vérifie l'extension d'un fichier audio donné par l'utilisateur et qui ajoute ce fichier
     * à l'arborescence pour pouvoir le stocker
     * @param string $nomFichier Nom du fichier que l'on veut tester
     * @return bool Vrai si le fichier est bon et stocké dans l'arborescence, faux sinon
     */
    private function verifFichierAudio(string $nomFichier) : bool {
        // Si aucun fichier n'a été envoyé
        if (count($_FILES) === 0) {
            // On renvoie false
            return false;
        }
        // Si le fichier a été envoyé par la méthode POST
        if (is_uploaded_file($_FILES['fichierAudio']['tmp_name'])) {
            // Si l'extension du fichier n'est pas en .mp3
            if (!(str_ends_with($_FILES['fichierAudio']['name'], '.mp3'))) {
                // On renvoie false
                return false;
            }
            // On met comme type au fichier audio 'audio/mpeg'
            $_FILES['fichierAudio']['type'] = 'audio/mpeg';
            // On met ce fichier dans le répertoire /audio
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/SAE_Dev_Web/audio/' . $nomFichier . '.mp3';
            move_uploaded_file($_FILES['fichierAudio']['tmp_name'], $dir);
            // On renvoie true (fichier correct)
            return true;
        }
        // Sinon
        else {
            // On renvoie false (fichier incorrect)
            return false;
        }
    }



    /**
     * Méthode qui vérifie l'extension d'un fichier image donné par l'utilisateur et qui ajoute ce fichier
     * à l'arborescence pour pouvoir le stocker
     * @param string $nomFichier Nom du fichier que l'on veut tester
     * @return bool Vrai si le fichier est bon et stocké dans l'arborescence, faux sinon
     */
    private function verifFichierImage(string $nomFichier) : bool {
        // Si aucun fichier n'a été envoyé
        if (count($_FILES) === 0) {
            // On renvoie false
            return false;
        }
        // Si le fichier a été envoyé par la méthode POST
        if (is_uploaded_file($_FILES['fichierImage']['tmp_name'])) {
            // Si l'extension du fichier n'est pas en .png
            if (!(str_ends_with($_FILES['fichierImage']['name'], '.png'))) {
                // On renvoie false
                return false;
            }
            // On met comme type au fichier image/png
            $_FILES['fichierImage']['type'] = 'image/png';
            // On met ce fichier dans le répertoire /image
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/SAE_Dev_Web/image/' . $nomFichier . '.png';
            move_uploaded_file($_FILES['fichierImage']['tmp_name'], $dir);
            // On renvoie true (fichier correct)
            return true;
        }
        // Sinon
        else {
            // On renvoie false (fichier incorrect)
            return false;
        }
    }



    /**
     * Méthode qui retourne le formulaire complété (avec tous les artistes et style que l'administrateur peut choisir)
     * @return string Le formulaire au format HTML
     */
    private function getFormulaire() : string {

        $listeSpectacle = SelectRepository::getInstance()->getSpectacles(null);

        // comboBox (liste déroulante) des Spectacles
        $listeDeroulanteSpectacles = '<select name="listeSpectacles" class="input-field"> <option value=""> -- Choisissez un spectacle -- </option>';
        // pour chaque spectacle dans l'array des specs en BD, on ajoute une option à la liste déroulante
        // les noms sont associés à des ID, mais seul le nom est affiché, et sa valeur est l'ID
        foreach ($listeSpectacle as $spectacle) {
            $listeDeroulanteSpectacles .= '<option value="' . $spectacle->getId() . '">' . $spectacle->getNom() . ' ' . $spectacle->getId() . '</option>';
        }
        $listeDeroulanteSpectacles .= '</select>';




        // On récupère la liste de tous les artistes dans la BDD
        $listeArtistes = $this->selectRepo->getArtistes();
        // On récupère la liste de tous les styles dans la BDD
        $listeStyles = $this->selectRepo->getStyles();
        // On crée la liste déroulante pour les artistes
        $listeDeroulanteArtistes = '<select name="artiste" class="input-field"> <option value="0"> -- Choisissez un artiste -- </option>';
        foreach ($listeArtistes as $artiste) {
            $listeDeroulanteArtistes .= "<option value='{$artiste->getId()}'> {$artiste->getNom()} </option>";
        }
        $listeDeroulanteArtistes .= '</select>';
        // On crée la liste déroulante pour les styles
        $listeDeroulanteStyle = '<select name="style" class="input-field"> <option value="0"> -- Choisissez un style -- </option>';
        foreach ($listeStyles as $style) {
            $listeDeroulanteStyle .= "<option value='{$style->getId()}'> {$style->getNom()} </option>";
        }
        $listeDeroulanteStyle .= '</select>';
        // On ajoute les deux listes au formulaire et on le renvoie
         return <<<END
            <form method="post" name="" action="?action=modifier-spectacle" enctype="multipart/form-data">
                <input type="text" name="nomSpec" placeholder="Nom du spectacle" class="input-field"> 
                $listeDeroulanteSpectacles
                $listeDeroulanteArtistes 
                $listeDeroulanteStyle
                <input type="time" name ="heureD" class="input-field">
                <input type="number" name="duree" min="0" placeholder="<Duree en minutes>" class="input-field">
                <input type="text" name="descSpec" placeholder="Description" class="input-field">
                Fichier Video : <input type="file" name="fichierVideo" placeholder="<fichierVideo>" class="input-field">
                Fichier Audio : <input type="file" name="fichierAudio" placeholder="<fichierAudio>" class="input-field">
                Image : <input type="file" name="fichierImage" placeholder="<fichierImage>" class="input-field">
                <button type="submit" name="valider" id="btn_connexion"> Valider </button>
            </form>
            END;
    }

}