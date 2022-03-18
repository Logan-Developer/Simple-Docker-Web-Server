<?php error_reporting(E_ALL ^ E_NOTICE); ?><?php
ob_start();
require('bib_fonctions.php');

htmlDebut('Nombre de livres');

//-- Connexion --------------------------------------
$bd = mysqli_connect('localhost', 'tuto_user', 
					 'tuto_pass', 'php_tuto');

//-- Requête ----------------------------------------
$sql = 'SELECT count(*) FROM livres';
$r = mysqli_query($bd, $sql);

//-- Traitement -------------------------------------
$enr = mysqli_fetch_row($r);

echo 'Il y a ', $enr[0], ' livres dans notre base';

//-- Déconnexion -----------------------------------
mysqli_free_result($r);
mysqli_close($bd);

htmlFin();
?>