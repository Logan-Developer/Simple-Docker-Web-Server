<!-- #include file="../../php07/exo/commun.inc" -->
<!DOCTYPE html>
<html>
<head>
<title>Liste des livres</title>
</head>
<body>
	<% '-- D�claration des variables Dim conBibli, leSQL, rstLivre, leLivre
	Dim nAbsolutePage, NbEnr, laPage Dim EnrPage, laCatego, lEditeur '--
	Ouverture Connexion Call Connexion(conBibli) '-- R�cup�ration des
	param�tres '-- si on re�oit un bouton de formulaire '-- c'est un appel
	depuis la page de s�lection '-- sinon c'est la page qui se rappelle
	elle m�me If Request.Form("btnEnvoi") <> "" Then laCatego =
	CInt(Request.Form("lstCategorie")) lEditeur =
	CInt(Request.Form("lstEditeur")) EnrPage = CInt(Request.Form("Pages"))
	nAbsolutePage = 1 Else laCatego = CInt(Request.QueryString("CA"))
	lEditeur = CInt(Request.QueryString("ED")) EnrPage =
	CInt(Request.QueryString("EP")) nAbsolutePage =
	Request.QueryString("AP") End If '-- Pr�paration du lien pour rappeler
	la page asp laPage = Request.ServerVariables("script_name") laPage =
	laPage & "?CA=" & laCatego &_ "&ED=" & lEditeur &_ "&EP=" & EnrPage &_
	"&AP=" '-- Pr�paration de la requ�te leSQL = "SELECT
	liTitre,liAnnee,liISBN,liPages,liPhoto" &_ ",caNom, edNom " &_ " FROM
	livres, categories, editeurs" &_ " WHERE liIDCat = caID" &_ " AND
	liIDEditeur = edID" If laCatego <> 0 Then leSQL = leSQL & " AND liIDCat
	= " & laCatego End If If lEditeur <> 0 Then leSQL = leSQL & " AND
	liIDEditeur = " & lEditeur End If leSQL = leSQL & " ORDER BY caNom,
	edNom, liTitre" '-- Cr�ation du recordset Set rstLivre =
	server.CreateObject("ADODB.Recordset") '-- Ex�cution de la requ�te
	rstLivre.open leSQL, conBibli, 3 '-- Affichage de l'ent�te du tableau
	HTML Response.Write("
	<table border="" 0"" width=""350"">
		" &_ "
		<tr>
			<td colspan=""2""><b>Dans nos rayons 
			
			</td>
		</tr>
		" &_ "
		<tr>
			<td colspan=""2""><hr></td>
		</tr>
		") '-- Nombre d'enregistrement � afficher par page rstLivre.PageSize =
		EnrPage '-- Se positionner sur la bonne page dans le recordset
		rstLivre.AbsolutePage = nAbsolutePage '-- Lecture du recordset et
		affichage des lignes Set leLivre = rstLivre.Fields NbEnr = 0 Do While
		Not rstLivre.EOF And NbEnr < rstLivre.PageSize NbEnr = NbEnr + 1
		Response.Write( "
		<tr>
			" &_ "
			<td width=""110""><img src=""../../exemple/" &_leLivre("liPhoto").Value & """>
			</td>" &_ "
			<td valign=""top"">" &_ "<b>" & leLivre("liTitre").Value & "</b><br>"
				&_ leLivre("liAnnee").Value &_ " - " & leLivre("liPages").Value & "
				pages<br>" &_ "ISBN " & leLivre("liISBN").Value & "<br>" &_
				"Cat�gorie : " & leLivre("caNom").Value & "<br>" &_ "Editeur : " &
				leLivre("edNom").Value & "
			</td>
		</tr>
		") Response.Write("
		<tr>
			<td colspan=""2""><hr></td>
		</tr>
		") rstLivre.MoveNext loop '-- Liens pour naviguer dans les pages
		Response.Write("
		<tr>
			<td colspan="" 2"" align=""center"">") If nAbsolutePage > 1 Then
				Response.Write("<a href=""" &_laPage & nAbsolutePage - 1 & """>" &_
					" << </a>") End If Response.Write("&nbsp; &nbsp; Page " &_
				nAbsolutePage & "/" & rstLivre.PageCount &_ "&nbsp; &nbsp;&nbsp;")

				If nAbsolutePage < CStr(rstLivre.PageCount) Then Response.Write("<a
				href=""" &_laPage & nAbsolutePage + 1 & """>" &_ " >> </a>") End If

				Response.Write("<br> <a href=""livres_select.asp"">" &_ "Autre
					s�lection</a>
			</td>
		</tr>
	</table>
	") '-- fermeture des objets rstLivre.close conBibli.close Set rstLivre
	= nothing Set conBibli = nothing %>
</body>
</html>
