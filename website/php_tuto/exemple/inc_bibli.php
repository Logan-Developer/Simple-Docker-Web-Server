<?php
define('TP_L2', ($_SERVER['SERVER_ADDR'] == '172.20.128.72'));
define('COOKIE', 'php_tuto');

//___________________________________________________________________
/**
 * Envoie à la sortie standard le début du code HTML d'une page
 *
 * @param string	$titre	Titre de la page
 */
function fp_htmlDebut($Titre) {
	echo '<!DOCTYPE html><html><head>',
		'<meta charset="ISO-8859-1"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">',
		'<title>', $Titre, '</title>',
		'<link rel="stylesheet" type="text/css" href="exemple.css">',
		'<link rel="stylesheet" type="text/css" href="x_redips_dialog.css">',
		'<script src="exemple.js"></script>',
		'<script src="x_redips_dialog.js"></script>',
		'</head>',
		'<body><h1>', $Titre, '</h1><div id="btnClose" onclick="top.FP.Voir.hidePLUS()"></div>';
}
//___________________________________________________________________
/**
 * Renvoie le chemin complet du dossier de base
 *
 * @return string	chemin complet du dossier de base
 */
function fp_getRepAppli() {
	$repert = (TP_L2) ? realpath('./../../') : realpath('./../');
	$repert = str_replace('\\','/',$repert);

	if (substr($repert, -1) != '/') {
		$repert .= '/';
	}

	return $repert;
}

//___________________________________________________________________
/**
 * Renvoie le nom du dossier de test de l'utilisateur
 *
 * @return string	nom du dossier de test
 */
function fp_getRepUser() {
	if (! TP_L2) {
		return '';
	}

	if (! isset($_COOKIE[COOKIE])) {
		return '--';
	}

	$repUser = trim($_COOKIE[COOKIE]);
	if (strpos($repUser, '.') === TRUE) {
		exit();
	}

	return $repUser;
}
//___________________________________________________________________
function fp_isUser($nom) {
	if (!TP_L2) {
		return true;
	}

	$fichier = '../../users.txt';
	if (!file_exists($fichier)
	|| !is_file($fichier))
	{
		exit('Le fichier des utilisateurs n\'existe pas.');
	}

	$pointeur = fopen($fichier,'r');
	$buffer = fread($pointeur, filesize($fichier));
	fclose($pointeur);
	$Enregs = explode(',', $buffer);

	return in_array($nom, $Enregs);
}
//___________________________________________________________________
/**
 * Renvoie le contenu d'un dossier sous la forme d'une matrice
 *
 * @param string	$repert		nom du réperoire à lister
 * @return array	contenu du dossier
 */
function fp_listeRepert($repert) {
	clearstatcache();
	if(substr($repert, -1) != '/') {
		$repert .= '/';
	}
	$sousReps = array();
	$fichiers = array();
	$liste = array();
	$pointeur = @opendir($repert);
	if (!$pointeur) {
		return $liste;
	}

	while (false!==($fichier = readdir($pointeur))) {
		if ($fichier == '.' || $fichier == '..') {
			continue;
		}

		if (@is_dir("{$repert}{$fichier}")) {
			$sousReps[] = $fichier;
		} else {
			$fichiers[] = $fichier;
		}
	}

	@closedir($pointeur);
	@natsort($sousReps);
	@natsort($fichiers);

	$nb = 0;
	for ($i = 0,$iMax = count($sousReps); $i < $iMax; $i++) {
		$liste[$nb]['nom'] = $sousReps[$i];
		$liste[$nb]['taille'] = '';
		$liste[$nb]['date'] = date('d/m/Y',filemtime($repert.$sousReps[$i]));
		$liste[$nb]['dir'] = TRUE;
		$nb ++;
	}
	for ($i = 0, $iMax = count($fichiers); $i < $iMax; $i++) {
		$liste[$nb]['nom'] = $fichiers[$i];
		$liste[$nb]['taille'] = fp_getOctets(filesize($repert.$fichiers[$i]));
		$liste[$nb]['date'] = date('d/m/Y',filemtime($repert.$fichiers[$i]));
		$liste[$nb]['dir'] = FALSE;
		$nb ++;
	}

	return $liste;
}
//___________________________________________________________________
/**
 * Renvoie une taille en octets en ko, Mo, etc
 *
 * @param integer	$taille		taille en octet
 * @return string	taille tranformée
 */
