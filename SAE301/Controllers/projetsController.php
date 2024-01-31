<?php
include "Modules/projets.php";
include "Models/projetsManager.php";



// Initialisation de tous les controllers 
class ProjetsController
{
	private $projetManager;
	private $tagsManager;
	private $contexteManager;
	private $categorieManager;
	private $contribuerManager;
	private $membreManager;
	private $commentaireManager;
	private $lienManager;
	private $assoManager;
	private $appManager;
	private $twig;


	// Connexion avec la base de donnée 
	public function __construct($db, $twig)
	{
		$this->projetManager = new ProjetsManager($db);
		$this->tagsManager = new TagsManager($db);
		$this->contexteManager = new ContexteManager($db);
		$this->categorieManager = new CategorieManager($db);
		$this->contribuerManager = new ContribuerManager($db);
		$this->membreManager = new MembreManager($db);
		$this->commentaireManager = new CommentaireManager($db);
		$this->lienManager = new LienManager($db);
		$this->assoManager = new AssocierManager($db);
		$this->appManager = new AppartenirManager($db);
		$this->twig = $twig;
	}


	// Fonction qui donne la liste de tous les projets présents dans l'application web 
	public function listeprojet($idutilisateur)
	{
		$projets = $this->projetManager->getList();
		$membre = $this->membreManager->getmembre($idutilisateur);
		echo $this->twig->render('projet_liste.html.twig', array('projet' => $projets, 'membres' => $membre, 'acces' => $_SESSION['acces']));
	}


	public function verifprojet($idutilisateur)
	{
		$projets = $this->projetManager->getListValidation();
		$membre = $this->membreManager->getmembre($idutilisateur);
		echo $this->twig->render('adminprojet.html.twig', array('projet' => $projets, 'membres' => $membre, 'acces' => $_SESSION['acces']));
	}

	public function saisieValidation($idutilisateur)
	{
		$tagsprojet = $this->tagsManager->gettags($_POST["Id_Projet"]);

		// Retourne la ou les catégories du projet 
		$categorieprojet = $this->categorieManager->getcat($_POST["Id_Projet"]);

		// Retourne le contexte du projet 
		$contexteprojet = $this->contexteManager->getcontexte($_POST["Id_Contexte"]);

		// Retourne le ou les contributeurs du projet 
		$utilisateurprojet = $this->membreManager->getProjetsByMembre($_POST["Id_Projet"]);

		// Retourne Titre, description, image 
		$projet = $this->projetManager->getprojet($_POST["Id_Projet"]);
		// Retourne les tags du projet 
		$tags = $this->tagsManager->ajoutag();
		// Retourne la ou les catégories du projet 
		$categorie = $this->categorieManager->ajoucat();
		// Retourne le contexte du projet 
		$contexte = $this->contexteManager->ajoucont();
		// Retourne le ou les contributeurs du projet 
		$listeuti = $this->membreManager->listuti();
		// Retourne les liens du projet 
		$lien = $this->lienManager->getlien($_POST["Id_Projet"]);


		// Si l'utilisateur est connecté appel de la fonction
		$utilisateur = $this->membreManager->getmembre($idutilisateur);

		echo $this->twig->render('projet_validation.html.twig', array(
			'acces' => $_SESSION['acces'],
			'membres' => $utilisateur,

			'tagsprojet' => $tagsprojet,
			'categoriesprojet' => $categorieprojet,
			'contextesprojet' => $contexteprojet,
			'utilisateursprojet' => $utilisateurprojet,

			'projets' => $projet,
			'tags' => $tags,
			'categorie' => $categorie,
			'contextes' => $contexte,
			'utilisateurs' => $listeuti,
			'liens' => $lien,
		));
	}

