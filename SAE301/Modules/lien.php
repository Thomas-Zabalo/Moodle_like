<?php
/** 
* définition de la classe itineraire
*/
class Lien {
    private int $_idlien;   
    private string $_demo;
    private string $_source;    
    private int $_idprojet;    
    /**
     * Constructeur
     * @param array $donnees
     */
    public function __construct(array $donnees) {
        if (isset($donnees['Id_Lien'])) {				$this->_idlien = $donnees['Id_Lien'];}
        if (isset($donnees['Demo'])) {				$this->_demo = $donnees['Demo'];}
        if (isset($donnees['Sources'])) {				$this->_source = $donnees['Sources'];}
        if (isset($donnees['Id_Projet'])) {				$this->_idprojet = $donnees['Id_Projet'];}
    }          
        // GETTERS //
        public function idLien() { return $this->_idlien; }
		public function nomdemo() {return $this->_demo;}
		public function nomsource() { return $this->_source;}
        public function idProjet() { return $this->_idprojet; }

		// SETTERS //
        public function setIdLien(int $idlien)             { $this->_idlien = $idlien; }
		public function setNomdemo(string $demo) { $this->_demo = $demo; }
        public function setNomsource(string $source) { $this->_source= $source; }
        public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
    }

	

?>