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
	var configLayout = getCookie("translateLayout");
    if (configLayout == null) {
        configLayout = "module";
        setCookie("translateLayout", "module");
    }
    $("#translateContainer").hide();
    $("#UIContainer").hide();
    $("#" + configLayout + "Container").show();
    $("#config" + capitalizeFirstLetter(translateLayout) + "Button").addClass("activeButton");

 });

 // Sélecteur de fonctions

$("#translateFormUIButton").on("click", function () {
	$("#translateContainer").hide();
	$("#UIContainer").show();
	$("#translateFormUIButton").addClass("activeButton");
	$("#translateFormContentButton").removeClass("activeButton");
	setCookie("translateLayout", "module");
});
$("#translateFormContentButton").on("click", function () {
	$("#translateContainer").hide();
	$("#dataContainer").show();
	$("#translateFormContentButton").removeClass("activeButton");
	$("#translateFormUIButton").addClass("activeButton");
	setCookie("translateLayout", "data");
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

