<?php
set_magic_quotes_runtime(0);

function fp_connexion() {
	@mysqli_connect('localhost','tuto_user','tuto_pass')
	or exit('Connexion au serveur impossible');

	@mysql_select_db('php_tuto')
	or exit('Connexion � la base de donn�es impossible');
}

function fp_addslashes ($t) {
	return (get_magic_quotes_gpc() == 1) ? $t : addslashes($t);
}

function fp_stripslashes ($t) {
	return (get_magic_quotes_gpc() == 1) ? stripslashes($t) : $t;
}

function fp_protectSQL($t) {
	$t = fp_stripslashes($t);
	if (function_exists('mysqli_real_escape_string')) {
		return mysqli_real_escape_string($t);
	}
	if (function_exists('mysql_escape_string')) {
		return mysql_escape_string($t);
	}
	return fp_addslashes($t);
}

function fp_htmlok($t) {
	return htmlentities($t);
}

function fp_erreur() {
	echo '<table cellpadding="2" cellspacing="2" align="center">',
	'<tr><td bgcolor="red"><p>',
	'Une erreur s\'est produite.</td></tr>',
	'<tr><td><p>Erreur : ', mysqli_errno(),'</td></tr>',
	'<tr><td><p>', mysqli_error(), '</td></tr></table>';
	exit();
}
?>