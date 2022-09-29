/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */


/**
 * Confirmation de suppression
 */
$(".themeFontDelete").on("click", function () {
	var _this = $(this);
	return core.confirm("Êtes-vous sûr de vouloir supprimer cette fonte ?", function () {
		$(location).attr("href", _this.attr("href"));
	});
});