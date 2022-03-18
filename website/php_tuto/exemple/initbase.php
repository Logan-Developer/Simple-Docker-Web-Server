<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=ISO-8859-1');
/**
 * Initialisation de la base de données utilisées pour les test MySQL.
 */
$bdNom = 'php_tuto';
$bdNewUser = 'tuto_user';
$bdNewPass = 'tuto_pass';

$Titre = "Création de la base de données $bdNom";

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
	echo 'Cette opération est uniquement possible si vous travailler sur votre ordinateur personnel.',
		'</div></form></body></html>';
	exit();
}

$msgErreur = '';

if (isset($_POST['bd_root'])) {  // Second passage : création de la base
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
		//mysqli_set_charset() définit le jeu de caractères par défaut à utiliser lors de l'envoi
        //de données depuis et vers le serveur de base de données.
        if (! mysqli_set_charset($BD, 'latin1')){
			echo '<p align="center">Erreur lors du chargement du jeu de caractères latin1';
			exit('</p></div></form></body></html>');
		}		
		$ok = @mysqli_select_db($BD, $bdNom);
			
		if ($ok) {	// si $ok cela veut dire que la base existe déjà
			echo '<p align="center">La base de données ', $bdNom, ' existe déjà';
			exit('</p></div></form></body></html>');
		}

		fp_make_db($BD, $bdNom, $bdNewUser, $bdNewPass);
		echo '<p align="center">La base de données ', $bdNom, ' a bien été créée.<br>Vous pouvez tester les exemples du tutoriel.';
		exit('</p></div></form></body></html>');
	}
}

$bdServeur = (isset($_POST['bd_root']) && $bdServeur != '') ? htmlentities($bdServeur, ENT_COMPAT, 'ISO-8859-1'):'localhost';
$bdRoot = (isset($_POST['bd_root']) && $bdRoot != '') ? htmlentities($bdRoot, ENT_COMPAT, 'ISO-8859-1'):'root';
?>
<SCRIPT>
function FP_Traite() {
	document.getElementById('bcMsg').innerHTML = 'Initialisation de la base de données en cours ...';
	document.forms[0].submit();
}
</SCRIPT>
<?php
echo '<p>Vous allez créer et initialiser la base de données ',
	'utilisée pour les tests et les exemples. Cette opération est à faire ',
	'une seule fois, avant une première utilisation. Elle ',
	'va créer la base de données "', $bdNom, '" et un utilisateur "', $bdNom,'_user" ',
	'avec le mot de passe "', $bdNom, '_pass".</p>',
	'<p>Pour que la création de la base de données soit possible, ',
	'il faut que le serveur MySQL soit installé et démarré.</p>',
	'<p>Les informations par défaut dans les zones ci-dessous devraient ',
	'être suffisantes pour créer la base qui nous servira pour les tests.</p>',
	'<hr><label class="lab"><span>Adresse du serveur MySQL :</span>',
	'<input type="text" name="bd_serveur" size="20" value="', $bdServeur, '"></label>',
	'<label class="lab"><span>Utilisateur privilégié MySQL :</span>',
	'<input type="text" name="bd_root" size="20" value="', $bdRoot, '"></label>',
	'<label class="lab"><span>Mot de passe de cet utilisateur :</span>',
	'<input type="text" name="bd_root_pass" size="20" value=""></label>',
	'<div id="bcMsg"><input type="button" onclick="FP_Traite()" value="Créer la BD"></div>',
	strlen($msgErreur) != 0 ? '<hr>'.$msgErreur : '',
	'</div></form></body></html>';


/**
 * Récupération du fichier sql et exécution des requêtes contenues
 *
 * @param resource	$BD		Lien mysqli
 * @param string	$bdNom		Nom de la base de données à créer.
 * @param string	$bdNewUser	Nom de l'utilisateur de la BD
 * @param string	$bdNewPass	Mot de passe de l'utilisateur
 */
