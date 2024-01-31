<?php
	/**
	* définition de la classe itineraire
	*/
	class Categorie {
		private int $_idcategorie =0;
		private string $_nomcat;
	
		// constructeur
		public function __construct(array $donnees) {
			if (isset($donnees['Id_Categorie'])) {				$this->_idcategorie = $donnees['Id_Categorie'];}
			if (isset($donnees['Nom_Categorie'])) {				$this->_nomcat = $donnees['Nom_Categorie'];}
		}
	
		// GETTERS //
		public function idcategorie() { return $this->_idcategorie; }
		public function nomcat() { return $this->_nomcat; }
	
		// SETTERS //
		public function setIdcategorie(int $idcategorie) { $this->_idcategorie = $idcategorie; }
		public function setNomcat(string $nomcat) { $this->_nomcat = $nomcat; }
	}
	

