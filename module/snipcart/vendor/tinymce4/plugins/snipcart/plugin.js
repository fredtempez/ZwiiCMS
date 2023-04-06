/**
** Plugin Snipcart pour Tinymce v4.x
** Pour le CMS ZWII
** 
** @author Sylvain Lelièvre
***/

function rgb2hex(rgb){
	rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
	return (rgb && rgb.length === 4) ? "#" +
	  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
	  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
	  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

(function () {
var snipcart = (function () {
    'use strict';
	
	tinymce.PluginManager.add('snipcart', function (editor, url) {
		
		function _onAction(){
		
			//Requête Jquery de lecture des valeurs par défaut, englobante
			$.get('./site/data/snipcart/module/datadefault.json', function(datajson) {
			
				function extractData(str, start, stop){
					var res = '';
					var deb = str.indexOf(start)
					if( deb != -1){
						deb = deb + start.length;
						var fin = str.indexOf(stop, deb );
						res = str.substr(deb, fin - deb);
					}
					return res;
				}
					
				var isUpdate = false;
				var inner = '';
				var dataInsertedCode = '';
				// Valeurs par défaut
				var dataTaxes = datajson.taxes;
				var dataId = '';
				var dataPrice = '';
				var dataDescription = '';
				var dataImage = '';
				var dataName = '';
				var dataWeight = datajson.poids;
				var dataShippable = datajson.transport;
				var dataCustom1Name = '';
				var dataCustom1Options = '';
				var dataCustom2Name = '';
				var dataCustom2Options = '';
				var dataCustom3Name = '';
				var dataCustom4Name = '';
				var dataCustom4Value = '';
				var dataButtonText = datajson.buttonText;
				var dataButtonWidth = datajson.buttonWidth;
				var dataButtonColor = rgb2hex(datajson.buttonColor);
				var dataButtonBgColor = rgb2hex(datajson.buttonBgColor);
				// dataTemplate c'est bouton_seul ou bouton_produit
				var dataTemplate = datajson.template;
				// dataTemplateButton pour mémorisation dans le bouton
				var dataTemplateButton ='';
				// Pour différencier bouton_produit en bouton_produit_ligne ou bouton_produit_colonne
				var dataTemplateSelect = 'bouton_produit_ligne';			
				if(dataTemplate === 'bouton_produit'){
					// La création du bouton + produit se fait dans un template 2 ou 3 colonnes avec la class col6 ou col4
					if( editor.selection.getNode().parentElement.className === "col6" || editor.selection.getNode().parentElement.className === "col4"){
						dataTemplateSelect = 'bouton_produit_colonne';
					}	
				}
				
				// Edition suppression d'un bouton d'ajout au panier
				if (editor.selection.getNode().nodeName === "BUTTON"
					&& editor.selection.getNode().className === "snipcart-add-item") {
					inner = editor.selection.getNode().parentElement.innerHTML;
					dataTaxes = extractData(inner, 'data-item-taxes="', '"');
					dataId = extractData(inner, 'data-item-id="', '"');
					dataPrice = extractData(inner, 'data-item-price="', '"');
					dataDescription = extractData(inner, 'data-item-description="', '"');
					dataImage = extractData(inner, 'data-item-image="', '"');
					dataName = extractData(inner, 'data-item-name="', '"');
					dataWeight = extractData(inner, 'data-item-weight="', '"');
					dataCustom1Name = extractData(inner, 'data-item-custom1-name="', '"');
					dataCustom1Options = extractData(inner, 'data-item-custom1-options="', '"');
					dataCustom2Name = extractData(inner, 'data-item-custom2-name="', '"');
					dataCustom2Options = extractData(inner, 'data-item-custom2-options="', '"');
					dataCustom3Name = extractData(inner, 'data-item-custom3-name="', '"');
					dataCustom4Name = extractData(inner, 'data-item-custom4-name="', '"');
					dataCustom4Value = extractData(inner, 'data-item-custom4-value="', '"');
					dataButtonText = extractData(inner, 'px;">', '<');
					dataButtonWidth = extractData(inner, 'width: ', 'p');
					dataButtonColor = extractData(inner, 'style="color:', ';');
					dataButtonBgColor = extractData(inner, 'background-color:', ';');
					var dataShippableStr = extractData(inner, 'data-item-shippable="', '"');
					if( dataShippableStr === 'true'){
						dataShippable = true;
					}
					else{
						dataShippable = false;
					}
					
					//Code inséré par onglet Avancé
					dataInsertedCode = extractData(inner, 'textFree"', 'style=');
					//dataTemplateButton inscrit à la création du bouton pour modifications ultérieures
					// Si data-template-button n'existe pas (v1.1) on fixe dataTemplateButton à bouton_seul
					if( inner.includes('data-template-button="')){
						dataTemplateButton = extractData(inner, 'data-template-button="', '"');
					}
					else{
						dataTemplateButton = 'bouton_seul';
					}

					isUpdate = true;
				}
				
				var titreOpen = '';
				var textSup = 'X';
				if(dataTemplateButton === 'bouton_seul'){
					titreOpen = 'Création d\'un bouton d\'ajout au panier Snipcart';
					textSup ='X';
					if(isUpdate) {
						titreOpen = 'Modification du bouton d\'ajout au panier Snipcart';
						textSup = 'Supprimer';
					}
				}
				else{
					titreOpen = 'Création d\'un produit Snipcart';
					if(isUpdate) {
						titreOpen = 'Modification du produit Snipcart';
						textSup = 'Supprimer';
					}
				}
				
				var body = [
					{	//Onglet Général
						title: 'Général',
						type: 'form',
						items: 
						[	// Nom du produit
							{type: 'textbox',
							name: 'name',
							label: 'Nom du produit',
							value: dataName
							},
							// Id unique du produit
							{type: 'textbox',
							name: 'id',
							label: 'Id du produit',
							value: dataId
							},
							// Description
							{type: 'textbox',
							name: 'description',
							label: 'Description',
							multiline: true,
							minWidth: 400,
							minHeight: 100,
							value: dataDescription
							},
							// Fichier image du produit pour le panier
							{type: 'filepicker',
							filetype: 'image',
							name: 'image',
							label: 'Illustration',
							value: dataImage
							},
							// Tarif TTC avec le . pour séparateur décimal
							{type: 'textbox',
							name: 'tarif',
							label: 'Tarif TTC',
							value: dataPrice
							},
							// Poids en grammes en nombre entier
							{type: 'textbox',
							name: 'poids',
							label: 'Poids en grammes',
							value: dataWeight
							},
							// Taxe : saisir le nom d'une taxe déclarée dans Snipcart
							{type: 'textbox',
							name: 'taxe',
							label: 'Taxe',
							value: dataTaxes
							},
							// Expédition ou collecte
							{type: 'checkbox',
							name: 'shippable',
							text: 'Frais de transport',
							label: 'Transport',
							checked: dataShippable
							}
						],
					},
					{	//Onglet Bouton
						title: 'Bouton',
						type: 'form',
						items:
						[	
							// Texte du bouton d'achat
							{type: 'textbox',
							name: 'textButton',
							label: 'Texte du bouton',
							value: dataButtonText
							},
							// Largeur du bouton d'achat
							{type: 'textbox',
							name: 'widthButton',
							label: 'Largeur du bouton',
							value: dataButtonWidth
							},
							// Couleur du texte du bouton
							{
							type: 'colorpicker',
							name: 'colorButton',
							label: 'Couleur du texte',
							value: dataButtonColor
							},
							// Couleur du bouton
							{
							type: 'colorpicker',
							name: 'colorBgButton',
							label: 'Couleur du bouton',
							value: dataButtonBgColor
							},
						]
					
					},
					{	//Onglet Options
						title: 'Options',
						type: 'form',
						items:
						[	// Container html
							{
							type   : 'container',
							name   : 'container',
							label  : '',
							html   : '<p><a href="https://docs.snipcart.com/v3/setup/products" target="_blank">Documentation Snipcart</a></p>
							<p>&nbsp;</p>
							<p>Pour permettre au client de choisir une option avec une liste déroulante</p>
							<p>Voir "Custom Fields"  et "1.Droptown", ne pas saisir les guillemets double (")</p>'
							},
							// Nom du champ custom1
							{type: 'textbox',
							name: 'advancedName1',
							label: 'data-item-custom1-name',
							value: dataCustom1Name
							},
							// Champ optionnel custom1
							{type: 'textbox',
							name: 'advancedOptions1',
							label: 'data-item-custom1-options',
							value: dataCustom1Options
							},
							// Container html
							{
							type   : 'container',
							name   : 'container',
							label  : '',
							html   : '<p>&nbsp;</p><p>Pour permettre au client de choisir une seconde option</p>'
							},
							// Nom du champ custom2
							{type: 'textbox',
							name: 'advancedName2',
							label: 'data-item-custom2-name',
							value: dataCustom2Name
							},
							// Champ optionnel custom2
							{type: 'textbox',
							name: 'advancedOptions2',
							label: 'data-item-custom2-options',
							value: dataCustom2Options
							},

						]
					
					},
					{	//Onglet Textes
						title: 'Textes',
						type: 'form',
						items:
						[	
							// Container html
							{
							type   : 'container',
							name   : 'container',
							label  : '',
							html   : '<p><a href="https://docs.snipcart.com/v3/setup/products" target="_blank">Documentation Snipcart</a></p>
							<p>&nbsp;</p>
							<p>Pour permettre au client d\'insérer un texte</p>
							<p>Voir "Custom Fields"  et "4.Texarea", ne pas saisir les guillemets double (")</p>'
							},
							// Nom du champ custom3
							{type: 'textbox',
							name: 'advancedName3',
							label: 'data-item-custom3-name',
							value: dataCustom3Name
							},
							// Container html
							{
							type   : 'container',
							name   : 'container',
							label  : '',
							html   : '<p>&nbsp;</p><p>Pour vous permettre d\'afficher un texte</p>
							<p>Voir "Custom Fields"  et "5.Readonly", ne pas saisir les guillemets double (")</p>'
							},
							// Nom du champ custom4
							{type: 'textbox',
							name: 'advancedName4',
							label: 'data-item-custom4-name',
							value: dataCustom4Name
							},
							// Valeur du champ custom4
							{type: 'textbox',
							name: 'advancedValue4',
							label: 'data-item-custom4-value',
							value: dataCustom4Value
							},

						]
					
					},
					{	//Onglet Avancé
						title: 'Avancé',
						type: 'form',
						items:
						[	// Container html
							{
							type   : 'container',
							name   : 'container',
							label  : '',
							html   : '<p><a href="https://docs.snipcart.com/v3/setup/products" target="_blank">Documentation Snipcart</a></p>
							<p>&nbsp;</p>
							<p>Vous pouvez saisir des attributs "data-..." et leur valeur, exemple :</p>
							<p><font face="Arial"><em> data-item-custom5-name="Cadeau" data-item-custom5-type="checkbox" </em></font></p>
							<p><a href="site/data/snipcart/module/interdits.html" target="_blank">ATTENTION Consultez la liste des attributs interdits !</a></p>'
							},
							// Champ libre
							{type: 'textbox',
							name: 'textCustomFree',
							label: '',
							multiline: true,
							minWidth: 400,
							minHeight: 100,
							value: dataInsertedCode
							},
						]
					
					},
					
				];
				
				editor.windowManager.open({
					title : titreOpen,
					bodyType: 'tabpanel',
					body: body,		
					onsubmit: function( e ) {
						// On élimine tous les " des élèments e.data sauf pour textCustomFree, container, color, shippable, url
						e.data.name = e.data.name.replace(/"/g,'');
						e.data.id = e.data.id.replace(/"/g,'');
						e.data.description = e.data.description.replace(/"/g,'');
						e.data.tarif = e.data.tarif.replace(/"/g,'');
						e.data.poids = e.data.poids.replace(/"/g,'');
						e.data.taxe = e.data.taxe.replace(/"/g,'');
						e.data.textButton = e.data.textButton.replace(/"/g,'');
						e.data.widthButton = e.data.widthButton.replace(/"/g,'');
						e.data.advancedName1 = e.data.advancedName1.replace(/"/g,'');
						e.data.advancedName2 = e.data.advancedName2.replace(/"/g,'');
						e.data.advancedName3 = e.data.advancedName3.replace(/"/g,'');
						e.data.advancedName4 = e.data.advancedName4.replace(/"/g,'');
						e.data.advancedOptions1 = e.data.advancedOptions1.replace(/"/g,'');
						e.data.advancedOptions2 = e.data.advancedOptions2.replace(/"/g,'');
						e.data.advancedValue4 = e.data.advancedValue4.replace(/"/g,'');
						// Filtrage de e.data.poids : nombre entier
						if(e.data.poids.search(',') != -1){
							e.data.poids = e.data.poids.replace(',','.');
						}
						e.data.poids = parseInt( e.data.poids);
						// Filtrage de e.data.tarif : . est le séparateur décimal
						if(e.data.tarif.search(',') != -1){
							e.data.tarif = e.data.tarif.replace(',','.');
						}		
						// data-item-url
						var str1 = window.location.href;
						// data-item-url des pages
						if( str1.search('page/edit/') != -1){
							str1 = str1.replace('page/edit/', '');
						}
						// data-item-url des articles de blog
						if( str1.search('blog/edit/') != -1){
							// suppression de 'edit/'
							str1 = str1.replace('edit/', '');
							// suppression de l'url après le nom de l'article
							var last = str1.lastIndexOf( '/' );
							str1 = str1.substr(0, last);
						}
						if( e.data.taxe == ''){
							e.data.taxe = 'NO_TAXE';
						}
						if( e.data.name == ''){
							e.data.name = '?';
						}
						if( e.data.id == ''){
							e.data.id = '?';
						}
						if( e.data.tarif == ''){
							e.data.tarif = '0';
						}
						var insertOption = '';
						if( e.data.advancedName1 != '' && e.data.advancedOptions1 !=''){
							insertOption = ' data-item-custom1-name="' + e.data.advancedName1 + '" data-item-custom1-options="' + e.data.advancedOptions1 + '"';
						}
						if( e.data.advancedName2 != '' && e.data.advancedOptions2 !=''){
							insertOption = insertOption + ' data-item-custom2-name="' + e.data.advancedName2 + '" data-item-custom2-options="' + e.data.advancedOptions2 + '"';
						}
						if(e.data.advancedName3 != ''){
							insertOption = insertOption + ' data-item-custom3-name="' + e.data.advancedName3 + '"' + 'data-item-custom3-type="textarea"';
						}
						if(e.data.advancedValue4 !=''){
							var value4 = e.data.advancedValue4.replace(/"/g, "'");
							insertOption = insertOption + ' data-item-custom4-name="' + e.data.advancedName4 + '" data-item-custom4-value="' + value4 + '" data-item-custom4-type="readonly"';
						}
						if(e.data.textCustomFree != ''){
							dataInsertedCode = e.data.textCustomFree;
						}
						// dataTemplateButton inscrit à la création, inchangé après
						if(isUpdate === false){
							dataTemplateButton = 'bouton_seul';
							if(dataTemplate === 'bouton_produit'){
								dataTemplateButton = dataTemplateSelect;
							}
						}

						// Création du code html avec le choix de template dataTemplate passé par datadefault.json
						var htmlProd='';
						var htmlButton = '<p style="text-align: center;"><button class="snipcart-add-item" data-item-id="' + e.data.id 
							+ '" data-item-price="' + e.data.tarif 
							+ '" data-item-url="' + str1
							+ '" data-item-description="' + e.data.description
							+ '" data-item-image="' + e.data.image 
							+ '" data-item-name="' + e.data.name
							+ '" data-item-weight="' + e.data.poids
							+ '" data-item-taxes="' + e.data.taxe +'"'
							+ ' data-item-has-taxes-included="true" data-item-shippable="' +  e.data.shippable
							+ '" data-currency="eur"' + insertOption + '" data-template-button="' + dataTemplateButton + '" labelStart="textFree"' + dataInsertedCode
							+ 'style="color:'+ e.data.colorButton + '; background-color:' + e.data.colorBgButton
							+ '; width:' + e.data.widthButton + 'px;">' + e.data.textButton +'</button></p>';
							
						switch(dataTemplateButton) {
						  case 'bouton_seul':
							if(isUpdate){
								// Modification : on commence par effacer
								editor.selection.getNode().parentElement.innerHTML  = '';
							}
							htmlProd = htmlButton;
							break;
						  case 'bouton_produit_ligne':
							if(isUpdate){ 
								editor.selection.getNode().parentElement.parentElement.parentElement.parentElement.parentElement.innerHTML  = '';
								htmlProd = '<div class="block"><h4>' 
								+ e.data.name + '</h4><div class="row"><div class="col6"><p><img src="'
								+ e.data.image + '"  /></p></div><div class="col6"><p>'
								+ e.data.description + '</p><p><h3 style="text-align: center;">Prix TTC : '
								+ e.data.tarif + '&euro;</h3></p>'		
								+ htmlButton +'</div></div></div>';
							}
							else{
								// Ajout de &nbsp; pour faciliter l'ajout ou l'insertion et d'une div indispensable pour l'effacement avant modification
								htmlProd = '<p>&nbsp;</p><div><div class="block"><h4>' 
								+ e.data.name + '</h4><div class="row"><div class="col6"><p><img src="'
								+ e.data.image + '"  /></p></div><div class="col6"><p>'
								+ e.data.description + '</p><p><h3 style="text-align: center;">Prix TTC : '
								+ e.data.tarif + '&euro;</h3></p>'		
								+ htmlButton +'</div></div></div></div><p>&nbsp;</p>';
							}
							break;
							case 'bouton_produit_colonne':
							if(isUpdate){ 
								editor.selection.getNode().parentElement.parentElement.parentElement.innerHTML  = '';
								htmlProd = '<div class="block"><h4>' 
								+ e.data.name + '</h4><p><img src="'
								+ e.data.image + '"  /></p><p>&nbsp;</p><p>'
								+ e.data.description + '</p><p><h3 style="text-align: center;">Prix TTC : '
								+ e.data.tarif + '&euro;</h3></p>'		
								+ htmlButton +'</div>';
							}
							else{
								// Ajout d'une div indispensable pour l'effacement avant modification
								htmlProd = '<div><div class="block"><h4>' 
								+ e.data.name + '</h4><p><img src="'
								+ e.data.image + '"  /></p><p>&nbsp;</p><p>'
								+ e.data.description + '</p><p><h3 style="text-align: center;">Prix TTC : '
								+ e.data.tarif + '&euro;</h3></p>'		
								+ htmlButton +'</div></div>';
							}
							break;
							
						  default:
						  	if(isUpdate){
								editor.selection.getNode().parentElement.innerHTML  = '';
							}
							htmlProd = htmlButton;
						}
						editor.insertContent(htmlProd);					
					},
					
					buttons: 
					[
						// Bouton Valider
						{
						text: 'Valider',
						subtype: 'primary',
						onclick : 'submit'
						},
						// Bouton Annuler
						{
						text: 'Annuler',
						onclick: 'close'
							
						},
						// Bouton  ou Supprimer
						{
						text: textSup,
						onclick: function formSup(){
							if(isUpdate){
								switch( dataTemplateButton ) {
									case 'bouton_seul':
										editor.selection.getNode().parentElement.innerHTML  = '';
										break;
									case 'bouton_produit_ligne':
										editor.selection.getNode().parentElement.parentElement.parentElement.parentElement.parentElement.innerHTML  = '';
										break;
									case 'bouton_produit_colonne':
										editor.selection.getNode().parentElement.parentElement.parentElement.innerHTML  = '';
									break;	
									default:
									editor.selection.getNode().parentElement.innerHTML  = '';
								}
							}
							editor.windowManager.close();
							}
						}
						
					]				
				});
			
			});
		};	
		
		// Icône Snipcart dans le toolbar
		editor.addButton('snipcart', {
			tooltip: 'Snipcart',
			image: url + '/img/snipcart.png',
			onclick: _onAction
		});

		// Icône et texte Snipcart dans le menu
		editor.addMenuItem('snipcart', {
			text: 'Snipcart',
			context: 'insert',
			image: url + '/img/snipcart.png',
			onclick: _onAction
		});
	});

    function Plugin () {
    }
    return Plugin;
}());
})();
  