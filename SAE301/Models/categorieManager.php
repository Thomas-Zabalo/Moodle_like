<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class CategorieManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD

    public function __construct($db)
    {
        $this->_db = $db;
    }


    // ============================== PROJET ==============================

    // Fonction qui appelle toute les catégories présentent dans un projet
    public function getcat($idProjet): array
    {
        $cats = array();
        $req = "SELECT SAE301_Categorie.* 
            FROM SAE301_Categorie
            JOIN SAE301_Appartenir ON SAE301_Categorie.Id_Categorie = SAE301_Appartenir.Id_Categorie 
            WHERE SAE301_Appartenir.Id_Projet = ?";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idProjet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch()) {
            $cats[] = new Categorie($donnees);
        }
        return $cats;
    }

    // Fonction pour ajouter des catégories qui seront liée au projet
    public function add(Categorie $projet)
    {

        $req = "INSERT INTO SAE301_Appartenir (Id_Projet, Id_Categories) VALUES (?, ?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array(
            $projet->idProjet(),
            $projet->idCategorie(),
        ));

        // For debugging SQL queries
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        return $res;
    }


    // Fonction qui s'exécute lors de la modificaiton du projet
    public function ajoucat(): array
    {
        $req = "SELECT * FROM SAE301_Categorie";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(array());

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        $results = $stmt->fetchAll();

        $cats = [];
        foreach ($results as $result) {
            $cat = new Categorie($result);
            $cats[] = $cat;
        }
        return $cats;
    }

    // ============================== ADMIN ==============================

    // Fonction qui affiche toutes les catégories présentent dans l'application 
    public function listcat(): array
    {
        $req = "SELECT * FROM SAE301_Categorie";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array());

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        $results = $stmt->fetchAll();

        $cats = [];
        foreach ($results as $result) {
            $cat = new Categorie($result);
            $cats[] = $cat;
        }
        return $cats;
    }

    // Fonction pour que l'admin puisse ajouter une catégorie utilisateurs dans la base de données
    public function addcat($cat)
    {
        $req = "INSERT INTO `SAE301_Categorie` (`Id_Categorie`, `Nom_Categorie`) VALUES (?, ?)";
        $stmt = $this->_db->prepare($req);

        $res = $stmt->execute(array(
            $cat->idcategorie(),
            $cat->nomcat(),
        ));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    // Fonction pour que l'admin puisse supprimer une catégories dans la base de données
    public function supcat($idcategorie)
    {

        $delap = "DELETE FROM SAE301_Appartenir
    WHERE `Id_Categorie` = ?";

        $stmtDelap = $this->_db->prepare($delap);
        $resDelap = $stmtDelap->execute(array($idcategorie));

        $delca = "DELETE FROM SAE301_Categorie
    WHERE `Id_Categorie` = ?";

        $stmtDelca = $this->_db->prepare($delca);
        $resDelca = $stmtDelca->execute(array($idcategorie));

        $errorInfoUpd = $stmtDelap->errorInfo();
        if ($errorInfoUpd[0] != 0) {
            print_r($errorInfoUpd);
        }

        $errorInfoDel = $stmtDelca->errorInfo();
        if ($errorInfoDel[0] != 0) {
            print_r($errorInfoDel);
        }

        return $resDelap && $resDelca;
    }
}
