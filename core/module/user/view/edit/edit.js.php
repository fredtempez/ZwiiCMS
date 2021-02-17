/**
 * This file is part of Zwii. *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

/**
 * Droits des groupes
 */
$("#userEditGroup").on("change", function() {
	$(".userEditGroupDescription").hide();
	$("#userEditGroupDescription" + $(this).val()).show();
	if ($("#userEditGroup option:selected").val() < 0) {
		$("#userEditLabelAuth").css("display","none");
	} else {
		$("#userEditLabelAuth").css("display","inline-block");
	}
}).trigger("change");