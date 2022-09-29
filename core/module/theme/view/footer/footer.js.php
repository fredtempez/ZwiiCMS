/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @link http://zwiicms.fr/
 */

/**
 * Aperçu en direct
 */
$("input, select").on("change", function () {
	// Import des polices de caractères
	var footerFont = $("#themeFooterFont :selected").val();
	var footerFontText = $("#themeFooterFont :selected").text();
	var css = "@import url('https://fonts.cdnfonts.com/css/" + footerFont + "');";
	// Couleurs du pied de page
	var colors = core.colorVariants($("#themeFooterBackgroundColor").val());
	var textColor = $("#themeFooterTextColor").val();
	var css = "footer {background-color:" + colors.normal + ";color:" + textColor + "}";
	css += "footer a{color:" + textColor + "}";
	// Couleur de l'éditeur
	css += ".editorWysiwyg{background-color:" + colors.normal + " !important; color:" + textColor + " !important;}";
	// Hauteur du pied de page
	//css += "#footersiteLeft, #footersiteCenter, #footersiteRight, #footerbodyLeft, #footerbodyCenter, #footerbodyRight {margin:" + $("#themeFooterHeight").val() + " 0}";
	css += "footer #footersite > div{margin:" + $("#themeFooterHeight").val() + " 0}";
	css += "footer #footerbody > div{margin:" + $("#themeFooterHeight").val() + " 0}";
	// Alignement du contenu
	css += "#footerSocials{text-align:" + $("#themeFooterSocialsAlign").val() + "}";
	css += "#footerText > p {text-align:" + $("#themeFooterTextAlign").val() + "}";
	css += "#footerCopyright{text-align:" + $("#themeFooterCopyrightAlign").val() + "}";
	// Taille, couleur, épaisseur et capitalisation du titre de la bannière
	css += "footer span, #footerText > p {color:" + $("#themeFooterTextColor").val() + ";font-family:'" + footerFontText + "',sans-serif;font-weight:" + $("#themeFooterFontWeight").val() + ";font-size:" + $("#themeFooterFontSize").val() + ";text-transform:" + $("#themeFooterTextTransform").val() + "}";
	// Marge
	if ($("#themeFooterMargin").is(":checked")) {
		css += 'footer{padding: 0 20px;}';
	}
	else {
		css += 'footer{padding:0}';
	}
	// Ajout du css au DOM
	$("#themePreview").remove();
	$("<style>")
		.attr("type", "text/css")
		.attr("id", "themePreview")
		.text(css)
		.appendTo("footer");
	// Position du pied de page
	switch ($("#themeFooterPosition").val()) {
		case 'hide':
			$("footer").hide();
			break;
		case 'site':
			$("footer").show().appendTo("#site");
			$("footer > div:first-child").removeAttr("class");
			$("footer > div:first-child").addClass("container");
			break;
		case 'body':
			$("footer").show().appendTo("body");
			$("footer > div:first-child").removeAttr("class");
			$("footer > div:first-child").addClass("container-large");
			break;
	}
	// Réduire la marge du paragraphe de la zone de texte enrichie
	$("#footerText > p").css("margin-top", "0");
	$("#footerText > p").css("margin-bottom", "0");
});



// Position dans les blocs
// Bloc texte personnalisé
$(".themeFooterContent").on("change", function () {
	// Position site ou body
	var footerPosition = $("#themeFooterPosition").val();
	switch ($("#themeFooterTextPosition").val()) {
		case "hide":
			$("#footerText").hide();
			break;
		default:
			// Choix de la position du bloc
			textPosition = $("#themeFooterTextPosition").val();
			textPosition = textPosition.substr(0, 1).toUpperCase() + textPosition.substr(1);
			$("#footerText").show().appendTo("#footer" + footerPosition + textPosition);
			break;
	}
	switch ($("#themeFooterSocialsPosition").val()) {
		case 'hide':
			$("#footerSocials").hide();
			break;
		default:
			// Choix de la position du bloc
			socialsPosition = $("#themeFooterSocialsPosition").val();
			socialsPosition = socialsPosition.substr(0, 1).toUpperCase() + socialsPosition.substr(1);
			$("#footerSocials").show().appendTo("#footer" + footerPosition + socialsPosition);
			break;
	}
	switch ($("#themeFooterCopyrightPosition").val()) {
		case 'hide':
			$("#footerCopyright").hide();
			break;
		default:
			// Choix de la position du bloc
			copyrightPosition = $("#themeFooterCopyrightPosition").val();
			copyrightPosition = copyrightPosition.substr(0, 1).toUpperCase() + copyrightPosition.substr(1);
			$("#footerCopyright").show().appendTo("#footer" + footerPosition + copyrightPosition);
			break;
	}


}).trigger("change");

