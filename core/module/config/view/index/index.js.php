/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */


$(document).ready(function () {

    /**
     * Confirmation de suppression
     */
    // Message de mise à jour (icône dans l'onglet Configuration)
    $(document).ready(function () {
        $("#configUpdateMessageIcon").on("click", function (e) {
            e.preventDefault();
            $("#configUpdateMessageModal").css("display", "flex");
        });
        $("#configUpdateMessageClose").on("click", function (e) {
            e.preventDefault();
            $("#configUpdateMessageModal").css("display", "none");
        });
        $("#configUpdateMessageModal").on("click", function (e) {
            if (e.target === this) {
                $(this).css("display", "none");
            }
        });
    });

    $("#configBackupDelButton").on("click", function () {
        var _this = $(this);
        var message_warning = "<?php
echo Helper::translate('Supprimer toutes les sauvegardes automatiques ?'); ?>";
        return core.confirm(message_warning, function () {
            $(location).attr("href", _this.attr("href"));
        });
    });

    // Positionnement inital des options
    //-----------------------------------------------------------------------------------------------------

    /**
     * Afficher et masquer options smtp
     */
    if ($("input[name=smtpEnable]").is(':checked')) {
        $("#smtpParam").addClass("disabled");
        $("#smtpParam").slideDown();
    } else {
        $("#smtpParam").removeClass("disabled");
        $("#smtpParam").slideUp();
    }
    /**
     * Afficher et masquer options Auth
     */

    if ($("select[name=smtpAuth]").val() == true) {
        $("#smtpAuthParam").addClass("disabled");
        $("#smtpAuthParam").slideDown();
    } else {
        $("#smtpAuthParam").removeClass("disabled");
        $("#smtpAuthParam").slideUp();
    }

    /**
     * Afficher et masquer les options de captcha
     */

    if ($("input[name=connectCaptcha]").is(':checked')) {
        $("#connectCaptchaStrongWrapper").addClass("disabled");
        $("#connectCaptchaStrongWrapper").slideDown();
        $("#connectCaptchaTypeWrapper").addClass("disabled");
        $("#connectCaptchaTypeWrapper").slideDown();
    } else {
        $("#connectCaptchaStrongWrapper").removeClass("disabled");
        $("#connectCaptchaStrongWrapper").slideUp();
        $("#connectCaptchaTypeWrapper").removeClass("disabled");
        $("#connectCaptchaTypeWrapper").slideUp();
        $("#connectCaptchaStrong").prop("checked", false);
    }

    // Récupérer la valeur du champ caché containerSelected
    let configLayout = $('input[name="containerSelected"]').val();
    // Si non défini, valeur par défaut
    if (!configLayout) {
        configLayout = "setup";
    }

    $("#localeContainer").hide();
    $("#socialContainer").hide();
    $("#connectContainer").hide();
    $("#networkContainer").hide();
    $("#setupContainer").hide();
    $("#" + configLayout + "Container").show();
    $("#config" + capitalizeFirstLetter(configLayout) + "Button").addClass("activeButton");


    // Gestion des événements
    //---------------------------------------------------------------------------------------------------------------------



    /**
     * Transmet le bouton de l'onglet sélectionné avant la soumission
     */

    // Mettre à jour le champ caché avant la soumission
    $('#configForm').on('submit', function () {
        $('#containerSelected').val(configLayout);
    });

    /**
     * Afficher et masquer options smtp
     */
    $("input[name=smtpEnable]").on("change", function () {
        if ($("input[name=smtpEnable]").is(':checked')) {
            $("#smtpParam").addClass("disabled");
            $("#smtpParam").slideDown();
        } else {
            $("#smtpParam").removeClass("disabled");
            $("#smtpParam").slideUp();
        }
    });

    /**
     * Afficher et masquer options Auth
     */

    $("select[name=smtpAuth]").on("change", function () {
        if ($("select[name=smtpAuth]").val() == true) {
            $("#smtpAuthParam").addClass("disabled");
            $("#smtpAuthParam").slideDown();
        } else {
            $("#smtpAuthParam").removeClass("disabled");
            $("#smtpAuthParam").slideUp();
        }
    });

    /**
     * Options de blocage de connexions
     * Contrôle la cohérence des sélections et interdit une seule valeur Aucune
     */
    $("select[name=connectAttempt]").on("change", function () {
        if ($("select[name=connectAttempt]").val() === "999") {
            $("select[name=connectTimeout]").val(0);
        } else {
            if ($("select[name=connectTimeout]").val() === "0") {
                $("select[name=connectTimeout]").val(300);
            }
        }
    });
    $("select[name=connectTimeout]").on("change", function () {
        if ($("select[name=connectTimeout]").val() === "0") {
            $("select[name=connectAttempt]").val(999);
        } else {
            if ($("select[name=connectAttempt]").val() === "999") {
                $("select[name=connectAttempt]").val(3);
            }
        }
    });

    /**
     * Captcha strong si captcha sélectionné
     */
    $("input[name=connectCaptcha]").on("change", function () {

        if ($("input[name=connectCaptcha]").is(':checked')) {
            $("#connectCaptchaStrongWrapper").addClass("disabled");
            $("#connectCaptchaStrongWrapper").slideDown();
            $("#connectCaptchaTypeWrapper").addClass("disabled");
            $("#connectCaptchaTypeWrapper").slideDown();

        } else {
            $("#connectCaptchaStrongWrapper").removeClass("disabled");
            $("#connectCaptchaStrongWrapper").slideUp();
            $("#connectCaptchaTypeWrapper").removeClass("disabled");
            $("#connectCaptchaTypeWrapper").slideUp();
            $("#connectCaptchaStrong").prop("checked", false);
        }
    });


    /**
     *  Sélection de la  page de configuration à afficher
     */
    $("#configLocaleButton").on("click", function () {
        $("#setupContainer").hide();
        $("#socialContainer").hide();
        $("#connectContainer").hide();
        $("#networkContainer").hide();
        $("#localeContainer").show();
        configLayout = "locale";
        $("#configLocaleButton").addClass("activeButton");
        $("#configSetupButton").removeClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
    });
    $("#configSetupButton").on("click", function () {
        $("#localeContainer").hide();
        $("#socialContainer").hide();
        $("#connectContainer").hide();
        $("#networkContainer").hide();
        $("#setupContainer").show();
        configLayout = "setup";
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSetupButton").addClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
    });
    $("#configSocialButton").on("click", function () {
        $("#localeContainer").hide();
        $("#connectContainer").hide();
        $("#setupContainer").hide();
        $("#networkContainer").hide();
        $("#socialContainer").show();
        configLayout = "social";
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSetupButton").removeClass("activeButton");
        $("#configSocialButton").addClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
    });
    $("#configConnectButton").on("click", function () {
        $("#localeContainer").hide();
        $("#setupContainer").hide();
        $("#socialContainer").hide();
        $("#networkContainer").hide();
        $("#connectContainer").show();
        configLayout = "connect";
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSetupButton").removeClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").addClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
    });
    $("#configNetworkButton").on("click", function () {
        $("#localeContainer").hide();
        $("#setupContainer").hide();
        $("#socialContainer").hide();
        $("#connectContainer").hide();
        $("#networkContainer").show();
        configLayout = "network";
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSetupButton").removeClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").addClass("activeButton");
    });


    /**
     * Aspect de la souris
     */
    $("#socialMetaImage, #socialSiteMap, #configBackupCopyButton").click(function (event) {
        $('body, .button').css('cursor', 'wait');
    });


    // Mise en évidence des erreurs de saisie dans les boutons de sélection
    var containers = ["setup", "locale", "social", "connect", "network"];
    $.each(containers, function (index, value) {
        var a = $("div#" + value + "Container").find("input.notice").not(".displayNone");
        if (a.length > 0) {
            $("#config" + capitalizeFirstLetter(value) + "Button").addClass("buttonNotice");
        } else {
            $("#config" + capitalizeFirstLetter(value) + "Button").removeClass("buttonNotice");
        }
    });

    // Contrôle l'image Open Screen Graph
    // Type d'image
    $("span#screenType").each(function () {
        var text = $(this).text();
        if (text.includes("jpg") || text.includes("jpeg") || text.includes("png")) {
            $(this).css("color", "green");
        } else {
            $(this).css("color", "red");
        }
    });
    // La largeur
    $("span#screenWide").each(function () {
        var screenId = parseInt($(this).text());
        if (screenId >= 1200) {
            $(this).css("color", "green");
        } else {
            $(this).css("color", "red");
        }
    });
    // La hauteur
    $("span#screenHeight").each(function () {
        var screenId = parseInt($(this).text());
        if (screenId >= 630) {
            $(this).css("color", "green");
        } else {
            $(this).css("color", "red");
        }
    });
    // Le ratio
    $('span#screenRatio').each(function () {
        var ratio = parseFloat($(this).text());
        if (ratio >= 1.90 && ratio <= 1.92) {
            $(this).css("color", "green");
            $("#screenFract").css("color", "green");
        } else {
            $(this).css("color", "red");
            $("#screenFract").css("color", "red");
        }
    });
    // Le poids
    $('span#screenWeight').each(function (index) {
        var weight = parseFloat($(this).text());
        var fileType = $('span#screenType').eq(index).text();
        if ((fileType === "jpg" || fileType === "jpeg") && weight < 5000000) {
            $(this).css("color", "green");
        } else {
            $(this).css("color", "red");
        }
    });

    $('span#screenWeight').each(function (index) {
        var weight = parseFloat($(this).text());
        var fileType = $('span#screenType').eq(index).text();

        if (fileType === "png" && weight <= 1000000) {
            $(this).css("color", "green");
        } else {
            $(this).css("color", "red");
        }
    });

});

// Define function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
