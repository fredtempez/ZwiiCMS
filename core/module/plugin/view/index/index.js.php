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


 $(document).ready(function () {
	var configLayout = getCookie("pluginLayout");
    if (configLayout == null) {
        configLayout = "module";
        setCookie("pluginLayout", "module");
    }
    $("#moduleContainer").hide();
    $("#dataContainer").hide();
    $("#" + configLayout + "Container").show();
    $("#config" + capitalizeFirstLetter(configLayout) + "Button").addClass("activeButton");

 });



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

$("#configModuleButton").on("click", function () {
	console.log("clic");
	$("#dataContainer").hide();
	$("#moduleContainer").show();
	$("#configModuleButton").addClass("activeButton");
	$("#configDataButton").removeClass("activeButton");
	setCookie("pluginLayout", "module");
});
$("#configDataButton").on("click", function () {
	$("#moduleContainer").hide();
	$("#dataContainer").show();
	$("#configModuleButton").removeClass("activeButton");
	$("#configDataButton").addClass("activeButton");
	setCookie("pluginLayout", "data");
});




// Fonctions
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/; samesite=lax";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Define function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
