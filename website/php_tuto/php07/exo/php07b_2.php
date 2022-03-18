<!DOCTYPE html>
<html>
<head>
<title>Premi�re page dynamique</title>
</head>
<body>
	Livres par auteurs
	<p>
		<?php
		require_once ('../../php07/exo/fonctions.php');

		//-- Connexion
		fp_connexion();

		$rupture = -1;  //Init de l'indicateur de rupture
		echo '<table width="450" border="1">';

		//-- Requ�te
		$sql = 'SELECT auID, auNom, auPrenom
		liTitre, liAnnee, liISBN, liPages
		FROM auteurs, aut_livre, livres
		WHERE auID = al_IDAuteur
		AND liID = al_IDLivre
		ORDER BY auNom, auPrenom, liTitre';

		$r = mysqli_query($bd, $sql) or fp_erreur();

		//-- Traitement des enregistrements
		while ($enr = mysqli_fetch_assoc($r)) {
			//-- Rupture sur l'auteur
			if ($rupture != $enr['auID']) {
				$rupture = $enr['auID'];
				//-- Affichage des infos auteur
				echo '<tr bgcolor="#FFFFCE"><td colspan="4"><b>',
				fp_htmlok($enr['auNom'] . ' ' . $enr['auPrenom']),
				'</b></td></tr>';
					
				//-- affichage ent�te des livres
				echo '<tr><td align="center">Titre</td>',
				'<td width="10%" align="center">Ann�e</td>',
				'<td align="25%" align="center">ISBN</td>',
				'<td width="10%" align="center">Pages</td></tr>';
			}

			//-- Affichage des livres de l'auteur
			echo '<td>', fp_htmlok($enr['liTitre']), '</td>',
			'<td align="center">', $enr['liAnnee'], '</td>',
			'<td>', $enr['liISBN'], '</td>',
			'<td align="center">', $enr['liPages'], '</td></tr>';
		}
		echo '</table>';
			
		//-- D�connexion
		mysqli_free_result($r);
		mysqli_close($bd);
		?>

</body>
</html>
