//___________________________________________________________________
// 	
// Singleton FP
//___________________________________________________________________
var FP = {
	//Dossier: {},		// Gestion des dossiers 
	DossierDD: {},		// dossiers avec drag & drop
	
	nomOk_ER: /^[a-z0-9_]{1,30}$/,	
		
	//-----------------------------------------------------
	// Soumission formulaire initialisation du dossier de travail
	formUserInit: function(repNom, btn) {
		if (! repNom.value.match(FP.nomOk_ER)) {
			REDIPS.dialog.show(300, 80, 
					'Le nom n\'est pas valide. ' +
					'Les caractéres acceptés sont les lettres en minuscule et non accentuées.');
			return;
		}	
			
		btn.enabled = false;
		document.getElementById('bcMsg').innerHTML = 'Traitement en cours ...';
		btn.form.submit();
	},
		
	//-----------------------------------------------------
	getEvent: function(Ev) {
		if (Ev.preventDefault) {
			Ev.src = Ev.target;
			Ev.stopPropagation();
			Ev.preventDefault();
			Ev.offsetX = Ev.layerX;
			Ev.offsetY = Ev.layerY;			
		} else {
			Ev.cancelBubble = true;
			Ev.returnValue = false;
			Ev.src = Ev.srcElement;
		}
		return Ev;
	},
	
	//-----------------------------------------------------
	windowModal: function(montre) {
		var B = document.getElementById('FP-xhr-modal');
		
		if (B === null) {
			B = document.createElement('div');
			B.id = 'FP-xhr-modal';
			B.innerHTML = '<div id="FP-xhr-loader"></div>';
			document.getElementsByTagName('BODY')[0].appendChild(B);
		}
		
		B.style.display = (montre) ? 'block' : 'none';
	}	
};	// Fin objet FP 

