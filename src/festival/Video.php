<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\festival;



/**
 * Classe qui représente une vidéo
 */
class Video {

    // Attribut
    private int $idVideo; // Id de la vidéo
    private int $idSpectacle; // Id du spectacle auquel appartient la vidéo
    private string $url; // Url de la vidéo



    /**
     * Constructeur de la classe
     * @param int $idV Id de la vidéo
     * @param int $idS Id du spectacle
     * @param string $url Url de la vidéo
     */
    public function __construct(int $idV, int $idS, string $url) {
        $this->idVideo = $idV;
        $this->idSpectacle = $idS;
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

}