// Fin Position dans les blocs

// Modification dynamique de la mise en page
$("#themeFooterTemplate").on("change", function () {
	// Nettoyage des sélecteurs des contenus
	var newOptions = {
		4: { 'hide': 'Masqué', 'left': 'En haut', 'center': 'Au milieu', 'right': 'En bas' },
		3: { 'hide': 'Masqué', 'left': 'A gauche', 'center': 'Au centre', 'right': 'A droite' },
		2: { 'hide': 'Masqué', 'left': 'A gauche', 'right': 'A droite' },
		1: { 'hide': 'Masqué', 'center': 'Affiché' }
	};
	var $el = $(".themeFooterContent");
	$el.empty();
	// Eléments des position de contenus
	$.each(newOptions[$("#themeFooterTemplate").val()], function (key, value) {
		$el.append($("<option></option>")
			.attr("value", key).text(value));
	});
	var position = $("#themeFooterPosition").val();
	// Masquer les contenus
	$("#footerCopyright").hide();
	$("#footerText").hide();
	$("#footerSocials").hide();
	// Dimension des blocs
	switch ($("#themeFooterTemplate").val()) {
		case "1":
			$("#footer" + position + "Left").css("display", "none");
			$("#footer" + position + "Center").removeAttr('class').addClass("col12").css("display", "");
			$("#footer" + position + "Right").css("display", "none");
			break;
		case "2":
			$("#footer" + position + "Left").removeAttr('class').addClass('col6').css("display", "");
			$("#footer" + position + "Center").css("display", "none").removeAttr('class');
			$("#footer" + position + "Right").removeAttr('class').addClass('col6').css("display", "");
			break;
		case "3":
			$("#footer" + position + "Left").removeAttr('class').addClass('col4').css("display", "");
			$("#footer" + position + "Center").removeAttr('class').addClass('col4').css("display", "");
			$("#footer" + position + "Right").removeAttr('class').addClass('col4').css("display", "");
			break;
		case "4":
			$("#footer" + position + "Left").removeAttr('class').addClass('col12').css("display", "");
			$("#footer" + position + "Center").removeAttr('class').addClass('col12').css("display", "");
			$("#footer" + position + "Right").removeAttr('class').addClass('col12').css("display", "");
			break;

	}
});


// Désactivation des sélections multiples
$("#themeFooterSocialsPosition").on("change", function () {
	if ($(this).prop('selectedIndex') >= 1) {
		if ($("#themeFooterTextPosition").prop('selectedIndex') === $(this).prop('selectedIndex')) {
			$("#themeFooterTextPosition").prop('selectedIndex', 0);
			$("#footerText").hide();
		}
		if ($("#themeFooterCopyrightPosition").prop('selectedIndex') === $(this).prop('selectedIndex')) {
			$("#themeFooterCopyrightPosition").prop('selectedIndex', 0);
			$("#footerCopyright").hide();
		}
	}
}).trigger("change");
$("#themeFooterTextPosition").on("change", function () {
	if ($(this).prop('selectedIndex') >= 1) {
		if ($("#themeFooterSocialsPosition").prop('selectedIndex') === $(this).prop('selectedIndex')) {
			$("#themeFooterSocialsPosition").prop('selectedIndex', 0);
			$("#footerSocials").hide();
		}
		if ($("#themeFooterCopyrightPosition").prop('selectedIndex') === $(this).prop('selectedIndex')) {
			$("#themeFooterCopyrightPosition").prop('selectedIndex', 0);
			$("#footerCopyright").hide();
		}
	}
}).trigger("change");

