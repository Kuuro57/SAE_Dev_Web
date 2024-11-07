<?php

namespace iutnc\sae_dev_web\auth;

use iutnc\sae_dev_web\exception\AuthException;
use iutnc\sae_dev_web\repository\InsertRepository;


/**
 * Classe Auth qui contient les méthode qui permettent l'authentification
 */
class Auth{

    /**
     * Méthode qui permet d'authentifier un utilisateur
     * @param string $e Une adresse email
     * @param string $p Un mot de passe
     * @return bool True si l'authentification s'est bien déroulée
     */
    public static function authenticate(string $e, string $p):bool{

        // On regarde si l'email est présent dans la BDD
        $bd = InsertRepository::getInstance();
        $res = $bd->findExistingEmail($e);

        // Si l'email n'existe pas
        if ($res === []) {
            // On retourne false
            return false;
        }

        // On vérifie que le mot de passe est bon (on lance une exception si non)
        if (!password_verify($p, $res['mdp'])) return false;

        // On ajoute l'email et le role dans la session
        $_SESSION['user']['email']=$e;
        $_SESSION['user']['role']=$res['role'];

        // On renvoie true
        return true;

    }



    /**
     * Méthode qui enregistre un utilisateur dans la BDD
     * @param string $e Une adresse email
     * @param string $p Un mot de passe
     * @return String Message qui indique si l'inscription s'est bien déroulée ou non
     */
    public static function register(string $e, string $p): String {

        // On vérifie que l'utilisateur n'existe pas déjà dans la BDD
        $bd = InsertRepository::getInstance();
        $list = $bd->findExistingEmail($e);

        // Si la liste n'est pas vide
        if (!($list === [])) {
            return "L'email {$e} existe déjà !";
        }

        // On hash son mot de passe
        $hashpassw = password_hash($p, PASSWORD_DEFAULT);
        // On ajoute l'email et le mot de passe à la BDD avec le role 1 (rôle standard)
        $bd->ajouterUtilisateur($e, $hashpassw, 1);
        // On retourne un message qui indique que l'inscription s'est bien déroulée
        return '<b>Compte créé !</b>';

    }

}