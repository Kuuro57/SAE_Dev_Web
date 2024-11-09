<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\action;


/**
 * Classe qui représente l'action par défaut
 */
class DefaultAction extends Action {

    
    public function execute(): string {

        return '<h1> Accueil de notre site ! </h1>';

    }
}