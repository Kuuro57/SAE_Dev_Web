<?php declare (strict_types=1);

namespace iutnc\sae_dev_web\repository;

use Exception;
use PDO;



/**
 * Classe abstraite qui définie les méthodes essentielles de l'accès à la BDD
 */
abstract class Repository {

    // Attributs
    private PDO $pdo; // Objet permettant d'accéder à la BDD et d'executer les requêtes SQL
    private static ?array $config = []; // Liste qui contient les configurations pour accéder à la BDD



    /**
     * Constructeur de la classe
     */
    protected function __construct(array $config) {

        $this->pdo = new PDO(
            $config['dns'],
            $config['username'],
            $config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

    }



    /**
     * Méthode setConfig qui prend un nom de fichier qui contient les paramètres de connexion, charge le fichier et stocke
     * le tableau dans une variable static
     * @param string $file Nom de fichier
     * @throws Exception Erreur lors de la lecture du fichier de configuration
     */
    public static function setConfig(string $file) : void {

        $conf = parse_ini_file($file);

        if ($conf === false) {
            throw new Exception("Error reading configuration file");
        }

        self::$config = [
            'dns' => "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']}",
            'username' => $conf['username'],
            'password' => $conf['password']
        ];

    }



    /**
     * Méthode getInstance qui retourne une instance d'un repository
     * @return Repository Un repository qui permet l'accès à la BDD
     */
    public static abstract function getInstance(): Repository;

}