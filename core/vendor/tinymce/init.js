/**

 * Initialisation de TinyMCE

 */

/**
 * Quand tinyMCE est invoqué hors connexion, initialiser privateKey
 */
if (typeof (privateKey) == 'undefined') {
	var privateKey = null;
};
tinymce.init({
	// Classe où appliquer l'éditeur
	selector: ".editorWysiwyg",
	// Aperçu dans le pied de page
	setup: function (ed) {
		ed.on('change', function (e) {
			if (ed.id === 'themeFooterText') {
				$("#footerText").html(tinyMCE.get('themeFooterText').getContent());
			}
			if (ed.id === 'themeHeaderText') {
				$("#featureContent").html(tinyMCE.get('themeHeaderText').getContent());
			}

		});
	},
	// Langue
	language: getCookie('ZWII_UI') === null ? "fr_FR" : getCookie('ZWII_UI'),
	// Plugins
	plugins: "advlist anchor autolink autoresize autosave codemirror contextmenu colorpicker fullscreen hr image imagetools link lists media paste searchreplace tabfocus table template textcolor visualblocks nonbreaking emoticons charmap",
	// Contenu du menu
	menu: {
		file: { title: 'File', items: 'newdocument restoredraft' },
		edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
		view: { title: 'View', items: 'code | visualaid visualblocks | fullscreen' },
		insert: { title: 'Insert', items: 'image link media pageembed template inserttable | charmap emoticons hr | nonbreaking anchor' },
		//format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | removeformat' },
		tools: { title: 'Tools', items: '' },
		table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
		help: { title: 'Help', items: 'help' }
	},
	// Contenu de la barre d'outils
	toolbar: [
		"bold italic underline forecolor backcolor |  h1 h2 h3 | | bullist numlist | alignleft aligncenter alignright alignjustify |  outdent indent removeformat | link image media |fullscreen",
	],
	toolbar_sticky: true,
	fontsize_formats:
		"8pt 9pt 10pt 11pt 12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt",
	theme: "silver",
	max_height: 600,
	// CodeMirror
	codemirror: {
		indentOnInit: true, // Whether or not to indent code on init.
		path: 'codemirror', // Path to CodeMirror distribution
		saveCursorPosition: false,    // Insert caret marker
		config: {           // CodeMirror config object
			fullscreen: true,
			/*mode: 'application/x-httpd-php',*/
			lineNumbers: true,
			indentUnit: 4,
			mode: "htmlmixed"
		},
		jsFiles: [
			'mode/php/php.js',
			'mode/css/css.js',
			'mode/htmlmixed/htmlmixed.js',
			'mode/htmlembedded/htmlembedded.js',
			'mode/javascript/javascript.js',
			'mode/xml/xml.js',
			'addon/search/searchcursor.js',
			'addon/search/search.js',
		],
		/*
		cssFiles: [
			'theme/cobalt.css',
		],*/
		width: 800,         // Default value is 800
		height: 500       // Default value is 550
	},
	// Cibles de la target
	target_list: [
		{ title: 'None', value: '' },
		{ title: 'Nouvel onglet', value: '_blank' }
	],
	// Target pour lightbox
	rel_list: [
		{ title: 'None', value: '' },
		{ title: 'Une popup (Lity)', value: 'data-lity' },
		{ title: 'Une galerie d\'images (SimpleLightbox)', value: 'gallery' }
	],
	// Titre des image
	image_title: true,
	// Pages internes
	link_list: baseUrl + "core/vendor/tinymce/links.php",
	// Contenu du menu contextuel
	contextmenu: "selectall searchreplace | hr | media image  link anchor nonbreaking  | insertable  cell row column deletetable",
	// Fichiers CSS à intégrer à l'éditeur
	content_css: [
		baseUrl + "core/layout/common.css",
		baseUrl + "core/vendor/tinymce/content.css",
		baseUrl + "site/data/theme.css",
		baseUrl + "site/data/custom.css"
	],
	// Classe à ajouter à la balise body dans l'iframe
	body_class: "editorWysiwyg",
	// Cache les menus
	menubar: true,
	// URL menu contextuel
	link_context_toolbar: true,
	// Cache la barre de statut
	statusbar: true,
	// Coller images blob
	paste_data_images: true,
	/* Eviter BLOB à tester
	images_dataimg_filter: function(img) {
		return img.hasAttribute('internal-blob');
	},*/
	// Autoriser tous les éléments
	valid_elements: '*[*]',
	// Autorise l'ajout de script
	extended_valid_elements: "script[language|type|src]",
	// Conserver les styles
	keep_styles: false,
	// Bloque le dimensionnement des médias (car automatiquement en fullsize avec fitvids pour le responsive)
	media_dimensions: true,
	// Désactiver la dimension des images
	image_dimensions: true,
	// Active l'onglet avancé lors de l'ajout d'une image
	image_advtab: true,
	// Urls absolues
	relative_urls: true,
	// Conversion des URLs
	convert_urls: false,
	// Url de base
	document_base_url: baseUrl,
	// Gestionnaire de fichiers
	filemanager_access_key: privateKey,
	external_filemanager_path: baseUrl + "core/vendor/filemanager/",
	external_plugins: {
		"filemanager": baseUrl + "core/vendor/filemanager/plugin.min.js"
	},
	// Contenu du bouton insérer
	insert_button_items: "anchor hr table",
	// Contenu du bouton formats
	/** 
	style_formats: [
		{
			title: "Headers", items: [
				{ title: "Header 1", format: "h1" },
				{ title: "Header 2", format: "h2" },
				{ title: "Header 3", format: "h3" },
				{ title: "Header 4", format: "h4" }
			]
		},
		{
			title: "Blocks", items: [
				{ title: "Paragraph", format: "p" },
				{ title: "Blockquote", format: "blockquote" },
				{ title: "Div", format: "div" },
				{ title: "Pre", format: "pre" }
			]
		}
	],*/
	// Templates
	templates: [
		{
			title: "Bloc de texte",
			url: baseUrl + "core/vendor/tinymce/templates/block.html",
			description: "Bloc de texte avec un titre."
		},
		{
			title: "Effet accordéon",
			url: baseUrl + "core/vendor/tinymce/templates/accordion.html",
			description: "Bloc de texte avec effet accordéon."
		},
		{
			title: "Grille symétrique : 6 - 6",
			url: baseUrl + "core/vendor/tinymce/templates/col6.html",
			description: "Grille adaptative sur 12 colonnes, sur mobile elles passent les unes en dessous des autres."
		},
		{
			title: "Grille symétrique : 4 - 4 - 4",
			url: baseUrl + "core/vendor/tinymce/templates/col4.html",
			description: "Grille adaptative sur 12 colonnes, sur mobile elles passent les unes en dessous des autres."
		},
		{
			title: "Grille symétrique : 3 - 3 - 3 - 3",
			url: baseUrl + "core/vendor/tinymce/templates/col3.html",
			description: "Grille adaptative sur 12 colonnes, sur mobile elles passent les unes en dessous des autres."
		},
		{
			title: "Grille asymétrique : 4 - 8",
			url: baseUrl + "core/vendor/tinymce/templates/col4-8.html",
			description: "Grille adaptative sur 12 colonnes, sur mobile elles passent les unes en dessous des autres."
		},
		{
			title: "Grille asymétrique : 8 - 4",
			url: baseUrl + "core/vendor/tinymce/templates/col8-4.html",
			description: "Grille adaptative sur 12 colonnes, sur mobile elles passent les unes en dessous des autres."
		},
		{
			title: "Grille asymétrique : 2 - 10",
			url: baseUrl + "core/vendor/tinymce/templates/col2-10.html",
			description: "Grille adaptative sur 12 colonnes, sur mobile elles passent les unes en dessous des autres."
		},
		{
			title: "Grille asymétrique : 10 - 2",
			url: baseUrl + "core/vendor/tinymce/templates/col10-2.html",
			description: "Grille adaptative sur 12 colonnes, sur mobile elles passent les unes en dessous des autres."
		}
	]
});


