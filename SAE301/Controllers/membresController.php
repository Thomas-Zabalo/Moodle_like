<?php

include "Modules/membre.php";
include "Models/membreManager.php";
/**
 * Définition d'une classe permettant de gérer les membres 
 *   en relation avec la base de données	
 */


class MembreController
{
	private $projetManager;
	private $categorieManager;
	private $contexteManager;
	private $membreManager;
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->projetManager = new ProjetsManager($db);
		$this->categorieManager = new CategorieManager($db);
		$this->contexteManager = new contexteManager($db);
		$this->membreManager = new MembreManager($db);
		$this->twig = $twig;
	}

	// ============================== CONNEXION ==============================

	// Fonction de formulaire de connexion
	function membreFormulaire()
	{
		echo $this->twig->render('membre_connexion.html.twig', array('acces' => $_SESSION['acces']));
	}

	// Fonction de connexion
	function membreConnexion($idutilisateur)
	{
		// Récupération du membre depuis la base de données
		$membre = $this->membreManager->verif_identification($_POST['login'], $_POST['passwd']);
		if ($membre != false) { // acces autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idmembre'] = $membre->idUtilisateur();
			$membre = $this->membreManager->getmembre($idutilisateur);

			if (isset($_POST['Remember']) && $_POST['Remember'] == 1) {
				//Cookie pour 3 jours
				setcookie("COOKIE_APPLICATION", $_SESSION['idmembre'], time() + 24 * 3 * 60 * 60);
			} else {
			}
			//Copyright Mohammed Alshanquiti
			header("Location: ?");
			// echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'membres' => $membre));
		} else { // acces non autorisé : variable de session acces = non
			$message = "Identification incorrecte";
			$_SESSION['acces'] = "non";
			echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
		}
	}

	// ============================== INSCRIPTION ==============================

	// Fonction de formulaire de connexion
	function mbInscription()
	{
		echo $this->twig->render('membre_inscription.html.twig', array('acces' => $_SESSION['acces']));
	}

	// Fonction d'inscription
	function membreInscription()
{
    $uti = new Membre($_POST);

    // Validation du format de l'e-mail
    if (!filter_var($_POST["Mail"], FILTER_VALIDATE_EMAIL)) {
        // Si l'adresse mail est invalide 
        $_SESSION['acces'] = "non";
        $message = "L'Adresse email n'est pas valide.";
        echo $this->twig->render('membre_inscription.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
        return;
    }

    // Validation de la syntaxe
    $emailParts = explode('@', $_POST["Mail"]);
    $domain = end($emailParts);
	if ($domain !== 'iut-tlse3.fr' && $domain !== 'etu.iut-tlse3.fr') {
        // Si le domaine de l'adresse mail est incorrect
        $_SESSION['acces'] = "non";
        $message = "L'adresse email doit contenir iut-tlse3.fr ou etu.iut-tlse3.fr";
        echo $this->twig->render('membre_inscription.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
        return;
    }

    $ok = $this->membreManager->ajoutcompte($uti);
    $message = $ok ? "Votre compte a bien été ajouté" : "problème lors de la création";
    echo $this->twig->render('membre_connexion.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
}


	// ============================== DECONNEXION ==============================

	// Fonction de deconnexion de l'utilisateur
	function membreDeconnexion()
	{
		$_SESSION['acces'] = "non";
		setcookie("COOKIE_APPLICATION");
		$message = "vous êtes déconnecté";
		echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
	}

	// ============================== PROFIL UTILISATEUR ==============================

	// Fonction qui permet d'avoir les informations de l'utilisateur
	public function mbconnec()
	{
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		echo $this->twig->render('index.html.twig', array('membres' => $membre, 'acces' => $_SESSION['acces']));
	}



	// Fonction qui permet d'avoir les informations de l'utilisateur pour voir son profil
	function membreView($idutilisateur)
	{
		$membres = $this->membreManager->getmembre($idutilisateur);
		$projet = $this->projetManager->getprojetbymembre($idutilisateur);

		echo $this->twig->render('membre.html.twig', array(
			'membres' => $membres,
			'projet' => $projet,
			'acces' => $_SESSION['acces']
		));
	}

	function modifmembre($idutilisateur){
		$membres = $this->membreManager->getmembre($idutilisateur);
		echo $this->twig->render('membre_modification.html.twig', array(
			'membres' => $membres,
			'acces' => $_SESSION['acces']
		));
	}
	
	function validerModMembre($idutilisateur){
		$membres = new Membre($_POST);
		$uploadfile = ""; // Initialise la variable en dehors de la condition
	
		// Vérifier si un nouveau fichier d'image a été téléchargé
		if ($_FILES["Image"]["error"] == UPLOAD_ERR_OK) {
			// Déplacer le fichier temporaire vers le dossier de destination
			$uploaddir = "./profil/";
			$uploadfile = $uploaddir . basename($_FILES["Image"]["name"]);
	
			if (!move_uploaded_file($_FILES["Image"]["tmp_name"], $uploadfile)) {
				echo "Problème lors du téléchargement";
				return false;
			}
			else{
				$membres->setImage($uploadfile);
			}
		}
		
		$ok = $this->membreManager->modifprofil($membres);
		$utilisateurs = $this->membreManager->getmembre($idutilisateur);
		$projet = $this->projetManager->getprojetbymembre($idutilisateur);
		echo $this->twig->render('membre.html.twig', array(
			'ok' =>$ok,
			'membres' => $utilisateurs,
			'projet' => $projet,
			'acces' => $_SESSION['acces']
		));
	}


	// ============================== ADMIN ==============================


	// Fonction qui permet d'avoir la liste des utilisateurs
	function adminutilisateur()
	{
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$utis = $this->membreManager->listuti();
		echo $this->twig->render('adminutilisateur.html.twig', array(
			'membre' => $membre,
			'utilisateurs' => $utis,
			'acces' => $_SESSION['acces']
		));
	}

	// Fonction qui permet d'ajouter un utilisateur à l'application
	public function ajout_uti()
	{
		$uti = new Membre($_POST);
		$ok = $this->membreManager->adduti($uti);
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$utis = $this->membreManager->listuti();
		$message = $ok ? "Itinéraire ajouté" : "probleme lors de l'ajout";
		echo $this->twig->render('adminutilisateur.html.twig', array(
			'message' => $message,
			'membre' => $membre,
			'utilisateurs' => $utis,
			'acces' => $_SESSION['acces']
		));
	}

	// Fonction qui permet de supprimer un utilisateur 
	public function sup_uti()
	{
		$ok = $this->membreManager->deluti($_POST["Id_Utilisateur"]);
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$utis = $this->membreManager->listuti();
		$message = $ok ? "contexte supprimé" : "probleme lors de la suppresion";
		echo $this->twig->render('adminutilisateur.html.twig', array(
			'message' => $message,
			'membre' => $membre,
			'utilisateurs' => $utis,
			'acces' => $_SESSION['acces']
		));
	}

	// Fonction qui permet d'avoir la liste des catégories
	function admincategorie()
	{
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$cat = $this->categorieManager->listcat();
		echo $this->twig->render('admincategorie.html.twig', array(
			'membre' => $membre,
			'categorie' => $cat,
			'acces' => $_SESSION['acces']
		));
	}

	// Fonction qui permet d'avoir la liste des ressources
	function adminressource()
	{
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$cont = $this->contexteManager->listcont();
		echo $this->twig->render('admincontexte.html.twig', array(
			'membre' => $membre,
			'contexte' => $cont,
			'acces' => $_SESSION['acces']
		));
	}
}
