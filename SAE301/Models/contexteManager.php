<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class ContexteManager
{
	private $_db; // Instance de PDO - objet de connexion au SGBD

	public function __construct($db)
	{
		$this->_db = $db;
	}

    // ============================== PROJET ==============================

	//Fonction pour afficher les contextes du projet
	public function getcontexte($idContexte)
	{
		$contextes = array();
		$req = "SELECT * FROM SAE301_Contexte WHERE Id_Contexte = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idContexte));

		// pour déboguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$contextes[] = new Contexte($donnees);
		}
		return $contextes;
	}

	// Fonction pour afficher toutes les ressources de l'appplication pour la modification
	public function ajoucont(): array
	{
		$req = "SELECT * FROM SAE301_Contexte";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array());

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		$results = $stmt->fetchAll();

		$conts = [];
		foreach ($results as $result) {
			$cont = new Contexte($result);
			$conts[] = $cont;
		}
		return $conts;
	}

	// ============================== ADMIN ==============================

	// Fonction pour afficher tous les contextes de l'application
	public function listcont(): array
	{
		$req = "SELECT * FROM SAE301_Contexte";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array());

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		$results = $stmt->fetchAll();

		$conts = [];
		foreach ($results as $result) {
			$cont = new Contexte($result);
			$conts[] = $cont;
		}
		return $conts;
	}

	// Fonction pour ajouter une ressources à l'application web 
	public function addcont($cont)
	{
		$req = "INSERT INTO `SAE301_Contexte` (`Id_Contexte`, `Identifiant`, `Semestre`, `Intitule`) VALUES (?, ?, ?, ?)";
		$stmt = $this->_db->prepare($req);

		$res = $stmt->execute(array(
			$cont->idcontexte(),
			$cont->identifiant(),
			$cont->semestre(),
			$cont->intitule(),
		));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}


	// Fonction pour supprimer une ressource d'un projet 
	public function supcont($idcontexte)
	{
		$upd = "UPDATE `SAE301_Projet`
            SET `Id_Contexte` = NULL
            WHERE `SAE301_Projet`.`Id_Contexte` = ?";

		$stmtUpd = $this->_db->prepare($upd);
		$resUpd = $stmtUpd->execute(array($idcontexte));

		$del = "DELETE FROM SAE301_Contexte
            WHERE `Id_Contexte` = ?";

		$stmtDel = $this->_db->prepare($del);
		$resDel = $stmtDel->execute(array($idcontexte));

		$errorInfoUpd = $stmtUpd->errorInfo();
		if ($errorInfoUpd[0] != 0) {
			print_r($errorInfoUpd);
		}

		$errorInfoDel = $stmtDel->errorInfo();
		if ($errorInfoDel[0] != 0) {
			print_r($errorInfoDel);
		}

		return $resUpd && $resDel;
	}
}
