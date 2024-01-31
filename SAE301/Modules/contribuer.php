<?php
	/**
	* définition de la classe itineraire
	*/
	class Contribuer {
		private int $_idprojet;
		private array $_idutilisateur = []; //Définie comme un tableau vide
	
		// contructeur
		public function __construct(array $donnees) {
			if (isset($donnees['Id_Projet'])) {
				$this->_idprojet = $donnees['Id_Projet'];
			}
			if (isset($donnees['Id_Utilisateur'])) {
				$this->_idutilisateur = $donnees['Id_Utilisateur'];
			}
		}
	
		// GETTERS //
		public function idProjet()      { return $this->_idprojet; }
		public function idUtilisateur() { return $this->_idutilisateur; }
	
		// SETTERS //
		public function setIdProjet(int $idprojet)                   { $this->_idprojet = $idprojet; }
		public function setIdUtilisateur(array $idutilisateur)         { $this->_idutilisateur = $idutilisateur; }
	}
	

