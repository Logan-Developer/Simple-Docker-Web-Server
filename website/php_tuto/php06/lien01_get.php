<?php
require('bib_fonctions.php');

htmlDebut('Informations transmises dans une url');

foreach ($_GET as $nom => $valeur) {
	htmlInfo($nom);
	echo $valeur;
}

htmlFin();
?>