	public function validationProjet()
	{
		$projet = new Projet($_POST);
		$idprojet = $_POST['Id_Projet'];

		$uploadfile = ""; // Initialise la variable en dehors de la condition

		// Vérifier si un nouveau fichier d'image a été téléchargé
		if ($_FILES["Image"]["error"] == UPLOAD_ERR_OK) {
			// Déplacer le fichier temporaire vers le dossier de destination
			$uploaddir = "./transfert/";
			$uploadfile = $uploaddir . basename($_FILES["Image"]["name"]);

			if (!move_uploaded_file($_FILES["Image"]["tmp_name"], $uploadfile)) {
				echo "Problème lors du téléchargement";
				return false;
			} else {
				$projet->setImage($uploadfile);
			}
		}

		$pro = $this->projetManager->update($projet);

		$liens = new Lien($_POST);
		$lien = $this->lienManager->update($liens);


		$this->assoManager->delete($idprojet);

		foreach ($_POST['Id_Tags'] as $idtags) {
			if (!empty($idtags)) {
				$this->assoManager->insert($idprojet, $idtags);
			}
		}

		$this->appManager->delete($idprojet);
		foreach ($_POST['Id_Categories'] as $idcategorie) {
			if (!empty($idcategorie)) {
				$this->appManager->insert($idprojet, $idcategorie);
			}
		}

		$this->contribuerManager->delete($idprojet);
		foreach ($_POST['Id_Utilisateur'] as $idutilisateur) {
			if (!empty($idutilisateur)) {
				$ok = $this->contribuerManager->insert($idprojet, $idutilisateur);
			}
		}

		$message = $ok ? "Votre projet à bien été modifier" : $message = "Problème lors de la modification";

		echo $this->twig->render('index.html.twig', array(
			'projet' => $pro,
			'message' => $message,
			'lien' => $lien,
			'acces' => $_SESSION['acces']
		));
	}


	// Fonction qui permet d'effectuer la recherche d'un projet dans l'application web 
	public function rechercheProjet()
	{
		$projets = $this->projetManager->search($_POST["projet"]);
		echo $this->twig->render('projet_liste.html.twig', array('projet' => $projets, 'acces' => $_SESSION['acces']));
	}



