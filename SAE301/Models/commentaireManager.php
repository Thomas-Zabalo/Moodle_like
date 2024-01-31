<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class CommentaireManager
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


	//Fonction qui affiche tous les commentaires du projet 
	public function getcom($idProjet)
	{
		$commentaires = array();
		$req = "SELECT SAE301_Commenter.Commentaire, SAE301_Utilisateur.Nom, SAE301_Utilisateur.Prenom, SAE301_Commenter.Date_insertion
		FROM SAE301_Commenter
		JOIN SAE301_Utilisateur ON SAE301_Commenter.Id_Utilisateur = SAE301_Utilisateur.Id_Utilisateur
		WHERE SAE301_Commenter.Id_Projet = ?
		ORDER BY SAE301_Commenter.Date_insertion DESC";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch()) {
			$commentaires[] = new Commentaire($donnees);
		}
		return $commentaires;
	}


	//Fonction qui ajoute un commentaire à un projet en particulier
	public function add($com)
	{
		$req = "INSERT INTO `SAE301_Commenter` (`Id_Projet`, `Id_Utilisateur`, `Commentaire`, `Date_insertion`) VALUES (?, ?, ?, NOW())";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array(
			$com->idProjet(),
			$com->idUtilisateur(),
			$com->com(),
		));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		return $res;
	}
}
