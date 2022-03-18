<?php
ob_start();
session_start();
error_reporting(E_ALL);
header('Content-Type: text/html; charset=ISO-8859-1');

require 'bib_params.php';
require 'bib_fonctions.php';

// Traitement  suivant le bouton trouv� dans
// le post ou l'id trouv� dans le get

//---------------------------------------------------------
// Nouvel auteur : initialisation des valeurs par
// d�faut des zones du formulaires, affichage
// du formulaire de saisie.
if (isset($_POST['btnNouveau'])) {
	//n�cessaire quand on vient de auteurs_maj.php
	$_SESSION['idAuteur'] = 0;
	
	$z = array('auNom' => '',
				'auPrenom' => '',
				'auBiographie' => '',
				'auPays' => 'FR');
	htmlDebut('Ajout auteur', 'bd.css');
	afficherForm($z);
	htmlFin();
	exit();		//==> FIN du traitement nouveau
}

$bd = bdConnecter();

//--------------------------------------------------------
// Affichage d'un auteur : on vient de la page de
// liste. V�rification de l'ID pass� dans l'url,
// s�lection de l'enregistrement auteur dans la
// base, affichage du formulaire.
if (count($_GET) > 0) {
	if (count($_GET) != 1){
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN : piratage ?
	}
	if (! isset($_GET['x'])){
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN : piratage ?
	}
	$idAuteur = decrypterURL($_GET['x']);
	if ($idAuteur === FALSE) {
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN : piratage ?
	}
	if (!estEntier($idAuteur) || $idAuteur <= 0){
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN : piratage ?
	}
	
	$idAuteur = (int) $idAuteur;

	$sql = "SELECT *
			FROM auteurs
			WHERE auID = $idAuteur";
			
	$r = mysqli_query($bd, $sql) or bdErreur($bd, $sql);

	$enr = mysqli_fetch_assoc($r);

	// Lib�ration de la m�moire associ�e au r�sultat de la requ�te
	mysqli_free_result($r);

	if ($enr === NULL) {
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN : piratage ?
	}
	
	$_SESSION['idAuteur'] = (int) $idAuteur;
	
	htmlDebut('Mise � jour/suppression auteur', 'bd.css');
	afficherForm($enr);
	htmlFin();
	
	//-- D�connexion ------------------------------------
	mysqli_close($bd);
	
	exit();		//==> FIN du traitement affichage
}

//--------------------------------------------------------
// Validation d'une saisie dans le cas d'un ajout ou 
// ou d'une modification d'un auteur. 
// Mise � jour de la base de
// donn�es si Ok, 
// sinon r�-affichage du formulaire de
// saisie avec les erreurs d�tect�es.
if (isset($_POST['btnValider'])) {
	$errs = verifierForm();
	
	$titre = $_SESSION['idAuteur'] != 0 ? 
	         'Mise � jour/suppression' : 'Ajout';
	if (count($errs) == 0) {
		if ($_SESSION['idAuteur'] != 0){
			$titre = 'Mise � jour';
		}
		htmlDebut($titre.' auteur', 'bd.css');
		mettreAJourBase($bd);
		afficherFin();
	}
	else{
		// Il y a des erreurs de saisie. Il faut r�afficher
		// le formulaire avec ce qui a �t� saisi
		htmlDebut($titre.' auteur', 'bd.css');
		afficherForm($_POST, $errs);
	}
	mysqli_close($bd);
	htmlFin();
	exit();		//==> FIN du traitement ajout/modification
}

//--------------------------------------------------------
// Suppression d'un auteur
if (isset($_POST['btnSupprimer'])) {
	$sql = "DELETE
			FROM auteurs
			WHERE auID = {$_SESSION['idAuteur']}";
	mysqli_query($bd, $sql) or bdErreur($bd, $sql);
	mysqli_close($bd);
	$_SESSION['idAuteur'] = 0; //plus prudent
	htmlDebut('Suppression auteur', 'bd.css');
	afficherFin();
	htmlFin();
	exit();		//==> FIN du traitement suppression
}

//--------------------------------------------------------
// Si on arrive ici c'est que l'utilisateur n'est
// pas pass� par les pages impos�es.
mysqli_close($bd);
header('Location: auteurs_cherche.php');
exit(); // ===> FIN 
// l'appel d'exit() n'est pas n�cessaire car fin du script

//--------------------------------------------------------
/**
 * Affichage du formulaire de saisie
 *
 * @param array		$z		Tab assoc. zone => valeur
 * @param array		$errs	Tab assoc. zone => msg erreur
 */
