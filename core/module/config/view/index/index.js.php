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
    /**
     * Afficher et masquer options SMTP
     */
    if ($("input[name=SmtpEnable]").is(':checked')) {
        $("#SmtpParam").addClass("disabled");
        $("#SmtpParam").slideDown();
    } else {
        $("#SmtpParam").removeClass("disabled");
        $("#SmtpParam").slideUp();
    }

    /**
     * Afficher et masquer options Auth
     */

    if ($("select[name=SmtpEnable]").val() == true) {
        $("#SmtpAuthParam").addClass("disabled");
        $("#SmtpAuthParam").slideDown();
    } else {
        $("#SmtpAuthParam").removeClass("disabled");
        $("#SmtpAuthParam").slideUp();
    }


  

    /**
     * Afficher et masquer options SMTP
     */
    $("input[name=SmtpEnable]").on("change", function() {
        if ($("input[name=SmtpEnable]").is(':checked')) {
            $("#SmtpParam").addClass("disabled");
            $("#SmtpParam").slideDown();
        } else {
            $("#SmtpParam").removeClass("disabled");
            $("#SmtpParam").slideUp();
        }
    });

    /**
     * Afficher et masquer options Auth
     */

    $("select[name=SmtpAuth]").on("change", function() {
        if ($("select[name=SmtpAuth]").val() == true) {
            $("#SmtpAuthParam").addClass("disabled");
            $("#SmtpAuthParam").slideDown();
        } else {
            $("#SmtpAuthParam").removeClass("disabled");
            $("#SmtpAuthParam").slideUp();
        }
    });

    /**
     * Options de blocage de connexions
     * Contrôle la cohérence des sélections et interdit une seule valeur Aucune
     */
    $("select[name=configConnectAttempt]").on("change", function() {
        if ($("select[name=configConnectAttempt]").val() === "999") {
            $("select[name=configConnectTimeout]").val(0);
        } else {
            if ($("select[name=configConnectTimeout]").val() === "0") {
                $("select[name=configConnectTimeout]").val(300);
            }
        }
    });
    $("select[name=configConnectTimeout]").on("change", function() {
        if ($("select[name=configConnectTimeout]").val() === "0") {
            $("select[name=configConnectAttempt]").val(999);
        } else {
            if ($("select[name=configConnectAttempt]").val() === "999") {
                $("select[name=configConnectAttempt]").val(3);
            }
        }
    });

    /**
     * Captcha strong si captcha sélectionné
     */
        $("input[name=configConnectCaptcha]").on("change", function() {
            
        if ($("input[name=configConnectCaptcha]").is(':checked')) {
            $("#configConnectCaptchaStrongWrapper").addClass("disabled");
            $("#configConnectCaptchaStrongWrapper").slideDown();
            $( "#configConnectCaptchaStrong" ).prop( "checked", false );
        } else {
            $("#configConnectCaptchaStrongWrapper").removeClass("disabled");
            $("#configConnectCaptchaStrongWrapper").slideUp();
        }
    });


    /**
     *  Sélection de la  page de configuration à afficher
     */
    $("#configSetupButton").on("click", function() {
        $("#localeContainer").slideUp();
        $("#socialContainer").slideUp();
        $("#connectContainer").slideUp();
        $("#networkContainer").slideUp();
        $("#setupContainer").slideDown();
    });
    $("#configLocalButton").on("click", function() {
        $("#setupContainer").slideUp();
        $("#socialContainer").slideUp();
        $("#connectContainer").slideUp();
        $("#networkContainer").slideUp();
        $("#localeContainer").slideDown();
    });
    $("#configSocialButton").on("click", function() {
        $("#connectContainer").slideUp();
        $("#setupContainer").slideUp();
        $("#localeContainer").slideUp();
        $("#networkContainer").slideUp();
        $("#socialContainer").slideDown();
    });
    $("#configConnectButton").on("click", function() {
        $("#setupContainer").slideUp();
        $("#localeContainer").slideUp();
        $("#socialContainer").slideUp();
        $("#networkContainer").slideUp();
        $("#connectContainer").slideDown();
    });
    $("#configNetworkButton").on("click", function() {
        $("#setupContainer").slideUp();
        $("#localeContainer").slideUp();
        $("#socialContainer").slideUp();
        $("#connectContainer").slideUp();
        $("#networkContainer").slideDown();
    });

});
