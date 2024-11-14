<?php


namespace iutnc\sae_dev_web\action;

use iutnc\sae_dev_web\render\Renderer;
use iutnc\sae_dev_web\render\SoireeRenderer;
use iutnc\sae_dev_web\render\SpectacleRenderer;
use iutnc\sae_dev_web\repository\SelectRepository;

class DisplaySoireeListeSpectacle
{
    public function execute(): string
    {
        $r = SelectRepository::getInstance();
        $res = "";

        // Vérifiez si un `id` spécifique est passé pour afficher un spectacle détaillé
        if (isset($_GET['id'])) {
            $spectacleId = (int) $_GET['id'];
            $spectacle = $r->getSpectacle($spectacleId);
            if ($spectacle) {
                $spectacleRenderer = new SpectacleRenderer($spectacle); // Créer un renderer pour le spectacle
                $res .= $spectacleRenderer->render(Renderer::LONG);  // Détail du spectacle
            } else {
                $res .= "<p>Spectacle non trouvé</p>";
            }
        } else {
            // Sinon, afficher la liste des soirées
            $listeSoirees = $r->getSoirees(null); // Récupérer la liste des soirées
            foreach ($listeSoirees as $soiree) {
                $renderer = new SoireeRenderer($soiree);
                $res .= $renderer->render(Renderer::COMPACT);
            }
        }

        return $res;
    }
}
