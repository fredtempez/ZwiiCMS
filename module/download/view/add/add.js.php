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

/**
 * Soumission du formulaire pour enregistrer en brouillon
 */
$("#downloadAddDraft").on("click", function() {
	$("#downloadAddState").val(0);
	$("#downloadAddForm").trigger("submit");
});

/**
 * Options de commentaires
 */
$("#downloadAddCommentClose").on("change", function() {
	if ($(this).is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}
});

$("#downloadAddCommentNotification").on("change", function() {
	if ($(this).is(':checked') ) {
		$("#downloadAddCommentGroupNotification").slideDown();
	} else {
		$("#downloadAddCommentGroupNotification").slideUp();
	}
});


$( document).ready(function() {

	if ($("#downloadAddCloseComment").is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}

	if ($("#downloadAddCommentNotification").is(':checked') ) {
		$("#downloadAddCommentGroupNotification").slideDown();
	} else {
		$("#downloadAddCommentGroupNotification").slideUp();
	}
});