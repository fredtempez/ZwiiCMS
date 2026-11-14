/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */
$("input, select").on("change", function () {
    var font = $("#themeFooterFont :selected").val();
    var fontName = $("#themeFooterFont :selected").text();
    var importUrl = "@import url('https://fonts.cdnfonts.com/css/" + font + "');";
    var colors = core.colorVariants($("#themeFooterBackgroundColor").val());
    var textColor = $("#themeFooterTextColor").val();

    var css = "footer {background-color:" + colors.normal + ";color:" + textColor + "}";
    css += "footer a{color:" + textColor + "}";
    css += ".editorWysiwyg{background-color:" + colors.normal + " !important; color:" + textColor + " !important;}";
    css += "footer #footersite > div{margin:" + $("#themeFooterHeight").val() + " 0}";
    css += "footer #footerbody > div{margin:" + $("#themeFooterHeight").val() + " 0}";
    css += "#footerSocials{text-align:" + $("#themeFooterSocialsAlign").val() + "}";
    css += "#footerText > p {text-align:" + $("#themeFooterTextAlign").val() + "}";
    css += "#footerCopyright{text-align:" + $("#themeFooterCopyrightAlign").val() + "}";
    css += "footer span, #footerText > p {color:" + textColor + ";font-family:'" + fontName + "',sans-serif;font-weight:" + $("#themeFooterFontWeight").val() + ";font-size:" + $("#themeFooterFontSize").val() + ";text-transform:" + $("#themeFooterTextTransform").val() + "}";
    if ($("#themeFooterMargin").is(":checked")) {
        css += "footer{padding: 0 20px;}";
    } else {
        css += "footer{padding:0}";
    }

    $("#themePreview").remove();
    $("<style>").attr("type", "text/css").attr("id", "themePreview").text(css).appendTo("footer");

    switch ($("#themeFooterPosition").val()) {
        case "hide":
            $("footer").hide();
            break;
        case "site":
            $("footer").show().appendTo("#site");
            $("footer > div:first-child").removeAttr("class").addClass("container");
            break;
        case "body":
            $("footer").show().appendTo("body");
            $("footer > div:first-child").removeAttr("class").addClass("container-large");
            break;
    }
    $("#footerText > p").css("margin-top", "0");
    $("#footerText > p").css("margin-bottom", "0");
});

$(".themeFooterContent").on("change", function () {
    var position = $("#themeFooterPosition").val();

    switch ($("#themeFooterTextPosition").val()) {
        case "hide":
            $("#footerText").hide();
            break;
        default:
            var textPosition = $("#themeFooterTextPosition").val();
            textPosition = textPosition.substr(0, 1).toUpperCase() + textPosition.substr(1);
            $("#footerText").show().appendTo("#footer" + position + textPosition);
            break;
    }

    switch ($("#themeFooterSocialsPosition").val()) {
        case "hide":
            $("#footerSocials").hide();
            break;
        default:
            var socialsPosition = $("#themeFooterSocialsPosition").val();
            socialsPosition = socialsPosition.substr(0, 1).toUpperCase() + socialsPosition.substr(1);
            $("#footerSocials").show().appendTo("#footer" + position + socialsPosition);
            break;
    }

    switch ($("#themeFooterCopyrightPosition").val()) {
        case "hide":
            $("#footerCopyright").hide();
            break;
        default:
            var copyrightPosition = $("#themeFooterCopyrightPosition").val();
            copyrightPosition = copyrightPosition.substr(0, 1).toUpperCase() + copyrightPosition.substr(1);
            $("#footerCopyright").show().appendTo("#footer" + position + copyrightPosition);
            break;
    }
}).trigger("change");

$("#themeFooterTemplate").on("change", function () {
    var templates = {
        4: { hide: "Masqué", left: "En haut", center: "Au milieu", right: "En bas" },
        3: { hide: "Masqué", left: "A gauche", center: "Au centre", right: "A droite" },
        2: { hide: "Masqué", left: "A gauche", right: "A droite" },
        1: { hide: "Masqué", center: "Affiché" }
    };
    var container = $(".themeFooterContent");
    container.empty();
    $.each(templates[$("#themeFooterTemplate").val()], function (key, label) {
        container.append($("<option></option>").attr("value", key).text(label));
    });

    var position = $("#themeFooterPosition").val();
    $("#footerCopyright").hide();
    $("#footerText").hide();
    $("#footerSocials").hide();

    switch ($("#themeFooterTemplate").val()) {
        case "1":
            $("#footer" + position + "Left").css("display", "none");
            $("#footer" + position + "Center").removeAttr("class").addClass("col12").css("display", "");
            $("#footer" + position + "Right").css("display", "none");
            break;
        case "2":
            $("#footer" + position + "Left").removeAttr("class").addClass("col6").css("display", "");
            $("#footer" + position + "Center").css("display", "none").removeAttr("class");
            $("#footer" + position + "Right").removeAttr("class").addClass("col6").css("display", "");
            break;
        case "3":
            $("#footer" + position + "Left").removeAttr("class").addClass("col4").css("display", "");
            $("#footer" + position + "Center").removeAttr("class").addClass("col4").css("display", "");
            $("#footer" + position + "Right").removeAttr("class").addClass("col4").css("display", "");
            break;
        case "4":
            $("#footer" + position + "Left").removeAttr("class").addClass("col12").css("display", "");
            $("#footer" + position + "Center").removeAttr("class").addClass("col12").css("display", "");
            $("#footer" + position + "Right").removeAttr("class").addClass("col12").css("display", "");
            break;
    }
});

