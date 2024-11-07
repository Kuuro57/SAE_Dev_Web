<?php

namespace iutnc\sae_dev_web\action;

class DefaultAction extends Action {

    public function execute() : string{

        $res = "<h3>Bienvenue sur la page d'acceuil du Nancy Rock Pulsation festival !</h3>";

        return $res;

    }

}