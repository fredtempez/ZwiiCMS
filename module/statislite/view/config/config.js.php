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
 * Confirmation de réinitialisation
 */
$(".configInitJson").on("click", function() {
	var _this = $(this);
	return core.confirm("Êtes-vous sûr de vouloir réinitialiser, remettre à 0, toutes les statistiques ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});