<?php
/** 
* définition de la classe itineraire
*/
class Associer {
    private int $_idprojet;   
    private array $_idtag = [];  //Définie comme un tableau vide

    /**
     * Constructeur
     * @param array $donnees
     */
    public function __construct(array $donnees) {
        if (isset($donnees['Id_Projet'])) {				$this->_idprojet = $donnees['Id_Projet'];}
        if (isset($donnees['Id_Tags'])) {				$this->_idtag = $donnees['Id_Tags'];}
    }          
    
    // GETTERS //
    public function idProjet() { return $this->_idprojet; }
    public function idTag() { return $this->_idtag; }

    // SETTERS //
    public function setIdProjet(int $idprojet) { $this->_idprojet = $idprojet; }
    public function setIdTag(array $idtag) { $this->_idtag = $idtag; }
}
?>
