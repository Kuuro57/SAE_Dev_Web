<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\repository\DeleteRepository;
use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;

class ToggleFavori extends Action {

    public function execute(): string {
        //redirection vers la page d'affichage des spectacles
        header('Location: afficherSpectacles.php?action=&renderMode=long');

        $res = "";

        $email = $_SESSION['user']['email'];

        //vérification si un utilisateur est connecté
        if($email == null){ // ici on est en mode invité car aucun utilisateur n'est connecté

            //Si l'on souhaite ajouter un spectacle à la liste de favoris
            if($_GET["state"] == "add") {

                if (isset($_COOKIE["Favoris"])) {

                    $spec = $_GET["idSp"];
                    $val = ($_COOKIE["Favoris"]);
                    $favs[] = $val;
                    $favs = $this->toArray($val);

                    //recherche dans la liste d'id si l'id manipulé est présent
                    $index = array_search($spec, $favs);

                    if($index === false) {
                        //ajout de l'idSpectacle récupéré dans la liste d'ids
                        $favs[] = $spec;
                        // réindexage des clés
                        $favs = array_values($favs);
                    }
                    // conversion de la liste en string
                    $ids = (implode($favs));
                    $exp = time() + 30 * 24 * 60 * 60;

                    setcookie("Favoris", $ids, $exp);


                } else {

                    $spec = $_GET["idSp"];
                    $favs[] = $spec;
                    $ids = (implode($favs));
                    setcookie("Favoris", $ids, time() + 30 * 24 * 60 * 60);

                }
            }

            //Si l'on souhaite supprimer un spectacle à la liste de favoris
            if($_GET["state"] == "sup") {
                if (isset($_COOKIE["Favoris"])) {

                    $spec = $_GET["idSp"];
                    $val = ($_COOKIE["Favoris"]);

                    //conversion de la chaine de caractère en array
                    $favs = $this->toArray($val);

                    $index = array_search($spec, $favs);

                    //si l'id est dans la liste
                    if($index !== false) {
                        //on le retire
                        unset($favs[$index]);
                    }

                    $favs = array_values($favs);

                    setcookie("Favoris", implode($favs), time() + 30 * 24 * 60 * 60);

                }
            }

            return $res;

        } else {

            if(isset($_COOKIE["Favoris"])) {
                setcookie("Favoris", "", time());
                echo $_COOKIE["Favoris"];
            }

            $repo = SelectRepository::getInstance();
            $insert = InsertRepository::getInstance();
            $delete = DeleteRepository::getInstance();

            //récupèration de l'id utilisateur avec son email
            $idUser = $repo->getIdFromEmail($email);

            if ($_GET["state"] == "add") {

                //on vérifie l'existance de préferences
                $exist = $repo->existPref($idUser, (int)$_GET["idSp"]);

                //sinon
                if(!$exist) {
                    //on ajoute la préference
                    $insert->ajouterPref($idUser, $_GET["idSp"]);
                    $res = "Ajouté à vos préférences";
                } else {
                    $res = "Déjà ajouté à votre liste de préferences";
                }
            }

            if ($_GET["state"] == "sup") {

                $exist = $repo->existPref($idUser, $_GET["idSp"]);

                if ($exist) {
                    //on retire la préfernce de la liste
                    $delete->supPref($idUser, $_GET["idSp"]);
                    $res = "Supprimé de vos préférences";
                }

            }

        }

        return $res;

    }

    //Fonction pour convertir un string en array
    public static function toArray(string $s): array {
        $res = [];
        for($i = 0; $i < strlen($s); $i++) {
            $res[$s[$i]] = $s[$i];
        }

        return $res;
    }

}