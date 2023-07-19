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


$(document).ready(function() {
    var translateLayout = getCookie("translateLayout");
    if (translateLayout == null) {
        translateLayout = "content";
        setCookie("translateLayout", "content");

    }
    $("#contentContainer").hide();
    $("#uiContainer").hide();
    $("#" + translateLayout + "Container").show();
    $("#translate" + capitalizeFirstLetter(translateLayout) + "Button").addClass("activeButton");


});

// Sélecteur de fonctions

$("#translateUiButton").on("click", function() {
    $("#contentContainer").hide();
    $("#uiContainer").show();
    $(this).addClass("activeButton");
    $("#translateContentButton").removeClass("activeButton");
    setCookie("translateLayout", "ui");

});
$("#translateContentButton").on("click", function() {
    $("#uiContainer").hide();
    $("#contentContainer").show();
    $(this).addClass("activeButton");
    $("#translateUiButton").removeClass("activeButton");
    setCookie("translateLayout", "content");
    // Afficher les boutons liés au contenu
    $(".contentButtonContainer").show();
});

/**
 * Confirmation de suppression
 */
$(".translateDelete").on("click", function() {
    var _this = $(this);
    var message_delete = "<?php echo helper::translate('Confirmer la suppression de cette langue'); ?>";
    return core.confirm(message_delete, function() {
        $(location).attr("href", _this.attr("href"));
    });
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