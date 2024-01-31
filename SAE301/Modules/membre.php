<?php
/** 
* définition de la classe itineraire
*/
class Membre {
    private int $_idutilisateur;
    private string $_nom;
    private string $_prenom;
    private string $_email;
    private string $_password;
    private string $_idiut;
    private int $_admin;
    private string $_dateNaissance;
    private string $_image;
    
    public function __construct(array $donnees) {
        if (isset($donnees['Id_Utilisateur'])) {				$this->_idutilisateur = $donnees['Id_Utilisateur'];}
        if (isset($donnees['Nom'])) {				$this->_nom = $donnees['Nom'];}
        if (isset($donnees['Prenom'])) {				$this->_prenom = $donnees['Prenom'];}
        if (isset($donnees['Mail'])) {				$this->_email = $donnees['Mail'];}
        if (isset($donnees['Mot_de_passe'])) {				$this->_password = $donnees['Mot_de_passe'];}
        if (isset($donnees['Identifiant_IUT'])) {				$this->_idiut = $donnees['Identifiant_IUT'];}
        if (isset($donnees['admin'])) {				$this->_admin = $donnees['admin'];}
        if (isset($donnees['Date_de_naissance'])) {				$this->_dateNaissance = $donnees['Date_de_naissance'];}
        if (isset($donnees['Image'])) {				$this->_image = $donnees['Image'];}
    }         

        // GETTERS //
		public function idUtilisateur() {return $this->_idutilisateur;}
		public function nom() { return $this->_nom;}
		public function prenom() { return $this->_prenom;}
		public function email() { return $this->_email;}
		public function password() { return $this->_password;}
		public function idIut() { return $this->_idiut;}
		public function admin() { return $this->_admin;}
		public function dateNaissance() { return $this->_dateNaissance;}
		public function Image() { return $this->_image;}

		// SETTERS //
		public function setIdUtilisateur(int $idutilisateur) { $this->_idutilisateur = $idutilisateur; }
        public function setNom(string $nom) { $this->_nom= $nom; }
		public function setPrenom(string $prenom) { $this->_prenom = $prenom; }
		public function setEmail(string $email) { $this->_email = $email; }
		public function setPassword(string $password) { $this->_password = $password; }
		public function setIdIut(string $idiut) { $this->_idiut = $idiut; }		
		public function setAdmin(int $admin) { $this->_admin = $admin; }		
		public function setNaissance(string $dateNaissance) { $this->_dateNaissance = $dateNaissance; }		
		public function setImage(String $image) { $this->_image = $image; }		

    }

	

?>