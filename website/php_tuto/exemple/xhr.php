<?php
/**
 * Gestion des appels AJAX. Tout est envoyé en POST.
 */
require ('inc_bibli.php');

$msg = '';

if (count($_POST) == 0) {
	exit();
}

switch ($_POST['t']) {
	case 'upload':
		$msg = fpl_upload($_POST['chemin'], $_FILES['fichier']);
		break;

	case 'folderDel':
		$msg = (fp_deleteRepert(realpath($_POST['chemin'])) == 0)
				? ''
				: 'Le dossier '.basename($_POST['chemin']).' n\'a pas pu être supprimé. Contactez votre enseignant.';
		break;

	case 'folderAdd':
		$msg = fpl_folderAdd($_POST['chemin'], $_POST['newRep']);
		break;

	case 'fileDel':
		$msg = (@unlink(realpath($_POST['chemin'])))
				? ''
				: 'Le fichier '.basename($_POST['chemin']).' n\'a pas pu être supprimé. Contactez votre enseignant.';
		break;
}

echo $msg;

//_______________________________________________________________
/**
 * Upload d'un fichier
 *
 * @param string	$chemin		Chemin par rapport à l'appli où uploader le fichier
 * @param string	$F			Descripteurs de $_FILES['fichier']
 */
function fpl_upload($chemin, $F) {
	$tailleMax = 100 * 1024;
	$exts = array('php', 'htm', 'css', 'gif', 'jpg', 'png', 'js', 'html');

	//-------------------------------------------------
	// Vérification nom, taille et extensions permises
	if (empty($F)) {
		return 'Erreur -1 pendant le transfert. Contactez votre enseignant.';
	}

	$fichierTmp = $F['tmp_name'];
	$fichierNom = $F['name'];
	$fichierType = $F['type'];
	$fichierTaille = $F['size'];

	if ($fichierTaille == 0
			|| $fichierTaille > $tailleMax)
	{
		return 'Fichier trop gros.';
	}

	$fichierExt = pathinfo($fichierNom, PATHINFO_EXTENSION);
	if (! in_array($fichierExt, $exts)) {
		return 'Type de fichier interdit en téléchargement.';
	}

	//-------------------------------------------------
	// Traitement du fichier uploadé

	$chemin .= "/$fichierNom";

	if (! @is_uploaded_file($fichierTmp)) {
		return 'Erreur -2 pendant le transfert. Contactez votre enseignant.';
	}

	if (! @move_uploaded_file($fichierTmp, $chemin)) {
		return 'Erreur -3 pendant le transfert. Contactez votre enseignant.';
	}

	@chmod($destin, 0440);

	return '';
}

//_______________________________________________________________
/**
 * Ajout d'un dossier dans une arborescence
 *
 * @param string	$chemin		Chemin par rapport à l'appli où ajouter le dossier
 * @param string	$newRep		Nom du dossier à ajouter
 */
function fpl_folderAdd($chemin, $newRep) {
	$newRep = trim($newRep);

	if (!preg_match('/^[a-z0-9_]{1,30}$/', $newRep)) {
		return 'Le nom du dossier n\'est pas valide.';
	}

	if (substr($chemin, -1) == '/') {
		$chemin = substr($chemin, 0, -1);
	}

	$newComplet = "$chemin/$newRep";

	if (@is_dir($newComplet)) {
		return "Le dossier $newRep existe déjà.";
	}

	if (fp_makeRepert($newComplet) != 0) {
		return "Le dossier $newRep n'a pas pu être créé. Contactez votre enseignant.";
	}

	return '';
}
?>