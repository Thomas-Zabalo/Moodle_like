<?php
include "Modules/tags.php";
include "Models/tagsManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class TagsController
{
	private $tagManager; // instance du manager
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->tagManager = new TagsManager($db);
		$this->twig = $twig;
	}
    
	}

