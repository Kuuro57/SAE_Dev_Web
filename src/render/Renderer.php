<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\render;



/**
 * Interface qui représente les renderers
 */
interface Renderer {

    // Attributs
    const COMPACT = 1;
    const LONG = 2;



    /**
     * Méthode qui permet d'afficher en format HTML un objet
     * @param int $selector Entier qui correspond au mode d'affichage
     * @return string Un texte en format HTML
     */
    public function render(int $selector = Renderer::COMPACT) : string;


}