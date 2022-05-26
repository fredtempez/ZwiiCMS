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
 * Soumission du formulaire pour enregistrer en brouillon
 */
$("#blogAddDraft").on("click", function() {
	$("#blogAddState").val(0);
	$("#blogAddForm").trigger("submit");
});

/**
 * Options de réponses
 */
$("#blogAddCommentClose").on("change", function() {
	if ($(this).is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}
});

$("#blogAddCommentNotification").on("change", function() {
	if ($(this).is(':checked') ) {
		$("#blogAddCommentGroupNotification").slideDown();
	} else {
		$("#blogAddCommentGroupNotification").slideUp();
	}
});


$( document).ready(function() {

	if ($("#blogAddCloseComment").is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}

	if ($("#blogAddCommentNotification").is(':checked') ) {
		$("#blogAddCommentGroupNotification").slideDown();
	} else {
		$("#blogAddCommentGroupNotification").slideUp();
	}

	// Permalink
	$('#blogAddTitle').furl({id:'blogAddPermalink', seperate: '_' });
});