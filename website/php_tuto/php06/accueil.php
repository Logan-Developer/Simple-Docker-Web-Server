<?php
require('bib_fonctions.php');

htmlDebut('Accueil');

if ($_POST['txtPasse'] != 'piat') {
	header('Location: login.php?err=1');
	exit();
}

htmlInfo('Page d\'accueil');

htmlFin();
?>