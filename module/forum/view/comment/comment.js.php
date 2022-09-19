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
 * Confirmation de suppression
 */
$(".blogCommentDelete").on("click", function() {
	var _this = $(this);
	var nom = "<?php echo $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'title' ]); ?>";
	return core.confirm("Supprimer le Réponse de l'sujet " + nom + " ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});

/**
 * Confirmation d'approbation
 */
$(".blogCommentApproved").on("click", function() {
	var _this = $(this);
	var nom = "<?php echo $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'title' ]); ?>";
	return core.confirm("Approuver le Réponse de l'sujet " + nom + " ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});

/**
 * Confirmation de rejet
 */
$(".blogCommentRejected").on("click", function() {
	var _this = $(this);
	var nom = "<?php echo $this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'title' ]); ?>";
	return core.confirm("Rejeter le Réponse de l'sujet " + nom + " ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});

/**
 * Confirmation de suppression en masse
 */
$(".blogCommentDeleteAll").on("click", function() {
	var _this = $(this);
	var nombre = "<?php echo count($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(2), 'comment' ])); ?>";
	var nom = "<?php echo $this->getData(['module', $this->getUrl(0), $this->getUrl(2), 'title' ]); ?>";
	if( nombre === "1"){
		var message = "Supprimer le Réponse de l'sujet " + nom + " ?";
	} else {
		var message = "Supprimer les " + nombre + " réponses de l'sujet " + nom + " ?";
	}
	return core.confirm(message, function() {
		$(location).attr("href", _this.attr("href"));
	});
});