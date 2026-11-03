/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

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
		$("#blogEditCommentGroupNotificationWrapper").slideDown();
	} else {
		$("#blogEditCommentGroupNotificationWrapper").slideUp();
	}
});


$( document).ready(function() {

	if ($("#blogEditCommentClose").is(':checked') ) {
		$(".commentOptionsWrapper").slideUp();
	} else {
		$(".commentOptionsWrapper").slideDown();
	}

	if ($("#blogEditCommentNotification").is(':checked') ) {
		$("#blogEditCommentGroupNotificationWrapper").slideDown();
	} else {
		$("#blogEditCommentGroupNotificationWrapper").slideUp();
	}

	// Permalink
	$('#blogEditTitle').furl({id:'blogEditPermalink', seperate: '_' });

});
