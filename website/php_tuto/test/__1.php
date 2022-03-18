<?php header('Content-Type: text/html; charset=ISO-8859-1'); error_reporting(E_ALL ^ E_NOTICE); ?><?php
require('bib_fonctions.php');

htmlDebut('guillemets simples et doubles');

echo '<h4>Chaîne sans complication</h4>';
echo 'Simple : Bonjour tout le monde';
echo "<br>Double : Bonjour tout le monde";

echo '<h4>Chaîne avec apostrophe</h4>';
echo 'Simple : Bonjour tout l\'monde';
echo "<br>Double : Bonjour tout l'monde";

echo '<h4>Chaîne avec guillemets doubles</h4>';
echo 'Simple : "Bonjour tout le monde"';
echo "<br>Double : \"Bonjour tout le monde\"";

echo '<h4>Chaîne avec un backslash</h4>';
echo 'Simple : Le caractère \\ doit être protégé';
echo "<br>Double : Le caractère \\ doit être protégé";

echo '<h4>Séquence d\'échappement : saut de ligne</h4>';
echo '<pre>';
echo 'Simple : \n est sans effet ici';
echo "<br>Double : \n \\n est\ninterprété\nici";
echo '</pre>';

echo '<h4>Chaîne avec une variable</h4>';
$x = 'François';
echo 'Simple : je m\'appelle $x Piat';
echo "<br>Double : je m'appelle $x Piat";

htmlFin();
?>