//___________________________________________________________________
// 		
// Objet pour la gestion des dossiers du file system
//___________________________________________________________________
FP.Folder = {
	nomOk_ER: FP.nomOk_ER,	//	/^[a-z0-9_]{1,30}$/,	
	
	//-----------------------------------------------------
	click: function(event, idLI) {
		var E = FP.getEvent(event);
		
		switch (E.src.className) {
		case 'FS-folder-name':
			E.src.nextSibling.className = 'FS-folder-opened';
			E.src.className = 'FS-folder-name FS-folder-name-opened';
			break;
		case 'FS-folder-name FS-folder-name-opened':
			E.src.nextSibling.className = 'FS-folder-closed';
			E.src.className = 'FS-folder-name';
			break;
				
		case 'FS-btn-upl':
			this.uplDialog(idLI, E.src);
			break;
		case 'FS-btn-add':
			this.add(idLI, E.src);
			break;
		case 'FS-btn-del':
			REDIPS.dialog.show(300, 80, 
							"Confirmez-vous la suppression du dossier " + 
							document.getElementById(idLI).getElementsByTagName('LABEL')[0].textContent +
							"\net de tout son contenu ?",
							'Non', 'Oui|FP.Folder.delSend|R' + idLI);			
			break;
		case 'FS-btn-file-del':
			REDIPS.dialog.show(300, 80, 
							"Confirmez-vous la suppression du fichier " + 
							document.getElementById(idLI).getElementsByTagName('A')[0].textContent + ' ?',
							'Non', 'Oui|FP.Folder.delSend|F' + idLI);	
			break;
		}
	},

	//-----------------------------------------------------
	// Suppression d'un dossier ou d'un fichier dans une arbo		
	delSend: function(param) {
		var idLI = param.substr(1),
			traite = (param.substr(0, 1) === 'R') ? 'folderDel' : 'fileDel';
			
		FP.windowModal(true);
		FP.XHR.send( {t: traite, chemin: document.getElementById(idLI).dataset.href}, 
					FP.Folder.delBack, 
					idLI);
	},
	//-----------------------------------------------------
	// Retour suppression dossier
	delBack: function(reponseXHR, idLI) {
		var LI;
		
		FP.windowModal(false);
		if (reponseXHR != '') {
			REDIPS.dialog.show(200, 50, reponseXHR);
		} else {
			LI = document.getElementById(idLI);
			LI.parentNode.removeChild(LI);
		}
	},
	
	//-----------------------------------------------------
	// Affichage ou suppression bloc de saisie pour le nom
	// d'un dossier à ajouter dans une arbo
	add: function(idLI, A) {
		var B, H, 
			idInput = 'folderAdd_' + idLI;
		
		B = document.getElementById(idInput);
		if (B !== null) {
			A.innerHTML = '';
			return;
		}
		
		H = '<div class="FS-add-dialog">' +
				'<input type="text" class="FS-add-folder-name" id="folderAdd_' + idLI + '">' +
				'<input type="submit" class="FS-btn-ok" onclick="FP.Folder.addOk(\'' + idLI + '\')" value="">' +
				'<span class="FS-btn-cancel" onclick="FP.Folder.addCancel(\'' + idLI + '\')"></span>' +
			'</div>';
		
		A.innerHTML = H;
		
		document.getElementById('folderAdd_' + idLI).focus();
	},
	
	addCancel: function(idLI) {
		document.getElementById('folderAdd_' + idLI).parentNode.parentNode.innerHTML = '';
	},
	
	//-----------------------------------------------------
	// Validation nom dossier et ajout dans une arbo
	addOk: function(idLI) {
		var B, nomRep,
			LI = document.getElementById(idLI),
			idInput = 'folderAdd_' + idLI;
		
		B = document.getElementById(idInput);
		nomRep = B.value;
						
		if (! nomRep.match(this.nomOk_ER)) {			
			REDIPS.dialog.show(300, 80, 
						'Le nom du dossier n\'est pas valide. ' +
						'Les caractéres acceptés sont les lettres en minuscule et ' +
						'non accentuées, les chiffres et le caractére _');
			return;
		}
		
		B.parentNode.parentNode.innerHTML = '';
		FP.windowModal(true);
		LI.dataset.newrep = nomRep;
		FP.XHR.send( {t: 'folderAdd', chemin: LI.dataset.href, newRep: nomRep}, 
					FP.Folder.addBack, 
					idLI);			
	},
	//-----------------------------------------------------
	// Retour ajout dossier
	addBack: function(reponseXHR, idLI) {	
		FP.windowModal(false);
		if (reponseXHR != '') {
			REDIPS.dialog.show(200, 50, reponseXHR);
			return;
		}
		
		FP.Folder.grow(idLI, '');
	},
	//-----------------------------------------------------	
	// Ajout d'un élément dans l'arborescence.
	// Si nomFichier est '', on ajoute un répertoire
	grow: function(idLI, nomFichier) {
		var LI, H, B, NewLI, idNewLI, idLabel, className, href,
			isFolder = (nomFichier == '');
				
		LI = document.getElementById(idLI);
		
		B = document.getElementById('Nb');
		B.value = Number(B.value) + 1;
		
		if (! isFolder) {
			idNewLI = 'LF_' + B.value;
			idLabel = 'LA_' + B.value;
			className = 'FS-li-file';
			href = LI.dataset.href + nomFichier;
			H = '<a class="FS-file-name" id="' + idLabel + '" href="' + href +'" target="piatphp">' + nomFichier +
					'<span class="FS-btn-file-del" onclick="FP.Folder.click(event, \'' + idNewLI + '\')"></span>' +
				'</a>';
		} else {				
			idNewLI = 'LR_' + B.value;
			idLabel = 'LL_' + B.value;
			className = 'FS-li-folder';
			href = LI.dataset.href + LI.dataset.newrep + '/';				
			H = '<label class="FS-folder-name" id="' + idLabel + '" ' +
					'onclick="FP.Folder.click(event, \'' + idNewLI + '\')">' + LI.dataset.newrep +
					'<span onclick="FP.Folder.click(event, \'' + idNewLI + '\')">' + 
						'<a class="FS-btn-upl"></a>' +
						'<a class="FS-btn-add"></a>' + 
						'<a class="FS-btn-del"></a>' +
					'</span>' +
				'</label>' +
				'<ol class="FS-folder-closed"></ol>';				
		}
					
		NewLI = document.createElement('LI');
		NewLI.id = idNewLI;
		NewLI.className = className;
		NewLI.dataset.href = href;
		NewLI.innerHTML = H;
		
		LI.getElementsByTagName('OL')[0].appendChild(NewLI);

		if (isFolder) {
			NewLI.dataset.newrep = '';
			FP.DossierDD[idLabel] = new FP.Upl(idLabel);	// gestion upload - d & d
		}		
	},
	//-----------------------------------------------------
	// Sélection d'un fichier à uploader
	uplDialog: function(idLI, A) {
		var B, H, 
			idInput = 'folderUpl_' + idLI;
		
		/*
		B = document.getElementById(idInput);
		if (B !== null) {
			A.innerHTML = '';
			return;
		}
		
		H = '<div class="FS-upl-dialog">' +
				'<input type="file" class="FS-upl-file-name" id="folderUpl_' + idLI + '">' +
				'<input type="submit" class="FS-btn-ok" onclick="FP.Folder.uplOk(\'' + idLI + '\')" value="">' +
				'<span class="FS-btn-cancel" onclick="FP.Folder.uplCancel(\'' + idLI + '\')"></span>' +
			'</div>';
		
		A.innerHTML = H;
		
		document.getElementById('folderUpl_' + idLI).focus();
		*/
		
		H = 'Sélectionnez un fichier à uploader<br>' +
			'<input type="file" id="folderUpl" onchange="FP.Folder.uplOk(\'' + idLI + '\')">';
			
		REDIPS.dialog.html(H);		
		REDIPS.dialog.show(300, 80, 'html');
	},
	
	uplOk: function(idLI) {
		var Nom = 'LL_' + idLI.substring(3);
		REDIPS.dialog.hide();
		FP.DossierDD[Nom].upload(document.getElementById('folderUpl').files);
	}	
};

