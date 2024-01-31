<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class MembreManager
{
	private $_db; // Instance de PDO - objet de connexion au SGBD

	/** 
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}


	// ============================== CONNEXION ==============================


	// Fonction pour vérifier le login et le mot de passe d'un utilisateur pour qu'il puisse se connecter 
	public function verif_identification($login, $password)
	{
		//echo $login." : ".$password;
		$req = "SELECT `Id_Utilisateur`, `Prenom`, `Nom`, `Mail`, `Identifiant_IUT`, `Mot_de_passe`
		FROM SAE301_Utilisateur
		WHERE Mail=:login";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login));

		// Verification du mot de passe avec la méthode du hashage
		if ($data = $stmt->fetch()) {
			if (password_verify($password, $data["Mot_de_passe"])) {
				$membre = new Membre($data);
				return $membre;
			}
		} else
			return false;
	}


	// ============================== INSCRIPTION  ==============================

	// Fonction qui s'execute lors de l'incription d'un utilisateur
	public function ajoutcompte(Membre $membre)
	{
		$req = "INSERT INTO `SAE301_Utilisateur`(`Prenom`, `Nom`, `Mail`, `Identifiant_IUT`, `Mot_de_passe`) VALUES (UPPER(?), UPPER(?), ?, ?, ?)";
		$stmt = $this->_db->prepare($req);


		// Hashage/Cryptage de son mot de passe 
		$hashedPassword = password_hash($membre->password(), PASSWORD_DEFAULT);

		$res = $stmt->execute(array($membre->prenom(), $membre->nom(), $membre->email(), $membre->idIut(), $hashedPassword));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		return $res;
	}


	// ============================== PROFIL ==============================

	// Fonction pour avoir toutes les informations de l'utilisateur 
	public function getmembre($idmembre)
	{

		$membres = array();

		$req = "SELECT Id_Utilisateur, Prenom, Nom, Mail, Identifiant_IUT, Mot_de_passe, admin,Date_de_naissance, Image  FROM `SAE301_Utilisateur` WHERE Id_Utilisateur = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idmembre));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		while ($donnees = $stmt->fetch()) {
			$membres[] = new Membre($donnees);
		}
		return $membres;
	}

	
	// Fonction pour afficher tous les projets liée à un utilisateurs 
	public function getProjetsByMembre($idMembre): array
	{
		$req = "SELECT SAE301_Projet.*, SAE301_Utilisateur.Id_Utilisateur, SAE301_Utilisateur.Nom, SAE301_Utilisateur.Prenom
	FROM SAE301_Projet 
	JOIN SAE301_Contribuer ON SAE301_Projet.Id_Projet = SAE301_Contribuer.Id_Projet 
	JOIN SAE301_Utilisateur ON SAE301_Contribuer.Id_Utilisateur = SAE301_Utilisateur.Id_Utilisateur
	WHERE SAE301_Contribuer.Id_Projet = ?";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idMembre));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		$results = $stmt->fetchAll();

		$projets = [];
		foreach ($results as $result) {
			$projet = new Membre($result);
			$projets[] = $projet;
		}
		return $projets;
	}


	public function modifprofil($membre)
	{
		$req = "UPDATE SAE301_Utilisateur 
				SET	Date_de_naissance = :Date_de_naissance, 
					Image = :Image
				WHERE Id_Utilisateur = :Id_Utilisateur";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(
			":Date_de_naissance" => $membre->dateNaissance(),
			":Image" => $membre->Image(),
			":Id_Utilisateur" => $membre->idUtilisateur(),
		));
	}



	// ============================== ADMIN ==============================


	// Fonction pour afficher tous les utilisateurs de l'application 
	public function listuti(): array
	{
		// Affichage de tous les utilisateurs sauf le premier car Id_Utilisateur(1) = Membre Admin donc on ne l'affiche pas 
		$req = "SELECT * FROM SAE301_Utilisateur LIMIT 99999999 OFFSET 1";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array());

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		$results = $stmt->fetchAll();

		$utis = [];
		foreach ($results as $result) {
			$uti = new Membre($result);
			$utis[] = $uti;
		}
		return $utis;
	}


	// Fonction pour que l'admin puisse ajouter un utilisateurs dans la base de données

	public function adduti(Membre $membre)
	{
		$req = "INSERT INTO `SAE301_Utilisateur`(`Prenom`, `Nom`, `Mail`, `Identifiant_IUT`, `Mot_de_passe`) VALUES (?, ?, ?, ?, ?)";
		$stmt = $this->_db->prepare($req);

		// Hashage/Cryptage de son mot de passe 
		$hashedPassword = password_hash($membre->password(), PASSWORD_DEFAULT);

		$res = $stmt->execute(array($membre->prenom(), $membre->nom(), $membre->email(), $membre->idIut(), $hashedPassword));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}


	// Fonction pour que l'admin puisse supprimer un utilisateurs dans la base de données

	public function deluti($membre)
	{

		$delcom = "DELETE FROM `SAE301_Commenter` 
		WHERE `Id_Utilisateur` = ?";

		$stmtDelcom = $this->_db->prepare($delcom);
		$resDelcom = $stmtDelcom->execute(array($membre));

		$delcont = "DELETE FROM `SAE301_Contribuer` 
		WHERE `Id_Utilisateur` = ?";

		$stmtDelcont = $this->_db->prepare($delcont);
		$resDelcont = $stmtDelcont->execute(array($membre));

		$deluti = "DELETE FROM `SAE301_Utilisateur` 
		WHERE `Id_Utilisateur` = ?";

		$stmtDeluti = $this->_db->prepare($deluti);
		$resDeluti = $stmtDeluti->execute(array($membre));

		$errorInfoUpd = $stmtDelcom->errorInfo();
		if ($errorInfoUpd[0] != 0) {
			print_r($errorInfoUpd);
		}

		$errorInfoDel = $stmtDelcont->errorInfo();
		if ($errorInfoDel[0] != 0) {
			print_r($errorInfoDel);
		}

		$errorInfoDel = $stmtDeluti->errorInfo();
		if ($errorInfoDel[0] != 0) {
			print_r($errorInfoDel);
		}

		return $resDelcom && $resDelcont && $resDeluti;
	}
}