function fp_getOctets($taille) {
	if ($taille == 0) return '0 o';
	elseif ($taille <= 1024) return $taille.' o';
	elseif ($taille <= (10*1024)) return sprintf ("%.2f k%s",($taille/1024),'o');
	elseif ($taille <= (100*1024)) return sprintf ("%.1f k%s",($taille/1024),'o');
	elseif ($taille <= (1024*1024)) return sprintf ("%d k%s",($taille/1024),'o');
	elseif ($taille <= (10*1024*1024)) return sprintf ("%.2f M%s",($taille/(1024*1024)),'o');
	elseif ($taille <= (100*1024*1024)) return sprintf ("%.1f M%s",($taille/(1024*1024)),'o');
	else return sprintf ("%d M%s",($taille/(1024*1024)),'o');
}
//___________________________________________________________________
/**
 *  Création de dossier.
 *
 *  @param string	$repert		chemin complet du dossier à créer,
 *  							avec des / comme séparateur. Tous les
 *  							dossiers (sauf le dernier) doivent exister.
 *  @param integer	$Mode		droits d'accés au dossier, en octal
 *
 *  @return	integer	0 ou no d'erreur
 */
function fp_makeRepert($repert, $Mode = 0777) {
	if (@is_dir($repert)) {
		return -2;
	}

	@mkdir($repert, $Mode);
	@chmod ($repert, $Mode);

	if (!@is_dir($repert)) {
		return -1;
	}

	return 0;
}
//___________________________________________________________________
/**
 * Supprime un dossier et son contenu (fonction récursive)
 *
 * @param string	$repert	chemin complet du dossier à supprimer
 *
 * @return integer	0 ou no d'erreur
 */
function fp_deleteRepert($repert) {
	$Erreur = 0;

	if (! @is_dir($repert)) {
		return 0;
	}

	$pointeur = @opendir($repert);
	while (false!==($fichier = readdir($pointeur))) {
		if ($fichier == '.' || $fichier == '..') {
			continue;
		}

		$Element = $repert.'/'.$fichier;
		if (@is_dir($Element)) {
			$Erreur = fp_deleteRepert($Element);
		} else {
			if (!@unlink($Element)) {
				$Erreur = -1;
			}
		}

		if ($Erreur != 0) {
			break;
		}
	}

	@closedir($pointeur);

	if (!@rmdir($repert)) {
		$Erreur = -2;
	}

	return $Erreur;
}
//___________________________________________________________________
/**
 * Copie un dossier et son contenu (fonction récursive)
 *
 * @param string	$repSource	Chemin complet du dossier source
 * @param string	$repDestin	Chemin complet du dossier de
 * 								destination - ne doit pas exister
 * @return integer	0 ou no d'erreur
 */
function fs_copieRep($repSource, $repDestin) {
	$Erreur = 0;
	if (!is_dir($repSource)) {
		return -3;  // pas de dossier source
	}

	$pointeur  = @opendir($repSource);
	if (fp_makeRepert($repDestin) != 0) {
		return -2;  // peut pas creer un dossier de destination
	}

	while (false!==($fichier = readdir($pointeur))) {
		if ($fichier == '.' || $fichier == '..') {
			continue;
		}
		$sourceComplet = "$repSource/$fichier";
		$destinComplet = "$repDestin/$fichier";
		if (@is_dir($sourceComplet)) {
			$Erreur = fs_copieRep($sourceComplet, $destinComplet);
		} else {
			if (!@copy($sourceComplet, $destinComplet)) {
				$Erreur = -1;
			}
		}
		if ($Erreur != 0) {
			break;
		}
	}

	@closedir($pointeur);

	return $Erreur;
}
//___________________________________________________________________
function fp_spy() {
	$args = func_get_args();
	foreach ($args as $a) {
		echo '<div style=\'background: #FFF; border:1px solid #000; padding: 4px; margin: 4px; z-index:999999\'>';
		if (is_array($a)) {
			echo '<pre>', print_r($a, true), '</pre>';
		} else {
			echo  htmlentities($a);
		}
		echo '</div>';
	}
}
?>