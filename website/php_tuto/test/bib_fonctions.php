<?php
//
// Bibliothèque de fonctions PHP
//
// Remarque 1 :
// Le code de ce fichier est le résultat de divers ajouts faits tout
// au long du tutoriel au fur et à mesure de la progression des
// connaissances. Si à un moment T vous regardez ce code, ne vous
// étonnez donc pas qu'il ne corresponde pas forcément à ce qui est
// indiqué dans les pages du tutoriel.
//
// Remarque 2 :
// Pour simplifier le code, la vérification du type des arguments
// passés aux fonctions est volontairement oubliée.
//
//___________________________________________________________________
/**
 * Envoie à la sortie standard le début du code HTML d'une page
 *
 * @param string	$titre	Titre de la page
 * @param string	$css	Fichier CSS éventuel
 */
function htmlDebut($titre, $css = '') {
	$titre = htmlentities($titre, ENT_COMPAT, 'ISO-8859-1');

	if ($css != '') {
		$css = "<link rel='stylesheet' href='$css'>";
	}

	echo '<!DOCTYPE html>',
			'<html lang="fr">',
				'<head>',
					'<meta charset="ISO-8859-1">',
					'<title>', $titre, '</title>',
					'<style>',
					'body {font-size: 13px;font-family: Verdana, sans-serif}',
					'h3 {font-size: 15px;margin: 0 0 15px 0;padding: 5px 0;text-align:center;background: #FFF5AB}',
					'h4 {font-size: 13px;margin: 1em 0 0 0;padding: 3px;background: #ebebeb;clear:both}',
					'label {display: block;font-weight: bold;margin-top: 10px;}',
					'h5 {font-size: 13px; font-weight: normal; margin: 1em 0 0 0; padding: 3px; border: 1px solid #aaa}',
					'.exp {font-weight: bold; color: green;}',
					'</style>',
					$css,
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
	echo '<h4>', htmlentities($txt, ENT_COMPAT, 'ISO-8859-1'), '</h4>';
}
//___________________________________________________________________
/**
 * Envoie à la sortie standard l'en-tête d'une table HTML
 *
 * @param array		$titres	Titres de colonnes de la table
 * @param string	$css	Classe CSS éventuelle de la table
 */
function htmlTable($titres, $css = '') {
	echo '<table', ($css == '') ? '>' : " class='$css'>";

	htmlLigne($titres);
}
//___________________________________________________________________
/**
 * Envoie à la sortie standard une ligne d'une table HTML
 *
 * @param array		$elts	Elements à afficher dans les colonnes
 * @param string	$css	Classe CSS éventuelle de la ligne
 */
function htmlLigne($elts, $css = '') {
	echo '<tr', ($css == '') ? '>' : " class='$css'>";

	foreach ($elts as $elt) {
		echo '<td>', $elt, '</td>';
	}

	echo '</tr>';
}
//___________________________________________________________________
/**
 * Envoie à la sortie standard le nombre d'éléments et le contenu d'un tableau
 *
 * @param string	$t	Tableau
 */
