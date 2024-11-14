<?php

namespace Super\SaeDevWeb\action;

use iutnc\sae_dev_web\action\Action;

class ToggleFavori extends Action {

    private array $favs;
    public function execute(): string {

        $res = "";

        $spec = $_GET["idSp"];
        $favs[] = $spec;
        $ids = serialize($favs);
        $exp = time() + 30 * 24 * 60 * 60;

        setcookie("Favoris", $ids, $exp);

        return $res;

    }
}