<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class AppartenirManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD

    /** 
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }



    // Fonction pour ajouter une catégorie à un projet 
    public function add($projet, $idcategorie)
    {

        $req = "INSERT INTO `SAE301_Appartenir` (`Id_Projet`, `Id_Categorie`) VALUES (?, ?)";
        $stmt = $this->_db->prepare($req);

        $res = $stmt->execute(array($projet->idProjet(), $idcategorie));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }



    public function delete($idprojet)
    {
        $req = "DELETE FROM SAE301_Appartenir WHERE Id_Projet = ?";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($idprojet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }


    public function insert($idprojet, $idcategorie)
    {
        $req = "INSERT INTO SAE301_Appartenir (Id_Projet, Id_Categorie) VALUES (?, ?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($idprojet, $idcategorie));


        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        return $res;
    }
}
