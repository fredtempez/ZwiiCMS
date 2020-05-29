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
 * Options de blocage de connexions choix aucun
 */
$("select[name=configConnectAttempt]").on("change", function() {
    if ($("select[name=configConnectAttempt]").val() === "999") {
        $("select[name=configConnectTimeout]").val(0);
    }
});

$("select[name=configConnectTimeout]").on("change", function() {
    if ($("select[name=configConnectTimeout]").val() === "0") {
        $("select[name=configConnectAttempt]").val(999);
    }
});