<?php
include "Modules/associer.php";
include "Models/associerManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class AssocierController
{
	private $assoManager; // instance du manager
	private $twig;

	public function __construct($db, $twig)
	{
		$this->assoManager = new AssocierManager($db);
	}

}
