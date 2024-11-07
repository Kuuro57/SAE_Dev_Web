<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;



use iutnc\sae_dev_web\festival\Spectacle;
use iutnc\sae_dev_web\repository\InsertRepository;

/**
 * Classe qui représente l'action d'ajouter un spectacle
 */
class AddSpectacleAction extends Action {

    // Attribut
    private string $formulaire = '<form method="post" name="" action="?action=add-spectacle" enctype="multipart/form-data">
                                        <input type="text" name="nomSpec" placeholder="Nom du spectacle" required> <br>
                                        <input type="text" name="style" placeholder="Style" required> <br>
                                        <input type="text" name="artiste" placeholder="Artiste" required> <br>
                                        <input type="text" name="descSpec" placeholder="Description" required> <br>
                                        Fichier Video : <input type="file" name="fichierVideo" placeholder="<fichierVideo>"> <br>
                                        Fichier Audio : <input type="file" name="fichierAudio" placeholder="<fichierAudio>"> <br>
                                        Image : <input type="file" name="fichierImage" placeholder="<fichierImage>"> <br>
                                        <button type="submit" name="valider" class="button"> Valider </button>
                                  </form>';



    public function execute(): string {
        // Si la méthode HTTP est de type GET
        if ($this->http_method === 'GET') {

            // Afficher une formulaire d'ajout d'une track
            return $this->formulaire;

        }
        // Sinon si la méthode HTTP est de type POST
        else if ($this->http_method === 'POST') {

            // On récupère et filtre les données du formulaire
            $nomSpec = filter_var($_POST['nomSpec'], FILTER_SANITIZE_SPECIAL_CHARS);
            $style = filter_var($_POST['style'], FILTER_SANITIZE_SPECIAL_CHARS);
            $artiste = filter_var($_POST['artiste'], FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['descSpec'], FILTER_SANITIZE_SPECIAL_CHARS);
            $nomFichierVideo = uniqid();
            $nomFichierAudio = uniqid();
            $nomFichierImage = uniqid();

            // Si le fichier video est bon
            if ($this->verifFichierVideo($nomFichierVideo)) {

                // Si le fichier audio est bon
                if ($this->verifFichierAudio($nomFichierAudio)) {

                    // Si le fichier image est bon
                    if ($this->verifFichierImage($nomFichierImage)) {

                        // On créé un objet de type Spectacle
                        $spectacle = new Spectacle(null, $nomSpec, $style, $artiste, $description, $nomFichierVideo, $nomFichierAudio, $nomFichierImage);
                        // On ajoute le spectacle à la BDD
                        $bd = InsertRepository::getInstance();
                        $bd->ajouterSpectacle($spectacle);
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
            return "Erreur : Méthode inconnue : {$this->http_method}";
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
            echo 'oups1';
            return false;
        }
        // Si le fichier a été envoyé par la méthode POST
        if (is_uploaded_file($_FILES['fichierVideo']['tmp_name'])) {
            // Si l'extension du fichier n'est pas en .mp4
            if (!(str_ends_with($_FILES['fichierVideo']['name'], '.mp4'))) {
                // On renvoie false
                echo 'oups2';
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
            echo 'oups3';
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

}