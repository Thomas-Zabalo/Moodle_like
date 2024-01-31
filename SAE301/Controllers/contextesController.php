<?php
include "Modules/contexte.php";
include "Models/contexteManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class ContexteController
{
	private $contexteManager; // instance du manager
	private $membreManager;
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->contexteManager = new ContexteManager($db);
		$this->membreManager = new MembreManager($db);
		$this->twig = $twig;
	}


	// Fonction d'ajout d'une ressource dans la base de donnée
	public function ajout_cont()
	{
		$cont = new Contexte($_POST);
		$ok = $this->contexteManager->addcont($cont);
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$cont = $this->contexteManager->listcont();
		$message = $ok ? "contexte ajouté" : "probleme lors de l'ajout";
		echo $this->twig->render('admincontexte.html.twig', array(
			'message' => $message,
			'membre' => $membre,
			'contexte' => $cont,
			'acces' => $_SESSION['acces']
		));
	}

	// Fonction de suppression d'une ressource dans la base de donnée
	public function sup_cont()
	{
		$ok = $this->contexteManager->supcont($_POST["idcontexte"]);
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		$cont = $this->contexteManager->listcont();
		$message = $ok ? "contexte supprimé" : "probleme lors de la suppresion";
		echo $this->twig->render('admincontexte.html.twig', array(
			'message' => $message,
			'membre' => $membre,
			'contexte' => $cont,
			'acces' => $_SESSION['acces']
		));
	}
}
