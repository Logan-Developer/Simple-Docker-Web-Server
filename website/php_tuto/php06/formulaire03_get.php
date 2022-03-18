<?php
require('bib_fonctions.php');

htmlDebut('Zones du formulaire dans $_GET');

foreach ($_GET as $nom => $valeur) {
	htmlInfo(utf8_decode($nom));
	echo utf8_decode($valeur);
}

htmlFin();
?>