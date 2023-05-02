/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
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

$(document).ready(function(){
	// Membre avec ou sans gestion de fichiers
	if($("#userEditGroup").val() === '1') {
		$("#userEditMemberFiles").slideDown();
	}
	else {
		$("#userEditMemberFiles").slideUp(function() {
			$("#userEditFiles").prop("checked", false).trigger("change");
		});
	}
});

$("#userEditGroup").on("change", function() {
	// Membre avec ou sans gestion de fichiers
	if($("#userEditGroup").val() === '1') {
		$("#userEditMemberFiles").slideDown();
	}
	else {
		$("#userEditMemberFiles").slideUp(function() {
			$("#userEditFiles").prop("checked", false).trigger("change");
		});
	}
}).trigger("change");
