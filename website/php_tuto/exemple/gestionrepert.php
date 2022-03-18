<?php
/**
 * Gestion du dossier de test d'un utilisateur.
 *
 * Ce script est utilitaire, mais également écrit à des fins d'exemple.
 * Des informations normalement cryptées ne le sont pas pour ne pas
 * compliquer les choses.
 *
 * @param string	$_POST['Traite']	Traitement à effectuer
 * @param string	$_GET['Traite']		Traitement à effectuer
 * 										- rien ou vide => 'affiche'
 * 										- creerRep => création d'un sous-dossier
 * 										- deleteRep => suppression d'un sous-dossier
 * 										- deleteFic => suppression d'un fichier
 *
 * @param string	$_POST['DeleteNom']	Nom d'un sous-dossier ou d'un fichier à supprimer
 * @param string	$_POST['NewRep']	Nom d'un sous-dossier à créer
 * @param string	$_POST['RepCourant']	Nom du dossier courant
 */
ob_start();

require ('inc_bibli.php');
header('Content-Type: text/html; charset=ISO-8859-1');
$relatif = (TP_L2) ? '../../test' : '../test';
$repAppliTest = fp_getRepAppli().'test';

$repUser = fp_getRepUser();

if ($repUser == '--') {
	// Initialisation du dossier de test - uniquement TP L2
	if (isset($_POST['Traite'])
	&& $_POST['Traite'] == 'init')
	{
		$repUser = fpl_folderUserInit($_POST['Repert']);

	} else {
		fpl_htmlFolderUserInit();
		exit();
	}
}

fp_htmlDebut('Dossier de travail');

echo '<div id="bcHelp">',
		'Cliquez sur un dossier pour l\'ouvrir.',
		'<br><img src="images/action_add.png"> crée un sous-dossier.',
		'<br><img src="images/action_remove.png">supprime le fichier ou le dossier.',
		'<br><img src="images/arrow_down.png"> uploade un fichier dans le dossier ',
		'ou glissez-déposez un fichier sur le nom d\'un dossier.</div>';

echo '<form method="post" action="gestionrepert.php">',
		'<ol class="FS">';

$id = 0;
fpl_echoContenuRep("$repAppliTest/$repUser");

echo '</ol><input type="hidden" id="Nb" value="', $id, '"></form></body></html>';

//___________________________________________________________________
/**
 * Affichage du contenu d'un dossier - fonction récursive
 *
 * @param string	$repSource	Chemin complet du dossier source
 * @param string	$repDestin	Chemin complet du dossier de
 * 								destination - ne doit pas exister
 * @return integer	0 ou no d'erreur
 */
function fpl_echoContenuRep($repert, $niveau=0) {
	if(substr($repert, -1) != '/') {
		$repert .= '/';
	}

	$items = fp_listeRepert($repert);

	$iMax = count($items);
	$GLOBALS['id']++;
	$idLI = "LR_{$GLOBALS['id']}";
	$idCHK = "CK_{$GLOBALS['id']}";
	$href = str_replace($GLOBALS['repAppliTest'], '', $repert);
	$href = $GLOBALS['relatif'].$href;
	$nom = substr($repert, 0, -1);
	$nom = substr($nom, strrpos($nom, '/') + 1);

	// Ligne pour le dossier (dupli dans FP.Folder.addBack.js)
	echo "<li class='FS-li-folder' id='$idLI' data-href='$href' data-newrep=''>",
		'<label class="FS-folder-name', (($niveau == 0) ? ' FS-folder-name-opened"' : '"'),
			" id='LL_{$GLOBALS['id']}' onclick='FP.Folder.click(event, \"$idLI\")'>$nom",
		"<span onclick='FP.Folder.click(event, \"$idLI\")'>",
		'<a class="FS-btn-upl"></a>',
		'<a class="FS-btn-add"></a>',
		'<a class="FS-btn-del"></a>',
		'</span>',
		'</label>';

	if ($niveau == 0) {
		echo '<ol class="FS-folder-opened">';
	} else {
		echo '<ol class="FS-folder-closed">';
	}

	// Contenu du dossier
	for ($i = 0; $i < $iMax; $i++) {
		$nom = $items[$i]['nom'];
		$complet = "{$repert}{$nom}";

		if ($items[$i]['dir']) {
			fpl_echoContenuRep($complet, $niveau + 1);
		} else {
			// Ligne pour un fichier (dupli dans FP.Upl.back.js)
			$GLOBALS['id']++;
			$idLI = "LF_{$GLOBALS['id']}";
			$href = str_replace($GLOBALS['repAppliTest'], '', $complet);
			$href = $GLOBALS['relatif'].$href;
			echo "<li class='FS-li-file' id='$idLI' data-href='$href'>",
				"<a class='FS-file-name' id='LA_{$GLOBALS['id']}' href='$href' target='piatphp'>$nom",
				"<span class='FS-btn-file-del' onclick='FP.Folder.click(event, \"$idLI\")'></span>",
				'</a>',
				'</li>';
		}
	}

	echo '</ol></li>';
}
//_______________________________________________________________
/**
 * Création du dossier de test de l'utilisateur
 *
 * @param string	$nomRep		nom du dossier à créer
 * @return string	Le nom du dossier créé
 */
function fpl_folderUserInit($nomRep) {
	if (! TP_L2) {
		$nomRep = '.';
	} else {
		$nomRep = trim($nomRep);

		if (!preg_match('/^[a-z]+$/', $nomRep)) {
			exit('Le nom saisi n\'est pas valide.');
		}

		if (!fp_isUser($nomRep)) {
			exit('Votre nom n\'est pas reconnu. Pr&eacute;venez l\'enseignant.');
		}


		$repert = fp_getRepAppli()."test/$nomRep";
		$erreur = fp_makeRepert($repert);

		if ($erreur == -1) {
			exit ("Le dossier $nomRep n'a pas pu &ecirc;tre cr&eacute;&eacute;. Pr&eacute;venez l'enseignant");
		}
	}

	setcookie(COOKIE, $nomRep, time() + (3600 * 24 * 365), '/');

	return $nomRep;
}

//_______________________________________________________________
/**
 * Page de saisie initialisation du dossier de test / travail
 */
function fpl_htmlFolderUserInit() {
	fp_htmlDebut('Initialisation de votre dossier de travail');

	echo '<form method="post" action="gestionrepert.php">',
		'<input type="hidden" name="Traite" value="init">',
		'<div id="bcTxt">',
		'<p>Votre dossier de travail n\'existe pas sur le serveur.</p>',
		'<p>Pour le créer, saisissez votre nom de famille (caractères alphabétiques, ',
		'sans accent, en minuscule uniquement, sans tiret ni espace) puis cliquez sur le bouton Initialiser.</p>',
		'<p>Votre nom : <input name="Repert" type="text" style="width: 150px">',
		'<input type="button" name="btnInit" class="btn" value="Initialiser" onclick="FP.formUserInit(this.form.Repert, this)">',
		'</div>',
		'</form>',
		'<div id="bcMsg"></div>',
		'</body></html>';
}
?>