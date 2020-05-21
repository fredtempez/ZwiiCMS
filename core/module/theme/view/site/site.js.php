/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

 /*
 * Chargement de l'aperçu
 */
$(document).ready(function() {	

	/**
	* Option de marge si la taille n'est pas fluide
	*/
	if ($('#themeSiteWidth').val() === '100%') {
		$("#themeSiteMargin").prop("checked", true);
		$("#themeSiteMargin").addClass("disabled");
	} else {
		$("#themeSiteMargin").addClass("enabled");
	}

	// Charger l'aperçu initial
	previewDOM();

});


/**
 * Aperçu en direct
 */
$("input, select").on("change",function() {	
	previewDOM();
});

function previewDOM() {

	/**
	 * Aperçu dans la boîte
	 */

	// Import des polices de caractères
	var titleFont = $("#themeTitleFont").val();
	var textFont = $("#themeTextFont").val();
	var css = "@import url('https://fonts.googleapis.com/css?family=" + titleFont + "|" + textFont + "');";
	// Couleurs des boutons		
	var colors = core.colorVariants($("#themeButtonBackgroundColor").val());	
	css += ".button.buttonSubmitPreview{background-color:" + colors.normal + ";}";
	css += ".button.buttonSubmitPreview:hover{background-color:" + colors.darken + "}";
	css += ".button.buttonSubmitPreview{color:" + colors.text + ";}";

	// Couleurs des liens
	var colors = core.colorVariants($("#themeTextLinkColor").val());
	css += "a.urlPreview{color:" + colors.normal + "}";
	css += "a.urlPreview:hover{color:" + colors.darken + "}";
	// Couleur, polices, épaisseur et capitalisation de caractères des titres
	css += ".headerPreview,.headerPreview{color:" + $("#themeTitleTextColor").val() + ";font-family:'" + titleFont.replace(/\+/g, " ") + "',sans-serif;font-weight:" + $("#themeTitleFontWeight").val() + ";text-transform:" + $("#themeTitleTextTransform").val() + "}";
	// Police de caractères
	// Police + couleur
	css += ".textPreview,.urlPreview{color:" + $("#themeTextTextColor").val() + ";font-family:'" + textFont.replace(/\+/g, " ") + "',sans-serif; font-size:" + $("#themeTextFontSize").val() + ";}";
	// Couleur des liens
	//css += "a.preview,.buttonSubmitPreview{font-family:'" + textFont.replace(/\+/g, " ") + "',sans-serif}";

	// Taille du texte
	// Couleur du texte
	css += "p.preview{color:" + $("#themeTextTextColor").val() + "}";

	/**
	 * Aperçu réel
	 */

	// Taille du site
	if ($("#themeSiteWidth").val() === "750px") {
		css += ".button, button{font-size:0.8em;}";
	} else {
		css += ".button, button{font-size:1em;}";
	}
	// Largeur du site
	var margin = $("#themeSiteMargin").is(":checked") ? 0 : '20px' ;
	css += ".container{max-width:" + $("#themeSiteWidth").val() + "}";
	if ($("#themeSiteWidth").val() === "100%") {
		css += "#site{margin: 0px auto;} body{margin:0 auto;}  #bar{margin:0 auto;} body > header{margin:0 auto;} body > nav {margin: 0 auto;} body > footer {margin:0 auto;}";
	} else {
		css += "#site{margin: " + margin + " auto !important;} body{margin:0px 10px;}  #bar{margin: 0 -10px;} body > header{margin: 0 -10px;} body > nav {margin: 0 -10px;} body > footer {margin: 0 -10px;} ";
	}
	// Couleur du site, arrondi sur les coins du site et ombre sur les bords du site
	//css += "#site{background-color:" + $("#themeSiteBackgroundColor").val() + ";border-radius:" + $("#themeSiteRadius").val() + ";box-shadow:" + $("#themeSiteShadow").val() + " #212223}";
	
	css += "#site{border-radius:" + $("#themeSiteRadius").val() + ";box-shadow:" + $("#themeSiteShadow").val() + " #212223}";	
	var backgroundImage = <?php echo json_encode(helper::baseUrl(false) . self::FILE_DIR . 'source/' . $this->getData(['theme','body','image'])); ?>;
	var backgroundcolor = <?php echo json_encode($this->getdata(['theme','body','backgroundColor'])); ?>;						
	css += "div.bodybackground{background-color:" +  backgroundcolor + "; background-image: url(" + backgroundImage + ");background-size:cover;}";
	css += "div.bgPreview{padding: 5px;background-color:" + $("#themeSiteBackgroundColor").val() + ";}";
	
	// Les blocs

	var colors = core.colorVariants($("#themeBlockBackgroundColor").val());  
	css += ".block.preview {padding: 20px 20px 10px;margin: 20px 0;	word-wrap: break-word;border-radius: 2px;border: 1px solid " + $("#themeBlockBorderColor").val() + ";}.block.preview h4.preview {background: "  + colors.normal + ";color:" + colors.text + ";margin: -20px -20px 10px -20px; padding: 10px;}";

	/**
	 * Injection dans le DOM
	 */
	// Ajout du css au DOM
	$("#themePreview").remove();
	$("<style>")
		.attr("type", "text/css")
		.attr("id", "themePreview")
		.text(css)
		.appendTo("head");

};
