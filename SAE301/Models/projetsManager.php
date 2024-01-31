<?php

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class ProjetsManager
{

	private $_db; // Instance de PDO - objet de connexion au SGBD

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * ajout d'un itineraire dans la BD
	 * @param Projet à ajouter
	 * @return int true si l'ajout a bien eu lieu, false sinon
	 */


	// ============================== PROJET ==============================

	// Fonction qui affiche tous les projets présents dans la base de donnée
	public function getList()
	{
		$projets = array();

		$req = "SELECT `Id_Projet`,`Titre`, `Description`, `Image`,`Id_Contexte` FROM SAE301_Projet WHERE Id_Validation = 1";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();

		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		// récup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}

	// Fonction qui affiche tous les projets présents non validé dans la base de donnée
	public function getListValidation()
	{
		$projets = array();

		$req = "SELECT `Id_Projet`,`Titre`, `Description`, `Image`,`Id_Contexte` FROM SAE301_Projet WHERE Id_Validation = 0";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();

		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		// récup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}



	// Fonction qui ajoute le projet (Titre, Description, Image, Contexte) 

	public function add(Projet $projet)
	{
	
		$stmt = $this->_db->prepare("SELECT MAX(Id_Projet) AS maximum FROM SAE301_Projet");
		$stmt->execute();
		$projet->setIdProjet($stmt->fetchColumn() + 1);

		$req = "INSERT INTO SAE301_Projet (Id_Projet, Titre, Description, Image, Id_Contexte, Id_Validation) VALUES (?, ?, ?, ?, ?, 0)";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(
			$projet->idProjet(),
			$projet->titre(),
			$projet->description(),
			$projet->image(),
			$projet->idContexte()
		));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		return $projet;
	}

	// Fonction qui cherche le projet par rapport à sa description ou à son contexte
	public function search(string $projet)
	{
		$req = "SELECT SAE301_Projet.Id_Projet, SAE301_Projet.Titre, SAE301_Projet.Description, SAE301_Projet.Image, SAE301_Projet.Id_Contexte, SAE301_Contexte.*, SAE301_Categorie.*
        FROM SAE301_Projet
        JOIN SAE301_Contexte ON SAE301_Projet.Id_Contexte = SAE301_Contexte.Id_Contexte
        JOIN SAE301_Appartenir ON SAE301_Projet.Id_Projet = SAE301_Appartenir.Id_Projet
        JOIN SAE301_Categorie ON SAE301_Appartenir.Id_Categorie = SAE301_Categorie.Id_Categorie";

		$cond = [];
		$params = [];

		if ($projet !== "") {
			$cond[] = "(SAE301_Projet.Titre LIKE :projet OR SAE301_Projet.Description LIKE :projet OR SAE301_Contexte.Identifiant LIKE :projet OR SAE301_Contexte.Semestre LIKE :projet OR SAE301_Contexte.Intitule LIKE :projet OR SAE301_Categorie.Nom_Categorie LIKE :projet)";
			$params[':projet'] = "%" . $projet . "%";
		}

		if (!empty($cond)) {
			$req .= " WHERE " . implode(" OR ", $cond);
		}

		$stmt = $this->_db->prepare($req);
		$stmt->execute($params);

		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$itineraires = array();
		while ($donnees = $stmt->fetch()) {
			$itineraires[] = new Projet($donnees);
		}
		return $itineraires;
	}


	// Fonction qui supprime le projet et tous ce qu'il lui est associé
	public function deletepro(Projet $projet): bool
	{
		$reqcom = "DELETE FROM SAE301_Commenter WHERE Id_Projet = ?";
		$stmtcom = $this->_db->prepare($reqcom);
		$stmtcom->execute(array($projet->idProjet()));

		// Suppression des liens liés au projet
		$reqlien = "DELETE FROM SAE301_Lien WHERE Id_Projet = ?";
		$stmtlien = $this->_db->prepare($reqlien);
		$stmtlien->execute(array($projet->idProjet()));

		// Suppression des contributions liées au projet
		$reqcon = "DELETE FROM SAE301_Contribuer WHERE Id_Projet = ?";
		$stmtcon = $this->_db->prepare($reqcon);
		$stmtcon->execute(array($projet->idProjet()));

		// Suppression des associations liées au projet
		$reqass = "DELETE FROM SAE301_Associer WHERE Id_Projet = ?";
		$stmtass = $this->_db->prepare($reqass);
		$stmtass->execute(array($projet->idProjet()));

		// Suppression des évaluations liées au projet
		$reqeva = "DELETE FROM SAE301_Evaluer WHERE Id_Projet = ?";
		$stmteva = $this->_db->prepare($reqeva);
		$stmteva->execute(array($projet->idProjet()));

		// Suppression des appartenances liées au projet
		$reqapp = "DELETE FROM SAE301_Appartenir WHERE Id_Projet = ?";
		$stmtapp = $this->_db->prepare($reqapp);
		$stmtapp->execute(array($projet->idProjet()));

		// Suppression du projet lui-même
		$reqpro = "DELETE FROM SAE301_Projet WHERE Id_Projet = ?";
		$stmtpro = $this->_db->prepare($reqpro);
		$stmtpro->execute(array($projet->idProjet()));

		return $stmtcom && $stmtlien && $stmtcon && $stmtass && $stmteva && $stmtapp && $stmtpro;
	}


	// Fonction qui permet de modifier le projet

	public function update(Projet $projet)
	{
		
			// Mettre à jour le chemin de l'image dans la base de données
			$req = "UPDATE SAE301_Projet SET 
				 Titre = :Titre, 
				 Description = :Description,
				 Image = :Image,
				 Id_Contexte = :Id_Contexte,
				 Id_Validation = 1
				 WHERE Id_Projet = :Id_Projet";

			$stmt = $this->_db->prepare($req);
			$stmt->execute(array(
				":Titre" => $projet->titre(),
				":Description" => $projet->description(),
				":Image" => $projet->image(),
				":Id_Contexte" => $projet->idContexte(),
				":Id_Projet" => $projet->idProjet(),
			));
		
	}



	// ============================== PROFIL ==============================

	// Fonction qui récupère tous les projes par rapport à un membre

	public function getprojetbymembre($projet)
	{
		$projets = array();

		$req = "SELECT SAE301_Projet.*
    FROM SAE301_Projet
    JOIN SAE301_Contribuer ON SAE301_Projet.Id_Projet = SAE301_Contribuer.Id_Projet
    WHERE SAE301_Contribuer.Id_Utilisateur = ?";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($projet));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		// récup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}



	public function getprojet($idProjet)
	{
		$projets = array();

		$req = "SELECT SAE301_Projet.Id_Projet, Titre, Description, Image, Id_Contexte FROM `SAE301_Projet` WHERE SAE301_Projet.Id_Projet = ?;";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));

		// pour déboguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}
}
