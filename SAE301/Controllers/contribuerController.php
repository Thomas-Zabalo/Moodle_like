<?php
include "Modules/contribuer.php";
include "Models/contribuerManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */


class ContribuerController
{
	private $contribueManager; 
	private $twig;

    public function __construct($db, $twig)
	{
		$this->contribueManager = new ContribuerManager($db);
        $this->twig = $twig;
    }


}