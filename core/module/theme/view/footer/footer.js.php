/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @link http://zwiicms.fr/
 */
$("input, select").on("change", (function () {
    var footerFont = $("#themeFooterFont :selected").val(),
        footerFontText = $("#themeFooterFont :selected").text(),
        css = "@import url('https://fonts.cdnfonts.com/css/" + footerFont + "');",
        colors = core.colorVariants($("#themeFooterBackgroundColor").val()),
        textColor = $("#themeFooterTextColor").val(),
        css = "footer {background-color:" + colors.normal + ";color:" + textColor + "}";
    switch (css += "footer a{color:" + textColor + "}", css += ".editorWysiwyg{background-color:" + colors.normal + " !important; color:" + textColor + " !important;}", css += "footer #footersite > div{margin:" + $("#themeFooterHeight").val() + " 0}", css += "footer #footerbody > div{margin:" + $("#themeFooterHeight").val() + " 0}", css += "#footerSocials{text-align:" + $("#themeFooterSocialsAlign").val() + "}", css += "#footerText > p {text-align:" + $("#themeFooterTextAlign").val() + "}", css += "#footerCopyright{text-align:" + $("#themeFooterCopyrightAlign").val() + "}", css += "footer span, #footerText > p {color:" + $("#themeFooterTextColor").val() + ";font-family:'" + footerFontText + "',sans-serif;font-weight:" + $("#themeFooterFontWeight").val() + ";font-size:" + $("#themeFooterFontSize").val() + ";text-transform:" + $("#themeFooterTextTransform").val() + "}", $("#themeFooterMargin").is(":checked") ? css += "footer{padding: 0 20px;}" : css += "footer{padding:0}", $("#themePreview").remove(), $("<style>").attr("type", "text/css").attr("id", "themePreview").text(css).appendTo("footer"), $("#themeFooterPosition").val()) {
        case "hide":
            $("footer").hide();
            break;
        case "site":
            $("footer").show().appendTo("#site"), $("footer > div:first-child").removeAttr("class"), $("footer > div:first-child").addClass("container");
            break;
        case "body":
            $("footer").show().appendTo("body"), $("footer > div:first-child").removeAttr("class"), $("footer > div:first-child").addClass("container-large")
    }
    $("#footerText > p").css("margin-top", "0"), $("#footerText > p").css("margin-bottom", "0")
})), $(".themeFooterContent").on("change", (function () {
    var footerPosition = $("#themeFooterPosition").val();
    switch ($("#themeFooterTextPosition").val()) {
        case "hide":
            $("#footerText").hide();
            break;
        default:
            textPosition = $("#themeFooterTextPosition").val(), textPosition = textPosition.substr(0, 1).toUpperCase() + textPosition.substr(1), $("#footerText").show().appendTo("#footer" + footerPosition + textPosition)
    }
    switch ($("#themeFooterSocialsPosition").val()) {
        case "hide":
            $("#footerSocials").hide();
            break;
        default:
            socialsPosition = $("#themeFooterSocialsPosition").val(), socialsPosition = socialsPosition.substr(0, 1).toUpperCase() + socialsPosition.substr(1), $("#footerSocials").show().appendTo("#footer" + footerPosition + socialsPosition)
    }
    switch ($("#themeFooterCopyrightPosition").val()) {
        case "hide":
            $("#footerCopyright").hide();
            break;
        default:
            copyrightPosition = $("#themeFooterCopyrightPosition").val(), copyrightPosition = copyrightPosition.substr(0, 1).toUpperCase() + copyrightPosition.substr(1), $("#footerCopyright").show().appendTo("#footer" + footerPosition + copyrightPosition)
    }
})).trigger("change"), $("#themeFooterTemplate").on("change", (function () {
    var newOptions = {
        4: {
            hide: "Masqué",
            left: "En haut",
            center: "Au milieu",
            right: "En bas"
        },
        3: {
            hide: "Masqué",
            left: "A gauche",
            center: "Au centre",
            right: "A droite"
        },
        2: {
            hide: "Masqué",
            left: "A gauche",
            right: "A droite"
        },
        1: {
            hide: "Masqué",
            center: "Affiché"
        }
    },
        $el = $(".themeFooterContent");
    $el.empty(), $.each(newOptions[$("#themeFooterTemplate").val()], (function (key, value) {
        $el.append($("<option></option>").attr("value", key).text(value))
    }));
    var position = $("#themeFooterPosition").val();
    switch ($("#footerCopyright").hide(), $("#footerText").hide(), $("#footerSocials").hide(), $("#themeFooterTemplate").val()) {
        case "1":
            $("#footer" + position + "Left").css("display", "none"), $("#footer" + position + "Center").removeAttr("class").addClass("col12").css("display", ""), $("#footer" + position + "Right").css("display", "none");
            break;
        case "2":
            $("#footer" + position + "Left").removeAttr("class").addClass("col6").css("display", ""), $("#footer" + position + "Center").css("display", "none").removeAttr("class"), $("#footer" + position + "Right").removeAttr("class").addClass("col6").css("display", "");
            break;
        case "3":
            $("#footer" + position + "Left").removeAttr("class").addClass("col4").css("display", ""), $("#footer" + position + "Center").removeAttr("class").addClass("col4").css("display", ""), $("#footer" + position + "Right").removeAttr("class").addClass("col4").css("display", "");
            break;
        case "4":
            $("#footer" + position + "Left").removeAttr("class").addClass("col12").css("display", ""), $("#footer" + position + "Center").removeAttr("class").addClass("col12").css("display", ""), $("#footer" + position + "Right").removeAttr("class").addClass("col12").css("display", "")
    }
})), $("#themeFooterSocialsPosition").on("change", (function () {
    $(this).prop("selectedIndex") >= 1 && ($("#themeFooterTextPosition").prop("selectedIndex") === $(this).prop("selectedIndex") && ($("#themeFooterTextPosition").prop("selectedIndex", 0), $("#footerText").hide()), $("#themeFooterCopyrightPosition").prop("selectedIndex") === $(this).prop("selectedIndex") && ($("#themeFooterCopyrightPosition").prop("selectedIndex", 0), $("#footerCopyright").hide()))
})).trigger("change"), $("#themeFooterTextPosition").on("change", (function () {
    $(this).prop("selectedIndex") >= 1 && ($("#themeFooterSocialsPosition").prop("selectedIndex") === $(this).prop("selectedIndex") && ($("#themeFooterSocialsPosition").prop("selectedIndex", 0), $("#footerSocials").hide()), $("#themeFooterCopyrightPosition").prop("selectedIndex") === $(this).prop("selectedIndex") && ($("#themeFooterCopyrightPosition").prop("selectedIndex", 0), $("#footerCopyright").hide()))
})).trigger("change"), $("#themeFooterCopyrightPosition").on("change", (function () {
    $(this).prop("selectedIndex") >= 1 && ($("#themeFooterTextPosition").prop("selectedIndex") === $(this).prop("selectedIndex") && ($("#themeFooterTextPosition").prop("selectedIndex", 0), $("#footerText").hide()), $("#themeFooterSocialsPosition").prop("selectedIndex") === $(this).prop("selectedIndex") && ($("#themeFooterSocialsPosition").prop("selectedIndex", 0), $("#footerSocials").hide()))
})).trigger("change"), $("#themeFooterPosition").on("change", (function () {
    "body" === $(this).val() ? $("#themeFooterPositionFixed").slideDown() : $("#themeFooterPositionFixed").slideUp((function () {
        $("#themeFooterFixed").prop("checked", !1).trigger("change")
    }))
})).trigger("change"), $("#themeFooterLoginLink").on("change", (function () {
    $(this).is(":checked") ? $("#footerLoginLink").show() : $("#footerLoginLink").hide()
})).trigger("change"), $("#themefooterDisplayVersion").on("change", (function () {
    $(this).is(":checked") ? $("#footerDisplayVersion").show() : $("#footerDisplayVersion").hide()
})).trigger("change"), $("#themefooterDisplayCopyright").on("change", (function () {
    $(this).is(":checked") ? $("#footerDisplayCopyright").show() : $("#footerDisplayCopyright").hide()
})).trigger("change"), $("#themefooterDisplaySiteMap").on("change", (function () {
    $(this).is(":checked") ? $("#footerDisplaySiteMap").show() : $("#footerDisplaySiteMap").hide()
})).trigger("change"), $("#themeFooterDisplaySearch").on("change", (function () {
    $(this).is(":checked") ? $("#footerDisplaySearch").show() : $("#footerDisplaySearch").hide()
})).trigger("change"), $("#themeFooterDisplayLegal").on("change", (function () {
    $(this).is(":checked") ? $("#footerDisplayLegal").show() : $("#footerDisplayLegal").hide()
})).trigger("change"), $("#configLegalPageId").on("change", (function () {
    "Aucune" === $("#configLegalPageId option:selected").text() ? ($("#themeFooterDisplayLegal").prop("checked", !1), $("#themeFooterDisplayLegal").prop("disabled", !0), $("#footerDisplayLegal").hide()) : $("#themeFooterDisplayLegal").prop("disabled", !1)
})).trigger("change"), $("#configSearchPageId").on("change", (function () {
    "Aucune" === $("#configSearchPageId option:selected").text() ? ($("#themeFooterDisplaySearch").prop("checked", !1), $("#themeFooterDisplaySearch").prop("disabled", !0), $("#footerDisplaySearch").hide()) : $("#themeFooterDisplaySearch").prop("disabled", !1)
})).trigger("change");