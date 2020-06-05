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
 * Affichage et masquage des blocs
 */
// Informations générales
$("#info .zwiico-plus").click(function() {
    $("#info .blockContainer").slideDown();
    $("#info .zwiico-plus").hide();
    $("#info .zwiico-minus").show();
   /* var _this = $(this);
    console.log(_this.parent());*/
});
$("#info .zwiico-minus").click(function() {
    $("#info .blockContainer").slideUp();
    $("#info .zwiico-plus").show();
    $("#info .zwiico-minus").hide();
});

// Paramètres
$("#parameter .zwiico-plus").click(function() {
    $("#parameter .blockContainer").slideDown();
    $("#parameter .zwiico-plus").hide();
    $("#parameter .zwiico-minus").show();
});
$("#parameter .zwiico-minus").click(function() {
    $("#parameter .blockContainer").slideUp();
    $("#parameter .zwiico-plus").show();
    $("#parameter .zwiico-minus").hide();
});

// Sociaux
$("#social .zwiico-plus").click(function() {
    $("#social .blockContainer").slideDown();
    $("#social .zwiico-plus").hide();
    $("#social .zwiico-minus").show();
});
$("#social .zwiico-minus").click(function() {
    $("#social .blockContainer").slideUp();
    $("#social .zwiico-plus").show();
    $("#social .zwiico-minus").hide();
});

// Référencement
$("#ceo .zwiico-plus").click(function() {
    $("#ceo .blockContainer").slideDown();
    $("#ceo .zwiico-plus").hide();
    $("#ceo .zwiico-minus").show();
});
$("#ceo .zwiico-minus").click(function() {
    $("#ceo .blockContainer").slideUp();
    $("#ceo .zwiico-plus").show();
    $("#ceo .zwiico-minus").hide();
});

// Réseau
$("#network .zwiico-plus").click(function() {
    $("#network .blockContainer").slideDown();
    $("#network .zwiico-plus").hide();
    $("#network .zwiico-minus").show();
});
$("#network .zwiico-minus").click(function() {
    $("#network .blockContainer").slideUp();
    $("#network .zwiico-plus").show();
    $("#network .zwiico-minus").hide();
});

// smtp
$("#smtp .zwiico-plus").click(function() {
    $("#smtp .blockContainer").slideDown();
    $("#smtp .zwiico-plus").hide();
    $("#smtp .zwiico-minus").show();
});
$("#smtp .zwiico-minus").click(function() {
    $("#smtp .blockContainer").slideUp();
    $("#smtp .zwiico-plus").show();
    $("#smtp .zwiico-minus").hide();
});

// sécurité login
$("#login .zwiico-plus").click(function() {
    $("#login .blockContainer").slideDown();
    $("#login .zwiico-plus").hide();
    $("#login .zwiico-minus").show();
});
$("#login .zwiico-minus").click(function() {
    $("#login .blockContainer").slideUp();
    $("#login .zwiico-plus").show();
    $("#login .zwiico-minus").hide();
});

// journaux
$("#log .zwiico-plus").click(function() {
    $("#log .blockContainer").slideDown();
    $("#log .zwiico-plus").hide();
    $("#log .zwiico-minus").show();
});
$("#log .zwiico-minus").click(function() {
    $("#log .blockContainer").slideUp();
    $("#log .zwiico-plus").show();
    $("#log .zwiico-minus").hide();
});

// script
$("#script .zwiico-plus").click(function() {
    $("#script .blockContainer").slideDown();
    $("#script .zwiico-plus").hide();
    $("#script .zwiico-minus").show();
});
$("#script .zwiico-minus").click(function() {
    $("#script .blockContainer").slideUp();
    $("#script .zwiico-plus").show();
    $("#script .zwiico-minus").hide();
});

// version
$("#version .zwiico-plus").click(function() {
    $("#version .blockContainer").slideDown();
    $("#version .zwiico-plus").hide();
    $("#version .zwiico-minus").show();
});
$("#version .zwiico-minus").click(function() {
    $("#version .blockContainer").slideUp();
    $("#version .zwiico-plus").show();
    $("#version .zwiico-minus").hide();
});





