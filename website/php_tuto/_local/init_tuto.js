(function() {
	var numChap, dossier;

	FP.init({tutoID: 'PHP',
			tutoTitre: '<span class="TIT-lettre">P</span>HP <span class="TIT-lettre">H</span>ypertext <span class="TIT-lettre">P</span>reprocessor',
			mailTo: 'francois.piat@univ-fcomte.fr',
			Video: {
				height: 360,
				width: 480,
				poster: '../_core/images/_video_file_1.png',
				TMovie: []
			},
			repTech: 'php_manual_fr/functions/',
			CodeMirrorEditeur: {
				dragDrop: false,
				lineNumbers: true,
				mode: 'application/x-httpd-php',
				indentUnit: 4,
				indentWithTabs: true,
				enterMode: 'keep',
				gutter: true,
				fixedGutter: true,
				readOnly: false,
				tabMode: 'shift'
			},
			CodeMirrorMode: {
				PHP:		'text/x-php',
				PHP_HTML:	'application/x-httpd-php'
			}
			});

	//=======================================================
	// Dans les objets pages suivant, si type n'est pas défini,
	// il sera PAGE_TUTO par défaut

	//=======================================================
	numChap = 0;
	FP.TChap[numChap] = new FP.Chapitre('Accueil',numChap);
	FP.addPage({titre: 'Accueil',
				rep: '_local',
				fic: 'tuto',
				type: FP.PAGE_ACCUEIL,
				chap: numChap});

	//=======================================================
	numChap ++;
	dossier = 'php01';
	FP.TChap[numChap] = new FP.Chapitre('Notions de base', numChap);

	FP.addPage({titre: 'Présentation',
				resume: 'Langage de scripts côté serveur. Produit open-source et multi plate-formes.',
				rep: dossier,
				fic: 'php01a1',
				chap: numChap});
	FP.addPage({titre: 'Fichiers et code PHP',
				resume: 'Des simples fichiers texte avec les mêmes structures de la langage que le C.',
				rep: dossier,
				fic: 'php01a2',
				chap: numChap});

	FP.addPage({titre: 'Variables développeur',
				resume: 'Variables définies par le développeur. Typage, nommage, portées',
				rep: dossier,
				fic: 'php01b1',
				chap: numChap});
	FP.addPage({titre: 'Variables prédéfinies',
				resume: 'Variables prédéfinies et intialisées par PHP.',
				rep: dossier,
				fic: 'php01b2',
				chap: numChap});
	FP.addPage({titre: 'Types de données',
				resume: 'Types de données supportés par PHP. Gestion des variables en mémoire.',
				rep: dossier,
				fic: 'php01b3',
				chap: numChap});
	FP.addPage({titre: 'Constantes',
				resume: 'Définition et utilisation de constantes.',
				rep: dossier,
				fic: 'php01b4',
				chap: numChap});
	FP.addPage({titre: 'Opérateurs',
				resume: 'Arithmétiques, concaténation, incrément, assignement, comparaison, logiques, type ...',
				rep: dossier,
				fic: 'php01c1',
				chap: numChap});
	FP.addPage({titre: 'Instructions de test',
				resume: 'Contrôler le flux et tester : if, switch.',
				rep: dossier,
				fic: 'php01d1',
				chap: numChap});
	FP.addPage({titre: 'Instructions de boucles',
				resume: 'Réaliser des itérations : while, for, foreach',
				rep: dossier,
				fic: 'php01d2',
				chap: numChap,
				exos: [['exo_table_mult', 'Table de multiplications']]});
	FP.addPage({titre: 'Instructions d\'arrêt',
				resume: 'Fin de script et gestion des erreurs.',
				rep: dossier,
				fic: 'php01d3',
				chap: numChap});

	FP.addPage({titre: 'Les fonctions',
				resume: 'Déclaration et appel de fonction.',
				rep: dossier,
				fic: 'php01e1',
				chap: numChap});
	FP.addPage({titre: 'Paramètres et arguments',
				resume: 'Pas de typage. Valeurs par défaut. Nombre variable.',
				rep: dossier,
				fic: 'php01e2',
				chap: numChap,
				exos: [
						['exo_fct_calculer', 'Fonction calculer']
					]});
	FP.addPage({titre: 'Valeurs de retour',
				resume: 'Renvoyer des valeurs et sortir d\'une fonction.',
				rep: dossier,
				fic: 'php01e3',
				chap: numChap,
				exos: [ ['exo_valeur_retour', 'Valeur de retour']
					]});
	FP.addPage({titre: 'Fonctions variables',
				resume: 'Invocation d\'une fonction d\'après une variable.',
				rep: dossier,
				fic: 'php01e4',
				chap: numChap});
	FP.addPage({titre: 'Inclure du code externe',
				resume: 'Inclure du code stocké dans d\'autres fichiers. Bibliothèques de fonctions.',
				rep: dossier,
				fic: 'php01e5',
				chap: numChap,
				exos: [
						['exo_bibli_fonctions', 'Bibliothèque de fonctions']
					]});

	//=======================================================
	numChap ++;
	dossier = 'php03';
	FP.TChap[numChap] = new FP.Chapitre('Chaines de caractères',numChap);

	FP.addPage({titre: 'Valeurs littérales',
				resume: 'Différentes modalités d\'inclusion des chaînes dans les scripts.',
				rep: dossier,
				fic: 'php03a1',
				chap: numChap});
	FP.addPage({titre: 'Affichage dans le navigateur',
				resume: 'Envoyer des élements au navigateur pour qu\'il les affiche.',
				rep: dossier,
				fic: 'php03a2',
				chap: numChap});

	FP.addPage({titre: 'Longueur et espaces blancs',
				resume: 'Nombre de caractères dans une chaîne. Eliminer les espaces blancs.',
				rep: dossier,
				fic: 'php03b1',
				chap: numChap});
	FP.addPage({titre: 'Parties de chaînes',
				resume: 'Extraire des parties d\'une chaînes. Décomposer une chaîne.',
				rep: dossier,
				fic: 'php03b2',
				chap: numChap});
	FP.addPage({titre: 'Remplacements',
				resume: 'Remplacer des caractères. Mise en majsucles/minuscules.',
				rep: dossier,
				fic: 'php03b3',
				chap: numChap});
	FP.addPage({titre: 'Rechercher',
				resume: 'Recherche de caractères ou de sous-chaînes.',
				rep: dossier,
				fic: 'php03b4',
				chap: numChap,
				exos: [
						['exo_isAdresseMail', 'Tester une adresse e-mail']
						]});

	FP.addPage({titre: 'Protection des chaînes',
				resume: 'Coder une chaîne pour HTML. Enlever les tags HTML. Protéger les caractères pour SQL.',
				rep: dossier,
				fic: 'php03c1',
				chap: numChap});
	FP.addPage({titre: 'Création de mots de passe.',
				resume: 'Exercice : créer des mots de passe selon diverses modalités.',
				rep: dossier,
				/*type: FP.PAGE_EXO,*/
				fic: 'php03c2',
				chap: numChap,
				exos: [
						['remplacerCaracteres', 'Mot de passe par remplacement'],
						['melangerCaracteres', 'Mot de passe par mélange'],
						['melangerPhrase', 'Mot de passe par mémorisation']
						]});

	//=======================================================
	numChap ++;
	dossier = 'php02';
	FP.TChap[numChap] = new FP.Chapitre('Dates',numChap);

	FP.addPage({titre: 'Timestamp et dates',
				resume: 'Timestamp = nombre de secondes. Récupérer et afficher les éléments d\'une date. Vérifier une date.',
				rep: dossier,
				fic: 'php02a1',
				chap: numChap});
	FP.addPage({titre: 'Définir une date',
				resume: 'Définir une date actuelle, future ou passée.',
				rep: dossier,
				fic: 'php02a2',
				chap: numChap,
				exos: [
						['newTimestamp', 'Fonction d\'ajout de temps'],
						['getTimeDiff', 'Fonction de différence de temps']
						]});
	FP.addPage({titre: 'Mesures en microsecondes',
				resume: 'Mesurer finement un laps de temps écoulé.',
				rep: dossier,
				fic: 'php02a3',
				chap: numChap});
	//=======================================================
	numChap ++;
	dossier = 'php05';
	FP.TChap[numChap] = new FP.Chapitre('Tableaux',numChap);

	FP.addPage({titre: 'A indices numériques ou associatifs',
				resume: 'Créer directement des tableaux à indices numériques ou associatifs.',
				rep: dossier,
				fic: 'php05a1',
				chap: numChap});
	FP.addPage({titre: 'Fonctions de création',
				resume: 'Créer des tableaux avec des fonctions de génération (simple, lettres, nombres, valeurs définies)',
				rep: dossier,
				fic: 'php05a2',
				chap: numChap});
	FP.addPage({titre: 'Matrices',
				resume: 'Gérer des tableaux à plusieurs dimensions pour émuler des matrices.',
				rep: dossier,
				fic: 'php05a3',
				chap: numChap});
	FP.addPage({titre: 'Opérations ensemblistes et filtrage',
				resume: 'Union ou différences de tableaux. Appliquer un filtrage sur un tableau.',
				rep: dossier,
				fic: 'php05a4',
				chap: numChap});
	FP.addPage({titre: 'Boucles avec foreach',
				resume: 'Itérations et boucles de lecture avec foreach',
				rep: dossier,
				fic: 'php05b1',
				chap: numChap});
	FP.addPage({titre: 'Boucles avec for',
				resume: 'Itérations et boucles de lecture avec for',
				rep: dossier,
				fic: 'php05b2',
				chap: numChap});
	FP.addPage({titre: 'Extraire les liens d\'une page HTML',
				resume: 'Exercice : extraire les liens d\'une page HTML',
				rep: dossier,
				/*type: FP.PAGE_EXO,*/
				fic: 'php05a5',
				chap: numChap,
				exos: [['extraire_liens', 'Extraire les liens d\'une page HTML']]});
	FP.addPage({titre: 'Fonctions d\'itération',
				resume: 'PHP offre des fonctions pour se déplacer dans un tableau.',
				rep: dossier,
				fic: 'php05b3',
				chap: numChap});

	FP.addPage({titre: 'Tableau en variables',
				resume: 'Transformer un tableau en variables',
				rep: dossier,
				fic: 'php05c1',
				chap: numChap});
	FP.addPage({titre: 'Parties de tableau',
				resume: 'Extraire, supprimer, remplacer, inserer des parties de tableau.',
				rep: dossier,
				fic: 'php05c2',
				chap: numChap});
	FP.addPage({titre: 'Clé et valeurs',
				resume: 'Obtenir les clés d\'un tableau. Vérifier que des clés ou des valeurs existent.',
				rep: dossier,
				fic: 'php05c3',
				chap: numChap});

	FP.addPage({titre: 'Quelques autres traitements',
				resume: 'Mélanger un tableau, retourner un tableau, somme de valeurs.',
				rep: dossier,
				fic: 'php05c4',
				chap: numChap});

	FP.addPage({titre: 'Trois façons de trier',
				resume: 'Tri ascendant ou descendant. Tri sur les clés ou sur les valeurs.',
				rep: dossier,
				fic: 'php05d1',
				chap: numChap});
	FP.addPage({titre: 'Ordre naturel et tri multiple',
				resume: 'Tri par ordre naturel. Tri multicritères.',
				rep: dossier,
				fic: 'php05d2',
				chap: numChap});
				//,
				//exos: [['tableau_statistique', 'Tableau de statistiques de ventes']]});

	//=======================================================
	numChap ++;
	dossier = 'php06';
	FP.TChap[numChap] = new FP.Chapitre('Web et PHP',numChap);

	FP.addPage({titre: 'HTTP, GET et POST',
				resume: 'Protocole HTTP et dialogue client-serveur.',
				rep: dossier,
				fic: 'php06a1',
				chap: numChap});
	FP.addPage({titre: 'Les formulaires',
				resume: 'Récupèrer les informations saisies dans des formulaires.',
				rep: dossier,
				fic: 'php06a2',
				chap: numChap});
	FP.addPage({titre: 'Validation de zones numériques',
				resume: 'Vérifier et traiter des informations numériques saisies dans des formulaires.',
				rep: dossier,
				fic: 'php06a3',
				chap: numChap});
	FP.addPage({titre: 'Validation de zones libres',
				resume: 'Vérifier et traiter des informations non numériques saisies dans des formulaires.',
				rep: dossier,
				fic: 'php06a4',
				chap: numChap});
	FP.addPage({titre: 'Validation de zones non saisies',
				resume: 'Vérifier et traiter des informations qui proviennent de boutons radio, cases à cocher, listes.',
				rep: dossier,
				fic: 'php06a5',
				chap: numChap});
	FP.addPage({titre: 'Traitement des erreurs de saisie',
				resume: 'Réaffichage du formulaire avec les saisies déjà faites. Soumission d\'une page sur elle-même.',
				rep: dossier,
				fic: 'php06a6',
				chap: numChap});

	FP.addPage({titre: 'Uploader des fichiers',
				resume: 'Télécharger des fichiers sur le serveur.',
				rep: dossier,
				fic: 'php06b1',
				chap: numChap});

	FP.addPage({titre: 'Les liens',
				resume: 'Utiliser les urls des liens pour passer des informations. Cryptage et signature.',
				rep: dossier,
				fic: 'php06b5',	// numérotation !!
				chap: numChap});

	FP.addPage({titre: 'Redirection',
				resume: 'Rediriger le navigateur vers une autre page. Envoyer des en-têtes HTTP.',
				rep: dossier,
				fic: 'php06b2',
				chap: numChap,
				exos: [['rediriger', 'Rediriger une page']]});

	FP.addPage({titre: 'Bufferiser les sorties',
				resume: 'Mettre les sorties vers le navigateur en attente.',
				rep: dossier,
				fic: 'php06b3',
				chap: numChap});

	FP.addPage({titre: 'Cookies',
				resume: 'Gérer les cookies sur le serveur.',
				rep: dossier,
				fic: 'php06b4',
				chap: numChap});

	FP.addPage({titre: 'Les sessions',
				resume: 'Qu\'est ce qu\'une session. Commencer une session.',
				rep: dossier,
				fic: 'php06c1',
				chap: numChap});
	FP.addPage({titre: 'Variables de session',
				resume: 'Persistance de données d\'un script à un autre.',
				rep: dossier,
				fic: 'php06c2',
				chap: numChap});
	FP.addPage({titre: 'Arrêter une session',
				resume: 'Mettre fin  une session complétement.',
				rep: dossier,
				fic: 'php06c3',
				chap: numChap});

	//=======================================================
	numChap ++;
	dossier = 'php07';

	FP.TChap[numChap] = new FP.Chapitre('MySQL',numChap);
	FP.addPage({titre: 'Présentation de MySQL',
				resume: 'Serveur de base de données SQL. Architecture 3 tiers',
				rep: dossier,
				fic: 'php07a1',
				chap: numChap});
	FP.addPage({titre: 'Connexion',
				resume: 'Etablir une connexion et ouvrir une base de données MySQL.',
				rep: dossier,
				fic: 'php07b1',
				chap: numChap});
	FP.addPage({titre: 'Erreurs de connexion',
				resume: 'Traitement des erreurs de connexion.',
				rep: dossier,
				fic: 'php07b2',
				chap: numChap});
	FP.addPage({titre: 'Bibliothèques de fonctions',
				resume: 'Fonctions de connexion et de gestion d\'erreurs. Paramètres d\'application.',
				rep: dossier,
				fic: 'php07b3',
				chap: numChap});
	FP.addPage({titre: 'Envoi de requêtes',
				resume: 'Envoyer une requête SQL à la base de données.',
				rep: dossier,
				fic: 'php07b4',
				chap: numChap});
	FP.addPage({titre: 'Erreurs dans une requête',
				resume: 'Traitement des erreurs dans les requêtes SQL.',
				rep: dossier,
				fic: 'php07b5',
				chap: numChap});
	FP.addPage({titre: 'Traiter une sélection simple',
				resume: 'Traitement du résulat d\'une requête de sélection renvoyant 1 seul élément.',
				rep: dossier,
				fic: 'php07b6',
				chap: numChap});
	FP.addPage({titre: 'Protéger les sorties',
				resume: 'Protection des éléments sélectionnés et affichés avec HTML.',
				rep: dossier,
				fic: 'php07b7',
				chap: numChap});
	FP.addPage({titre: 'Traiter une sélection multiple',
				resume: 'Traitement du résulat d\'une requête de sélection renvoyant plusieurs éléments.',
				rep: dossier,
				fic: 'php07b8',
				chap: numChap});
	FP.addPage({titre: 'Présentation d\'une sélection',
				resume: 'Utiliser une table HTML pour mettre en forme des résultats d\'une sélection multiple.',
				rep: dossier,
				fic: 'php07b9',
				chap: numChap});
				
	FP.addPage({titre: 'Libération des ressources',
				resume: 'Libération des objets connexion et résultats.',
				rep: dossier,
				fic: 'php07b10',
				chap: numChap});
						
	FP.addPage({titre: 'Exercices sur les sélections',
				resume: 'Divers exercices de sélection et de mise en forme des résultats.',
				rep: dossier,
				/*type: FP.PAGE_EXO,*/
				fic: 'php07b11',
				chap: numChap,
				exos: [
						['liste_editeurs', 'Liste des éditeurs'],
						['liste_livres', 'Liste des livres simple'],
						['liste_livres_cat', 'Liste des livres par catégorie'],
						['liste_livres_aut', 'Liste des livres et des auteurs']
						]});

	FP.addPage({titre: 'Pagination',
				resume: 'Affichage des résultats d\'une sélection multiple en plusieurs pages.',
				rep: dossier,
				fic: 'php07c1',
				chap: numChap,
				exos: [['pagination', 'Pagination liste des livres']]});

	FP.addPage({titre: 'Mise à jour - Principes',
				resume: 'Principes des traitements de mises à jour de données : création, modification, suppression.',
				rep: dossier,
				fic: 'php07d1',
				chap: numChap});
	FP.addPage({titre: 'Mise à jour - Recherche',
				resume: 'Saisie de critère de recherches d\'enregistrements.',
				rep: dossier,
				fic: 'php07d2',
				chap: numChap});
	FP.addPage({titre: 'Mise à jour - Liste',
				resume: 'Liste des enregistrements recherchés et choix pour mise à jour. Cryptage de lien.',
				rep: dossier,
				fic: 'php07d3',
				chap: numChap});
	FP.addPage({titre: 'Protéger les entrées',
				resume: 'Protection des chaînes de caractères utilisées dans des requêtes.',
				rep: dossier,
				fic: 'php07d4',
				chap: numChap});
	FP.addPage({titre: 'Saisie et mise à jour des données',
				resume: 'Saisie, affichage, modification des données d\'un enregistrement. Traitement final dans la base de données : insert, update ou delete.',
				rep: dossier,
				fic: 'php07d5',
				chap: numChap});


	//=======================================================
	numChap ++;
	dossier = 'php09';

	FP.TChap[numChap] = new FP.Chapitre('Fichiers et dossiers',numChap);
	FP.addPage({titre: 'Trouver un fichier',
				resume: 'Savoir si le fichier existe et s\'il n\'est pas un répertoire. Gérer les chemins d\'accès.',
				rep: dossier,
				fic: 'php09a1',
				chap: numChap});
	FP.addPage({titre: 'Informations sur un fichier',
				resume: 'Obtenir la taille, les permissions d\'accès et les dates de mise à jour d\'un fichier.',
				rep: dossier,
				fic: 'php09a2',
				chap: numChap});


	FP.addPage({titre: 'Lire un fichier',
				resume: 'Différentes façons de lire un fichier et d\'obtenir son contenu.',
				rep: dossier,
				fic: 'php09b1',
				chap: numChap});
	FP.addPage({titre: 'Lire un fichier sur Internet',
				resume: 'Lire un fichier qui se trouve sur un autre serveur.',
				rep: dossier,
				fic: 'php09b2',
				chap: numChap});

	FP.addPage({titre: 'Ecrire dans un fichier',
				resume: 'Ecrire du contenu dans un fichier.',
				rep: dossier,
				fic: 'php09b3',
				chap: numChap});


	FP.addPage({titre: 'Gestion des fichiers',
				resume: 'Copier, déplacer, renommer, supprimer un fichier.',
				rep: dossier,
				fic: 'php09c1',
				chap: numChap});

	FP.addPage({titre: 'Contenu d\'un dossier',
				resume: 'Déterminer le contenu d\'un dossier.',
				rep: dossier,
				fic: 'php09d1',
				chap: numChap});
	FP.addPage({titre: 'Lecture récursive',
				resume: 'Traverser tous les niveaux d\'une arborescence. Mise en forme des résultats.',
				rep: dossier,
				fic: 'php09d2',
				chap: numChap});
	FP.addPage({titre: 'Taille d\'un dossier',
				resume: 'Fonction récursive pour définir la taille d\'un dossier.',
				rep: dossier,
				fic: 'php09d3',
				chap: numChap});


	FP.addPage({titre: 'Gestion des dossiers',
				resume: 'Créer, copier, supprimer, déplacer des dossiers.',
				rep: dossier,
				fic: 'php09e1',
				chap: numChap});



	//=======================================================
	numChap ++;
	dossier = 'php10';

	FP.TChap[numChap] = new FP.Chapitre('Objets',numChap);
		FP.addPage({titre: 'Syntaxe de base',
				resume: 'Classes, attributs, méthodes, constantes, instanciation. Les opérateurs new et -&gt;',
				rep: dossier,
				fic: 'php10a1',
				chap: numChap,
				exos: [
						['exoClasseLivre', 'Définir et utiliser une classe']
					]});
	FP.addPage({titre: 'Encapsulation, accesseurs et mutateurs',
				resume: 'Définir la visibilité des attributs et des méthodes.',
				rep: dossier,
				fic: 'php10a2',
				chap: numChap,
				exos: [
						['exoGetSet', 'Getter et setter']
					]});
	FP.addPage({titre: 'Constructeur et destructeur',
				resume: 'Initialisation automatique d\'un objet et nettoyage après utilisation.',
				rep: dossier,
				fic: 'php10a3',
				chap: numChap,
				exos: [
						['exoConstructeur1', 'Constructeur explicite'],
						['exoConstructeur2', 'Tableau de paramètres'],
						['exoDestructeur', 'Destructeur']
					]});
	FP.addPage({titre: 'Constantes et opérateur ::',
				resume: 'Définir des constantes et les utiliser avec l\'opérateur de résolution de portée ::',
				rep: dossier,
				fic: 'php10a4',
				chap: numChap,
				exos: [
						['exoConstantesClasse', 'Constantes de classe']
					]});

	FP.addPage({titre: 'Héritage - principes',
				resume: 'Classe mére et classe fille. Partage des attributs et méthodes. Extension, spécialisation',
				rep: dossier,
				fic: 'php10b1',
				chap: numChap});
	FP.addPage({titre: 'Héritage - surcharge',
				resume: 'Redéfinition et surcharge de méthodes.',
				rep: dossier,
				fic: 'php10b2',
				chap: numChap,
				exos: [
						['exoGuitare2Manches', 'Etendre une classe']
					]});

	FP.addPage({titre: 'Les exceptions',
				resume: 'Gestion des erreurs. Lever et capturer une exception. Exceptions spécialisées.',
				rep: dossier,
				fic: 'php10c1',
				chap: numChap,
				exos: [
						['exoException', 'Exceptions']
					]});

	//=======================================================
	numChap ++;
	dossier = 'php04';

	FP.TChap[numChap] = new FP.Chapitre('Expressions régulières',numChap);
		FP.addPage({titre: 'Présentation',
				resume: 'Qu\'est ce que sont les expressions régulières.',
				rep: dossier,
				fic: 'php04a1',
				chap: numChap});

	FP.addPage({titre: 'Règles de base',
				resume: 'Construire une expression régulière simple : caractères ordinaires, opérateur ou, énumération, listes, classes de caractères.',
				rep: dossier,
				fic: 'php04a2',
				chap: numChap});

	FP.addPage({titre: 'Répétitions fixées',
				resume: 'Définir des répétitions fixées de caractères, classes ou modèles.',
				rep: dossier,
				fic: 'php04b1',
				chap: numChap,
				exos: [['cartebancaire', 'Vérifier le format d\'un numéro de carte bancaire']]});

	FP.addPage({titre: 'Répétitions délimitées',
				resume: 'Définir des répétitions de caractères dans des limites inférieures et supérieures.',
				rep: dossier,
				fic: 'php04b2',
				chap: numChap});

	FP.addPage({titre: 'Sous-chaînes',
				resume: 'Définir des sous-chaînes dans une expression régulière.',
				rep: dossier,
				fic: 'php04b3',
				chap: numChap,
				exos: [['verif_mail', 'Vérifier le format d\'une adresse e-mail']]});

	FP.addPage({titre: 'Quantificateurs non gourmands',
				resume: 'Limiter la portée d\'une sélection trop large.',
				rep: dossier,
				fic: 'php04b4',
				chap: numChap});

	FP.addPage({titre: 'Assertions simples',
				resume: 'Rechercher une correspondance de position.',
				rep: dossier,
				fic: 'php04b5',
				chap: numChap});

	FP.addPage({titre: 'Options',
				resume: 'Les options permettent de modifier le comportement d\'une expression régulière.',
				rep: dossier,
				fic: 'php04b6',
				chap: numChap});

	FP.addPage({titre: 'Recherche et extraction avec PHP',
				resume: 'Les fonctions PHP qui permettent de rechercher et d\'extraire des correspondances.',
				rep: dossier,
				fic: 'php04c1',
				chap: numChap,
				exos: [['extraire_liens', 'Extraire les liens d\'une page HTML']]});

	FP.addPage({titre: 'Captures non gourmandes. Références arrières.',
				resume: 'Capturer une partie d\'expression et la réutiliser dans la suite.',
				rep: dossier,
				fic: 'php04c2',
				chap: numChap});

	FP.addPage({titre: 'Remplacement de modèle',
				resume: 'Les fonctions PHP qui permettent de remplacer des correspondances.',
				rep: dossier,
				fic: 'php04c3',
				chap: numChap});

	FP.addPage({titre: 'Remplacement de références arrières',
				resume: 'Les références arrières peuvent être utilisées dans le remplacement. Exemple d\'effet stabilo.',
				rep: dossier,
				fic: 'php04c4',
				chap: numChap});

	FP.addPage({titre: 'Remplacement par fonctions',
				resume: 'Nous pouvons écrire des fonctions pour remplacer les correspondances.',
				rep: dossier,
				fic: 'php04c5',
				chap: numChap});

	FP.addPage({titre: 'Découper une chaîne',
				resume: 'Découper une chaîne en sous-partie avec les expressions régulières.',
				rep: dossier,
				fic: 'php04c6',
				chap: numChap});
	//=======================================================
	/*
	numChap ++;
	dossier = 'php12';

	FP.TChap[numChap] = new FP.Chapitre('Graphisme',numChap);
	FP.addPage({titre: 'Fonctionnalités graphiques : principes',
				resume: 'PHP propose des librairies de fonctions permettant de ' +
						'dessiner des images et de les envoyer au navigateur ou de les ' +
						'enregistrer sur le serveur.',
				rep: dossier,
				fic: 'php12a',
				type: FP.PAGE_TUTO,
				chap: numChap});
	FP.addPage({titre: 'Représentations statistiques',
				resume: 'Création de représentations graphiques de données. Exemples ' +
						'de graphiques en barres, en courbes et en camemberts.',
				rep: dossier,
				fic: 'php12b',
				type: FP.PAGE_TUTO,
				chap: numChap});
	FP.addPage({titre: 'Manipuler des images',
				resume: 'Avec les fonctions graphiques nous pouvons travailler sur ' +
						'des images déjà existantes.',
				rep: dossier,
				fic: 'php12c',
				type: FP.PAGE_TUTO,
				chap: numChap});
	*/



	// Initialisation des vidéos
	/*
	i = 0;

	FP.Video.TMovie[i++] = 'PHP Introduction';
	FP.Video.TMovie[i++] = ['Introduction',  '2:51',  'MEDIA111114110557442'];
	FP.Video.TMovie[i++] = ['Environnement',  '2:16',  'MEDIA111114110457785'];
	FP.Video.TMovie[i++] = ['Client-Serveur 1',  '3:14',  'MEDIA111114110530573'];
	FP.Video.TMovie[i++] = ['Client-Serveur 2',  '1:28',  'MEDIA111114110617219'];
	FP.Video.TMovie[i++] = ['Histoire',  '2:29',  'MEDIA111114110635840'];
	FP.Video.TMovie[i++] = ['Langage',  '1:50',  'MEDIA111114110651758'];

	FP.Video.TMovie[i++] = 'PHP Types et variables';
	FP.Video.TMovie[i++] = ['Types de données',  '2:56',  'MEDIA111114150641716'];
	FP.Video.TMovie[i++] = ['Nommer une variable',  '1:11',  'MEDIA111114150658655'];
	FP.Video.TMovie[i++] = ['Initialiser une variable',  '2:44',  'MEDIA111114150716984'];
	FP.Video.TMovie[i++] = ['Transtypage',  '2:27',  'MEDIA111114150737176'];
	FP.Video.TMovie[i++] = ['Portée',  '6:21',  'MEDIA111114150806408'];
	FP.Video.TMovie[i++] = ['Super-globales',  '3:07',  'MEDIA111114150823391'];
	FP.Video.TMovie[i++] = ['Constantes',  '3:53',  'MEDIA111114150848831'];

	FP.Video.TMovie[i++] = 'PHP Opérateurs';
	FP.Video.TMovie[i++] = ['Assignement',  '3:15',  'MEDIA111114155353753'];
	FP.Video.TMovie[i++] = ['Mathématiques',  '1:26',  'MEDIA111114155411352'];
	FP.Video.TMovie[i++] = ['Comparaison',  '5:11',  'MEDIA111114155430832'];
	FP.Video.TMovie[i++] = ['Logiques',  '1:12',  'MEDIA111114155521714'];
	FP.Video.TMovie[i++] = ['Concaténation',  '1:16',  'MEDIA111114155534507'];
	FP.Video.TMovie[i++] = ['Conditionnel',  '1:29',  'MEDIA111114155551849'];
	FP.Video.TMovie[i++] = ['Crochets et parenthèses',  '1:09',  'MEDIA111114155605554'];

	FP.Video.TMovie[i++] = 'PHP Instructions';
	FP.Video.TMovie[i++] = ['Commentaires',  '3:14',  'MEDIA111115150432977'];
	FP.Video.TMovie[i++] = ['echo - print',  '1:15',  'MEDIA111115150456548'];
	FP.Video.TMovie[i++] = ['if',  '2:42',  'MEDIA111115150911257'];
	FP.Video.TMovie[i++] = ['switch',  '1:50',  'MEDIA111115150927306'];
	FP.Video.TMovie[i++] = ['for - foreach',  '7:02',  'MEDIA111115150949309'];
	FP.Video.TMovie[i++] = ['while',  '2:00',  'MEDIA111115151006970'];
	FP.Video.TMovie[i++] = ['continue - break',  '2:37',  'MEDIA111115151037423'];
	FP.Video.TMovie[i++] = ['exit - return',  '1:34',  'MEDIA111115151055480'];

	FP.Video.TMovie[i++] = 'PHP Fonctions';
	FP.Video.TMovie[i++] = ['Base de PHP',  '2:46',  'MEDIA111116160216119'];
	FP.Video.TMovie[i++] = ['Défintion - invocation',  '1:19',  'MEDIA111116160252614'];
	FP.Video.TMovie[i++] = ['Paramètres',  '3:33',  'MEDIA111116160433239'];
	FP.Video.TMovie[i++] = ['return',  '5:27',  'MEDIA111116160659699'];
	FP.Video.TMovie[i++] = ['Fonctions variables',  '2:01',  'MEDIA111116160912224'];
	FP.Video.TMovie[i++] = ['Bibliothèques',  '2:35',  'MEDIA111116161010805'];
	FP.Video.TMovie[i++] = ['Décrire une fonction',  '3:14',  'MEDIA111116161157283'];

	FP.Video.TMovie[i++] = 'PHP les chaînes';
	FP.Video.TMovie[i++] = ['Chaînes de caractères ?',  '3:15',  'MEDIA111117113515350'];
	FP.Video.TMovie[i++] = ['Guillemets',  '2:28',  'MEDIA111117113650103'];
	FP.Video.TMovie[i++] = ['Heredoc',  '1:15',  'MEDIA111117113730250'];
	FP.Video.TMovie[i++] = ['Concaténation',  '1:16',  'MEDIA111117113818863'];
	FP.Video.TMovie[i++] = ['Afficher une chaîne',  '1:02',  'MEDIA111117113857501'];
	FP.Video.TMovie[i++] = ['Bibliothèque',  '1:46',  'MEDIA111117113948751'];
	FP.Video.TMovie[i++] = ['Fonctions incontournables',  '4:29',  'MEDIA111117114118582'];
	FP.Video.TMovie[i++] = ['Codage serveur - client',  '2:38',  'MEDIA111117114217480'];
	FP.Video.TMovie[i++] = ['Codage client - BD',  '2:06',  'MEDIA111117114305721'];

	FP.Video.TMovie[i++] = 'PHP les tableaux';
	FP.Video.TMovie[i++] = ['Présentation',  '3:00',  'MEDIA111120143036657'];
	FP.Video.TMovie[i++] = ['Index numérique',  '4:59',  'MEDIA111120143714659'];
	FP.Video.TMovie[i++] = ['Index associatif',  '1:53',  'MEDIA111120143805419'];
	FP.Video.TMovie[i++] = ['Lecture des tableaux',  '1:42',  'MEDIA111120143845329'];
	FP.Video.TMovie[i++] = ['for',  '2:49',  'MEDIA111120143958955'];
	FP.Video.TMovie[i++] = ['foreach',  '3:25',  'MEDIA111120144112265'];
	FP.Video.TMovie[i++] = ['Quelques fonctions',  '5:47',  'MEDIA111120144341298'];

	FP.Video.TMovie[i++] = 'PHP MySQL (A)';
	FP.Video.TMovie[i++] = ['Architecture 3 tiers',  '2:52',  'MEDIA111120201452624'];
	FP.Video.TMovie[i++] = ['Serveur Mysql / root',  '1:35',  'MEDIA111120201555557'];
	FP.Video.TMovie[i++] = ['Utilisateurs et BD du site',  '3:05',  'MEDIA111120201727707'];

	FP.Video.TMovie[i++] = 'PHP MySQL (B)';
	FP.Video.TMovie[i++] = ['Select',  '3:04',  'MEDIA111125162454692'];
	FP.Video.TMovie[i++] = ['Select mise en forme',  '3:16',  'MEDIA111125162516645'];
	FP.Video.TMovie[i++] = ['Gestion des requêtes',  '1:41',  'MEDIA111125162531261'];
	FP.Video.TMovie[i++] = ['Connexion',  '1:40',  'MEDIA111125162543168'];
	FP.Video.TMovie[i++] = ['Envoyer une requête',  '3:16',  'MEDIA111125162559988'];
	FP.Video.TMovie[i++] = ['Résultat d\'une requête',  '1:24',  'MEDIA111125162611763'];
	FP.Video.TMovie[i++] = ['mysql_fetch_row',  '1:14',  'MEDIA111125162625302'];
	FP.Video.TMovie[i++] = ['mysql_fetch_assoc',  '1:35',  'MEDIA111125162638261'];
	FP.Video.TMovie[i++] = ['mysql_fetch_object',  '0:58',  'MEDIA111125162652626'];
	FP.Video.TMovie[i++] = ['Boucle de traitement',  '2:18',  'MEDIA111125162708760'];
	FP.Video.TMovie[i++] = ['Déconnexion',  '1:39',  'MEDIA111125162721855'];

	FP.Video.TMovie[i++] = 'PHP MySQL (C)';
	FP.Video.TMovie[i++] = ['Gestion des erreurs - a',  '4:19',  'MEDIA111129112721591'];
	FP.Video.TMovie[i++] = ['Gestion des erreurs - b',  '3:10',  'MEDIA111129112748131'];
	FP.Video.TMovie[i++] = ['Protéger les chaînes',  '3:43',  'MEDIA111129112817647'];
	FP.Video.TMovie[i++] = ['Injection SQL',  '2:28',  'MEDIA111129112841898'];

	FP.Video.TMovie[i++] = 'PHP + WEB';
	FP.Video.TMovie[i++] = ['HTTP - GET - POST',  '4:33',  'MEDIA111129163512979'];
	FP.Video.TMovie[i++] = ['Request GET',  '4:52',  'MEDIA111129163557517'];
	FP.Video.TMovie[i++] = ['Piratage Url',  '2:30',  'MEDIA111129171300919'];
	FP.Video.TMovie[i++] = ['Request POST',  '5:26',  'MEDIA111202090302137'];
	FP.Video.TMovie[i++] = ['Vérifier les données - 1',  '3:42',  'MEDIA111202090319489'];
	FP.Video.TMovie[i++] = ['Vérifier les données - 2',  '3:56',  'MEDIA111202090350933'];
	FP.Video.TMovie[i++] = ['Vérifier les données - 3',  '4:41',  'MEDIA111202090410940'];
	FP.Video.TMovie[i++] = ['Upload de fichier',  '3:18',  'MEDIA111202090445579'];
	FP.Video.TMovie[i++] = ['Vérif/dépôt de fichier',  '3:51',  'MEDIA111202090507860'];

	FP.Video.TMovie[i++] = 'PHP  WEB +';
	FP.Video.TMovie[i++] = ['Redirections',  '2:35',  'MEDIA111204132358430'];
	FP.Video.TMovie[i++] = ['Caching',  '1:09',  'MEDIA111204132436782'];
	FP.Video.TMovie[i++] = ['Bufferisation',  '3:34',  'MEDIA111204132544138'];
	FP.Video.TMovie[i++] = ['Exemples 1',  '4:23',  'MEDIA111204132716461'];
	FP.Video.TMovie[i++] = ['Exemples 2',  '3:40',  'MEDIA111204132836645'];
	FP.Video.TMovie[i++] = ['Cookies',  '3:36',  'MEDIA111204132948756'];

	FP.Video.TMovie[i++] = 'PHP  Sessions';
	FP.Video.TMovie[i++] = ['2 problèmes',  '2:35',  'MEDIA111204133148246'];
	FP.Video.TMovie[i++] = ['No session - Stockage - $_SESSION',  '3:30',  'MEDIA111204133256842'];
	FP.Video.TMovie[i++] = ['Persistance et partage',  '2:29',  'MEDIA111204133410425'];
	*/

})();