//___________________________________________________________________
//
//  Upl : gestion d'upload de fichiers par XHR - Drag & Drop
//___________________________________________________________________
//

// L'objet FP.Upl est à instancier avec new. 
// 1 objet par zone acceptant le drop de fichiers.
			
FP.Upl = function(nom) {
	var dropZone, Moi;

	Moi = this;		// Pour closure dans les gestionnaires d'évenement
	
	this.nomOk_ER = FP.nomOk_ER;	//	/^[a-z0-9_]{1,30}$/,	
	this.MAX_UPL = 100 * 1024;		// max upload size : 100ko
	this.ext_ER = /.\.php$|.\.htm$|.\.css$|.\.gif$|.\.jpg$|.\.png$|.\.js$|.\.html$/;
	this.nbEnCours = 0;
	
	this.LIDossier = document.getElementById('LR_' + nom.substring(3));
	this.nom = nom;
	
	dropZone = document.getElementById(nom);
			
	// Gestionniaires d'événement drag & drop sur la drop zone	
	dropZone.addEventListener(
		'dragleave', 
		function(Evt) {
			//(Evt.target) && (Evt.target === dropZone) && (this.classList.remove('UPL-drag-over'));
			this.classList.remove('UPL-drag-over');
			Evt.preventDefault();
			Evt.stopPropagation();
		}, 
		false);
	
	dropZone.addEventListener(
		'dragenter', 
		function(Evt) {
			this.classList.add('UPL-drag-over');
			Evt.preventDefault();
			Evt.stopPropagation();
		}, 
		false);

	dropZone.addEventListener(
		'dragover', 
		function(Evt) {
			Evt.preventDefault();
			Evt.stopPropagation();
		}, 
		false);

	dropZone.addEventListener(
		'drop', 
		function(Evt) {
			Moi.upload(Evt.dataTransfer.files);
			this.classList.remove('UPL-drag-over');
			Evt.preventDefault();
			Evt.stopPropagation();
		}, 
		false);
};

