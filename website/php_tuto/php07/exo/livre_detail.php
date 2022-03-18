<?php
require_once ('../../php07/exo/fonctions.php');
//-- Connexion
fp_connexion();
//-- Requ�te
$sql = 'SELECT livres.*, editeurs.*, categories.* '
.'FROM livres, editeurs, categories '
."WHERE liID = '".$_GET['ID'] . "' "
.'AND edID = liIDEditeur '
.'AND caID = liIDCat';

$r = mysqli_query($bd, $sql) or fp_erreur();

$enr = mysqli_fetch_array($r);
?>
<!DOCTYPE html>
<html>
<head>
<title>Détail d'un livre</title>
</head>
<body bgcolor="#FFFFFF">
	<form method="post" action="" name="frmLivre">
		<table border="0" cellspacing="0" cellpadding="2" align="center">
			<tr>
				<td align="right">Titre</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['liTitre']) ?>" name="txtTitre"
					size="40">
				</td>
			</tr>
			<tr>
				<td align="right" valign="top">Auteur(s)</td>
				<td><?php
				// Requête auteurs
				$sql = "SELECT auNom, auPrenom
				FROM auteurs, aut_livre
				WHERE al_IDLivre = '{$_GET['ID']}
				AND auID = al_IDAuteur
				ORDER BY auNom";

				$r = mysqli_query($bd, $sql) or fp_erreur();
				$nb = 0;
				while ($aut = mysqli_fetch_array($r)) {
					if ($nb > 0) echo '<br>';
					$nb ++;
					echo fp_htmlok($aut['auNom'].' '.$aut['auPrenom']);
				}

				//-- Déconnexion
				mysqli_free_result($r);
				mysqli_close($bd);
				?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">Sommaire</td>
				<td><textarea name="txtSommaire" cols="40" rows="10">
						<?php echo fp_htmlok($enr['liSommaire']) ?>
					</textarea></td>
			</tr>
			<tr>
				<td valign="top" align="right">Critique</td>
				<td><textarea name="txtCritique" cols="40" rows="10">
						<?php echo fp_htmlok($enr['liCritique']) ?>
					</textarea></td>
			</tr>
			<tr>
				<td align="right">Editeur</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['edNom']) ?>" name="txtEditeur"
					size="40"></td>
			</tr>
			<tr>
				<td align="right">Catégorie</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['caNom']) ?>" name="txtCategorie"
					size="40"></td>
			</tr>
			<tr>
				<td align="right">Année</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['liAnnee']) ?>" name="txtAnnee"
					size="6"></td>
			</tr>
			<tr>
				<td align="right">NB pages</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['liPages']) ?>" name="txtPages"
					size="6"></td>
			</tr>
			<tr>
				<td align="right">ISBN</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['liISBN']) ?>" name="txtISBN"
					size="30"></td>
			</tr>
			<tr>
				<td align="right">Prix</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['liPrix']) ?>" name="txtPrix"
					size="20"></td>
			</tr>
			<tr>
				<td align="right">Langue</td>
				<td><input type="text"
					value="<?php echo fp_htmlok($enr['liLangue']) ?>" name="txtLangue"
					size="6"></td>
			</tr>
		</table>
	</form>
</body>
</html>
