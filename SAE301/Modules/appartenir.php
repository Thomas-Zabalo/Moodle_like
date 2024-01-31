<?php

/** 
 * définition de la classe itineraire
 */
class Appartenir
{
    private int $_idprojet;
    private array $_idcat = []; //Définie comme un tableau vide

    public function __construct(array $donnees)
    {
        if (isset($donnees['Id_Projet'])) {				$this->_idprojet = $donnees['Id_Projet'];}
        if (isset($donnees['Id_Categories'])) {				$this->_idcat = $donnees['Id_Categories'];}
    }

    // GETTERS //
    public function idProjet() { return $this->_idprojet; }
    public function idCat() { return $this->_idcat; }
  
    // SETTERS //
    public function setIdProjet(int $idprojet) { $this->_idprojet = $idprojet; }
	public function setIdcat(array $idcat) { $this->_idcat = $idcat; }
}
