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
    if ($("input[name=configAdvancedSmtpEnable]").is(':checked')) {
        $("#configAdvancedSmtpParam").addClass("disabled");
        $("#configAdvancedSmtpParam").slideDown();
    } else {
        $("#configAdvancedSmtpParam").removeClass("disabled");
        $("#configAdvancedSmtpParam").slideUp();
    }

    /**
     * Afficher et masquer options Auth
     */

    if ($("select[name=configAdvancedSmtpEnable]").val() == true) {
        $("#configAdvancedSmtpAuthParam").addClass("disabled");
        $("#configAdvancedSmtpAuthParam").slideDown();
    } else {
        $("#configAdvancedSmtpAuthParam").removeClass("disabled");
        $("#configAdvancedSmtpAuthParam").slideUp();
    }


  

    /**
     * Afficher et masquer options SMTP
     */
    $("input[name=configAdvancedSmtpEnable]").on("change", function() {
        if ($("input[name=configAdvancedSmtpEnable]").is(':checked')) {
            $("#configAdvancedSmtpParam").addClass("disabled");
            $("#configAdvancedSmtpParam").slideDown();
        } else {
            $("#configAdvancedSmtpParam").removeClass("disabled");
            $("#configAdvancedSmtpParam").slideUp();
        }
    });

    /**
     * Afficher et masquer options Auth
     */

    $("select[name=configAdvancedSmtpAuth]").on("change", function() {
        if ($("select[name=configAdvancedSmtpAuth]").val() == true) {
            $("#configAdvancedSmtpAuthParam").addClass("disabled");
            $("#configAdvancedSmtpAuthParam").slideDown();
        } else {
            $("#configAdvancedSmtpAuthParam").removeClass("disabled");
            $("#configAdvancedSmtpAuthParam").slideUp();
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

});
