/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

/**
 * Aperçu en direct
 */
$("input, select").on("change",function() {

	/**
	* Option de marge si la taille n'est pas fluide
	*/
	if ($('#themeSiteWidth').val() === '100%') {
		$("#themeSiteMarginWrapper").prop("checked", true);
		$("#themeSiteMarginWrapper").hide();
	} else {
		$("#themeSiteMarginWrapper").show();
	}


	/**
	 * Aperçu dans la boîte
	 */

	// Import des polices de caractères pour l'aperçu en temps réel
	var titleFont = $("#themeTitleFont :selected").val();
	var titleFontText = $("#themeTitleFont :selected").text();
	var textFont = $("#themeTextFont :selected").val();
	var textFontText = $("#themeTextFont :selected").text();
	// Couleurs des boutons
	var colors = core.colorVariants($("#themeButtonBackgroundColor").val());
	var css = ".button.buttonSubmitPreview{background-color:" + colors.normal + ";}";
	css += ".button.buttonSubmitPreview:hover{background-color:" + colors.darken + "}";
	css += ".button.buttonSubmitPreview{color:" + colors.text + ";}";

	// Couleurs des liens
	var colors = core.colorVariants($("#themeTextLinkColor").val());
	css += "a.urlPreview{color:" + colors.normal + "}";
	css += "a.urlPreview:hover{color:" + colors.darken + "}";
	// Couleur, polices, épaisseur et capitalisation de caractères des titres
	css += ".headerPreview,.headerPreview{color:" + $("#themeTitleTextColor").val() + ";font-family:'" + titleFontText + "',sans-serif;font-weight:" + $("#themeTitleFontWeight").val() + ";text-transform:" + $("#themeTitleTextTransform").val() + "}";
	// Police de caractères
	// Police + couleur
	css += ".textPreview,.urlPreview{color:" + $("#themeTextTextColor").val() + ";font-family:'" + textFontText + "',sans-serif; font-size:" + $("#themeTextFontSize").val() + ";}";
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
	// Largeur
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

	// Couleur ou image de fond
	var backgroundImage = "<?php echo json_encode($this->getData(['theme','body','image'])); ?>";
	var backgroundcolor = "<?php echo json_encode($this->getdata(['theme','body','backgroundColor'])); ?>";
	if(backgroundImage ) {
		css += "div.bodybackground{background-image:url(" + window.location.origin + window.location.pathname + '/site/file/source/' +  backgroundImage + ");background-repeat:" + $("#themeBodyImageRepeat").val() + ";background-position:" + $("#themeBodyImagePosition").val() + ";background-attachment:" + $("#themeBodyImageAttachment").val() + ";background-size:" + $("#themeBodyImageSize").val() + "}";
		css += "div.bodybackground{background-color:rgba(0,0,0,0);}";
	}
	else {
		css += "div.bodybackground{background-image:none}";
	}
	css += 'div.bodybackground {background-color:'  + backgroundcolor + ';color:' + $("#themeBodyToTopColor").val() + ';}';

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
}).trigger("change");
