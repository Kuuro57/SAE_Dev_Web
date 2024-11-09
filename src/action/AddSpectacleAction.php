<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;



use iutnc\sae_dev_web\festival\Audio;
use iutnc\sae_dev_web\festival\Image;
use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\festival\Video;
use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;

/**
 * Classe qui représente l'action d'ajouter un spectacle
 */
class AddSpectacleAction extends Action {



    public function execute(): string {
        // Si la méthode HTTP est de type GET
        if ($this->http_method === 'GET') {

            // Afficher une formulaire d'ajout d'un spectacle
            return $this->getFormulaire();

        }
        // Sinon si la méthode HTTP est de type POST
        else if ($this->http_method === 'POST') {

            // On récupère et filtre les données du formulaire
            $nomSpec = filter_var($_POST['nomSpec'], FILTER_SANITIZE_SPECIAL_CHARS);
            $style = filter_var($_POST['style'], FILTER_SANITIZE_SPECIAL_CHARS);
            $artiste = filter_var($_POST['artiste'], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_var($_POST['duree'], FILTER_SANITIZE_SPECIAL_CHARS);
            $heureD = filter_var($_POST['heureD'], FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['descSpec'], FILTER_SANITIZE_SPECIAL_CHARS);
            $nomFichierVideo = uniqid();
            $nomFichierAudio = uniqid();
            $nomFichierImage = uniqid();

            // Si la valeur du style est égale à 0
            if ((int) $style === 0) {
                // On retourne le formulaire + un message disant que le style n'a pas été renseignée
                return "<h3><strong> Veuillez renseigner le style </strong></h3><br>" . $this->getFormulaire();
            }
            // Sinon si la valeur de l'artiste est égale à 0
            elseif ((int) $artiste === 0) {
                // On retourne le formulaire + un message disant que l'artiste n'a pas été renseignée
                return "<h3><strong> Veuillez renseigner l'artiste </strong></h3><br>" . $this->getFormulaire();
            }


            // Si le fichier video est bon
            if ($this->verifFichierVideo($nomFichierVideo)) {

                // Si le fichier audio est bon
                if ($this->verifFichierAudio($nomFichierAudio)) {

                    // Si le fichier image est bon
                    if ($this->verifFichierImage($nomFichierImage)) {

                        // On créé un objet de type Spectacle (sans la video, l'audio et l'image)
                        $spectacle = new Spectacle(
                            null,
                            $nomSpec,
                            $this->selectRepo->getStyle((int) $style),
                            $this->selectRepo->getArtiste((int) $artiste),
                            (int) $duree,
                            $heureD,
                            $description,
                            [],
                            [],
                            []);
                        // On ajoute le spectacle à la BDD (en récupérant le nouvel objet spectacle avec son id)
                        $spectacle = $this->insertRepo->ajouterSpectacle($spectacle);

                        // On ajoute l'image à la BDD (en construisant un objet Image)
                        $image = new Image(
                            null,
                            $spectacle->getId(),
                            $nomFichierImage
                        );
                        $this->insertRepo->ajouterImage($image);

                        // On ajoute l'audio à la BDD (en construisant un objet Audio)
                        $audio = new Audio(
                            null,
                            $spectacle->getId(),
                            $nomFichierAudio
                        );
                        $this->insertRepo->ajouterAudio($audio);

                        // On ajoute la vidéo à la BDD (en construisant un objet Video)
                        $video = new Video(
                            null,
                            $spectacle->getId(),
                            $nomFichierVideo
                        );
                        $this->insertRepo->ajouterVideo($video);

                        // On informe que le spectacle à bien été créé
                        return '<p> Le spectacle à bien été créé et ajouté ! </p>';
                    }
                    // Sinon
                    else {
                        // On informe à l'utilisateur que le fichier image n'est pas bon
                        return '<p> Le fichier image n\'est pas accepté ! </p>';
                    }
                }
                // Sinon
                else {
                    // On informe à l'utilisateur que le fichier audio n'est pas bon
                    return '<p> Le fichier audio n\'est pas accepté ! </p>';

                }
            }
            // Sinon
            else {
                // On informe à l'utilisateur que le fichier vidéo n'est pas bon
                return '<p> Le fichier vidéo n\'est pas accepté ! </p>';

            }
        }
        // Sinon
        else {
            // On informe d'une erreur
            return "Erreur : Méthode HTTP inconnue : {$this->http_method}";
        }
    }



    /**
     * Méthode qui vérifie l'extention d'un fichier vidéo donné par l'utilisateur et qui ajoute ce fichier
     * à l'arborecense pour pouvoir le stocker
     * @param string $nomFichier Nom du fichier que l'on veut tester
     * @return bool Vrai si le fichier est bon et stocké dans l'arborecense, faux sinon
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
            // On met comme type au fichier audio audio/mpeg-4
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
     * Méthode qui vérifie l'extention d'un fichier audio donné par l'utilisateur et qui ajoute ce fichier
     * à l'arborecense pour pouvoir le stocker
     * @param string $nomFichier Nom du fichier que l'on veut tester
     * @return bool Vrai si le fichier est bon et stocké dans l'arborecense, faux sinon
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
            // On met comme type au fichier audio audio/mpeg
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
     * Méthode qui vérifie l'extention d'un fichier image donné par l'utilisateur et qui ajoute ce fichier
     * à l'arborecense pour pouvoir le stocker
     * @param string $nomFichier Nom du fichier que l'on veut tester
     * @return bool Vrai si le fichier est bon et stocké dans l'arborecense, faux sinon
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
     * Méthode qui retourne le formulaire complété (avec tous les artistes et style que l'administrateur peut chosir)
     * @return string Le formulaire au format HTML
     */
    private function getFormulaire() : string {
        // On récupère la liste de tous les artistes dans la BDD
        $listeArtistes = $this->selectRepo->getArtistes();
        // On récupère la liste de tous les styles dans la BDD
        $listeStyles = $this->selectRepo->getStyles();
        // On créé la liste déroulante pour les artistes
        $listeDeroulanteArtistes = '<select name="artiste"> <option value="0"> -- Choisissez un artiste -- </option>';
        foreach ($listeArtistes as $artiste) {
            $listeDeroulanteArtistes .= "<option value='{$artiste->getId()}'> {$artiste->getNom()} </option>";
        }
        $listeDeroulanteArtistes .= '</select>';
        // On créé la liste déroulante pour les styles
        $listeDeroulanteStyle = '<select name="style"> <option value="0"> -- Choisissez un style -- </option>';
        foreach ($listeStyles as $style) {
            $listeDeroulanteStyle .= "<option value='{$style->getId()}'> {$style->getNom()} </option>";
        }
        $listeDeroulanteStyle .= '</select>';
        // On ajoute les deux listes au formulaire et on le renvoie
         return <<<END
            <form method="post" name="" action="?action=add-spectacle" enctype="multipart/form-data">
                <input type="text" name="nomSpec" placeholder="Nom du spectacle" required> 
                $listeDeroulanteArtistes 
                $listeDeroulanteStyle
                <input type="time" name ="heureD" required>
                <input type="number" name="duree" min="0" placeholder="<Duree en minutes>" required>
                <input type="text" name="descSpec" placeholder="Description" required>
                Fichier Video : <input type="file" name="fichierVideo" placeholder="<fichierVideo>">
                Fichier Audio : <input type="file" name="fichierAudio" placeholder="<fichierAudio>">
                Image : <input type="file" name="fichierImage" placeholder="<fichierImage>">
                <button type="submit" name="valider" class="button"> Valider </button>
            </form>';
            END;
    }

}