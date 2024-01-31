<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class AssocierManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD

    /** 
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }


    // Aide de GPT car beaucoup de problème sur l'ajout d'un tableau dans la bdd


    //Fonction pour associer des tags à un projet en particulier
    public function add($projet, $idtags)
    {
            $req = "INSERT INTO `SAE301_Associer` (`Id_Projet`, `Id_Tags`) VALUES (?, ?)";
            $stmt = $this->_db->prepare($req);

            $res = $stmt->execute(array($projet->idProjet(), $idtags));

            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            return $res;
        }
      




    public function delete($idprojet)
    {
        $req = "DELETE FROM SAE301_Associer WHERE Id_Projet = ?";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($idprojet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }


    public function insert($idprojet, $idtags)
    {
        $req = "INSERT INTO SAE301_Associer (Id_Projet, Id_Tags) VALUES (?, ?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($idprojet, $idtags));

       
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        return $res;
    }
}
