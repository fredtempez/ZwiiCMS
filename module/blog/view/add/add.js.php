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
$("#blogAddDraft").on("click", function() {
	$("#blogAddState").val(0);
	$("#blogAddForm").trigger("submit");
});

/**
 * Options de commentaires
 */
$("#blogAddCloseComment").on("change", function() {
	if ($(this).is(':checked') ) {
		$("#commentOptionsWrapper").slideUp();
	} else {
		$("#commentOptionsWrapper").slideDown();
	}
});

$("#blogAddMailNotification").on("change", function() {
	if ($(this).is(':checked') ) {
		$("#blogAddGroupNotification").slideDown();
	} else {
		$("#blogAddGroupNotification").slideUp();
	}
});


$( document).ready(function() {

	if ($("#blogAddCloseComment").is(':checked') ) {
		$("#commentOptionsWrapper").slideUp();
	} else {
		$("#commentOptionsWrapper").slideDown();
	}

	if ($("#blogAddMailNotification").is(':checked') ) {
		$("#blogAddGroupNotification").slideDown();
	} else {
		$("#blogAddGroupNotification").slideUp();
	}

});