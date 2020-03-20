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
$( document).ready(function() {	
	updateDOM();
});

/**
 * Aperçu en direct
 */
$("input, select").on("change",function() {	
	updateDOM();
});


function updateDOM() {
		// Import des polices de caractères
		var titleFont = $("#themeTitleFont").val();
		var textFont = $("#themeTextFont").val();
		var css = "@import url('https://fonts.googleapis.com/css?family=" + titleFont + "|" + textFont + "');";
		// Couleurs des boutons
		var colors = core.colorVariants($("#themeButtonBackgroundColor").val());
		css += ".speechBubble,.button,.button:hover,.pagination a,.pagination a:hover,input[type='checkbox']:checked + label:before,.helpContent{background-color:" + colors.normal + ";color:" + colors.text + "}";
		//css += ".helpButton span{color:" + colors.normal + "}";
		//css += "input[type='text']:hover,input[type='password']:hover,.inputFile:hover,select:hover,textarea:hover{border-color:" + colors.normal + "}";
		//css += ".speechBubble:before{border-color:" + colors.normal + " transparent transparent transparent}";
		//css += ".button:hover,button[type='submit']:hover,.pagination a:hover,input[type='checkbox']:not(:active):checked:hover + label:before,input[type='checkbox']:active + label:before{background-color:" + colors.darken + "}";
		//css += ".helpButton span:hover{color:" + colors.darken + "}";
		//css += ".button:active,button[type='submit']:active,.pagination a:active{background-color:" + colors.veryDarken + "}";
		if ($("#themeSiteWidth").val() === "750px") {
			css += ".button, button{font-size:0.8em;}";
		} else {
			css += ".button, button{font-size:1em;}";
		}
		// Couleurs des liens
		colors = core.colorVariants($("#themeLinkTextColor").val());
		css += "a{color:" + colors.normal + "}";
		css += "a:hover{color:" + colors.darken + "}";
		css += "a.preview,.buttonPreview{color:" + colors.normal + "}";
		css += "a.preview:hover,.buttonPreview{color:" + colors.darken + "}";
		// Couleur, polices, épaisseur et capitalisation de caractères des titres
		//css += "h1,h2,h3,h4,h5,h6{color:" + $("#themeTitleTextColor").val() + ";font-family:'" + titleFont.replace(/\+/g, " ") + "',sans-serif;font-weight:" + $("#themeTitleFontWeight").val() + ";text-transform:" + $("#themeTitleTextTransform").val() + "}";
		css += "h1.preview,h3.preview{color:" + $("#themeTitleTextColor").val() + ";font-family:'" + titleFont.replace(/\+/g, " ") + "',sans-serif;font-weight:" + $("#themeTitleFontWeight").val() + ";text-transform:" + $("#themeTitleTextTransform").val() + "}";
		// Police de caractères
		//css += "body{font-family:'" + textFont.replace(/\+/g, " ") + "',sans-serif}";
		// Police + couleur
		css += "p.preview{color:" + $("#themeTextTextColor").val() + ";font-family:'" + textFont.replace(/\+/g, " ") + "',sans-serif}";
		css += "a.preview,.buttonPreview{font-family:'" + textFont.replace(/\+/g, " ") + "',sans-serif}";
		// Taille du texte
		//css += "body,.row > div{font-size:" + $("#themeTextFontSize").val() + "}";
		// Couleur du texte
		//css += "body,.block h4,input[type='email'],input[type='text'],input[type='password'],.inputFile,select,textarea,.inputFile,.button.buttonGrey,.button.buttonGrey:hover{color:" + $("#themeTextTextColor").val() + "}";
		css += "p.preview,.buttonPreview{color:" + $("#themeTextTextColor").val() + "}";
		// Largeur du site
		css += ".container{max-width:" + $("#themeSiteWidth").val() + "}";
		if ($("#themeSiteWidth").val() === "100%") {
			css += "#site{margin:0 auto;} body{margin:0 auto;}  #bar{margin:0 auto;} body > header{margin:0 auto;} body > nav {margin: 0 auto;} body > footer {margin:0 auto;}";
		} else {
			css += "#site{margin:20px auto !important;} body{margin:0px 10px;}  #bar{margin: 0 -10px;} body > header{margin: 0 -10px;} body > nav {margin: 0 -10px;} body > footer {margin: 0 -10px;} ";
			
		}
		// Couleur du site, arrondi sur les coins du site et ombre sur les bords du site
		//css += "#site{background-color:" + $("#themeSiteBackgroundColor").val() + ";border-radius:" + $("#themeSiteRadius").val() + ";box-shadow:" + $("#themeSiteShadow").val() + " #212223}";
		css += "#site{border-radius:" + $("#themeSiteRadius").val() + ";box-shadow:" + $("#themeSiteShadow").val() + " #212223}";
		css += "div.preview{background-color:" + $("#themeSiteBackgroundColor").val() + ";}";
		// Ajout du css au DOM
		$("#themePreview").remove();
		$("<style>")
			.attr("type", "text/css")
			.attr("id", "themePreview")
			.text(css)
			.appendTo("head");

}
