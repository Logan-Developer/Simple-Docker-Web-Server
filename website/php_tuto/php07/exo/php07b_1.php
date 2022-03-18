<!DOCTYPE html>
<html>
<head>
<title>Premi�re page dynamique</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
</head>
<body>
	Liste de la table livres
	<p>
		<?php
		require_once ('../../php07/exo/fonctions.php');

		//-- Connexion
		fp_connexion();

		//-- Requ�te
		$sql = 'SELECT liTitre, liAnnee, liISBN, liPages
		FROM livres
		ORDER BY liTitre';
		$r = mysqli_query($bd, $sql) or fp_erreur();

		//-- Affichage de l'ent�te du tableau HTML
		echo '<table border="0"><tr bgcolor="#FFFFBD">',
		'<td width="100" align="center">Titre</td>',
		'<td width="100" align="center">Ann�e</td>',
		'<td width="100" align="center">ISBN</td>',
		'<td width="100" align="center">Nb pages</td></tr>';

		//-- Traitement des enregistrements
		$nb = 0;
		while ($enr = mysqli_fetch_assoc($r)) {
			$nb ++;
			echo ( ($nb % 2) ? '<tr>':'<tr bgcolor="#FFFFCE">'),
			'<td>', fp_htmlok($enr['liTitre']), '</td>',
			'<td align="center">', $enr['liAnnee'], '</td>',
			'<td>', $enr['liISBN'], '</td>',
			'<td align="center">', $enr['liPages'], '<td></tr>';
		}
		echo '</table>';
			
		//-- D�connexion
		mysqli_free_result($r);
		mysqli_close($bd);
		?>

</body>
</html>

