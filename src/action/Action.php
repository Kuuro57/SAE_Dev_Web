<?php

namespace iutnc\sae_dev_web\action;



use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;

/**
 * Classe abstraite qui représente une action
 */
abstract class Action {

    // Attributs
    protected ?string $http_method = null; // Méthode utilisée par une requête HTTP
    protected ?string $hostname = null; // Nom du host
    protected ?string $script_name = null; // Nom du script
    protected SelectRepository $selectRepo; // Instance de la classe SelectRepository pour accéder aux méthodes d'insertions
    protected InsertRepository $insertRepo; // Instance de la classe SelectRepository pour accéder aux méthodes de sélections



    /**
     * Constructeur de la classe
     */
    public function __construct(){
        
        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
        $this->selectRepo = SelectRepository::getInstance();
        $this->insertRepo = InsertRepository::getInstance();

    }



    /**
     * Méthode qui execute une action
     * @return string
     */
    abstract public function execute() : string;
    
}