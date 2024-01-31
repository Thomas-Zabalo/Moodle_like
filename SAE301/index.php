<?php


// utilisation des sessions
session_start();

include "moteurtemplate.php";
include "connect.php";

//Controller 
include "Controllers/projetsController.php";
include "Controllers/membresController.php";
include "Controllers/tagsController.php";
include "Controllers/contextesController.php";
include "Controllers/categoriesController.php";
include "Controllers/commentaireController.php";
include "Controllers/associerController.php";
include "Controllers/appartenirController.php";
include "Controllers/contribuerController.php";
include "Controllers/lienController.php";


$projetController = new ProjetsController($bdd, $twig);
$memController = new MembreController($bdd, $twig);
$comController = new CommentaireController($bdd, $twig);
$catController = new CategorieController($bdd, $twig);
$conController = new ContexteController($bdd, $twig);
$tagController = new TagsController($bdd, $twig);
$assoController = new AssocierController($bdd, $twig);
$appController = new AppartenirController($bdd, $twig);
$contribueController = new ContribuerController($bdd, $twig);
$lienController = new LienController($bdd, $twig);


// texte du message
$message = "";


// ============================== CONNEXION / DECONNEXION - SESSION ==================

// si la variable de session n'existe pas, on la crée
if (!isset($_SESSION['acces'])) {
  // Initialisation de la variable de session 'acces' à "non"
  $_SESSION['acces'] = "non";

  // Vérification si le cookie 'COOKIE_APPLICATION' existe
  if (isset($_COOKIE['COOKIE_APPLICATION'])) {
      // Renouvellement du cookie tant que l'utilisateur est sur le site
      setcookie('COOKIE_APPLICATION', $_COOKIE['COOKIE_APPLICATION'], time() + (3 * 24 * 60 * 60)); // 3 jours en secondes
      // Mise à jour de la variable de session 'acces' à "oui"
      $_SESSION['acces'] = "oui";
  }
}

if (!isset($_SESSION['idmembre'])) {
  $_SESSION['idmembre'] = "non";
}


// ============================== CONNEXION ==============================

// Formulaire de connexion
if (isset($_GET["action"])  && $_GET["action"] == "login") {
  $memController->membreFormulaire();
}

// Connexion : click sur le bouton connexion
if (isset($_POST["connexion"])) {
  $memController->membreConnexion($_SESSION['idmembre']);
}


// ============================== INSCRIPTION ==============================

// Formulaire d'inscription'
if (isset($_GET["action"])  && $_GET["action"] == "inscription") {
  $memController->mbInscription();
}

// Ajout d'un compte dans la base de donnée
// Inscription : click sur le bouton inscription
if (isset($_POST["valider_compte"])) {
  $memController->membreInscription();
}


// ============================== DECONNEXIONl ==============================

// Deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action'] == "logout") {
  $message = $memController->membreDeconnexion();
}


// ============================== PAGE D'ACCUEIL ==============================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  if (isset($_SESSION['idmembre'])) {
    $memController->mbconnec($_SESSION['idmembre']);
  } else {
    echo $twig->render('index.html.twig', array('acces' => $_SESSION['acces']));
  }
}


// ============================== GESTION DE L'UTILISATEUR ==============================

// Affichage du membre grâce à la connexion 
if (isset($_GET["action"]) && $_GET["action"] == "moi") {
  $memController->membreView($_SESSION['idmembre']);
}

if (isset($_GET["action"]) && $_GET["action"] == "modif_profil") {
  $memController->modifmembre($_SESSION['idmembre']);
}

// Recherche : Validation de la recherche
if (isset($_POST["valider_profil"])) {
  $memController->validerModMembre($_SESSION['idmembre']);
}


// ============================== LISTE PROJET ==============================

// Liste de tous les projets présents dans l'application web 
if (isset($_GET["action"]) && $_GET["action"] == "liste") {
  $projetController->listeprojet($_SESSION['idmembre']);
}



// ============================== RECHERCHE PROJET ==============================

// Recherche : Validation de la recherche
if (isset($_POST["valider_recher"])) {
  $projetController->rechercheProjet();
}


// ============================== VISUALISATION PROJET ==============================

// Details : Voir toutes les informations du projet
if (isset($_POST["details"])) {
  $projetController->viewprojet($_SESSION['idmembre']);
}

// Ajout commentaire : Chaque utilisateur peut ajouter un ou plusieurs commentaires à un projet
if (isset($_POST["ajout_com"])) {
  $comController->ajoutcom($_SESSION['idmembre']);
}


// ============================== AJOUT PROJET ==============================

// Ajout projet : Chaque membre peut ajouter un projet depuis son espace utilisateur
if (isset($_POST["ajout_pro"])) {
  $projetController->formAjoutprojet($_SESSION['idmembre']);
}

// Ajout du projet dans la base de donnée
if (isset($_POST["valider_ajout"])) {
  $projetController->ajoutprojet($_SESSION['idmembre']);
}


// ============================== SUPPRESSION PROJET ==============================

// Suppression d'un projet : Supprime le projet en fonction de son ID
if (isset($_POST["sup_pro"])) {
  $projetController->supprojet();
}


// ============================== MODIFICATION PROJET ==============================

// Modification d'un projet : Choix du projet à modifier
if (isset($_POST["modif"])) {
  $projetController->saisieModItineraire($_SESSION['idmembre']);
}
// Modification d'un projet : Validation de la modification du projet
if (isset($_POST["valider_modif"])) {
  $projetController->modifProjet();
}


// ============================== ADMIN ==============================


// ============ GESTION UTILISATEURS ============

// Visualisation de tous les utilisateurs présents dans l'application web 
if (isset($_POST["utilisateur"])) {
  $memController->adminutilisateur($_SESSION['idmembre']);
}

// Ajout d'un utilisateur dans la base de donnée
if (isset($_POST["valider_uti"])) {
  $memController->ajout_uti();
}

// Suppression d'un utilisateur dans la base de donnée
if (isset($_POST["sup_uti"])) {
  $memController->sup_uti();
}


// ============ GESTION CATEGORIES ============

// Visualisation de toutes les catégories présentent dans l'application web 
if (isset($_POST["categorie"])) {
  $memController->admincategorie($_SESSION['idmembre']);
}

// Ajout d'une catégorie dans la base de donnée
if (isset($_POST["valider_cat"])) {
  $catController->ajout_cat();
}

// Suppression d'une catégorie dans la base de donnée
if (isset($_POST["sup_cat"])) {
  $catController->sup_cat();
}


// ============ GESTION RESSOURCES ============


// Visualisation de toutes les ressources présentent dans l'application web 
if (isset($_POST["ressource"])) {
  $memController->adminressource($_SESSION['idmembre']);
}

// Ajout d'une ressource dans la base de donnée
if (isset($_POST["valider_cont"])) {
  $conController->ajout_cont();
}

// Suppression d'une ressource dans la base de donnée
if (isset($_POST["sup_cont"])) {
  $conController->sup_cont();
}

// ============ VALIDATION PROJET ============

if (isset($_POST["projets"])) {
  $projetController->verifprojet($_SESSION['idmembre']);
}


if (isset($_POST["verif_pro"])) {
  $projetController->saisieValidation($_SESSION['idmembre']);
}

if (isset($_POST["valider_pro"])) {
  $projetController->validationProjet();
}

