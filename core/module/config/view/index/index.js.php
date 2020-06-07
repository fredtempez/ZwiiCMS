/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

$( document).ready(function() {
    /**
     * Afficher et masquer options SMTP
     */
    if ($("input[name=configSmtpEnable]").is(':checked')) {
        $("#configSmtpParam").addClass("disabled");
        $("#configSmtpParam").slideDown();
    } else {
        $("#configSmtpParam").removeClass("disabled");
        $("#configSmtpParam").slideUp();
    }

    /**
     * Afficher et masquer options Auth
     */

    if ($("select[name=configSmtpAuth]").val() == true) {
        $("#configSmtpAuthParam").addClass("disabled");
        $("#configSmtpAuthParam").slideDown();
    } else {
        $("#configSmtpAuthParam").removeClass("disabled");
        $("#configSmtpAuthParam").slideUp();
    }

    /**
     * Blocs dépliants
     */

    $("div .block").click(function(e) {
        $(this).find(".zwiico-plus").toggle();
        $(this).find(".zwiico-minus").toggle();
        $(this).find(".blockContainer").slideToggle();
    }).on("click", "span > input, input, textarea, label, option, button, a, .blockContainer", function(e) {
        // Empêcher les déclenchements dans les blocs
        e.stopPropagation();
    });



    /**
     * Afficher et masquer options SMTP
     */
    $("input[name=configSmtpEnable]").on("change", function() {
        if ($("input[name=configSmtpEnable]").is(':checked')) {
            $("#configSmtpParam").addClass("disabled");
            $("#configSmtpParam").slideDown();
        } else {
            $("#configSmtpParam").removeClass("disabled");
            $("#configSmtpParam").slideUp();
        }
    });

    /**
     * Afficher et masquer options Auth
     */

    $("select[name=configSmtpAuth]").on("change", function() {
        if ($("select[name=configSmtpAuth]").val() == true) {
            $("#configSmtpAuthParam").addClass("disabled");
            $("#configSmtpAuthParam").slideDown();
        } else {
            $("#configSmtpAuthParam").removeClass("disabled");
            $("#configSmtpAuthParam").slideUp();
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