function infoTableau($t) {
	echo 'Tableau de ', count($t), ' &eacute;l&eacute;ments',
			'<pre>', print_r($t, true), '</pre>';
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
 * Connexion à une base de données MySQL.
 * En cas d'erreur de connexion le script est arrêté.
 *
 * @return objet connecteur à la base de données
 */
function bdConnecter() {
	$bd = mysqli_connect(BD_SERVEUR, BD_USER, BD_PASS, BD_NOM);

	if ($bd !== FALSE) {
		//mysqli_set_charset() définit le jeu de caractères par défaut à utiliser
        //lors de l'envoi de données depuis et vers le serveur de base de données.
        mysqli_set_charset($bd, 'latin1') or
        bdErreurExit('<h4>Erreur lors du chargement du charset latin1</h4>');
		return $bd;		// Sortie connexion OK
	}

	// Erreur de connexion
	// Collecte des informations facilitant le debugage
	$msg = '<h4>Erreur de connexion base MySQL</h4>'
			.'<div style="margin: 20px auto; width: 350px;">'
			.'BD_SERVEUR : '.BD_SERVEUR
			.'<br>BD_USER : '.BD_USER
			.'<br>BD_PASS : '.BD_PASS
			.'<br>BD_NOM : '.BD_NOM
			.'<p>Erreur MySQL num&eacute;ro : '.mysqli_connect_errno($bd)
			.'<br>'.mysqli_connect_error($bd)
			.'</div>';

	bdErreurExit($msg);
}

//___________________________________________________________________
/**
 * Arrêt du script si erreur base de données.
 * Affichage d'un message d'erreur si on est en phase de
 * développement, sinon stockage dans un fichier log.
 *
 * @param string	$msg	Message affiché ou stocké.
 */
function bdErreurExit($msg) {
    ob_end_clean();     // Supression de tout ce qui
                        // a pu être déja généré

    // Si on est en phase de développement, on affiche le message
    if (IS_DEV) {
        htmlDebut('Erreur base de données');
        echo $msg;
        htmlFin();
        exit();
    }

    // Si on est en phase de production on stocke les
    // informations de débuggage dans un fichier d'erreurs
    // et on affiche un message sibyllin.
    $buffer = date('d/m/Y H:i:s')."\n$msg\n";
    error_log($buffer, 3, 'erreurs_bd.txt');

    htmlDebut('Maintenance en cours');
    // Gros mensonge
    echo 'Notre site est momentanément indisponible ',
        'pour cause de maintenance. Merci de ré-essayer ',
        'dans quelques instants.';
    htmlFin();
    exit();
}

//___________________________________________________________________
/**
  * Gestion d'une erreur de requête à la base de données.
  *
  * @param objet	$bd		Connecteur sur la bd ouverte
  * @param string	$sql	requête SQL provoquant l'erreur
  */
function bdErreur($bd, $sql) {
    $errNum = mysqli_errno($bd);
    $errTxt = mysqli_error($bd);

    // Collecte des informations facilitant le debugage
    $msg =  '<h4>Erreur de requête</h4>'
            ."<b>Erreur mysql :</b> $errNum"
            ."<br> $errTxt"
            ."<br><br><b>Requête :</b><br><pre>$sql</pre>"
            .'<br><br><b>Pile des appels de fonction :</b>';
    
    $tdStyle = 'style="border: 1px solid black;padding: 4px 10px"';
    
    // Récupération de la pile des appels de fonction
    $msg .= '<table style="border-collapse: collapse">'
            ."<tr><td $tdStyle>Fonction</td>"
            ."<td $tdStyle>Appelée ligne</td>"
            ."<td $tdStyle>Fichier</td></tr>";

    $appels = debug_backtrace();
    for ($i = 0, $iMax = count($appels); $i < $iMax; $i++) {
        $msg .= "<tr style='text-align: center'><td $tdStyle>"
                .$appels[$i]['function']."</td><td $tdStyle>"
                .$appels[$i]['line']."</td><td $tdStyle>"
                .$appels[$i]['file'].'</td></tr>';
    }

    $msg .= '</table>';

    bdErreurExit($msg);
}

//___________________________________________________________________
/**
 * Protection HTML des chaînes contenues dans un tableau
 * Le tableau est passé par référence.
 *
 * @param array		$tab	Tableau des chaînes à protéger
 */
function htmlProteger(&$tab) {
	foreach ($tab as $cle => &$val) {
		$val = htmlentities($val, ENT_COMPAT, 'ISO-8859-1');
	}
}

//___________________________________________________________________
/**
 * Crypte une valeur pour la passer dans une URL.
 *
 * @param mixed		$val	La valeur à crypter
 * @return string	La valeur cryptée et encodée url
 */
function crypterURL($val) {
	$ivlen = openssl_cipher_iv_length($cipher='AES-128-CBC');
	$sha2len=32;
	if (! isset ($_SESSION['cle_crytage'])){
		$_SESSION['cle_crytage'] = base64_encode(
		                           openssl_random_pseudo_bytes($ivlen));
		$_SESSION['cle_hachage'] = base64_encode(
		                           openssl_random_pseudo_bytes($sha2len));
	}
	
	// -- génération du vecteur d'initialisation
	$iv = openssl_random_pseudo_bytes($ivlen);
	// -- cryptage de $val
	$x = openssl_encrypt($val, $cipher, 
						 base64_decode($_SESSION['cle_crytage']), 
	                     OPENSSL_RAW_DATA, $iv);
	// -- calcul de la signature de la valeur cryptée
	$hmac = hash_hmac('sha256', $x, 
	                  base64_decode($_SESSION['cle_hachage']), true);
	
	$x = substr($hmac, 0, $sha2len/2)
	     .$iv.$x.substr($hmac, $sha2len/2);
	$x = base64_encode($x);
	return urlencode($x);
}
//___________________________________________________________________
/**
 * Décrypte une valeur cryptée avec la fonction crypterURL
 *
 * @param string	$x	La valeur à décrypter
 * @return mixed	La valeur décryptée ou FALSE si erreur
 */
function decrypterURL($x) {
	$ivlen = openssl_cipher_iv_length($cipher='AES-128-CBC');
	$x = base64_decode($x);
	$sha2len=32;
	$hmac = substr($x, 0, $sha2len/2).substr($x, -$sha2len/2);
	$iv = substr($x, $sha2len/2, $ivlen);
	$x = substr($x, $sha2len/2 + $ivlen, -$sha2len/2);
	// calcul de  la signature de la chaine cryptée reçue
	$hmacCalc = hash_hmac('sha256', $x, 
	                      base64_decode($_SESSION['cle_hachage']), true);
	if (! hash_equals($hmac, $hmacCalc)){
		return FALSE;
	}
	return openssl_decrypt($x, $cipher, 
	                       base64_decode($_SESSION['cle_crytage']), 
	                       OPENSSL_RAW_DATA, $iv);
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
	$t = preg_split($exp, $txt);

	// on affiche le résultat
	echo '<h5>Le mod&egrave;le <span class="exp">', $exp, '</span> a &eacute;t&eacute; trouv&eacute; ',
			(count($t) - 1), ' fois</h5>',
			preg_replace($exp, '<span class="exp">$0</span>', $txt);
}
//__________________________________________________________
/**
 * Test et affichage du résultat d'une expression régulière
 * qui contient du code HTML
 *
 * @param string	$exp	Expression régulière
 * @param string	$txt	Texte où appliquer l'expression
 */
function testerExpHtml($exp, $txt) {
	// on découpe la chaîne suivant l'expression régulière
	$t = preg_split($exp, $txt);

	// on affiche le résultat
	$r = preg_replace($exp, '[span class=exp]$0[/span]', $txt);
	$r = htmlentities($r, ENT_IGNORE, 'ISO-8859-1');
	$r = str_replace(array('[', ']'), array('<', '>'), $r);
	echo '<h5>Le mod&egrave;le <span class="exp">', $exp, '</span> ',
			'a &eacute;t&eacute; trouv&eacute; ',
			(count($t) - 1), ' fois</h5>', $r;
}
?>