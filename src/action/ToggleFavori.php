<?php

namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\action\Action;

class ToggleFavori extends Action {

    public function execute(): string {

        $res = "";

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

    }

    public function toArray(string $s): array {
        $res = [];
        for($i = 0; $i < strlen($s); $i++) {
            array_push($res, $s[$i]);
        }

        return $res;
    }

}