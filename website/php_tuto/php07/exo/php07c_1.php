<!DOCTYPE html>
<html>
<head>
<title>Liste des livres</title>
<script>
var win = null;
function PopUp(IDLivre) {
	Quitter();
	var sUrl = 'livre_detail.php?ID=' + IDLivre;
	var sOptions = 'width=500,height=400,scrollbars';
	win = window.open(sUrl,'livre',sOptions);
}
function Quitter() {
	if (win != null && !win.closed) win.close();
}
</script>
</head>
<body onUnload="Quitter()">
	<?php
	require_once ('../../php07/exo/fonctions.php');
	//-- Connexion
	fp_connexion();

	//-- Requ�te
	$sql = 'SELECT * FROM livres ORDER BY liTitre';

	$r = mysqli_query($bd, $sql) or fp_erreur();

	//-- Affichage de l'ent�te du tableau HTML
	echo '<table border="0">',
	'<tr><td colspan="2"><b>Dans nos rayons</td></tr>',
	'<tr><td colspan="2"><hr></td></tr>';

	//-- Traitement des enregistrements
	while ($enr = mysqli_fetch_assoc($r)) {
		echo '<tr>',
		'<td><img src="../../exemple/', $enr['liPhoto'], '"></td>',
		'<td valign="top"><b>',
		'<a href="javascript:PopUp(', $enr['liID'], ')">',
		fp_htmlok($enr['liTitre']),'</a></b>',
		'<br>', $enr['liAnnee'], ' - ', $enr['liPages'],' pages',
		'<br>ISBN ', $enr['liISBN'], '</td></tr>';

		echo '<tr><td colspan="2"><hr></td></tr>';
	}
	echo '</table>';

	//-- D�connexion
	mysqli_free_result($r);
	mysqli_close($bd);
	?>
</body>
</html>
