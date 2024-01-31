<?php
include "Modules/commentaire.php";
include "Models/commentaireManager.php";

/**
 * Définition d'une classe permettant de gérer les itinéraires 
 *   en relation avec la base de données	
 */
class CommentaireController
{
	private $projetManager; // instance du manager
	private $tagsManager;
	private $contexteManager;
	private $categorieManager;
	private $membreManager;
	private $commentaireManager;
	private $lienManager;
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->projetManager = new ProjetsManager($db);
		$this->tagsManager = new TagsManager($db);
		$this->contexteManager = new ContexteManager($db);
		$this->categorieManager = new CategorieManager($db);
		$this->membreManager = new MembreManager($db);
		$this->commentaireManager = new CommentaireManager($db);
		$this->lienManager = new LienManager($db);
		$this->twig = $twig;
	}

	// Fonction pour ajouter un commentaire
	public function ajoutcom($idutilisateur)
	{

		$com = new Commentaire($_POST);

		$ok = $this->commentaireManager->add($com);
		$message = $ok ? "Commentaire ajouté" : "Problème lors de l'ajout";


		// Retourne Titre, description, image 
		$projet = $this->projetManager->getprojet($_POST["Id_Projet"]);

		// Retourne les tags du projet 
		$tags = $this->tagsManager->gettags($_POST["Id_Projet"]);

		// Retourne la ou les catégories du projet 
		$categorie = $this->categorieManager->getcat($_POST["Id_Projet"]);

		// Retourne le contexte du projet 
		$contexte = $this->contexteManager->getcontexte($_POST["Id_Contexte"]);

		// Retourne le ou les contributeurs du projet 
		$membre = $this->membreManager->getProjetsByMembre($_POST["Id_Projet"]);

		// Retourne les liens du projet 
		$lien = $this->lienManager->getlien($_POST["Id_Projet"]);

		// Retourne les commentaires du projet 
		$commentaires = $this->commentaireManager->getcom($_POST["Id_Projet"]);

		// Element pour avoir accés au formulaire d'ajout de commentaire en fonction d'un utilisateur
		// Si l'utilisateur est connecté appel de la fonction
		$utilisateur = $this->membreManager->getmembre($idutilisateur);

		echo $this->twig->render('view_projet.html.twig', array(
			'message' => $message,
			'acces' => $_SESSION['acces'],
			'projets' => $projet,
			'tags' => $tags,
			'categories' => $categorie,
			'contextes' => $contexte,
			'membre' => $membre,
			'membres' => $utilisateur,
			'liens' => $lien,
			'commentaires' => $commentaires,
		));
	}
}
