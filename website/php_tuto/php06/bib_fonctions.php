<?php
//
// Bibliothèque de fonctions PHP
//
//___________________________________________________________________
/**
 * Envoie à la sortie standard le début du code HTML d'une page
 *
 * @param string	$titre	Titre de la page
 */
function htmlDebut($titre) {
	$titre = htmlentities($titre);

	echo '<!DOCTYPE html>',
			'<html>',
				'<head>',
					'<meta charset="ISO-8859-1">',
					'<title>', $titre, '</title>',
					'<style>',
					'body {font-size: 13px;font-family: Verdana, sans-serif}',
					'h3 {font-size: 15px;margin: 0 0 15px 0;padding: 5px 0;text-align:center;background: #FFF5AB}',
					'h4 {font-size: 13px;margin: 1em 0 0 0;padding: 3px;background: #ebebeb}',
					'label {display: block;font-weight: bold;margin-top: 10px;}',
					'</style>',
				'</head>',
				'<body>',
					'<h3>', $titre, '</h3>';
}
//___________________________________________________________________
/**
 * Envoie à la sortie standard la fin du code HTML d'une page
 */
function htmlFin() {
	echo '</body></html>';
}
//___________________________________________________________________
/**
 * Envoie à la sortie standard une info / titre pour les exemples
 *
 * @param string	$txt	Texte à afficher
 */
function htmlInfo($txt) {
	echo '<h4>', htmlentities($txt), '</h4>';
}
//___________________________________________________________________
/**
 * Envoie à la sortie standard le nombre d'éléments et le contenu d'un tableau
 *
 * @param string	$t	Tableau
 */
function infoTableau($t) {
	echo 'Tableau de ', count($t), ' &eacute;l&eacute;ments';
	echo '<pre>', print_r($t, true), '</pre>';
}
//___________________________________________________________________
/**
 * Testeur de fonction
 *
 * @param string	arg[0]			nom de la fonction à tester
 * @param mixed		arg[1 à n-1]	paramètres à passer à la fonction
 * @param mixed		arg[n]			résultat attendu
 */
function tester() {
	$args = func_get_args();
	if (count($args) < 3) {
		echo '<hr>La fonction tester doit avoir au moins 3 param&egrave;tres.';
		return;
	}

	$fonction = array_shift($args);
	$attendu = array_pop($args);
	$retour = call_user_func_array($fonction, $args);

	$iMax = count($args);

	echo '<hr><span style="color:', ($retour === $attendu) ? 'green' : 'red', '">',
			'<b>', $fonction, '</b></span>',
			'<br>Param&egrave;tre', ($iMax > 1) ? 's : ' : ' : ';

	for ($i = 0; $i < $iMax; $i++) {
		echo '<span style="background-color: #ebebeb; margin: 0 5px;">';
		var_dump($args[$i]);
		echo '</span>';
	}

	echo '<br>Attendu : ';
	var_dump($attendu);

	echo '<br>Retourn&eacute; : ';
	var_dump($retour);
}

//___________________________________________________________________
/**
 * Teste si une valeur est une valeur entière
 *
 * @param mixed		$x	valeur à tester
 * @return boolean	TRUE si entier, FALSE sinon
 */
function estEntier($x) {
	return is_numeric($x) && ($x == (int) $x);
}

//___________________________________________________________________
/**
 * Teste si un nombre est compris entre 2 autres
 *
 * @param integer	$x	nombre à tester
 * @return boolean	TRUE si ok, FALSE sinon
 */
function estEntre($x, $min, $max) {
	return ($x >= $min) && ($x <= $max);
}

//___________________________________________________________________
/**
 * Test et affichage du résultat d'une expression régulière
 *
 * @param string	$exp	Expression régulière
 * @param string	$txt	Texte sur lequel appliquer l'expression
 */
function testerExp($exp, $txt) {
	// on découpe le texte suivant l'expression régulière
	$tab = preg_split($exp, $txt);

	// on affiche le résultat
	echo '<hr>Le mod&egrave;le <b>', $exp, '</b> a &eacute;t&eacute; trouv&eacute; ',
			(count($tab) - 1), ' fois : <br>',
			preg_replace($exp, '<span style="color: red; font-weight:bold">$0</span>', $txt);
}

?>