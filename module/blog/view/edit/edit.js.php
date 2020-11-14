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
$("#blogEditMailNotification").on("change", function() {
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
$("#blogEditDraft").on("click", function() {
	$("#blogEditState").val(0);
	$("#blogEditForm").trigger("submit");
});

/**
 * Options de commentaires
 */
$("#blogEditCommentClose").on("change", function() {
	if ($(this).is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}
});

$("#blogEditCommentNotification").on("change", function() {
	if ($(this).is(':checked') ) {
		$("#blogEditCommentGroupNotification").slideDown();
	} else {
		$("#blogEditCommentGroupNotification").slideUp();
	}
});


$( document).ready(function() {

	if ($("#blogEditCloseComment").is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}

	if ($("#blogEditCommentNotification").is(':checked') ) {
		$("#blogEditCommentGroupNotification").slideDown();
	} else {
		$("#blogEditCommentGroupNotification").slideUp();
	}
});