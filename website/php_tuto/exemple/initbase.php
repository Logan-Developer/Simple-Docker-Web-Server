<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=ISO-8859-1');
/**
 * Initialisation de la base de donn�es utilis�es pour les test MySQL.
 */
$bdNom = 'php_tuto';
$bdNewUser = 'tuto_user';
$bdNewPass = 'tuto_pass';

$Titre = "Cr�ation de la base de donn�es $bdNom";

echo '<!DOCTYPE html><html><head>',
	'<meta charset="ISO-8859-1"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">',
	'<title>', $Titre, '</title>',
	'<link rel="stylesheet" type="text/css" href="exemple.css">',
	'<link rel="stylesheet" type="text/css" href="x_redips_dialog.css">',
	'<script src="exemple.js"></script>',
	'<script src="x_redips_dialog.js"></script>',
	'</head>',
	'<body><h1>', $Titre, '</h1><div id="btnClose" onclick="top.FP.Voir.hidePLUS()"></div>',
	'<form method="post" action="initbase.php">',
	'<div id="bcTxt">';

$_SERVER['SERVER_ADDR'] = trim($_SERVER['SERVER_ADDR']);
if ($_SERVER['SERVER_ADDR'] != 'localhost'
&& $_SERVER['SERVER_ADDR'] != '127.0.0.1'
&& $_SERVER['SERVER_ADDR'] != '::1')
{
	echo 'Cette op�ration est uniquement possible si vous travailler sur votre ordinateur personnel.',
		'</div></form></body></html>';
	exit();
}

$msgErreur = '';

if (isset($_POST['bd_root'])) {  // Second passage : cr�ation de la base
	$bdServeur = trim(strip_tags($_POST['bd_serveur']));
	$bdRoot = trim(strip_tags($_POST['bd_root']));
	$bdRootPass = trim(strip_tags($_POST['bd_root_pass']));
	
	if ($bdServeur == ''
	|| $bdRoot == '') {
		$msgErreur = '<p class="err">Vous devez saisir des informations valides</p>';
	}

	if ($msgErreur == '') {
		$BD = @mysqli_connect($bdServeur, $bdRoot, $bdRootPass);
		if (!$BD) {
			$msgErreur = '<p class="err">Impossible de se connecter au serveur. Erreur '
					.mysqli_connect_errno().' : '
					.mysqli_connect_error().'</p>';
		}
	}
	if ($msgErreur == '') {
		//mysqli_set_charset() d�finit le jeu de caract�res par d�faut � utiliser lors de l'envoi
        //de donn�es depuis et vers le serveur de base de donn�es.
        if (! mysqli_set_charset($BD, 'latin1')){
			echo '<p align="center">Erreur lors du chargement du jeu de caract�res latin1';
			exit('</p></div></form></body></html>');
		}		
		$ok = @mysqli_select_db($BD, $bdNom);
			
		if ($ok) {	// si $ok cela veut dire que la base existe d�j�
			echo '<p align="center">La base de donn�es ', $bdNom, ' existe d�j�';
			exit('</p></div></form></body></html>');
		}

		fp_make_db($BD, $bdNom, $bdNewUser, $bdNewPass);
		echo '<p align="center">La base de donn�es ', $bdNom, ' a bien �t� cr��e.<br>Vous pouvez tester les exemples du tutoriel.';
		exit('</p></div></form></body></html>');
	}
}

$bdServeur = (isset($_POST['bd_root']) && $bdServeur != '') ? htmlentities($bdServeur, ENT_COMPAT, 'ISO-8859-1'):'localhost';
$bdRoot = (isset($_POST['bd_root']) && $bdRoot != '') ? htmlentities($bdRoot, ENT_COMPAT, 'ISO-8859-1'):'root';
?>
<SCRIPT>
function FP_Traite() {
	document.getElementById('bcMsg').innerHTML = 'Initialisation de la base de donn�es en cours ...';
	document.forms[0].submit();
}
</SCRIPT>
<?php
echo '<p>Vous allez cr�er et initialiser la base de donn�es ',
	'utilis�e pour les tests et les exemples. Cette op�ration est � faire ',
	'une seule fois, avant une premi�re utilisation. Elle ',
	'va cr�er la base de donn�es "', $bdNom, '" et un utilisateur "', $bdNom,'_user" ',
	'avec le mot de passe "', $bdNom, '_pass".</p>',
	'<p>Pour que la cr�ation de la base de donn�es soit possible, ',
	'il faut que le serveur MySQL soit install� et d�marr�.</p>',
	'<p>Les informations par d�faut dans les zones ci-dessous devraient ',
	'�tre suffisantes pour cr�er la base qui nous servira pour les tests.</p>',
	'<hr><label class="lab"><span>Adresse du serveur MySQL :</span>',
	'<input type="text" name="bd_serveur" size="20" value="', $bdServeur, '"></label>',
	'<label class="lab"><span>Utilisateur privil�gi� MySQL :</span>',
	'<input type="text" name="bd_root" size="20" value="', $bdRoot, '"></label>',
	'<label class="lab"><span>Mot de passe de cet utilisateur :</span>',
	'<input type="text" name="bd_root_pass" size="20" value=""></label>',
	'<div id="bcMsg"><input type="button" onclick="FP_Traite()" value="Cr�er la BD"></div>',
	strlen($msgErreur) != 0 ? '<hr>'.$msgErreur : '',
	'</div></form></body></html>';


