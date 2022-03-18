/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */


// Lien de connexion
$("#downloadEditMailNotification").on("change", function() {
	if($(this).is(":checked")) {
		$("#formConfigGroup").show();
	}
	else {
		$("#formConfigGroup").hide();
	}
}).trigger("change");


/**
 * Soumission du formulaire pour enregistrer en brouillon
 */
$("#downloadEditDraft").on("click", function() {
	$("#downloadEditState").val(0);
	$("#downloadEditForm").trigger("submit");
});

/**
 * Options de commentaires
 */
$("#downloadEditCommentClose").on("change", function() {
	if ($(this).is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}
});

$("#downloadEditCommentNotification").on("change", function() {
	if ($(this).is(':checked') ) {
		$("#downloadEditCommentGroupNotification").slideDown();
	} else {
		$("#downloadEditCommentGroupNotification").slideUp();
	}
});


$( document).ready(function() {

	/** Gestion des commentaires */

	if ($("#downloadEditCloseComment").is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}

	if ($("#downloadEditCommentNotification").is(':checked') ) {
		$("#downloadEditCommentGroupNotification").slideDown();
	} else {
		$("#downloadEditCommentGroupNotification").slideUp();
	}


	/**
	 * Paramétrage du sélecteur de date
	 * Supprimer les heures

	const datepickr = flatpickr("#downloadEditversionDate", {});
	datepickr.set (enableTime, false);
 */
});