tinymce.init({
	// Classe où appliquer l'éditeur
	selector: ".editorWysiwygComment",
	setup: function (ed) {
		// Aperçu dans le pied de page
		ed.on('change', function (e) {
			if (ed.id === 'themeFooterText') {
				$("#footerText").html(tinyMCE.get('themeFooterText').getContent());
			}
		});
		// Limitation du nombre de caractères des commentaires à maxlength
		var alarmCaraMin = 200; // alarme sur le nombre de caractères restants à partir de...
		var maxlength = parseInt($("#" + (ed.id)).attr("maxlength"));
		var id_alarm = "#blogArticleContentAlarm"
		var contentLength = 0;
		ed.on("keydown", function (e) {
			contentLength = ed.getContent({ format: 'text' }).length;
			if (contentLength > maxlength) {
				$(id_alarm).html("Vous avez atteint le maximum de " + maxlength + " caractères ! ");
				if (e.keyCode != 8 && e.keyCode != 46) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			}
			else {
				if (maxlength - contentLength < alarmCaraMin) {
					$(id_alarm).html((maxlength - contentLength) + " caractères restants");
				}
				else {
					$(id_alarm).html(" ");
				}
			}
		});
		// Limitation y compris lors d'un copier/coller
		ed.on("paste", function (e) {
			contentLeng = ed.getContent({ format: 'text' }).length - 16;
			var data = e.clipboardData.getData('Text');
			if (data.length > (maxlength - contentLeng)) {
				$(id_alarm).html("Vous alliez dépasser le maximum de " + maxlength + " caractères ! ");
				return false;
			} else {
				if (maxlength - contentLeng < alarmCaraMin) {
					$(id_alarm).html((maxlength - contentLeng - data.length) + " caractères restants");
				}
				else {
					$(id_alarm).html(" ");
				}
				return true;
			}
		});
	},
	// Langue
	language: getCookie('ZWII_UI') === null ? "fr_FR" : getCookie('ZWII_UI'),
	// Plugins
	plugins: "advlist anchor autolink autoresize autosave colorpicker contextmenu hr lists paste searchreplace tabfocus template textcolor",
	// Contenu de la barre d'outils
	toolbar: "bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist",
	// Titre des images
	image_title: true,
	// Pages internes
	link_list: baseUrl + "core/vendor/tinymce/links.php",
	// Contenu du menu contextuel
	contextmenu: "cut copy paste pastetext | selectall searchreplace ",
	// Fichiers CSS à intégrer à l'éditeur
	content_css: [
		baseUrl + "core/layout/common.css",
		baseUrl + "core/vendor/tinymce/content.css",
		baseUrl + "site/data/theme.css",
		baseUrl + "site/data/custom.css"
	],
	// Classe à ajouter à la balise body dans l'iframe
	body_class: "editorWysiwyg",
	// Cache les menus
	menubar: false,
	// URL menu contextuel
	link_context_toolbar: true,
	// Cache la barre de statut
	statusbar: false,
	// Autorise le copié collé à partir du web
	paste_data_images: true,
	// Bloque le dimensionnement des médias (car automatiquement en fullsize avec fitvids pour le responsive)
	media_dimensions: true,
	// Désactiver la dimension des images
	image_dimensions: true,
	// Active l'onglet avancé lors de l'ajout d'une image
	image_advtab: true,
	// Urls absolues
	relative_urls: true,
	// Conversion des URLs
	convert_urls: false,
	// Url de base
	document_base_url: baseUrl,
	max_height: 200,
});


function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}