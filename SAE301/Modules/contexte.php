<?php
	/**
	* définition de la classe itineraire
	*/
	class Contexte {
		private ?int $_idcontexte = null;   
		private string $_Identifiant;
		private string $_semestre;
		private string $_intitule;
			
		// Constructor
		public function __construct(array $donnees) {
			if (isset($donnees['Id_Contexte'])) {				$this->_idcontexte = $donnees['Id_Contexte'];}
			if (isset($donnees['Identifiant'])) {				$this->_Identifiant = $donnees['Identifiant'];}
			if (isset($donnees['Semestre'])) {				$this->_semestre = $donnees['Semestre'];}
			if (isset($donnees['Intitule'])) {				$this->_intitule = $donnees['Intitule'];}
		}         
	
		// GETTERS //
		public function idcontexte() { return $this->_idcontexte; }
		public function identifiant() { return $this->_Identifiant; }
		public function semestre() { return $this->_semestre; }
		public function intitule() { return $this->_intitule; }
			
		// SETTERS //
		public function setIdContexte(int $idcontexte) { $this->_idcontexte = $idcontexte; }
		public function setIdentifiant(string $Identifiant) { $this->_Identifiant = $Identifiant; }
		public function setSemestre(string $semestre) { $this->_semestre = $semestre; }
		public function setIntitule(string $intitule) { $this->_intitule = $intitule; }
	}
	

