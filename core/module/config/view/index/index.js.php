/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

$( document).ready(function() {

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

    if ($("select[name=smtpEnable]").val() == true) {
        $("#smtpAuthParam").addClass("disabled");
        $("#smtpAuthParam").slideDown();
    } else {
        $("#smtpAuthParam").removeClass("disabled");
        $("#smtpAuthParam").slideUp();
    }

    var configLayout = getCookie("configLayout");
    if (configLayout == null) {
        configLayout = "setup";
        setCookie("configLayout","setup");
    }
    $("#localeContainer").hide();
    $("#socialContainer").hide();
    $("#connectContainer").hide();
    $("#networkContainer").hide();
    $("#setupContainer").hide();
    $("#" + configLayout + "Container" ).show();
    $("#config" + capitalizeFirstLetter(configLayout) + "Button").addClass("activeButton");


    // Gestion des événements
    //---------------------------------------------------------------------------------------------------------------------

     /**
     * Afficher et masquer options smtp
     */
    $("input[name=smtpEnable]").on("change", function() {
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

    $("select[name=smtpAuth]").on("change", function() {
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
    $("select[name=connectAttempt]").on("change", function() {
        if ($("select[name=connectAttempt]").val() === "999") {
            $("select[name=connectTimeout]").val(0);
        } else {
            if ($("select[name=connectTimeout]").val() === "0") {
                $("select[name=connectTimeout]").val(300);
            }
        }
    });
    $("select[name=connectTimeout]").on("change", function() {
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
        $("input[name=connectCaptcha]").on("change", function() {
            
        if ($("input[name=connectCaptcha]").is(':checked')) {
            $("#connectCaptchaStrongWrapper").addClass("disabled");
            $("#connectCaptchaStrongWrapper").slideDown();
            $( "#connectCaptchaStrong" ).prop( "checked", false );
        } else {
            $("#connectCaptchaStrongWrapper").removeClass("disabled");
            $("#connectCaptchaStrongWrapper").slideUp();
        }
    });


    /**
     *  Sélection de la  page de configuration à afficher
     */
    $("#configSetupButton").on("click", function() {
        $("#localeContainer").hide();
        $("#socialContainer").hide();
        $("#connectContainer").hide();
        $("#networkContainer").hide();
        $("#setupContainer").show();
        $("#configSetupButton").addClass("activeButton");
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
        setCookie("configLayout","setup");
    });
    $("#configLocaleButton").on("click", function() {
        $("#setupContainer").hide();
        $("#socialContainer").hide();
        $("#connectContainer").hide();
        $("#networkContainer").hide();
        $("#localeContainer").show();
        $("#configSetupButton").removeClass("activeButton");
        $("#configLocaleButton").addClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
        setCookie("configLayout","locale");
    });
    $("#configSocialButton").on("click", function() {
        $("#connectContainer").hide();
        $("#setupContainer").hide();
        $("#localeContainer").hide();
        $("#networkContainer").hide();
        $("#socialContainer").show();
        $("#configSetupButton").removeClass("activeButton");
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSocialButton").addClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
        setCookie("configLayout","social");
    });
    $("#configConnectButton").on("click", function() {
        $("#setupContainer").hide();
        $("#localeContainer").hide();
        $("#socialContainer").hide();
        $("#networkContainer").hide();
        $("#connectContainer").show();
        $("#configSetupButton").removeClass("activeButton");
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").addClass("activeButton");
        $("#configNetworkButton").removeClass("activeButton");
        setCookie("configLayout","connect");
    });
    $("#configNetworkButton").on("click", function() {
        $("#setupContainer").hide();
        $("#localeContainer").hide();
        $("#socialContainer").hide();
        $("#connectContainer").hide();
        $("#networkContainer").show();
        $("#configSetupButton").removeClass("activeButton");
        $("#configLocaleButton").removeClass("activeButton");
        $("#configSocialButton").removeClass("activeButton");
        $("#configConnectButton").removeClass("activeButton");
        $("#configNetworkButton").addClass("activeButton");
        setCookie("configLayout","network");
    });
  

    /**
     * Aspect de la souris
    */
        $("#socialMetaImage, #socialSiteMap, #configBackupCopyButton").click(function(event) {
        $('body, .button').css('cursor', 'wait');
    });
        

});


function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/; samesite=lax";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// Define function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}