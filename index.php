<?php declare (strict_types=1);

// ################################
//       AUTOLOADER (COMPOSER)
// ################################
/*
    Pour générer l'autoloader composer :
        1 -> Aller sur https://getcomposer.org/download/ et rentrer les lignes de commandes
            (dans votre dossier où il y a vos classes) pour installer composer
        2 -> Aller sur le terminal et faire : 'php composer.phar install'
        3 -> Mettre la ligne de commandes ci-dessous dans l'index
*/
require_once 'vendor/autoload.php';




// ################################
//               USE
// ################################
use \iutnc\sae_dev_web\dispatch\Dispatcher;
use iutnc\sae_dev_web\repository\InsertRepository;
use iutnc\sae_dev_web\repository\SelectRepository;



// ################################
//             MAIN
// ################################


session_start();

 try {
    SelectRepository::setConfig('db.conf.ini');
    InsertRepository::setConfig('db.conf.ini');
// Erreur lors de la lecture du fichier de configuration
} catch (Exception $e) {
    echo $e->getMessage();
}


$_GET['action'] = 'default';

$dispatcher = new Dispatcher();
$dispatcher->run();