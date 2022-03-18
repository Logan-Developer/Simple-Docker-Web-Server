<?php
require_once ('../../php07/exo/fonctions.php');
//-- Connexion
fp_connexion();
//-- Requête
$sql = 'SELECT * FROM livres WHERE liID = '.$_GET['ID'];
$r = mysqli_query($bd, $sql) or fp_erreur();

$enr = mysqli_fetch_array($r);

//-- D�connexion
mysqli_free_result($r);
mysqli_close($bd);
?>
<!DOCTYPE html>
<html>
<head>
<title>Détail d'un livre</title>
</head>
<body bgcolor="#FFFFFF">
	<p>&nbsp;</p>
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
				<td align="right">Auteur(s)</td>
				<td>&nbsp;</td>
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
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">Catégorie</td>
				<td>&nbsp;</td>
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
