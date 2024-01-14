/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

/**
 * Confirmation de suppression
 */
$(".newsConfigDelete").on("click", function() {
	var _this = $(this);
	var message = "<?php echo helper::translate('Supprimer cet article ?'); ?>";
	return core.confirm(message, function() {
		$(location).attr("href", _this.attr("href"));
	});
});