/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Fred Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

 /**
 * Aperçu en direct
 */
$("input, select").on("change", function() {
    
    var titleFont = $("#adminFontTitle").val();
    var textFont = $("#adminFontText").val();
    var css = "@import url('https://fonts.googleapis.com/css?family=" + titleFont + "|" + textFont + "');";
    var colors = core.colorVariants($("#adminBackgroundColor").val());
    var css = "#site{background-color:" + colors.normal + ";}";
    css += "body, .row > div {font:" + $("#adminFontTextSize").val() + " '" + $("#adminColorTitle").val()  + "', sans-serif;}";
    css += "body h1, h2, h3, h4, h5, h6 {font-family:'" +  titleFont + "', sans-serif; color:" + $("#adminColorTitle").val() + ";}";
    css += "body:not(.editorWysiwyg),span .zwiico-help {color:" + colors.text + ";}";
    css += "input[type=email],input[type=text],input[type=password],select,textarea:not(.editorWysiwyg),.inputFile{color: #222;}";
    var colors = core.colorVariants($("#adminColorButton").val());
    css += ".button,input[type='checkbox']:checked + label::before,.speechBubble{ background-color:" + colors.normal + "; color:" + $("#adminColorButtonText").val() + ";}";    
    css += ".speechBubble::before {border-color:" + colors.normal + " transparent transparent transparent;}";
    css += ".button:hover, button[type=submit]:hover { background-color:" + colors.darken + ";}";
    var colors = core.colorVariants($("#adminColorGrey").val());    
    css += ".button.buttonGrey {background: " + colors.normal + ";color:" + $("#adminColorButtonText").val() + ";}.button.buttonGrey:hover {background:" + colors.darken + ";color:" + $("#adminColorButtonText").val() + "}.button.buttonGrey:active {background:" + colors.veryDarken + ";color:" + $("#adminColorButtonText").val() + ";}";
    var colors = core.colorVariants($("#adminColorRed").val());    
    css += ".button.buttonRed {background: " + colors.normal + ";color:" + $("#adminColorButtonText").val() + ";}.button.buttonRed:hover {background:" + colors.darken + ";color:" + $("#adminColorButtonText").val() + "}.button.buttonRed:active {background:" + colors.veryDarken + ";color:" + $("#adminColorButtonText").val() + "}";
    var colors = core.colorVariants($("#adminColorGreen").val());    
    css += "button[type=submit] {background-color: " + colors.normal + ";color: " + ";color:" + $("#adminColorButtonText").val() + "}button[type=submit]:hover {background-color: " + colors.darken +  ";color:" + $("#adminColorButtonText").val() + ";}button[type=submit]:active {background:" + colors.veryDarken + ";color:" + $("#adminColorButtonText").val() + "}";
    var colors = core.colorVariants($("#adminBackGroundBlockColor").val());  
    css += ".block {border: 1px solid " + $("#adminBorderBlockColor").val() + ";}.block h4 {background-color: "  + colors.normal + ";color:" + colors.text + ";}";

	// Ajout du css au DOM
	$("#themePreview").remove();
	$("<style>")
		.attr("type", "text/css")
		.attr("id", "themePreview")
		.text(css)
		.appendTo("head");

});
