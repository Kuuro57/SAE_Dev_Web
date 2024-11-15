<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\action\Action;
use iutnc\sae_dev_web\repository\DeleteRepository;
use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;

class ToggleFavori extends Action {

    public function execute(): string {

        //header('Location: afficherSpectacles.php?action=&renderMode=long');

        $res = "";

        $email = $_SESSION['user']['email'];

        if($email == null){


            if($_GET["state"] == "add") {

                if (isset($_COOKIE["Favoris"])) {

                    $spec = $_GET["idSp"];
                    $val = ($_COOKIE["Favoris"]);
                    $favs[] = $val;
                    $favs[] = $spec;
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

            if($_GET["state"] == "sup") {
                if (isset($_COOKIE["Favoris"])) {

                    $spec = $_GET["idSp"];
                    $val = ($_COOKIE["Favoris"]);
                    $favs = $this->toArray($val);
                    $index = array_search($spec, $favs);
                    if($index !== false) {
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
            $idUser = $repo->getIdFromEmail($email);

            if ($_GET["state"] == "add") {

                $exist = $repo->existPref($idUser, (int)$_GET["idSp"]);

                if(!$exist) {
                    $insert->ajouterPref($idUser, $_GET["idSp"]);
                    $res = "Ajouté à vos préférences";
                } else {
                    $res = "Déjà ajouté à votre liste de préferences";
                }
            }

            if ($_GET["state"] == "sup") {

                $exist = $repo->existPref($idUser, $_GET["idSp"]);

                if ($exist) {
                    $delete->supPref($idUser, $_GET["idSp"]);
                    $res = "Supprimé de vos préférences";
                }

            }

        }

        return $res;

    }

    public function toArray(string $s): array {
        $res = [];
        for($i = 0; $i < strlen($s); $i++) {
            array_push($res, $s[$i]);
        }

        return $res;
    }

}