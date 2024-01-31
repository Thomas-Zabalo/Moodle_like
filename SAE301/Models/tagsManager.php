<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class TagsManager
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

    //Fonction qui appelle toute les catégories présentent dans un projet
    public function gettags($idProjet): array
    {
        $tags = array();

        $req = "SELECT SAE301_Tags.* 
            FROM SAE301_Tags
            JOIN SAE301_Associer ON SAE301_Tags.Id_Tags = SAE301_Associer.Id_Tags 
            WHERE SAE301_Associer.Id_Projet = ?";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idProjet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch()) {
            $tags[] = new Tags($donnees);
        }
        return $tags;
    }


  //Fonction qui affiche tous les tags présentent dans  l'application
    public function ajoutag(): array
    {
        $tags = array();

        $req = "SELECT * FROM SAE301_Tags";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array());

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        // récup des données
        while ($donnees = $stmt->fetch()) {
            $tags[] = new Tags($donnees);
        }
        return $tags;
    }
}
