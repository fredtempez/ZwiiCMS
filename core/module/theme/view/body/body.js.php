/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

/**
 * Affichage de l'icone de remontée et permettre l'aperçu.
 */
$(document).ready(function () {
	$("#backToTop").css("display", "show");
});

/**
 * Aperçu en direct
 */
$("input, select").on("change", function () {

	// Option fixe pour contain et cover
	var themeBodyImageSize = $("#themeBodyImageSize").val();

	if (themeBodyImageSize === "cover" ||
		themeBodyImageSize === "contain") {
		$("#themeBodyImageAttachment").val("fixed");
	}

	// Couleur du fond
	var css = "html{background-color:" + $("#themeBodyBackgroundColor").val() + "}";
	// Image du fond
	var themeBodyImage = $("#themeBodyImage").val();
	if (themeBodyImage) {
		css += "html{background-image:url('<?php echo helper::baseUrl(false); ?>site/file/source/" + themeBodyImage + "');background-repeat:" + $("#themeBodyImageRepeat").val() + ";background-position:" + $("#themeBodyImagePosition").val() + ";background-attachment:" + $("#themeBodyImageAttachment").val() + ";background-size:" + $("#themeBodyImageSize").val() + "}";
		css += "html{background-color:rgba(0,0,0,0);}";
	}
	else {
		css += "html{background-image:none}";
	}
	css += '#backToTop {background-color:' + $("#themeBodyToTopBackground").val() + ';color:' + $("#themeBodyToTopColor").val() + ';}';

	// Ajout du css au DOM
	$("#themePreview").remove();
	$("<style>")
		.attr("type", "text/css")
		.attr("id", "themePreview")
		.text(css)
		.appendTo("head");
});
// Affiche / Cache les options de l'image du fond
$("#themeBodyImage").on("change", function () {
	if ($(this).val()) {
		$("#themeBodyImageOptions").slideDown();
	}
	else {
		$("#themeBodyImageOptions").slideUp();
	}
}).trigger("change");

