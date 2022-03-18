<?php
ob_start();
session_start();
error_reporting(E_ALL);
header('Content-Type: text/html; charset=ISO-8859-1');
$_SESSION['idAuteur'] = 0;
$_SESSION['recherche'] = array();

require('bib_params.php');
require('bib_fonctions.php');

htmlDebut('Recherche auteurs', 'bd.css');

echo '<form method="POST" class="maj w300" ',
		'action="auteurs_liste.php">',
		'Rechercher des auteurs dont le nom',

		'<label>',
		'<input type="radio" name="radNom" ',
			'value="1">commence par',
		'</label>',

        '<label>',
		'<input type="radio" name="radNom" ',
			'value="2" checked>contient',
		'</label>',

		'<label>',
		'<input type="radio" name="radNom" ',
			'value="3">finit par',
		'</label>',

		'<input type="text" name="txtNom" ',
			'style="width: 280px;">',

		'<p class="pagination">',
		'<input type="submit" value="Ajouter" ',
			'name="btnNouveau" formaction="auteurs_maj.php">',
		'<input type="submit" value="Rechercher" ',
			'name="btnChercher">',
		'</p>',

	 '</form>';

htmlFin();
?>