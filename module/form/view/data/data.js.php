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
 * Confirmation de suppression
 */
$(".formDataDelete").on("click", function() {
	var _this = $(this);
	var message = "<?php
echo helper::translate('Supprimer cet enregistrement ?'); ?>";
	return core.confirm(message, function() {
		$(location).attr("href", _this.attr("href"));
	});
});

/**
 * Confirmation de suppression de toutes les donénes
 */
$(".formDataDeleteAll").on("click", function() {
	var _this = $(this);
	var message = "<?php
echo helper::translate('Supprimer tous les enregistrements ?'); ?>";
	return core.confirm(message, function() {
		$(location).attr("href", _this.attr("href"));
	});
});
