<?php

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class LienManager
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

	//Fonction qui appelle tous les liens présentent dans un projet
	public function getlien($idprojet)
	{
		$liens = array();
		$req = "SELECT * FROM SAE301_Lien WHERE Id_Projet = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		// récup des données
		while ($donnees = $stmt->fetch()) {
			$liens[] = new Lien($donnees);
		}
		return $liens;
	}


	// Fonction ajout des liens liée au projet dans la base de donnée
	public function add(Lien $liens, $projet)
	{
		$stmt = $this->_db->prepare("SELECT MAX(Id_Lien) AS maximum FROM SAE301_Lien");
		$stmt->execute();
		$liens->setIdLien($stmt->fetchColumn() + 1);

		$req = "INSERT INTO `SAE301_Lien` (`Id_Lien`, `Demo`, `Sources`, `Id_Projet`) VALUES (?, ?, ?, ?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array(
			$liens->idLien(),
			$liens->nomdemo(),
			$liens->nomsource(),
			$projet->idProjet()
		));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		return $res;
	}

	// Fonction de modification des liens du projet
	public function update(Lien $projet)
	{

		$req = "UPDATE SAE301_Lien SET 
		   Sources = :Sources,
		   Demo = :Demo
		WHERE Id_Projet = :Id_Projet";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(
			":Sources" => $projet->nomsource(),
			":Demo" => $projet->nomdemo(),
			":Id_Projet" => $projet->idProjet(),
		));
		return $stmt->rowCount();
	}







}