//___________________________________________________________________
// 	
// Protoype de l'objet Upl
//___________________________________________________________________
FP.Upl.prototype = {

	//-----------------------------------------------------
	// Méthode pour vérifier les fichiers droppés
	upload: function(Fichiers) {
		var i, Fichier, nomFichier;
	
		if (typeof Fichiers === 'undefined') {
			alert('Pas de support API file pour ce navigateur');
			return;		
		}
		
		if (Fichiers.length == 0) {
			return;
		}
		
		for (i = 0; i < Fichiers.length; i++) {
			Fichier = Fichiers[i];		
			nomFichier = Fichier.name || Fichier.fileName;
				
			if (Fichier.size > this.MAX_UPL) {
				REDIPS.dialog.show(300, 80, 
						'Le fichier ' + nomFichier + ' est trop gros. ' +
						'(Max : ' + this.MAX_UPL + ' octets)');			
				return;
			} 
		
			if (! nomFichier.match(this.ext_ER)) {
				REDIPS.dialog.show(300, 80, 
						'Le fichier ' + nomFichier + ' n\'est pas téléchargeable. ' +
						'(Type permis : htm, html, css, gif, jpg, png, js et php)');	
				return;
			}
			
			nomFichier = nomFichier.substr(0, nomFichier.lastIndexOf('.'));
			if (! nomFichier.match(this.nomOk_ER)) {
				REDIPS.dialog.show(300, 80, 
						'Le nom du fichier ' + nomFichier + ' n\'est pas valide. ' +
						'Les caractéres acceptés sont les lettres en minuscule et ' +
						'non accentuées, les chiffres et le caractére _');
				return;
			}
		}
		
		FP.windowModal(true)
		
		this.nbEnCours = 0;
		
		for (i = 0; i < Fichiers.length; i++) {
			this.send(Fichiers[i]);
		}
	},
			
	//-----------------------------------------------------
	// Méthode pour le transfert par XHR
	send: function(Fichier) {
		var Moi, FData, XHR, 
			nomFichier = Fichier.name || Fichier.fileName;
													
		// Upload
		XHR = new XMLHttpRequest();
				
		// Evénément quand le téléchargement est fini.
		// Si erreur, responseText commence par .ERREUR.
		Moi = this;
		XHR.addEventListener(
			'load', 
			function() {
				Moi.back(XHR, nomFichier);		
			}, 
			false);
		
		// Envoi du fichier au serveur
		FData = new FormData();
		FData.append('t', 'upload');	
		FData.append('chemin', this.LIDossier.dataset.href);	
		FData.append('fichier', Fichier);
					
		XHR.open('post', 'xhr.php', true);
		XHR.send(FData);
		
		this.nbEnCours ++;	
	},

	//-----------------------------------------------------
	// Méthode exécutée automatiquement aprés un upload	
	// responseText :  nom fichier
	back: function(XHR, nomFichier) {
		var nomComplet, LIs, E, i, isNew;
		
		this.nbEnCours --;
		if (this.nbEnCours < 1) {
			FP.windowModal(false);
		}
				
		if (XHR.status != 200) {
			alert("Erreur dans la connexion au serveur Web \n" + 
						XHR.status + ' : ' + XHR.statusText);
			return;
		} 
		
		if (XHR.responseText != '') {
			REDIPS.dialog.show(300, 80, XHR.responseText); 	
			return;		
		}

		// Recherche si le fichier existe déjà dans le dossier
		// <li> LIDossier
		//		<ol>
		//			<li class="FS-li-file">
		isNew = true;
		nomComplet = this.LIDossier.dataset.href + nomFichier;
		E = this.LIDossier.firstChild;
		while (E) {
			if (E.tagName == 'OL') {
				LIs = E.getElementsByTagName('LI');
				for (i = 0; i < LIs.length; i++) {
					if (LIs[i].dataset.href == nomComplet) {
						isNew = false; 
						break;	
					}
				}
				
				if (!isNew) {
					break;
				}
			}
			
			E = E.nextSibling;
		}
		
		if (isNew) {
			FP.Folder.grow(this.LIDossier.id, nomFichier);
		}
	}
};

//___________________________________________________________________
// 	
// Objet XHR 
//___________________________________________________________________
FP.XHR = {
	Ajax: null,
	isRunning: false,
	backFonction: null,
	backParam: null,
	
	//---------------------------------------------------------------------			
	send: function(oParams, backFonction, backParam) {
		var post = '',
			nom;
						
		if (this.Ajax === null) {
			this.Ajax = new XMLHttpRequest();
		}
		
		if (this.isRunning) {
			alert('Un traitement est déjà en cours.');
			return false;
		}
		
		for (nom in oParams) {
			post += '&' + nom + '=' + encodeURIComponent(oParams[nom]);
		}
		post = post.substring(1);
		
		if (post == '') {
			return;
		}
		
		this.Ajax.open('POST', 'xhr.php', true);
		this.Ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');	
		this.isRunning = true;
		this.Ajax.onload = FP.XHR.back;
		this.backFonction = (backFonction !== undefined) ? backFonction : null;
		this.backParam = (backParam !== undefined) ? backParam : null;
		
		this.Ajax.send(post);
		
	},
	//---------------------------------------------------------------------		
	back: function() {
		var XHR = FP.XHR;
		
		if (XHR.Ajax.status != 200) {
			alert("Erreur dans la connexion au serveur Web \n" + 
						XHR.Ajax.status + ' : ' + XHR.Ajax.statusText);
		} else {
			if (XHR.backFonction !== null) {
				XHR.backFonction(XHR.Ajax.responseText, XHR.backParam);
			}	
		}

		XHR.isRunning = false;
		XHR.backFonction = XHR.backParam = null;
		XHR.Ajax.onload = function() {};		
	}
};

//___________________________________________________________________
window.addEventListener(
		'load', 
		function() {
			REDIPS.dialog.init();
			REDIPS.dialog.op_high = 25;
			REDIPS.dialog.fade_speed = 18;
		}, 
		false);
//___________________________________________________________________
document.addEventListener(
		'DOMContentLoaded', 
		function() {
			for (var i = 0, LABELs = document.getElementsByClassName('FS-folder-name'); 
				i < LABELs.length; 
				FP.DossierDD[LABELs[i].id] = new FP.Upl(LABELs[i].id), i++);
		}, 
		false);
		