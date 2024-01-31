<?php

/**
 * Définition de la classe Commentaire
 */
class Commentaire
{
    private int $_idprojet;
    private int $_idutilisateur;
    private string $_com;
    private $_datecommentaire;
    private string $_nomUtilisateur;
    private string $_prenomUtilisateur;

    // Constructeur
    public function __construct(array $donnees)
    {
        if (isset($donnees['Id_Projet'])) {				$this->_idprojet = $donnees['Id_Projet'];}
        if (isset($donnees['Id_Utilisateur'])) {				$this->_idutilisateur = $donnees['Id_Utilisateur'];}
        if (isset($donnees['Commentaire'])) {				$this->_com = $donnees['Commentaire'];}	
        if (isset($donnees['Date_insertion']))   { $this->_datecommentaire =   $donnees['Date_insertion']; }
        if (isset($donnees['Nom'])) {				$this->_nomUtilisateur = $donnees['Nom'];}
        if (isset($donnees['Prenom'])) {				$this->_prenomUtilisateur = $donnees['Prenom'];}
    }

    // Getters
    public function idProjet()      { return $this->_idprojet; }
	public function idUtilisateur()         { return $this->_idutilisateur; }
	public function com()       { return $this->_com; }
    public function dateCommentaire()  { return $this->_datecommentaire;}
	public function nomUtilisateur()        { return $this->_nomUtilisateur; }
	public function prenomUtilisateur()         { return $this->_prenomUtilisateur; }


    // Setters
    public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
    public function setIdUtilisateur(int $idutilisateur)             { $this->_idutilisateur = $idutilisateur; }
    public function setcom(string $com)    {$this->_com = $com;}
    public function setdateCommentaire( $datecommentaire)   { $this->_datecommentaire  = $datecommentaire; }
    public function setnomUtilisateur(string $nomUtilisateur) { $this->_nomUtilisateur = $nomUtilisateur; }
    public function setprenomUtilisateur(string $prenomUtilisateur) { $this->_prenomUtilisateur = $prenomUtilisateur; }
}
