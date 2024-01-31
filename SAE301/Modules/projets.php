	<?php
	/**
	* définition de la classe itineraire
	*/
	class Projet {
		private int $_idprojet;   
		private string $_titre;
		private string $_description;
		private string $_image = ''; //Aide de Monsieur BARREAU 
		private ?int $_idcontexte = null; //Aide de Monsieur BARREAU 
		private int $_idvalidation;
			
		// contructeur
		public function __construct(array $donnees) {
			if (isset($donnees['Id_Projet'])) {				$this->_idprojet = $donnees['Id_Projet'];}
			if (isset($donnees['Titre'])) {				$this->_titre = $donnees['Titre'];}	
			if (isset($donnees['Description'])) {				$this->_description = $donnees['Description'];}
			if (isset($donnees['Image'])) {				$this->_image = $donnees['Image'];}
			if (isset($donnees['Id_Contexte'])) {				$this->_idcontexte = $donnees['Id_Contexte'];}
			if (isset($donnees['Id_Validation'])) {				$this->_idvalidation = $donnees['Id_Validation'];}
		}         

		// GETTERS //
		public function idProjet() { return $this->_idprojet; }
		public function titre() { return $this->_titre; }
		public function description() { return $this->_description; }
		public function image() { return $this->_image; }
		public function idContexte() { return $this->_idcontexte; }
		public function idValidation() { return $this->_idvalidation; }
			
		// SETTERS //
		public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
		public function setTitre(int $titre)       { $this->_titre = $titre; }
		public function setDescription(string $description)   { $this->_description= $description; }
		public function setImage(string $image) { $this->_image = $image; }
		public function setIdContexte($idcontexte) { $this->_idcontexte = $idcontexte; }
		public function setIdValidation($idvalidation) { $this->_idvalidation = $idvalidation; }
	}

