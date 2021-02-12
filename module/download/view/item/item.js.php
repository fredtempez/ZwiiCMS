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
 * Affiche le bloc pour rédiger un commentaire
 */
var commentShowDOM = $("#downloaditemCommentShow");
commentShowDOM.on("click focus", function() {
	$("#downloaditemCommentShowWrapper").fadeOut(function() {
		$("#downloaditemCommentWrapper").fadeIn();
		$("#downloaditemCommentContent").trigger("focus");
	});
});
if($("#downloaditemCommentWrapper").find("textarea.notice,input.notice").length) {
	commentShowDOM.trigger("click");
}

/**
 * Cache le bloc pour rédiger un commentaire
 */
$("#downloaditemCommentHide").on("click focus", function() {
	$("#downloaditemCommentWrapper").fadeOut(function() {
		$("#downloaditemCommentShowWrapper").fadeIn();
		$("#downloaditemCommentContent").val("");
		$("#downloaditemCommentAuthor").val("");
	});
});

/**
 * Force le scroll vers les commentaires en cas d'erreur
 */
$("#downloaditemCommentForm").on("submit", function() {
	$(location).attr("href", "#comment");
});