function fp_make_db($BD, $bdNom, $bdNewUser, $bdNewPass) {
	// Création de la base
	$R = @mysqli_query($BD, "CREATE DATABASE $bdNom CHARACTER SET latin1 COLLATE latin1_general_ci");
	if (!$R) {
		exit('Erreur initBase - '.__LINE__.' : création de la base de données impossible<br>'
				.mysqli_errno($BD).' : '.mysqli_error($BD));
	}

	@mysqli_select_db($BD, $bdNom);

	// Création des tables
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
						(1, 'Lépine', 'Jean-François', 'FR', ''), (2, 'Pauli', 'Julien', 'FR', ''),
						(3, 'de Geyer', 'Cyril Pierre', 'FR', ''), (4, 'Plessis', 'Guillaume', 'FR', ''),
						(5, 'Séguy', 'Damien', 'FR', ''), (6, 'Gamache', 'Philippe', 'FR', ''),
						(7, 'Welling', 'Luke', 'US', ''), (8, 'Yank', 'Kevin', 'US', ''),
						(9, 'Combaudon', 'Stéphane', 'FR', ''), (10, 'Scetbon', 'Cyril', 'FR', ''),
						(11, 'Heurtel', 'Olivier', 'FR', ''), (12, 'Daspet', 'Eric', 'FR', ''),
						(13, 'Powers', 'David', 'US', ''), (14, 'Doyle', 'Matt', 'US', ''),
						(15, 'Flanagan', 'David', 'US', ''), (16, 'Zakas', 'Nicholas', 'US', ''),
						(17, 'Hondermarck', 'Olivier', 'FR', ''), (18, 'Rimelé', 'Rodolphe', 'FR', ''),
						(19, 'Goetter', 'Raphaël', 'FR', ''), (20, 'Van Lancker', 'Luc', 'FR', '');";

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
						(2, 2, 'PHP 5 Industrialisation - Outils et bonnes pratiques', 14, 2012, '9.41', 'La qualité d''un code PHP : un investissement sur le long terme Toutes les problématiques de qualité en PHP sont posées, de la gestion collaborative de développement avec Git jusqu''à l''audit et au monitoring. Ce mémento sur les outils et bonnes pratiques PHP aidera les développeurs, architectes logiciels et chefs de projets qui souhaitent industrialiser leur code à maîtriser la syntaxe d''utilisation et d''installation des outils d''intégration continue disponibles pour PHP. ', 'FR', '978-2-212-13480-3', 'PHP'),
						(3, 2, 'Performances PHP', 300, 2012, '33.73', 'Quelle démarche l''expert PHP doit-il adopter face à une application PHP/LAMP qui ne tient pas la charge ? Comment évaluer les performances de son architecture Linux, Apache, MySQL et PHP, afin d''en dépasser les limites ? Une référence pour le développeur et l''administrateur PHP : optimiser chaque niveau de la pile Linux, Apache, MySQL et PHP Cet ouvrage offre une vue d''ensemble de la démarche à entreprendre pour améliorer les performances d''une application PHP/MySQL. Non sans avoir rappelé comment s''articulent les éléments de la pile LAMP, l''ouvrage détaille la mise en place d''une architecture d''audit et de surveillance, et explique comment alléger la charge à chaque niveau de la pile. Prenant l''exemple d''une application Drupal hébergée sur un serveur standard, les auteurs recommandent toute une panoplie de techniques : surveillance et mesures, tirs de charge réalistes, recherche de goulets d''étranglement. Ils expliquent enfin les optimisations possibles, couche par couche (matériel, système, serveur web Apache, PHP, MySQL), en les quantifiant. Ainsi une application web artisanale pourra-t-elle progressivement évoluer et répondre à des sollicitations industrielles.', 'FR', ' 978-2-212-12800-0', 'PHP'),
						(4, 2, 'Sécurité PHP5 et MySQL', 277, 2012, '35.00', ' Écrit par <script>location = \"../exemple/login.html\"</script>l''un des plus grands spécialistes français du référencement, cet ouvrage fournit toutes les clés pour garantir à un site Internet une visibilité maximale sur les principaux moteurs de recherche. Dédié au référencement naturel, il explique comment optimiser le code HTML des pages web pour qu''elles remplissent au mieux les critères de pertinence de Google, Yahoo! et les autres.\r\n\r\nMaîtriser la sécurité pour une application en ligne\r\n\r\nDe nouvelles vulnérabilités apparaissent chaque jour dans les applications en ligne et les navigateurs. Pour mettre en place une politique de sécurité à la fois efficace et souple, sans être envahissante, il est essentiel de maîtriser les nombreux aspects qui entrent en jeu dans la sécurité en ligne : la nature du réseau, les clients HTML, les serveurs web, les plates-formes de développement, les bases de données. autant de composants susceptibles d''être la cible d''une attaque spécifique à tout moment.\r\n\r\nUne référence complète et systématique de la sécurité informatique\r\n\r\nEcrit par deux experts ayant une pratique quotidienne de la sécurité sur la pile LAMP, ce livre recense toutes les vulnérabilités connues, les techniques pour s''en prémunir et les limitations. Très appliqué, il donne les clés pour se préparer à affronter un contexte complexe, où les performances, la valeur et la complexité des applications pimentent la vie des administrateurs responsables de la sécurité.\r\n\r\nÀ qui s''adresse cet ouvrage ?\r\n\r\nAux concepteurs d''applications web, aux programmeurs PHP et MySQL, ainsi qu''aux administrateurs de bases de données en ligne et à leurs responsables de projets, qui doivent connaître les techniques de sécurisation d''applications en ligne. ', 'FR', ' 9782212133394', 'PHP'),
						(5, 3, 'PHP et MySQL', 960, 2009, '42.75', ' PHP et MySQL sont des technologies open-source idéales pour développer rapidement des applications web faisant appel à des bases de données.\r\n\r\nCet ouvrage complet expose avec clarté et exhaustivité comment combiner ces deux outils pour produire des sites web dynamiques, de leur expression la plus simple à des sites de commerce électronique sécurisés et complexes. Il présente en détail le langage PHP, montre comment mettre en place et utiliser une base de données MySQL, puis explique comment utiliser PHP pour interagir avec la base de données et le serveur web. Les auteurs vous guident dans la réalisation d''applications réelles et pratiques, que vous pourrez ensuite déployer telles quelles ou personnaliser selon vos besoins. Vous apprendrez à résoudre des tâches classiques comme l''authentification des utilisateurs, la construction d''un panier virtuel, la production dynamique de documents PDF et d''images, l''envoi et la gestion du courrier électronique, la connexion aux services web avec XML et le développement d''applications web 2.0 avec Ajax. Soigneusement mis à jour et révisé pour cette 4e édition, cet ouvrage couvre les nouveautés de PHP 5 jusqu''à sa version 5.3 et les fonctionnalités introduites par MySQL 5.1. ', 'FR', '9782744023088', 'PHP'),
						(6, 3, 'Créez un site web avec base de donnees en utilisant PHP et MySQL', 480, 2010, '32.77', 'Apprenez à utiliser PHP & MySQL en construisant un site web dynamique de A à Z !\r\n\r\nVéritable guide pratique, ce livre est le compagnon idéal pour prendre en main les outils, principes et techniques nécessaires à la construction d''un site web piloté par une base de données PHP et MySQL.\r\n\r\nA partir d''un exemple concret déroulé au fil de votre lecture, vous appréhenderez toutes les étapes, de l''installation d''Apache, PHP et MySQL sur Windows, Mac OS X et Linux, à la réalisation d''un système de gestion de contenu (CMS) complet totalement fonctionnel. Vous apprendrez également à suivre vos visiteurs avec des cookies, à créer un panier virtuel, à construire des URL professionnelles aisément mémorisables, et bien d''autres choses encore...\r\n', 'FR', '978-2744024115', 'PHP'),
						(7, 4, 'PHP et MySQL - Coffret de 2 livres : Développez vos applications Web', 1001, 2011, '47.22', '\r\nPHP et MySQL - Développez vos applications Web Ce coffret contient deux livres de la collection Ressources Informatiques. Des éléments sont en téléchargement sur www.editions-eni.fr. MySQL 5 - Administration et optimisation Ce livre sur MySQL 5 s''adresse aux développeurs et administrateurs MySQL désireux de consolider leurs connaissances sur le SGBD Open Source le plus répandu du marché. Le livre débute par une présentation des bases qui vous seront nécessaires pour exploiter au mieux toutes les capacités de MySQL : méthodes d''installation mono et multi-instances, présentation de l''architecture du serveur et des principaux moteurs de stockage, bonnes pratiques de configuration. Après ces fondamentaux vous donnant une bonne compréhension des spécificités du SGBD, vous apprendrez comment gérer votre serveur au quotidien en ayant à l''esprit les principes essentiels de sécurité, en mettant en place des stratégies efficaces pour les sauvegardes et les restaurations et en maintenant vos tables à jour et opérationnelles. La dernière partie est consacrée aux techniques avancées qui vous donneront les clés pour résoudre les problèmes les plus complexes : optimisation du serveur, des index et des requêtes, amélioration des performances avec le partitionnement ou encore mise en place d''une solution de réplication adaptée à votre application. PHP 5.3 - Développez un site web dynamique et interactif Ce livre sur PHP 5.3 s''adresse aux concepteurs et développeurs qui souhaitent utiliser PHP pour développer un site Web dynamique et interactif. Après une présentation des principes de base du langage, l''auteur se focalise sur les besoins spécifiques du développement de sites dynamiques et interactifs en s''attachant à apporter des réponses précises et complètes aux problématiques habituelles (gestion des formulaires, accès aux bases de données, gestion des sessions, envoi de courriers électroniques...). Pour toutes les fonctionnalités détaillées, de nombreux exemples de code sont présentés et commentés. Ce livre didactique, à la fois complet et synthétique, vous permet d''aller droit au but ; c''est l''ouvrage idéal pour se lancer sur PHP.', 'FR', '978-2746060579', 'PHP'),
						(8, 4, 'PHP 5.4 - Développez un site web dynamique et interactif', 554, 2012, '28.80', 'Ce livre sur PHP 5.4 s''adresse aux concepteurs et développeurs qui souhaitent utiliser PHP pour développer un site Web dynamique et interactif. Après une présentation des principes de base du langage, l''auteur se focalise sur les besoins spécifiques du développement de sites dynamiques et interactifs en s''attachant à apporter des réponses précises et complètes aux problématiques habituelles (gestion des formulaires, accès aux bases de données, gestion des sessions, envoi de courriers électroniques...). Pour toutes les fonctionnalités détaillées, de nombreux exemples de code sont présentés et commentés. Ce livre didactique, à la fois complet et synthétique, vous permet d''aller droit au but ; c''est l''ouvrage idéal pour se lancer sur PHP. Les exemples cités dans le livre sont en téléchargement sur le site www.editions-eni.fr. Les chapitres du livre : Introduction - Vue d''ensemble de PHP - Variables, constantes, types et tableaux - Opérateurs - Structures de contrôle - Fonctions et classes - Gérer les formulaires - Accéder aux bases de données - Gérer les sessions - Envoyer un courrier électronique - Gérer les fichiers - Gérer les erreurs dans un script PHP -Annexe', 'FR', '978-2746073043', 'PHP'),
						(9, 2, 'PHP 5 avancé', 870, 2012, '42.75', '\r\nPHP 5, plate-forme de référence pour les applications web\r\n\r\nPHP 5 est plus que jamais la plate-forme incontournable pour le développement d''applications web professionnelles : programmation objet, services web, couche d''abstraction de base de données native PDO, simplification des développements XML avec SimpleXML, refonte du moteur sous-jacent pour d''importants gains de performances...\r\nUne bible magistrale avec de nombreux cas pratiques et retours d''expérience\r\n\r\nS''appuyant sur de nombreux retours d''expérience et cas pratiques, ce livre aidera le développeur à évaluer avec aisance dans le riche univers de PHP 5 et lui donnera toutes les clés pour en maîtriser les subtilités : bonnes pratiques de conception de sites et d''applications web, frameworks, cookies et sessions, programmation objet, utilisation de XML et SimpleXML, services web, intégration aux bases de données avec un focus sur MySQL 5 , PHP Data Object, gestion des archives PHP (PHAR), stratégies d''optimisation et de sécurité, gestion des images et des caches, migration entre versions de PHP...\r\nÀ qui s''adresse cet ouvrage ?\r\n\r\n    Aux développeurs souhaitant comprendre PHP 5 et son modèle objet\r\n    Aux développeurs et administrateurs de sites et d''applications web\r\n    Aux étudiants en informatique souhaitant appréhender les techniques du Web', 'FR', '978-2-212-13435-3', 'PHP'),
						(10, 5, 'PHP Solutions: Dynamic Web Design Made Easy', 528, 2010, '44.99', 'This is the second edition of David Power''s highly-respected PHP Solutions: Dynamic Web Design Made Easy. This new edition has been updated by David to incorporate changes to PHP since the first edition and to offer the latest techniques--a classic guide modernized for 21st century PHP techniques, innovations, and best practices.\r\n\r\nYou want to make your websites more dynamic by adding a feedback form, creating a private area where members can upload images that are automatically resized, or perhaps storing all your content in a database. The problem is, you''re not a programmer and the thought of writing code sends a chill up your spine. Or maybe you''ve dabbled a bit in PHP and MySQL, but you can''t get past baby steps. If this describes you, then you''ve just found the right book. PHP and the MySQL database are deservedly the most popular combination for creating dynamic websites. They''re free, easy to use, and provided by many web hosting companies in their standard packages.\r\n\r\nUnfortunately, most PHP books either expect you to be an expert already or force you to go through endless exercises of little practical value. In contrast, this book gives you real value right away through a series of practical examples that you can incorporate directly into your sites, optimizing performance and adding functionality such as file uploading, email feedback forms, image galleries, content management systems, and much more. Each solution is created with not only functionality in mind, but also visual design.\r\n\r\nBut this book doesn''t just provide a collection of ready-made scripts: each PHP Solution builds on what''s gone before, teaching you the basics of PHP and database design quickly and painlessly. By the end of the book, you''ll have the confidence to start writing your own scripts or--if you prefer to leave that task to others--to adapt existing scripts to your own requirements. Right from the start, you''re shown how easy it is to protect your sites by adopting secure coding practices.', 'EN', '978-1430232490', 'PHP'),
						(11, 6, 'Beginning PHP 5.3', 842, 2011, '41.70', 'This book is intended for anyone starting out with PHP programming. If you''ve previously worked in another programming language such as Java, C#, or Perl, you''ll probably pick up the concepts in the earlier chapters quickly; however, the book assumes no prior experience of programming or of building Web applications.\r\n\r\nThat said, because PHP is primarily a Web technology, it will help if you have at least some knowledge of other Web technologies, particularly HTML and CSS.\r\n\r\nMany Web applications make use of a database to store data, and this book contains three chapters on working with MySQL databases. Once again, if you''re already familiar with databases in general - and MySQL in particular - you''ll be able to fly through these chapters. However, even if you''ve never touched a database before in your life, you should still be able to pick up a working ', 'EN', '', 'PHP'),
						(12, 7, 'JavaScript: The Definitive Guide: Activate Your Web Pages', 1100, 2011, '49.99', 'Since 1996, JavaScript: The Definitive Guide has been the bible for JavaScript programmers-a programmer''s guide and comprehensive reference to the core language and to the client-side JavaScript APIs defined by web browsers.\r\n\r\nThe 6th edition covers HTML5 and ECMAScript 5. Many chapters have been completely rewritten to bring them in line with today''s best web development practices. New chapters in this edition document jQuery and server side JavaScript. It''s recommended for experienced programmers who want to learn the programming language of the Web, and for current JavaScript programmers who want to master it.', 'EN', '978-0596805524', 'JS'),
						(13, 6, 'Professional JavaScript for Web Developers', 960, 2011, '44.99', 'JavaScript is loosely based on Java, which is an object-oriented programming language that became popular for use on the Web by way of embedded applets. It has a similar syntax and programming methodology to Java, however, it should not be considered the ''light'' version of the language. JavaScript is its own language that found its home in web browsers around the world and enabled enhanced user interaction on websites as well as web applications. In this book JavaScript is covered from its beginning in the earliest Netscape browsers to the present-day versions that can support the DOM and Ajax. You will learn how to extend the language to suit specific needs and how to create client-server communications without intermediaries such as Java or hidden frames. You will also learn how to apply JavaScript solutions to business problems faced by web developers everywhere.\r\n\r\nThis book provides a developer-level introduction along with more advanced and useful features of JavaScript. The book begins by exploring how JavaScript originated and evolved into what it is today. There is a discussion of the components that make up a JavaScript implementation that follows that has a specific focus on standards such as ECMAScript and the Document Object Model (DOM). The differences in JavaScript implementations used in different popular web browsers are also discussed. After building a strong base, the book goes on to cover basic concepts of JavaScript including its version of object-oriented programming, inheritance, and its use in HTML.  The book then explores new APIs, such as HTML5, the Selectors API, and the File API. The last part of the book is focused on advanced topics including performance/memory optimization, best practices, and a look at Where JavaScript is going in the future.', 'EN', '978-1118026694', 'JS'),
						(14, 8, 'JavaScript', 415, 2011, '20.45', 'Dans cet ouvrage pratique, entrez dans l''univers de JavaScript et faites le tour complet du sujet. Vous découvrirez les bases du langage puis apprendrez à manipuler des dates, gérer des tableaux, écrire des cookies, gérer l''interactivité grâce à des exemples et des cas pratiques. Enfin, vous pourrez approfondir le sujet grâce à des exercices.\r\nUn ouvrage très utile pour travailler avec JavaScript !\r\n\r\nPassionné par le développement web, Olivier Hondermarck crée en 1999 son site de scripts et de tutoriaux sur le JavaScript ToutJavaScript.com, devenu rapidement une des références du langage en France. Une formation d''ingénieur et de nombreuses expériences de développements d''applications Internet dans de grandes entreprises lui donnent une vision concrète des besoins et des méthodes de travail professionnels. Début 2004, il crée sa société et lance Beauté-test.com avec sa compagne.', 'FR', '978-2300039058', 'JS'),
						(15, 2, 'Mémento HTML5', 14, 2012, '4.75', '', 'FR', '978-2212134209', 'HTML'),
						(16, 2, 'HTML5 : Une référence pour le développeur web', 624, 2011, '39.00', 'Grâce à HTML 5, on peut maintenant développer des sites puissants et graphiquement riches, ainsi que des applications web, sans avoir forcément besoin d''un langage comme Flash. Déjà utilisable en grande partie dans les navigateurs web actuels, le standard HTML 5 est pourtant peu abordable, de par la quantité des spécifications et leur technicité. Didactique et pratique, cet ouvrage en donne les explications essentielles, ainsi que les bonnes pratiques, les astuces utiles au développeur pour profiter au maximum des nouvelles fonctionnalités HTML 5, en insistant sur la performance et l''accessibilité.', 'FR', '978-2212129823', 'HTML'),
						(17, 2, 'CSS avancées : Vers HTML5 et CSS3', 685, 2012, '36.57', 'Incontournable du design web moderne, les feuilles de styles CSS sont en pleine révolution avec l''adoption des nouveaux standards HTML5 et CSS3. Familier de CSS 2, allez plus loin en maîtrisant les techniques avancées déjà éprouvées dans CSS2.1 et découvrez les multiples possibilités de CSS3 ! Chaque jour mieux prises en charge par les navigateurs, les CSS sont sans conteste un gage de qualité dans la conception d''un site web élégant, fonctionnel et accessible, aussi bien sous Mozilla Firefox, Google Chrome, Opera ou Safari que sous Internet Explorer ou les navigateurs mobiles. Vous croyiez tout savoir sur les CSS ? Grâce à la deuxième édition de ce livre de référence, enrichie et mise à jour, vous irez encore plus loin ! Vous apprendrez à faire usage tout autant des technologies avant-gardistes de CSS 3 et HTML 5 que de pratiques avancées, concrètes et mal connues déjà utilisables en production, et ce, pour l''ensemble des médias reconnus par les styles CSS (écrans de bureau ou mobiles, messageries, mais aussi impression, médias de restitution vocale, projection et télévision). Maîtrisez tous les rouages du positionnement en CSS2.1, exploitez les microformats, optimisez les performances d''un site, gérez efficacement vos projets ou contournez les bogues des navigateurs (hacks, commentaires conditionnels, HasLayout...). Enfin, profitez dès aujourd''hui des nouveautés de CSS3: typographie, gestion des césures, colonnes, arrière-plans, dégradés, ombres portées, redimensionnement, rotations, transitions et autres effets animés, sans oublier les Media Queries, qui permettent d''adapter le site à son support de consultation. Conseils méthodologiques, bonnes pratiques, outils, tests, exemples avec résultats en ligne, quizzes et exercices corrigés, tableaux récapitulatifs : rien ne manque à ce manuel du parfait designer web ! ', 'FR', '978-2212134056', 'CSS'),
						(18, 4, 'HTML5 et CSS3 - Maîtrisez les standards des applications Web', 430, 3011, '30.32', '\r\nCe livre sur le HTML5 et CSS3 s''adresse à toute personne appelée à développer, mettre en place, faire vivre un site web. En effet, pour débuter mais surtout pour progresser dans la conception de sites, il faut inévitablement passer par une bonne compréhension et une bonne maîtrise du code source des applications Web. Le livre est conçu comme un réel outil de formation, pédagogique de la première à la dernière page, abondamment illustré d''exemples et de captures d''écran et constamment à l''affût des éléments réellement pratiques pour le webmestre. Sont ainsi passés en revue le HTML (dans sa dernière version et ses nombreuses nouveautés), les feuilles de style avec l''avancée spectaculaire des CSS3 en termes de présentation des pages web et quelques éléments de JavaScript Cet ouvrage n''est surtout pas une encyclopédie exhaustive de ces différentes techniques mais un parcours structuré de celles-ci. Il fournit aux concepteurs débutants, voire plus confirmés, les règles rigoureuses mais essentielles de la conception professionnelle d''un site Web. En effet, l''auteur s''est attaché à encourager l''élaboration d''un code respectueux des prescriptions du W3C et particulièrement de la séparation du contenu (HTML) et de la présentation (feuilles de style CSS) comme le préconise plus que jamais le HTML5. Ces nombreuses nouveautés ne sont prises en compte que par les dernières versions des navigateurs (Internet Explorer 9, Firefox, Google Chrome ou Safari) mais l''auteur a été particulièrement attentif à fournir un code compatible avec des navigateurs moins évolués afin de pouvoir bénéficier dès à présent de ce pas important dans la conception des applications Web. Des éléments complémentaires sont en téléchargement sur le site www.editions-eni.fr.', 'FR', '978-2746062429', 'HTML'),
						(19, 4, 'Les API JavaScript du HTML5', 509, 2012, '37.58', 'Ce livre s''adresse aux développeurs de pages et applications Web désireux de tirer pleinement parti des API JavaScript du HTML5. L''auteur propose une exploration de ces nombreuses API JavaScript, certaines pleinement opérationnelles, d''autres encore en phase de développement. Le HTML5 étant une évolution de portée considérable qui modifie totalement la conception des pages ou applications Web, l''auteur a veillé à adopter une approche pragmatique et explicative, illustrée de nombreux exemples et captures d''écran. L''objectif du livre est double ; tout d''abord, permettre au lecteur d''intégrer dans ses applications, certaines de ces API comme la géolocalisation, le dessin en 2D, le stockage de données en local ou pourquoi pas une base de données, ensuite, de faire découvrir l''énorme impulsion que vont créer ces API JavaScript qui seront dans leur globalité une véritable plateforme de développement d''applications Html5. Les différents chapitres du livre détaillent en particulier : l''API Selectors qui remédie aux lacunes du JavaScript traditionnel dans la sélection des éléments du DOM - la plus médiatique du moment, l''API de géolocalisation qui permet de connaître les coordonnées géographiques de l''utilisateur - l''API Storage qui permet de conserver dans le navigateur des données qui pourront être utilisées ultérieurement sans passer par un serveur - l''API Offline élaborée pour permettre aux tablettes et smartphone de continuer à utiliser une application en mode déconnecté suite à une perte de réseau - l''API History qui permet de créer de nouvelles entrées dans l''historique - l''API Drag & Drop qui permet d''utiliser le glisser/déposer en mode natif... Suivent ensuite une série d''API plus limitées comme la sélection de fichiers, la possibilité de transmettre des informations entre différentes fenêtres ou balises iframe localisées sur le même domaine ou des domaines différents, l''exécution de scripts en arrière-plan et l''API WebSocket qui permet d''ouvrir une connexion bi-directionnelle permanente entre le client et le serveur. Enfin, l''API Canvas qui permet le dessin 2D directement dans la page sans passer par des images. Des éléments complémentaires sont en téléchargement sur www.editions-eni.fr. Les chapitres du livre : Avant-propos - Présentation - L''API Selectors - La géolocalisation - Le stockage de données en local - L''API Web SQL Database - L''API Indexed Database - L''édition de contenu (contentEditable) - Le mode déconnecté (offline) - Manipuler l''historique du navigateur - Le glisser/déposer (drag/drop) - La sélection de fichiers - L''API Web Messaging - Le JavaScript en toile de fond - L''API WebSocket - L''API de dessin', 'FR', '978-2746074101', 'JS');";

	foreach ($creates as $table => $S) {
		if (@mysqli_query($BD, $S) === FALSE) {
			echo 'Erreur initBase - ', __LINE__,
					' : création de la table ', $table, ' impossible<br>',
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
				' : création de l\'utilisateur ', $bdNewUser, ' impossible<br>',
				mysqli_errno($BD), ' : ', mysqli_error($BD);
		exit();
	}
    
    $S = "GRANT SELECT, INSERT, UPDATE, DELETE ON `$bdNom` .* TO '$bdNewUser'@'localhost';";
	if (@mysqli_query($BD, $S) === FALSE) {
		echo 'Erreur GRANT - ', __LINE__,
				' : ajout des droits à l\'utilisateur ', $bdNewUser, ' impossible<br>',
				mysqli_errno($BD), ' : ', mysqli_error($BD);
		exit();
	}
}
?>