$("#themeFooterCopyrightPosition").on("change", function () {
	if ($(this).prop('selectedIndex') >= 1) {
		if ($("#themeFooterTextPosition").prop('selectedIndex') === $(this).prop('selectedIndex')) {
			$("#themeFooterTextPosition").prop('selectedIndex', 0);
			$("#footerText").hide();
		}
		if ($("#themeFooterSocialsPosition").prop('selectedIndex') === $(this).prop('selectedIndex')) {
			$("#themeFooterSocialsPosition").prop('selectedIndex', 0);
			$("#footerSocials").hide();
		}
	}
}).trigger("change");
// Affiche / Cache les options du footer fixe
$("#themeFooterPosition").on("change", function () {
	if ($(this).val() === 'body') {
		$("#themeFooterPositionFixed").slideDown();
	}
	else {
		$("#themeFooterPositionFixed").slideUp(function () {
			$("#themeFooterFixed").prop("checked", false).trigger("change");
		});
	}
}).trigger("change");

// Lien de connexion
$("#themeFooterLoginLink").on("change", function () {
	if ($(this).is(":checked")) {
		$("#footerLoginLink").show();
	}
	else {
		$("#footerLoginLink").hide();
	}
}).trigger("change");

// Numéro de version
$("#themefooterDisplayVersion").on("change", function () {
	if ($(this).is(":checked")) {
		$("#footerDisplayVersion").show();
	}
	else {
		$("#footerDisplayVersion").hide();
	}
}).trigger("change");

// Numéro de version
$("#themefooterDisplayCopyright").on("change", function () {
	if ($(this).is(":checked")) {
		$("#footerDisplayCopyright").show();
	}
	else {
		$("#footerDisplayCopyright").hide();
	}
}).trigger("change");

// Site Map
$("#themefooterDisplaySiteMap").on("change", function () {
	if ($(this).is(":checked")) {
		$("#footerDisplaySiteMap").show();
	}
	else {
		$("#footerDisplaySiteMap").hide();
	}
}).trigger("change");

// Rechercher
$("#themeFooterDisplaySearch").on("change", function () {
	if ($(this).is(":checked")) {
		$("#footerDisplaySearch").show();
	}
	else {
		$("#footerDisplaySearch").hide();
	}
}).trigger("change");

// Mentions légales
$("#themeFooterDisplayLegal").on("change", function () {
	if ($(this).is(":checked")) {
		$("#footerDisplayLegal").show();
	}
	else {
		$("#footerDisplayLegal").hide();
	}
}).trigger("change");


// Pages spéciales  : activation si une page est sélectionnée
$("#configLegalPageId").on("change", function () {
	if ($("#configLegalPageId option:selected").text() === 'Aucune') {
		$("#themeFooterDisplayLegal").prop('checked', false);
		$("#themeFooterDisplayLegal").prop("disabled", true);
		$("#footerDisplayLegal").hide();
	} else {
		$("#themeFooterDisplayLegal").prop("disabled", false);
	}
}).trigger("change");
$("#configSearchPageId").on("change", function () {
	if ($("#configSearchPageId option:selected").text() === 'Aucune') {
		$("#themeFooterDisplaySearch").prop('checked', false);
		$("#themeFooterDisplaySearch").prop("disabled", true);
		$("#footerDisplaySearch").hide();
	} else {
		$("#themeFooterDisplaySearch").prop("disabled", false);
	}
}).trigger("change");


/*
// Affiche / Cache les options de la position
$("#themeFooterPosition").on("change", function() {
	if($(this).val() === 'site') {
		$("#themeFooterPositionOptions").slideDown();
	}
	else {
		$("#themeFooterPositionOptions").slideUp(function() {
			$("#themeFooterMargin").prop("checked", false).trigger("change");
		});
	}
}).trigger("change");
*/