// Évite les doublons de position entre texte, réseaux sociaux et copyright
$("#themeFooterSocialsPosition").on("change", function () {
    if ($(this).prop("selectedIndex") >= 1) {
        if ($("#themeFooterTextPosition").prop("selectedIndex") === $(this).prop("selectedIndex")) {
            $("#themeFooterTextPosition").prop("selectedIndex", 0);
            $("#footerText").hide();
        }
        if ($("#themeFooterCopyrightPosition").prop("selectedIndex") === $(this).prop("selectedIndex")) {
            $("#themeFooterCopyrightPosition").prop("selectedIndex", 0);
            $("#footerCopyright").hide();
        }
    }
}).trigger("change");

$("#themeFooterTextPosition").on("change", function () {
    if ($(this).prop("selectedIndex") >= 1) {
        if ($("#themeFooterSocialsPosition").prop("selectedIndex") === $(this).prop("selectedIndex")) {
            $("#themeFooterSocialsPosition").prop("selectedIndex", 0);
            $("#footerSocials").hide();
        }
        if ($("#themeFooterCopyrightPosition").prop("selectedIndex") === $(this).prop("selectedIndex")) {
            $("#themeFooterCopyrightPosition").prop("selectedIndex", 0);
            $("#footerCopyright").hide();
        }
    }
}).trigger("change");

$("#themeFooterCopyrightPosition").on("change", function () {
    if ($(this).prop("selectedIndex") >= 1) {
        if ($("#themeFooterTextPosition").prop("selectedIndex") === $(this).prop("selectedIndex")) {
            $("#themeFooterTextPosition").prop("selectedIndex", 0);
            $("#footerText").hide();
        }
        if ($("#themeFooterSocialsPosition").prop("selectedIndex") === $(this).prop("selectedIndex")) {
            $("#themeFooterSocialsPosition").prop("selectedIndex", 0);
            $("#footerSocials").hide();
        }
    }
}).trigger("change");

$("#themeFooterPosition").on("change", function () {
    if ($(this).val() === "body") {
        $("#themeFooterPositionFixed").slideDown();
    } else {
        $("#themeFooterPositionFixed").slideUp(function () {
            $("#themeFooterFixed").prop("checked", false).trigger("change");
        });
    }
}).trigger("change");

$("#themeFooterLoginLink").on("change", function () {
    $(this).is(":checked") ? $("#footerLoginLink").show() : $("#footerLoginLink").hide();
}).trigger("change");

$("#themefooterDisplayVersion").on("change", function () {
    $(this).is(":checked") ? $("#footerDisplayVersion").show() : $("#footerDisplayVersion").hide();
}).trigger("change");

$("#themefooterDisplayCopyright").on("change", function () {
    $(this).is(":checked") ? $("#footerDisplayCopyright").show() : $("#footerDisplayCopyright").hide();
}).trigger("change");

$("#themefooterDisplaySiteMap").on("change", function () {
    $(this).is(":checked") ? $("#footerDisplaySiteMap").show() : $("#footerDisplaySiteMap").hide();
}).trigger("change");

$("#themeFooterDisplaySearch").on("change", function () {
    $(this).is(":checked") ? $("#footerDisplaySearch").show() : $("#footerDisplaySearch").hide();
}).trigger("change");

$("#themeFooterDisplayLegal").on("change", function () {
    $(this).is(":checked") ? $("#footerDisplayLegal").show() : $("#footerDisplayLegal").hide();
}).trigger("change");

$("#configLegalPageId").on("change", function () {
    if ($("#configLegalPageId option:selected").index() === 0) {
        $("#themeFooterDisplayLegal").prop("checked", false);
        $("#themeFooterDisplayLegal").prop("disabled", true);
        $("#footerDisplayLegal").hide();
    } else {
        $("#themeFooterDisplayLegal").prop("disabled", false);
    }
}).trigger("change");

$("#configSearchPageId").on("change", function () {
    if ($("#configSearchPageId option:selected").index() === 0) {
        $("#themeFooterDisplaySearch").prop("checked", false);
        $("#themeFooterDisplaySearch").prop("disabled", true);
        $("#footerDisplaySearch").hide();
    } else {
        $("#themeFooterDisplaySearch").prop("disabled", false);
    }
}).trigger("change");
