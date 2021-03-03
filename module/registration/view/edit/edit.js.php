/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/  
 */

/**
 * Droits des groupes
 */
$("#registrationUserEditGroup").on("change", function() {
	$(".registrationUserEditGroupDescription").hide();
	$("#registrationUserEditGroupDescription" + $(this).val()).show();
}).trigger("change");
