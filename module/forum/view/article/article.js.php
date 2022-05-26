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
 * Affiche le bloc pour rédiger un Réponse
 */
var commentShowDOM = $("#blogSujetCommentShow");
commentShowDOM.on("click focus", function() {
	$("#blogSujetCommentShowWrapper").fadeOut(function() {
		$("#blogSujetCommentWrapper").fadeIn();
		$("#blogSujetCommentContent").trigger("focus");
	});
});
if($("#blogSujetCommentWrapper").find("textarea.notice,input.notice").length) {
	commentShowDOM.trigger("click");
}

/**
 * Cache le bloc pour rédiger un Réponse
 */
$("#blogSujetCommentHide").on("click focus", function() {
	$("#blogSujetCommentWrapper").fadeOut(function() {
		$("#blogSujetCommentShowWrapper").fadeIn();
		$("#blogSujetCommentContent").val("");
		$("#blogSujetCommentAuthor").val("");
	});
});

/**
 * Force le scroll vers les réponses en cas d'erreur
 */
$("#blogSujetCommentForm").on("submit", function() {
	$(location).attr("href", "#comment");
});