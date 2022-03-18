<?php
/**
 * Initialisation / copie des dossiers de test utilisés pour
 * le chapitre sur les fichiers et dossiers.
 *
 * Ce script est utilitaire, mais également écrit à des fins d'exemple.
 * Des informations normalement cryptées ne le sont pas pour ne pas
 * compliquer les choses.
 *
 * @param string	$_POST['x']		Traitement à effectuer
 * @param string	$_GET['x']		Traitement à effectuer
 * 										- f => init du dossier test_fichiers
 * 										- d => init du dossier test_repert
 * 										- l => init du dossier test_liens
 */
ob_start();

require ('inc_bibli.php');

if (isset($_POST['x'])) {
	$traite = $_POST['x'];
} elseif (isset($_GET['x'])) {
	$traite = $_GET['x'];
} else {
	$traite = '';
}

if ($traite != 'f' && $traite != 'd' && $traite != 'l') {
	exit();
}

$inits = array('f' => 'test_fichiers',
				'd' => 'test_repert',
				'l' => 'test_liens');

fp_htmlDebut("Initialisation du dossier {$inits[$traite]}");

$repUser = fp_getRepUser();

if ($repUser == '--') {
	echo '<p>Vous devez déjà <a href="gestionrepert.php">initialiser votre dossier de dossier de travail</a>.</p>';
	exit();
}

$repAppliTest = fp_getRepAppli().'test';


if (isset($_POST['x'])) {  // Second passage : init du répertoire
	$repert = "$repAppliTest/$repUser/{$inits[$traite]}";
	fp_deleteRepert($repert);

	if (TP_L2) {
		$source = fp_getRepAppli()."php/exemple/{$inits[$traite]}";
	} else {
		$source = fp_getRepAppli()."exemple/{$inits[$traite]}";
	}
	fs_copieRep($source, $repert);

	header('Location: gestionrepert.php');
	exit();
}
?>
<SCRIPT>
function FP_Traite() {
	document.getElementById('bcMsg').innerHTML = 'Initialisation du dossier en cours ...';
	document.forms[0].submit();
}
</SCRIPT>
<?php
echo '<form method="post" action="init_chap09.php">',
		'<input type="hidden" name="x" value="', $traite, '">',
		'<div id="bcTxt"><p>&nbsp;</p>',
		'<p>Vous allez initialiser le dossier ', $inits[$traite],
		' qui permet de réaliser les tests du chapitre "Fichiers et dossiers".',
		'<div id="bcMsg"><input type="button" onclick="FP_Traite()" value="OK"></div>',
		'</div></form></body></html>';
?>