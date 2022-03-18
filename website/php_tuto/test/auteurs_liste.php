<?php
ob_start();
session_start();
error_reporting(E_ALL);
header('Content-Type: text/html; charset=ISO-8859-1');

require('bib_params.php');
require('bib_fonctions.php');

$_SESSION['idAuteur'] = 0;

/*----------------------------------------------------------
-- D�but du traitement des param�tres re�us 
-- (contenus dans $_POST)
----------------------------------------------------------*/
if (isset($_POST['btnChercher'])) {
	// Si on vient de la page de recherche, on r�cup�re
	// les crit�res de recherche, et on les stocke dans
	// la variable de session pour pouvoir les r�utiliser.
	
	// contr�le que les cl�s attendues dans $_POST 
	// sont bien pr�sentes
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
		// Mise � jour de la variable de session
		$_SESSION['recherche']['pos'] = $position;
		$_SESSION['recherche']['nom'] = $nom;
	}
	
} elseif (! isset($_POST['btnListe'])) {
	header('Location: auteurs_cherche.php');
	exit();	//==> FIN piratage ?

}
/*-----------------------------------------------------------
-- Fin du traitement des param�tres re�us 
-----------------------------------------------------------*/

/*-----------------------------------------------------------
-- Envoi du code HTML de la page
-----------------------------------------------------------*/
htmlDebut('Liste auteurs', 'bd.css');

if (count($_SESSION['recherche']) == 2){
	$bd = bdConnecter();
	$nom = mysqli_real_escape_string($bd, 
	                     $_SESSION['recherche']['nom']);
		
	// pour notamment emp�cher un utilisateur de lister  
	// toute la table si il saisit un pourcent 
	// dans le crit�re de recherche
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
	
	//-- Requ�te ----------------------------------------
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
		htmlTable(array('Nom', 'Pr�nom', 'Pays'), 'tab-bd');

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

	// Lib�ration de la m�moire associ�e au 
	// r�sultat de la requ�te
	mysqli_free_result($r);
	
	//-- D�connexion --------------------------------
	mysqli_close($bd);

}

//-- Boutons ----------------------------------------
echo '<form method="POST" class="maj" ',
		 	'action="auteurs_cherche.php">',
	    count($_SESSION['recherche']) == 0 ? 
		'<p class="pagination"> Veuillez saisir un crit�re de recherche non vide</p>': '',
		'<p class="pagination">',
		'<input type="submit" value="Ajouter" ',
			'name="btnNouveau" formaction="auteurs_maj.php">',
		'<input type="submit" value="Recherche" ',
			'name="btnChercher">',
		'</p>',
	'</form>';

htmlFin();
?>