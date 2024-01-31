<?php
include "Modules/lien.php";
include "Models/lienManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class LienController
{
	private $lienManager; // instance du manager
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->lienManager = new LienManager($db);
		$this->twig = $twig;
	}

}