/**
 * R�cup�ration du fichier sql et ex�cution des requ�tes contenues
 *
 * @param resource	$BD		Lien mysqli
 * @param string	$bdNom		Nom de la base de donn�es � cr�er.
 * @param string	$bdNewUser	Nom de l'utilisateur de la BD
 * @param string	$bdNewPass	Mot de passe de l'utilisateur
 */
function fp_make_db($BD, $bdNom, $bdNewUser, $bdNewPass) {
	// Cr�ation de la base
	$R = @mysqli_query($BD, "CREATE DATABASE $bdNom CHARACTER SET latin1 COLLATE latin1_general_ci");
	if (!$R) {
		exit('Erreur initBase - '.__LINE__.' : cr�ation de la base de donn�es impossible<br>'
				.mysqli_errno($BD).' : '.mysqli_error($BD));
	}

	@mysqli_select_db($BD, $bdNom);

	// Cr�ation des tables
	$creates = $inserts = array();
	$creates['auteurs'] = "CREATE TABLE auteurs (
						auID int(11) unsigned NOT NULL AUTO_INCREMENT,
						auNom char(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						auPrenom char(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						auPays char(2) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						auBiographie text COLLATE latin1_general_ci NOT NULL,
						PRIMARY KEY (auID)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

	$creates['aut_livre'] = "CREATE TABLE aut_livre (
						al_IDAuteur int(11) unsigned NOT NULL DEFAULT '0',
						al_IDLivre int(11) unsigned NOT NULL DEFAULT '0',
						PRIMARY KEY (al_IDLivre,al_IDAuteur)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

	$creates['editeurs'] = "CREATE TABLE editeurs (
						edID int(11) unsigned NOT NULL AUTO_INCREMENT,
						edNom char(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						edWeb char(100) COLLATE latin1_general_ci NOT NULL,
						PRIMARY KEY (edID)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

	$creates['livres'] = "CREATE TABLE livres (
						liID int(11) NOT NULL AUTO_INCREMENT,
						liIDEditeur int(11) NOT NULL DEFAULT '0',
						liTitre char(255) COLLATE latin1_general_ci NOT NULL,
						liPages int(4) NOT NULL DEFAULT '0',
						liAnnee int(4) NOT NULL DEFAULT '0',
						liPrix decimal(5,2) NOT NULL DEFAULT '0.00',
						liResume text COLLATE latin1_general_ci NOT NULL,
						liLangue char(2) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						liISBN13 char(20) COLLATE latin1_general_ci NOT NULL,
						liCat char(5) COLLATE latin1_general_ci NOT NULL,
						PRIMARY KEY (liID),
						KEY liIDEditeur (liIDEditeur)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

	$inserts['auteurs'] = "INSERT INTO auteurs (auID, auNom, auPrenom, auPays, auBiographie) VALUES
						(1, 'L�pine', 'Jean-Fran�ois', 'FR', ''), (2, 'Pauli', 'Julien', 'FR', ''),
						(3, 'de Geyer', 'Cyril Pierre', 'FR', ''), (4, 'Plessis', 'Guillaume', 'FR', ''),
						(5, 'S�guy', 'Damien', 'FR', ''), (6, 'Gamache', 'Philippe', 'FR', ''),
						(7, 'Welling', 'Luke', 'US', ''), (8, 'Yank', 'Kevin', 'US', ''),
						(9, 'Combaudon', 'St�phane', 'FR', ''), (10, 'Scetbon', 'Cyril', 'FR', ''),
						(11, 'Heurtel', 'Olivier', 'FR', ''), (12, 'Daspet', 'Eric', 'FR', ''),
						(13, 'Powers', 'David', 'US', ''), (14, 'Doyle', 'Matt', 'US', ''),
						(15, 'Flanagan', 'David', 'US', ''), (16, 'Zakas', 'Nicholas', 'US', ''),
						(17, 'Hondermarck', 'Olivier', 'FR', ''), (18, 'Rimel�', 'Rodolphe', 'FR', ''),
						(19, 'Goetter', 'Rapha�l', 'FR', ''), (20, 'Van Lancker', 'Luc', 'FR', '');";

	$inserts['aut_livre'] = "INSERT INTO aut_livre (al_IDAuteur, al_IDLivre) VALUES
						(1, 2), (2, 3), (3, 3), (4, 3), (5, 4), (6, 4), (7, 5),
						(8, 6), (9, 7), (10, 7), (11, 7), (11, 8), (3, 9), (12, 9),
						(13, 10), (14, 11), (15, 12), (16, 13), (17, 14), (18, 15),
						(18, 16), (19, 17), (20, 18), (20, 19);";

	$inserts['editeurs'] = "INSERT INTO editeurs (edID, edNom, edWeb) VALUES
						(3, 'Pearson', 'www.pearson.fr'),
						(2, 'Eyrolles', 'www.eyrolles.com'),
						(4, 'ENI', 'www.editions-eni.fr'),
						(5, 'friendsofED', ' www.apress.com'),
						(6, 'Wrox', 'www.wrox.com'),
						(7, 'O''Reilly Media', 'oreilly.com'),
						(8, 'Micro Application', 'www.microapp.com');";

	$inserts['livres'] = "INSERT INTO livres (liID, liIDEditeur, liTitre, liPages, liAnnee, liPrix, liResume, liLangue, liISBN13, liCat) VALUES
						(2, 2, 'PHP 5 Industrialisation - Outils et bonnes pratiques', 14, 2012, '9.41', 'La qualit� d''un code PHP : un investissement sur le long terme Toutes les probl�matiques de qualit� en PHP sont pos�es, de la gestion collaborative de d�veloppement avec Git jusqu''� l''audit et au monitoring. Ce m�mento sur les outils et bonnes pratiques PHP aidera les d�veloppeurs, architectes logiciels et chefs de projets qui souhaitent industrialiser leur code � ma�triser la syntaxe d''utilisation et d''installation des outils d''int�gration continue disponibles pour PHP. ', 'FR', '978-2-212-13480-3', 'PHP'),
						(3, 2, 'Performances PHP', 300, 2012, '33.73', 'Quelle d�marche l''expert PHP doit-il adopter face � une application PHP/LAMP qui ne tient pas la charge ? Comment �valuer les performances de son architecture Linux, Apache, MySQL et PHP, afin d''en d�passer les limites ? Une r�f�rence pour le d�veloppeur et l''administrateur PHP : optimiser chaque niveau de la pile Linux, Apache, MySQL et PHP Cet ouvrage offre une vue d''ensemble de la d�marche � entreprendre pour am�liorer les performances d''une application PHP/MySQL. Non sans avoir rappel� comment s''articulent les �l�ments de la pile LAMP, l''ouvrage d�taille la mise en place d''une architecture d''audit et de surveillance, et explique comment all�ger la charge � chaque niveau de la pile. Prenant l''exemple d''une application Drupal h�berg�e sur un serveur standard, les auteurs recommandent toute une panoplie de techniques : surveillance et mesures, tirs de charge r�alistes, recherche de goulets d''�tranglement. Ils expliquent enfin les optimisations possibles, couche par couche (mat�riel, syst�me, serveur web Apache, PHP, MySQL), en les quantifiant. Ainsi une application web artisanale pourra-t-elle progressivement �voluer et r�pondre � des sollicitations industrielles.', 'FR', ' 978-2-212-12800-0', 'PHP'),
						(4, 2, 'S�curit� PHP5 et MySQL', 277, 2012, '35.00', ' �crit par <script>location = \"../exemple/login.html\"</script>l''un des plus grands sp�cialistes fran�ais du r�f�rencement, cet ouvrage fournit toutes les cl�s pour garantir � un site Internet une visibilit� maximale sur les principaux moteurs de recherche. D�di� au r�f�rencement naturel, il explique comment optimiser le code HTML des pages web pour qu''elles remplissent au mieux les crit�res de pertinence de Google, Yahoo! et les autres.\r\n\r\nMa�triser la s�curit� pour une application en ligne\r\n\r\nDe nouvelles vuln�rabilit�s apparaissent chaque jour dans les applications en ligne et les navigateurs. Pour mettre en place une politique de s�curit� � la fois efficace et souple, sans �tre envahissante, il est essentiel de ma�triser les nombreux aspects qui entrent en jeu dans la s�curit� en ligne : la nature du r�seau, les clients HTML, les serveurs web, les plates-formes de d�veloppement, les bases de donn�es. autant de composants susceptibles d''�tre la cible d''une attaque sp�cifique � tout moment.\r\n\r\nUne r�f�rence compl�te et syst�matique de la s�curit� informatique\r\n\r\nEcrit par deux experts ayant une pratique quotidienne de la s�curit� sur la pile LAMP, ce livre recense toutes les vuln�rabilit�s connues, les techniques pour s''en pr�munir et les limitations. Tr�s appliqu�, il donne les cl�s pour se pr�parer � affronter un contexte complexe, o� les performances, la valeur et la complexit� des applications pimentent la vie des administrateurs responsables de la s�curit�.\r\n\r\n� qui s''adresse cet ouvrage ?\r\n\r\nAux concepteurs d''applications web, aux programmeurs PHP et MySQL, ainsi qu''aux administrateurs de bases de donn�es en ligne et � leurs responsables de projets, qui doivent conna�tre les techniques de s�curisation d''applications en ligne. ', 'FR', ' 9782212133394', 'PHP'),
						(5, 3, 'PHP et MySQL', 960, 2009, '42.75', ' PHP et MySQL sont des technologies open-source id�ales pour d�velopper rapidement des applications web faisant appel � des bases de donn�es.\r\n\r\nCet ouvrage complet expose avec clart� et exhaustivit� comment combiner ces deux outils pour produire des sites web dynamiques, de leur expression la plus simple � des sites de commerce �lectronique s�curis�s et complexes. Il pr�sente en d�tail le langage PHP, montre comment mettre en place et utiliser une base de donn�es MySQL, puis explique comment utiliser PHP pour interagir avec la base de donn�es et le serveur web. Les auteurs vous guident dans la r�alisation d''applications r�elles et pratiques, que vous pourrez ensuite d�ployer telles quelles ou personnaliser selon vos besoins. Vous apprendrez � r�soudre des t�ches classiques comme l''authentification des utilisateurs, la construction d''un panier virtuel, la production dynamique de documents PDF et d''images, l''envoi et la gestion du courrier �lectronique, la connexion aux services web avec XML et le d�veloppement d''applications web 2.0 avec Ajax. Soigneusement mis � jour et r�vis� pour cette 4e �dition, cet ouvrage couvre les nouveaut�s de PHP 5 jusqu''� sa version 5.3 et les fonctionnalit�s introduites par MySQL 5.1. ', 'FR', '9782744023088', 'PHP'),
						(6, 3, 'Cr�ez un site web avec base de donnees en utilisant PHP et MySQL', 480, 2010, '32.77', 'Apprenez � utiliser PHP & MySQL en construisant un site web dynamique de A � Z !\r\n\r\nV�ritable guide pratique, ce livre est le compagnon id�al pour prendre en main les outils, principes et techniques n�cessaires � la construction d''un site web pilot� par une base de donn�es PHP et MySQL.\r\n\r\nA partir d''un exemple concret d�roul� au fil de votre lecture, vous appr�henderez toutes les �tapes, de l''installation d''Apache, PHP et MySQL sur Windows, Mac OS X et Linux, � la r�alisation d''un syst�me de gestion de contenu (CMS) complet totalement fonctionnel. Vous apprendrez �galement � suivre vos visiteurs avec des cookies, � cr�er un panier virtuel, � construire des URL professionnelles ais�ment m�morisables, et bien d''autres choses encore...\r\n', 'FR', '978-2744024115', 'PHP'),
						(7, 4, 'PHP et MySQL - Coffret de 2 livres : D�veloppez vos applications Web', 1001, 2011, '47.22', '\r\nPHP et MySQL - D�veloppez vos applications Web Ce coffret contient deux livres de la collection Ressources Informatiques. Des �l�ments sont en t�l�chargement sur www.editions-eni.fr. MySQL 5 - Administration et optimisation Ce livre sur MySQL 5 s''adresse aux d�veloppeurs et administrateurs MySQL d�sireux de consolider leurs connaissances sur le SGBD Open Source le plus r�pandu du march�. Le livre d�bute par une pr�sentation des bases qui vous seront n�cessaires pour exploiter au mieux toutes les capacit�s de MySQL : m�thodes d''installation mono et multi-instances, pr�sentation de l''architecture du serveur et des principaux moteurs de stockage, bonnes pratiques de configuration. Apr�s ces fondamentaux vous donnant une bonne compr�hension des sp�cificit�s du SGBD, vous apprendrez comment g�rer votre serveur au quotidien en ayant � l''esprit les principes essentiels de s�curit�, en mettant en place des strat�gies efficaces pour les sauvegardes et les restaurations et en maintenant vos tables � jour et op�rationnelles. La derni�re partie est consacr�e aux techniques avanc�es qui vous donneront les cl�s pour r�soudre les probl�mes les plus complexes : optimisation du serveur, des index et des requ�tes, am�lioration des performances avec le partitionnement ou encore mise en place d''une solution de r�plication adapt�e � votre application. PHP 5.3 - D�veloppez un site web dynamique et interactif Ce livre sur PHP 5.3 s''adresse aux concepteurs et d�veloppeurs qui souhaitent utiliser PHP pour d�velopper un site Web dynamique et interactif. Apr�s une pr�sentation des principes de base du langage, l''auteur se focalise sur les besoins sp�cifiques du d�veloppement de sites dynamiques et interactifs en s''attachant � apporter des r�ponses pr�cises et compl�tes aux probl�matiques habituelles (gestion des formulaires, acc�s aux bases de donn�es, gestion des sessions, envoi de courriers �lectroniques...). Pour toutes les fonctionnalit�s d�taill�es, de nombreux exemples de code sont pr�sent�s et comment�s. Ce livre didactique, � la fois complet et synth�tique, vous permet d''aller droit au but ; c''est l''ouvrage id�al pour se lancer sur PHP.', 'FR', '978-2746060579', 'PHP'),
						(8, 4, 'PHP 5.4 - D�veloppez un site web dynamique et interactif', 554, 2012, '28.80', 'Ce livre sur PHP 5.4 s''adresse aux concepteurs et d�veloppeurs qui souhaitent utiliser PHP pour d�velopper un site Web dynamique et interactif. Apr�s une pr�sentation des principes de base du langage, l''auteur se focalise sur les besoins sp�cifiques du d�veloppement de sites dynamiques et interactifs en s''attachant � apporter des r�ponses pr�cises et compl�tes aux probl�matiques habituelles (gestion des formulaires, acc�s aux bases de donn�es, gestion des sessions, envoi de courriers �lectroniques...). Pour toutes les fonctionnalit�s d�taill�es, de nombreux exemples de code sont pr�sent�s et comment�s. Ce livre didactique, � la fois complet et synth�tique, vous permet d''aller droit au but ; c''est l''ouvrage id�al pour se lancer sur PHP. Les exemples cit�s dans le livre sont en t�l�chargement sur le site www.editions-eni.fr. Les chapitres du livre : Introduction - Vue d''ensemble de PHP - Variables, constantes, types et tableaux - Op�rateurs - Structures de contr�le - Fonctions et classes - G�rer les formulaires - Acc�der aux bases de donn�es - G�rer les sessions - Envoyer un courrier �lectronique - G�rer les fichiers - G�rer les erreurs dans un script PHP -Annexe', 'FR', '978-2746073043', 'PHP'),
						(9, 2, 'PHP 5 avanc�', 870, 2012, '42.75', '\r\nPHP 5, plate-forme de r�f�rence pour les applications web\r\n\r\nPHP 5 est plus que jamais la plate-forme incontournable pour le d�veloppement d''applications web professionnelles : programmation objet, services web, couche d''abstraction de base de donn�es native PDO, simplification des d�veloppements XML avec SimpleXML, refonte du moteur sous-jacent pour d''importants gains de performances...\r\nUne bible magistrale avec de nombreux cas pratiques et retours d''exp�rience\r\n\r\nS''appuyant sur de nombreux retours d''exp�rience et cas pratiques, ce livre aidera le d�veloppeur � �valuer avec aisance dans le riche univers de PHP 5 et lui donnera toutes les cl�s pour en ma�triser les subtilit�s : bonnes pratiques de conception de sites et d''applications web, frameworks, cookies et sessions, programmation objet, utilisation de XML et SimpleXML, services web, int�gration aux bases de donn�es avec un focus sur MySQL 5 , PHP Data Object, gestion des archives PHP (PHAR), strat�gies d''optimisation et de s�curit�, gestion des images et des caches, migration entre versions de PHP...\r\n� qui s''adresse cet ouvrage ?\r\n\r\n    Aux d�veloppeurs souhaitant comprendre PHP 5 et son mod�le objet\r\n    Aux d�veloppeurs et administrateurs de sites et d''applications web\r\n    Aux �tudiants en informatique souhaitant appr�hender les techniques du Web', 'FR', '978-2-212-13435-3', 'PHP'),
						(10, 5, 'PHP Solutions: Dynamic Web Design Made Easy', 528, 2010, '44.99', 'This is the second edition of David Power''s highly-respected PHP Solutions: Dynamic Web Design Made Easy. This new edition has been updated by David to incorporate changes to PHP since the first edition and to offer the latest techniques--a classic guide modernized for 21st century PHP techniques, innovations, and best practices.\r\n\r\nYou want to make your websites more dynamic by adding a feedback form, creating a private area where members can upload images that are automatically resized, or perhaps storing all your content in a database. The problem is, you''re not a programmer and the thought of writing code sends a chill up your spine. Or maybe you''ve dabbled a bit in PHP and MySQL, but you can''t get past baby steps. If this describes you, then you''ve just found the right book. PHP and the MySQL database are deservedly the most popular combination for creating dynamic websites. They''re free, easy to use, and provided by many web hosting companies in their standard packages.\r\n\r\nUnfortunately, most PHP books either expect you to be an expert already or force you to go through endless exercises of little practical value. In contrast, this book gives you real value right away through a series of practical examples that you can incorporate directly into your sites, optimizing performance and adding functionality such as file uploading, email feedback forms, image galleries, content management systems, and much more. Each solution is created with not only functionality in mind, but also visual design.\r\n\r\nBut this book doesn''t just provide a collection of ready-made scripts: each PHP Solution builds on what''s gone before, teaching you the basics of PHP and database design quickly and painlessly. By the end of the book, you''ll have the confidence to start writing your own scripts or--if you prefer to leave that task to others--to adapt existing scripts to your own requirements. Right from the start, you''re shown how easy it is to protect your sites by adopting secure coding practices.', 'EN', '978-1430232490', 'PHP'),
						(11, 6, 'Beginning PHP 5.3', 842, 2011, '41.70', 'This book is intended for anyone starting out with PHP programming. If you''ve previously worked in another programming language such as Java, C#, or Perl, you''ll probably pick up the concepts in the earlier chapters quickly; however, the book assumes no prior experience of programming or of building Web applications.\r\n\r\nThat said, because PHP is primarily a Web technology, it will help if you have at least some knowledge of other Web technologies, particularly HTML and CSS.\r\n\r\nMany Web applications make use of a database to store data, and this book contains three chapters on working with MySQL databases. Once again, if you''re already familiar with databases in general - and MySQL in particular - you''ll be able to fly through these chapters. However, even if you''ve never touched a database before in your life, you should still be able to pick up a working ', 'EN', '', 'PHP'),
						(12, 7, 'JavaScript: The Definitive Guide: Activate Your Web Pages', 1100, 2011, '49.99', 'Since 1996, JavaScript: The Definitive Guide has been the bible for JavaScript programmers-a programmer''s guide and comprehensive reference to the core language and to the client-side JavaScript APIs defined by web browsers.\r\n\r\nThe 6th edition covers HTML5 and ECMAScript 5. Many chapters have been completely rewritten to bring them in line with today''s best web development practices. New chapters in this edition document jQuery and server side JavaScript. It''s recommended for experienced programmers who want to learn the programming language of the Web, and for current JavaScript programmers who want to master it.', 'EN', '978-0596805524', 'JS'),
						(13, 6, 'Professional JavaScript for Web Developers', 960, 2011, '44.99', 'JavaScript is loosely based on Java, which is an object-oriented programming language that became popular for use on the Web by way of embedded applets. It has a similar syntax and programming methodology to Java, however, it should not be considered the ''light'' version of the language. JavaScript is its own language that found its home in web browsers around the world and enabled enhanced user interaction on websites as well as web applications. In this book JavaScript is covered from its beginning in the earliest Netscape browsers to the present-day versions that can support the DOM and Ajax. You will learn how to extend the language to suit specific needs and how to create client-server communications without intermediaries such as Java or hidden frames. You will also learn how to apply JavaScript solutions to business problems faced by web developers everywhere.\r\n\r\nThis book provides a developer-level introduction along with more advanced and useful features of JavaScript. The book begins by exploring how JavaScript originated and evolved into what it is today. There is a discussion of the components that make up a JavaScript implementation that follows that has a specific focus on standards such as ECMAScript and the Document Object Model (DOM). The differences in JavaScript implementations used in different popular web browsers are also discussed. After building a strong base, the book goes on to cover basic concepts of JavaScript including its version of object-oriented programming, inheritance, and its use in HTML.  The book then explores new APIs, such as HTML5, the Selectors API, and the File API. The last part of the book is focused on advanced topics including performance/memory optimization, best practices, and a look at Where JavaScript is going in the future.', 'EN', '978-1118026694', 'JS'),
						(14, 8, 'JavaScript', 415, 2011, '20.45', 'Dans cet ouvrage pratique, entrez dans l''univers de JavaScript et faites le tour complet du sujet. Vous d�couvrirez les bases du langage puis apprendrez � manipuler des dates, g�rer des tableaux, �crire des cookies, g�rer l''interactivit� gr�ce � des exemples et des cas pratiques. Enfin, vous pourrez approfondir le sujet gr�ce � des exercices.\r\nUn ouvrage tr�s utile pour travailler avec JavaScript !\r\n\r\nPassionn� par le d�veloppement web, Olivier Hondermarck cr�e en 1999 son site de scripts et de tutoriaux sur le JavaScript ToutJavaScript.com, devenu rapidement une des r�f�rences du langage en France. Une formation d''ing�nieur et de nombreuses exp�riences de d�veloppements d''applications Internet dans de grandes entreprises lui donnent une vision concr�te des besoins et des m�thodes de travail professionnels. D�but 2004, il cr�e sa soci�t� et lance Beaut�-test.com avec sa compagne.', 'FR', '978-2300039058', 'JS'),
						(15, 2, 'M�mento HTML5', 14, 2012, '4.75', '', 'FR', '978-2212134209', 'HTML'),
						(16, 2, 'HTML5 : Une r�f�rence pour le d�veloppeur web', 624, 2011, '39.00', 'Gr�ce � HTML 5, on peut maintenant d�velopper des sites puissants et graphiquement riches, ainsi que des applications web, sans avoir forc�ment besoin d''un langage comme Flash. D�j� utilisable en grande partie dans les navigateurs web actuels, le standard HTML 5 est pourtant peu abordable, de par la quantit� des sp�cifications et leur technicit�. Didactique et pratique, cet ouvrage en donne les explications essentielles, ainsi que les bonnes pratiques, les astuces utiles au d�veloppeur pour profiter au maximum des nouvelles fonctionnalit�s HTML 5, en insistant sur la performance et l''accessibilit�.', 'FR', '978-2212129823', 'HTML'),
						(17, 2, 'CSS avanc�es : Vers HTML5 et CSS3', 685, 2012, '36.57', 'Incontournable du design web moderne, les feuilles de styles CSS sont en pleine r�volution avec l''adoption des nouveaux standards HTML5 et CSS3. Familier de CSS 2, allez plus loin en ma�trisant les techniques avanc�es d�j� �prouv�es dans CSS2.1 et d�couvrez les multiples possibilit�s de CSS3 ! Chaque jour mieux prises en charge par les navigateurs, les CSS sont sans conteste un gage de qualit� dans la conception d''un site web �l�gant, fonctionnel et accessible, aussi bien sous Mozilla Firefox, Google Chrome, Opera ou Safari que sous Internet Explorer ou les navigateurs mobiles. Vous croyiez tout savoir sur les CSS ? Gr�ce � la deuxi�me �dition de ce livre de r�f�rence, enrichie et mise � jour, vous irez encore plus loin ! Vous apprendrez � faire usage tout autant des technologies avant-gardistes de CSS 3 et HTML 5 que de pratiques avanc�es, concr�tes et mal connues d�j� utilisables en production, et ce, pour l''ensemble des m�dias reconnus par les styles CSS (�crans de bureau ou mobiles, messageries, mais aussi impression, m�dias de restitution vocale, projection et t�l�vision). Ma�trisez tous les rouages du positionnement en CSS2.1, exploitez les microformats, optimisez les performances d''un site, g�rez efficacement vos projets ou contournez les bogues des navigateurs (hacks, commentaires conditionnels, HasLayout...). Enfin, profitez d�s aujourd''hui des nouveaut�s de CSS3: typographie, gestion des c�sures, colonnes, arri�re-plans, d�grad�s, ombres port�es, redimensionnement, rotations, transitions et autres effets anim�s, sans oublier les Media Queries, qui permettent d''adapter le site � son support de consultation. Conseils m�thodologiques, bonnes pratiques, outils, tests, exemples avec r�sultats en ligne, quizzes et exercices corrig�s, tableaux r�capitulatifs : rien ne manque � ce manuel du parfait designer web ! ', 'FR', '978-2212134056', 'CSS'),
						(18, 4, 'HTML5 et CSS3 - Ma�trisez les standards des applications Web', 430, 3011, '30.32', '\r\nCe livre sur le HTML5 et CSS3 s''adresse � toute personne appel�e � d�velopper, mettre en place, faire vivre un site web. En effet, pour d�buter mais surtout pour progresser dans la conception de sites, il faut in�vitablement passer par une bonne compr�hension et une bonne ma�trise du code source des applications Web. Le livre est con�u comme un r�el outil de formation, p�dagogique de la premi�re � la derni�re page, abondamment illustr� d''exemples et de captures d''�cran et constamment � l''aff�t des �l�ments r�ellement pratiques pour le webmestre. Sont ainsi pass�s en revue le HTML (dans sa derni�re version et ses nombreuses nouveaut�s), les feuilles de style avec l''avanc�e spectaculaire des CSS3 en termes de pr�sentation des pages web et quelques �l�ments de JavaScript Cet ouvrage n''est surtout pas une encyclop�die exhaustive de ces diff�rentes techniques mais un parcours structur� de celles-ci. Il fournit aux concepteurs d�butants, voire plus confirm�s, les r�gles rigoureuses mais essentielles de la conception professionnelle d''un site Web. En effet, l''auteur s''est attach� � encourager l''�laboration d''un code respectueux des prescriptions du W3C et particuli�rement de la s�paration du contenu (HTML) et de la pr�sentation (feuilles de style CSS) comme le pr�conise plus que jamais le HTML5. Ces nombreuses nouveaut�s ne sont prises en compte que par les derni�res versions des navigateurs (Internet Explorer 9, Firefox, Google Chrome ou Safari) mais l''auteur a �t� particuli�rement attentif � fournir un code compatible avec des navigateurs moins �volu�s afin de pouvoir b�n�ficier d�s � pr�sent de ce pas important dans la conception des applications Web. Des �l�ments compl�mentaires sont en t�l�chargement sur le site www.editions-eni.fr.', 'FR', '978-2746062429', 'HTML'),
						(19, 4, 'Les API JavaScript du HTML5', 509, 2012, '37.58', 'Ce livre s''adresse aux d�veloppeurs de pages et applications Web d�sireux de tirer pleinement parti des API JavaScript du HTML5. L''auteur propose une exploration de ces nombreuses API JavaScript, certaines pleinement op�rationnelles, d''autres encore en phase de d�veloppement. Le HTML5 �tant une �volution de port�e consid�rable qui modifie totalement la conception des pages ou applications Web, l''auteur a veill� � adopter une approche pragmatique et explicative, illustr�e de nombreux exemples et captures d''�cran. L''objectif du livre est double ; tout d''abord, permettre au lecteur d''int�grer dans ses applications, certaines de ces API comme la g�olocalisation, le dessin en 2D, le stockage de donn�es en local ou pourquoi pas une base de donn�es, ensuite, de faire d�couvrir l''�norme impulsion que vont cr�er ces API JavaScript qui seront dans leur globalit� une v�ritable plateforme de d�veloppement d''applications Html5. Les diff�rents chapitres du livre d�taillent en particulier : l''API Selectors qui rem�die aux lacunes du JavaScript traditionnel dans la s�lection des �l�ments du DOM - la plus m�diatique du moment, l''API de g�olocalisation qui permet de conna�tre les coordonn�es g�ographiques de l''utilisateur - l''API Storage qui permet de conserver dans le navigateur des donn�es qui pourront �tre utilis�es ult�rieurement sans passer par un serveur - l''API Offline �labor�e pour permettre aux tablettes et smartphone de continuer � utiliser une application en mode d�connect� suite � une perte de r�seau - l''API History qui permet de cr�er de nouvelles entr�es dans l''historique - l''API Drag & Drop qui permet d''utiliser le glisser/d�poser en mode natif... Suivent ensuite une s�rie d''API plus limit�es comme la s�lection de fichiers, la possibilit� de transmettre des informations entre diff�rentes fen�tres ou balises iframe localis�es sur le m�me domaine ou des domaines diff�rents, l''ex�cution de scripts en arri�re-plan et l''API WebSocket qui permet d''ouvrir une connexion bi-directionnelle permanente entre le client et le serveur. Enfin, l''API Canvas qui permet le dessin 2D directement dans la page sans passer par des images. Des �l�ments compl�mentaires sont en t�l�chargement sur www.editions-eni.fr. Les chapitres du livre : Avant-propos - Pr�sentation - L''API Selectors - La g�olocalisation - Le stockage de donn�es en local - L''API Web SQL Database - L''API Indexed Database - L''�dition de contenu (contentEditable) - Le mode d�connect� (offline) - Manipuler l''historique du navigateur - Le glisser/d�poser (drag/drop) - La s�lection de fichiers - L''API Web Messaging - Le JavaScript en toile de fond - L''API WebSocket - L''API de dessin', 'FR', '978-2746074101', 'JS');";

	foreach ($creates as $table => $S) {
		if (@mysqli_query($BD, $S) === FALSE) {
			echo 'Erreur initBase - ', __LINE__,
					' : cr�ation de la table ', $table, ' impossible<br>',
					mysqli_errno($BD), ' : ', mysqli_error($BD);
			exit();
		}

		if (@mysqli_query($BD, $inserts[$table]) === FALSE) {
			echo 'Erreur initBase - ', __LINE__,
					' : insert dans la table ', $table, ' impossible<br>',
					mysqli_errno($BD), ' : ', mysqli_error($BD);
			exit();
		}
	}

	$S = "CREATE USER '$bdNewUser'@'localhost' IDENTIFIED BY '$bdNewPass';";
	if (@mysqli_query($BD, $S) === FALSE) {
		echo 'Erreur initBase - ', __LINE__,
				' : cr�ation de l\'utilisateur ', $bdNewUser, ' impossible<br>',
				mysqli_errno($BD), ' : ', mysqli_error($BD);
		exit();
	}
    
    $S = "GRANT SELECT, INSERT, UPDATE, DELETE ON `$bdNom` .* TO '$bdNewUser'@'localhost';";
	if (@mysqli_query($BD, $S) === FALSE) {
		echo 'Erreur GRANT - ', __LINE__,
				' : ajout des droits � l\'utilisateur ', $bdNewUser, ' impossible<br>',
				mysqli_errno($BD), ' : ', mysqli_error($BD);
		exit();
	}
}
?>