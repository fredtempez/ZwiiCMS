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
$(".moduleDelete").on("click", function() {
	var _this = $(this);
	return core.confirm("Êtes-vous sûr de vouloir désinstaller ce module ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});

/**
 * Confirmation de suppression
 */
 $(".dataDelete").on("click", function() {
	var _this = $(this);
	return core.confirm("Êtes-vous sûr de vouloir supprimer le module de cette page ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});

// Sélecteur de fonctions

$("#configManageModuleButton").on("click", function () {
	console.log("clic");
	$("#manageDatas").hide();
	$("#manageModules").show();
	$("#configManageModuleButton").addClass("activeButton");
	$("#configManageDatasButton").removeClass("activeButton");
	setCookie("pluginLayout", "module");
});
$("#configManageDatasButton").on("click", function () {
	$("#manageModules").hide();
	$("#manageDatas").show();
	$("#configManageModuleButton").removeClass("activeButton");
	$("#configManageDatasButton").addClass("activeButton");
	setCookie("pluginLayout", "data");
});
