<?php
include "Modules/categorie.php";
include "Models/categorieManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class CategorieController
{
	private $categorieManager; // instance du manager
	private $membreManager;
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->categorieManager = new CategorieManager($db);
		$this->membreManager = new MembreManager($db);
		$this->twig = $twig;
	}


	// Fonction d'ajout d'une catégorie dans la base de donnée
	public function ajout_cat()
	{
		$cont = new Categorie($_POST);
		$ok = $this->categorieManager->addcat($cont);
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$cat = $this->categorieManager->listcat();
		$message = $ok ? "Itinéraire ajouté" : "probleme lors de l'ajout";
		echo $this->twig->render('admincategorie.html.twig', array(
			'message' => $message,
			'membre' => $membre,
			'categorie' => $cat,
			'acces' => $_SESSION['acces']
		));
	}

	// Fonction de suppression d'une catégorie dans la base de donnée
	public function sup_cat()
	{
		$ok = $this->categorieManager->supcat($_POST["idcategorie"]);
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$cat = $this->categorieManager->listcat();
		$message = $ok ? "contexte supprimé" : "probleme lors de la suppresion";
		echo $this->twig->render('admincategorie.html.twig', array(
			'message' => $message,
			'membre' => $membre,
			'categorie' => $cat,
			'acces' => $_SESSION['acces']
		));
	}
}