	// Fonction qui permet d'afficher le projet et toute ses informations
	public function viewprojet($idutilisateur)
	{
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


	// Fonction qui permet d'afficher un form pour ajouter un projet par un utilisateur
	public function formAjoutprojet()
	{
		$tags = $this->tagsManager->ajoutag();
		$categorie = $this->categorieManager->ajoucat();
		$contexte = $this->contexteManager->ajoucont();
		$utilisateur = $this->membreManager->listuti();
		$membre = $this->membreManager->getmembre($_SESSION['idmembre']);
		echo $this->twig->render('projet_ajout.html.twig', array(
			'acces' => $_SESSION['acces'],
			'tags' => $tags,
			'utilisateurs' => $utilisateur,
			'membres' => $membre,
			'categorie' => $categorie,
			'contexte' => $contexte,
		));
	}


	// Fonction qui permet de valider la création d'un nouveau projet
	public function ajoutprojet($idutilisateur)
	{
		$projet = new Projet($_POST);

		// Si l'utilisateur est connecté appel de la fonction
		$utilisateur = $this->membreManager->getmembre($idutilisateur);

		$uploadfile = ""; // Initialise la variable en dehors de la condition

		// Vérifier si un nouveau fichier d'image a été téléchargé
		if ($_FILES["Image"]["error"] == UPLOAD_ERR_OK) {
			// Déplacer le fichier temporaire vers le dossier de destination
			$uploaddir = "./transfert/";
			$uploadfile = $uploaddir . basename($_FILES["Image"]["name"]);

			if (!move_uploaded_file($_FILES["Image"]["tmp_name"], $uploadfile)) {
				echo "Problème lors du téléchargement";
				return false;
			} else {
				$projet->setImage($uploadfile);
			}
		}

		$pro = $this->projetManager->add($projet);
var_dump($pro);	
		$liens = new Lien($_POST);
		$lien = $this->lienManager->add($liens, $pro);


		foreach ($_POST['Id_Tags'] as $idtags) {
			if (!empty($idtags)) {
				$this->assoManager->add($pro, $idtags);
			}
		}

		foreach ($_POST['Id_Categories'] as $idcategorie) {
			if (!empty($idcategorie)) {
				$this->appManager->add($pro, $idcategorie);
			}
		}

		foreach ($_POST['Id_Utilisateur'] as $idutilisateur) {
			if (!empty($idutilisateur)) {
				$ok = $this->contribuerManager->add($pro, $idutilisateur);
			}
		}

		$message = $ok ? "Projet ajouter" : $message = "Problème lors de l'ajout";

		echo $this->twig->render('index.html.twig', array(
			'acces' => $_SESSION['acces'],
			'message' => $message,
			'projet' => $pro,
			'lien' => $lien,
			'membres' => $utilisateur,
			'message' => $message,
		));
	}


	// Fonction qui permet de modifier un projet en affichant un formulaire avec toute les infromations du projet 
	public function saisieModItineraire($idutilisateur)
	{
		$tagsprojet = $this->tagsManager->gettags($_POST["Id_Projet"]);

		// Retourne la ou les catégories du projet 
		$categorieprojet = $this->categorieManager->getcat($_POST["Id_Projet"]);

		// Retourne le contexte du projet 
		$contexteprojet = $this->contexteManager->getcontexte($_POST["Id_Contexte"]);

		// Retourne le ou les contributeurs du projet 
		$utilisateurprojet = $this->membreManager->getProjetsByMembre($_POST["Id_Projet"]);

		// Retourne Titre, description, image 
		$projet = $this->projetManager->getprojet($_POST["Id_Projet"]);
		// Retourne les tags du projet 
		$tags = $this->tagsManager->ajoutag();
		// Retourne la ou les catégories du projet 
		$categorie = $this->categorieManager->ajoucat();
		// Retourne le contexte du projet 
		$contexte = $this->contexteManager->ajoucont();
		// Retourne le ou les contributeurs du projet 
		$listeuti = $this->membreManager->listuti();
		// Retourne les liens du projet 
		$lien = $this->lienManager->getlien($_POST["Id_Projet"]);


		// Si l'utilisateur est connecté appel de la fonction
		$utilisateur = $this->membreManager->getmembre($idutilisateur);

		echo $this->twig->render('projet_modification.html.twig', array(
			'acces' => $_SESSION['acces'],
			'membres' => $utilisateur,

			'tagsprojet' => $tagsprojet,
			'categoriesprojet' => $categorieprojet,
			'contextesprojet' => $contexteprojet,
			'utilisateursprojet' => $utilisateurprojet,

			'projets' => $projet,
			'tags' => $tags,
			'categorie' => $categorie,
			'contextes' => $contexte,
			'utilisateurs' => $listeuti,
			'liens' => $lien,
		));
	}


	// Fonction qui permet de valider la modification d'un projet
	public function modifProjet()
	{

		$projet = new Projet($_POST);
		$idprojet = $_POST['Id_Projet'];

		$uploadfile = ""; // Initialise la variable en dehors de la condition

		// Vérifier si un nouveau fichier d'image a été téléchargé
		if ($_FILES["Image"]["error"] == UPLOAD_ERR_OK) {
			// Déplacer le fichier temporaire vers le dossier de destination
			$uploaddir = "./transfert/";
			$uploadfile = $uploaddir . basename($_FILES["Image"]["name"]);

			if (!move_uploaded_file($_FILES["Image"]["tmp_name"], $uploadfile)) {
				echo "Problème lors du téléchargement";
				return false;
			} else {
				$projet->setImage($uploadfile);
			}
		}

		$pro = $this->projetManager->update($projet);

		$liens = new Lien($_POST);
		$lien = $this->lienManager->update($liens);


		$this->assoManager->delete($idprojet);

		foreach ($_POST['Id_Tags'] as $idtags) {
			if (!empty($idtags)) {
				$this->assoManager->insert($idprojet, $idtags);
			}
		}

		$this->appManager->delete($idprojet);
		foreach ($_POST['Id_Categories'] as $idcategorie) {
			if (!empty($idcategorie)) {
				$this->appManager->insert($idprojet, $idcategorie);
			}
		}

		$this->contribuerManager->delete($idprojet);
		foreach ($_POST['Id_Utilisateur'] as $idutilisateur) {
			if (!empty($idutilisateur)) {
				$ok = $this->contribuerManager->insert($idprojet, $idutilisateur);
			}
		}

		$message = $ok ? "Votre projet à bien été modifier" : $message = "Problème lors de la modification";

		echo $this->twig->render('index.html.twig', array(
			'projet' => $pro,
			'message' => $message,
			'lien' => $lien,
			'acces' => $_SESSION['acces']
		));
	}



	// Fonction qui permet de supprimer toute les informations liée au projet sans impact direct sur les utilisateurs, tags, ou contexte du projet
	public function supprojet()
	{
		$projet = new Projet($_POST);
		$ok = $this->projetManager->deletepro($projet);
		$message = $ok ? "projet supprimé" : $message = "probleme lors de la suppression";
		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
	}
}
