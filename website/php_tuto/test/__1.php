<?php header('Content-Type: text/html; charset=ISO-8859-1'); error_reporting(E_ALL ^ E_NOTICE); ?><?php
require('bib_fonctions.php');

htmlDebut('guillemets simples et doubles');

echo '<h4>Cha�ne sans complication</h4>';
echo 'Simple : Bonjour tout le monde';
echo "<br>Double : Bonjour tout le monde";

echo '<h4>Cha�ne avec apostrophe</h4>';
echo 'Simple : Bonjour tout l\'monde';
echo "<br>Double : Bonjour tout l'monde";

echo '<h4>Cha�ne avec guillemets doubles</h4>';
echo 'Simple : "Bonjour tout le monde"';
echo "<br>Double : \"Bonjour tout le monde\"";

echo '<h4>Cha�ne avec un backslash</h4>';
echo 'Simple : Le caract�re \\ doit �tre prot�g�';
echo "<br>Double : Le caract�re \\ doit �tre prot�g�";

echo '<h4>S�quence d\'�chappement : saut de ligne</h4>';
echo '<pre>';
echo 'Simple : \n est sans effet ici';
echo "<br>Double : \n \\n est\ninterpr�t�\nici";
echo '</pre>';

echo '<h4>Cha�ne avec une variable</h4>';
$x = 'Fran�ois';
echo 'Simple : je m\'appelle $x Piat';
echo "<br>Double : je m'appelle $x Piat";

htmlFin();
?>