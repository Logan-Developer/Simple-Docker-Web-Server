<?php
require('bib_fonctions.php');

htmlDebut('Login');

if (isset($_GET['err'])) {
	htmlInfo('Login incorrect');
}

echo '<form method="post" action="accueil.php">',
	'<label>Login</label>',
	'<input type="text" name="txtLogin">',
	'<label>Mot de passe</label>',
	'<input type="password" name="txtPasse">',
	'<input type="submit" name="btnSub" value="Ok">',
	'</form>';

htmlFin();
?>