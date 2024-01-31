<?php
include "Modules/appartenir.php";
include "Models/appartenirManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class AppartenirController
{
	private $appManager; // instance du manager
	private $twig;

	public function __construct($db, $twig)
	{
		$this->appManager = new AppartenirManager($db);
	}

}
