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
 * Incrémente les stats
 */
$('#downloadItemFile').click(function() {
    $('#downloadStats').html(function(i, val) { return val*1+1 });
});

/**
 * Affiche le bloc pour rédiger un commentaire
 */
var commentShowDOM = $("#downloadItemCommentShow");
commentShowDOM.on("click focus", function() {
	$("#downloadItemCommentShowWrapper").fadeOut(function() {
		$("#downloadItemCommentWrapper").fadeIn();
		$("#downloadItemCommentContent").trigger("focus");
	});
});
if($("#downloadItemCommentWrapper").find("textarea.notice,input.notice").length) {
	commentShowDOM.trigger("click");
}

/**
 * Cache le bloc pour rédiger un commentaire
 */
$("#downloadItemCommentHide").on("click focus", function() {
	$("#downloadItemCommentWrapper").fadeOut(function() {
		$("#downloadItemCommentShowWrapper").fadeIn();
		$("#downloadItemCommentContent").val("");
		$("#downloadItemCommentAuthor").val("");
	});
});

/**
 * Force le scroll vers les commentaires en cas d'erreur
 */
$("#downloadItemCommentForm").on("submit", function() {
	$(location).attr("href", "#comment");
});