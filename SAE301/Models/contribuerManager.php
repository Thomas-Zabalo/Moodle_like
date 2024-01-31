<?php

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class ContribuerManager
{

    private $_db; // Instance de PDO - objet de connexion au SGBD

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }


    // ============================== PROJET ==============================

    //Fonction qui ajoute des contributeurs au projet
    public function add($projet, $idutilisateur)
    {
      
            $req = "INSERT INTO `SAE301_Contribuer` (`Id_Projet`, `Id_Utilisateur`) VALUES (?, ?)";
            $stmt = $this->_db->prepare($req);

            $res = $stmt->execute(array($projet->idProjet(), $idutilisateur));

            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
        return $res;
    }



    public function delete($idprojet)
    {
        $req = "DELETE FROM SAE301_Contribuer WHERE Id_Projet = ?";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($idprojet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }


    public function insert($idprojet, $idutilisateur)
    {
        $req = "INSERT INTO SAE301_Contribuer (Id_Projet, Id_Utilisateur) VALUES (?, ?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($idprojet, $idutilisateur));

       
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        return $res;
    }
}