function afficherForm($z, $errs = array()) {
	htmlProteger($z);

	echo '<form method="POST" class="maj"',
				' action="auteurs_maj.php">',

			'<label>Nom ',
				'<span class="err">',
				(isset($errs['auNom']) ? $errs['auNom'] : ''),
				'</span>',
				'<input type="text" name="auNom" ',
				'value="',$z['auNom'], '">',
			'</label>',

			'<label>Pr�nom ',
				'<span class="err">',
				(isset($errs['auPrenom']) ? $errs['auPrenom'] : ''),
				'</span>',
				'<input type="text" name="auPrenom" ',
					'value="', $z['auPrenom'], '">',
			'</label>',

			'<label>Pays',
				'<select name="auPays">';
				
	$t = array( 'FR' => 'France', 
	            'US' => 'Etats-unis', 
				'XX' => 'Autre'  );
				
	foreach($t as $cle => $val){
		echo 	'<option value="', $cle, '"',
		        ($z['auPays'] == $cle) ? ' selected>' : '>',
				$val, 
				'</option>';
	}
	
	echo    	'</select>',
			'</label>',

			'<label>Biographie',
				'<textarea name="auBiographie">',
					$z['auBiographie'],
				'</textarea>',
			'</label>',

			'<p class="pagination">',
				'<input type="submit" value="Recherche" ',
					'name="btnChercher" ',
					'formaction="auteurs_cherche.php">',
				'<input type="submit" value="Liste" ',
					'name="btnListe" ',
					'formaction="auteurs_liste.php">';

	// Si nouveau => pas de bouton supprimer, 1 bouton Ajouter
	// Sinon => 1 bouton supprimer et 1 bouton Modifier
	if ($_SESSION['idAuteur'] == 0) {
		echo 	'<input type="submit" value="Ajouter" ',
					'name="btnValider">';
	} else {
		echo 	'<input type="submit" value="Supprimer" ',
					'name="btnSupprimer">',
				'<input type="submit" value="Modifier" ',
					'name="btnValider">';
	}

	echo '</p></form>';
}
//--------------------------------------------------------
/**
 * V�rification des zones de saisie.
 *
 * @global  array     $_POST (modification)
 *
 * @return array	Tab. assoc. zone => msg erreur
 */
function verifierForm() {
	// contr�le que les cl�s attendues 
	// dans $_POST sont bien pr�sentes 
	$clesAttendues = array('auNom', 'auPrenom', 
							'auBiographie', 'auPays');
	foreach ($clesAttendues as $cle){
		if (!isset ($_POST[$cle])){
			header('Location: auteurs_cherche.php');
			exit();	//==> FIN : piratage ?
		}
	}
	
	$errs  = array();

	$_POST['auNom'] = strip_tags($_POST['auNom']);
	$_POST['auNom'] = trim($_POST['auNom']);
	$long = strlen($_POST['auNom']);
	if ($long < 2) {
		$errs['auNom'] = '2 caract�res minimum';
	} elseif ($long > 30) {
		$errs['auNom'] = '30 caract�res maximum';
	}

	$_POST['auPrenom'] = strip_tags($_POST['auPrenom']);
	$_POST['auPrenom'] = trim($_POST['auPrenom']);
	$long = strlen($_POST['auPrenom']);
	if ($long < 2) {
		$errs['auPrenom'] = '2 caract�res minimum';
	} elseif ($long > 20) {
		$errs['auPrenom'] = '20 caract�res maximum';
	}

	$_POST['auBiographie'] = strip_tags($_POST['auBiographie']);
	$_POST['auBiographie'] = trim($_POST['auBiographie']);

	$vals = array('FR', 'US', 'XX');
	if (!in_array($_POST['auPays'], $vals)) {
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN : piratage ?
	}
	return $errs;
}
//--------------------------------------------------------
/**
 * Mise � jour de la base de donn�es
 *
 * @param array		$bd	  objet repr�sentant la connexion au serveur MySQL
 *
 * @global  array   $_SESSION
 * @global  array   $_POST
 */
function mettreAJourBase($bd) {
	// Protection SQL
	$auNom = mysqli_real_escape_string($bd, $_POST['auNom']);
	$auPrenom = mysqli_real_escape_string($bd, $_POST['auPrenom']);
	$auBiographie = mysqli_real_escape_string($bd, 
	                                       $_POST['auBiographie']);

	$sql = "auNom = '$auNom',
			auPrenom = '$auPrenom',
			auPays = '{$_POST['auPays']}',
			auBiographie = '$auBiographie'";

	if ($_SESSION['idAuteur'] == 0) {
		$sql = "INSERT INTO auteurs SET $sql";
	} else {
		$sql = "UPDATE auteurs
				SET $sql
				WHERE auID = {$_SESSION['idAuteur']}";
	}

	mysqli_query($bd, $sql) or bdErreur($bd, $sql);
}
//--------------------------------------------------------
/**
 * Affichage de la page de fin de traitement de la bd.
 *
 * @global  array     $_SESSION
 * @global  array     $_POST  
 */
function afficherFin() {
	echo '<form method="POST" class="maj pagination" ',
				'action="auteurs_cherche.php">';

	if (isset($_POST['btnSupprimer'])) {
		echo '<p>L\'auteur a bien �t� supprim�.</p>';
	} elseif ($_SESSION['idAuteur'] == 0) {
		echo '<p>Le nouvel auteur a bien �t� ajout�.</p>';
	} else {
		echo '<p>L\'auteur a bien �t� modifi�.</p>';
	}

	echo '<p>',
			'<input type="submit" value="Rechercher" name="btnChercher">',
			'<input type="submit" value="Liste" name="btnListe" ',
				'formaction="auteurs_liste.php">',
			'<input type="submit" value="Ajouter" name="btnNouveau" ',
				'formaction="auteurs_maj.php">',
		'</p>';

	echo '</form>';
}
?>