/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.com/  
 */

/**
 * Droits des groupes
 */
$("#registrationUserEditGroup").on("change", function() {
	$(".registrationUserEditGroupDescription").hide();
	$("#registrationUserEditGroupDescription" + $(this).val()).show();
}).trigger("change");
