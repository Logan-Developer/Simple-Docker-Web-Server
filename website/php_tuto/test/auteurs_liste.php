<?php
ob_start();
session_start();
error_reporting(E_ALL);
header('Content-Type: text/html; charset=ISO-8859-1');

require('bib_params.php');
require('bib_fonctions.php');

$_SESSION['idAuteur'] = 0;

/*----------------------------------------------------------
-- Début du traitement des paramètres reçus 
-- (contenus dans $_POST)
----------------------------------------------------------*/
if (isset($_POST['btnChercher'])) {
	// Si on vient de la page de recherche, on récupère
	// les critères de recherche, et on les stocke dans
	// la variable de session pour pouvoir les réutiliser.
	
	// contrôle que les clés attendues dans $_POST 
	// sont bien présentes
	if (!isset($_POST['radNom']) || !isset($_POST['txtNom'])) {
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN piratage ?
	}
	if (!estEntier($_POST['radNom'])) {
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN piratage ?
	}

	$position = (int) $_POST['radNom'];
	if (!estEntre($position, 1, 3)) {
		header('Location: auteurs_cherche.php');
		exit();	//==> FIN piratage ?
	}

	$nom = trim($_POST['txtNom']);
	
	if ($nom != '') {
		// Mise à jour de la variable de session
		$_SESSION['recherche']['pos'] = $position;
		$_SESSION['recherche']['nom'] = $nom;
	}
	
} elseif (! isset($_POST['btnListe'])) {
	header('Location: auteurs_cherche.php');
	exit();	//==> FIN piratage ?

}
/*-----------------------------------------------------------
-- Fin du traitement des paramètres reçus 
-----------------------------------------------------------*/

/*-----------------------------------------------------------
-- Envoi du code HTML de la page
-----------------------------------------------------------*/
htmlDebut('Liste auteurs', 'bd.css');

if (count($_SESSION['recherche']) == 2){
	$bd = bdConnecter();
	$nom = mysqli_real_escape_string($bd, 
	                     $_SESSION['recherche']['nom']);
		
	// pour notamment empécher un utilisateur de lister  
	// toute la table si il saisit un pourcent 
	// dans le critère de recherche
	$nom = addcslashes($nom, '%_');
	
	$message = 'Recherche des auteurs dont le nom ';
	
	if ($_SESSION['recherche']['pos'] == 1) {
		$where = "WHERE auNom LIKE '$nom%'";
		$message .= 'commence par "';
	} elseif ($_SESSION['recherche']['pos'] == 2) {
		$where = "WHERE auNom LIKE '%$nom%'";
		$message .= 'contient "';
	} else {
		$where = "WHERE auNom LIKE '%$nom'";
		$message .= 'finit par "';
	}
	$message .= htmlentities($_SESSION['recherche']['nom'], 
	                         ENT_COMPAT, 'ISO-8859-1').'".';
	echo '<p class="pagination">', $message, '</p>';
	
	//-- Requête ----------------------------------------
	$sql = "SELECT auID, auNom, auPrenom, auPays
			FROM auteurs
			$where
			ORDER BY auNom, auPrenom";

	$r = mysqli_query($bd, $sql) or bdErreur($bd, $sql);

	//-- Traitement -------------------------------------
	if (mysqli_num_rows($r) == 0){
		echo '<p class="pagination">La liste est vide</p>';
	}
	else{
		htmlTable(array('Nom', 'Prénom', 'Pays'), 'tab-bd');

		while ($enr = mysqli_fetch_assoc($r)) {
			htmlProteger($enr);

			$id = crypterURl($enr['auID']);
			$enr['auNom'] = '<a href="auteurs_maj.php?x='
			                    .$id.'">'
								.$enr['auNom'].'</a>';
			unset($enr['auID']);

			htmlLigne($enr);
		}

		echo '</table>';
	}

	// Libération de la mémoire associée au 
	// résultat de la requête
	mysqli_free_result($r);
	
	//-- Déconnexion --------------------------------
	mysqli_close($bd);

}

//-- Boutons ----------------------------------------
echo '<form method="POST" class="maj" ',
		 	'action="auteurs_cherche.php">',
	    count($_SESSION['recherche']) == 0 ? 
		'<p class="pagination"> Veuillez saisir un critère de recherche non vide</p>': '',
		'<p class="pagination">',
		'<input type="submit" value="Ajouter" ',
			'name="btnNouveau" formaction="auteurs_maj.php">',
		'<input type="submit" value="Recherche" ',
			'name="btnChercher">',
		'</p>',
	'</form>';

htmlFin();
?>