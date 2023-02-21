/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Fred Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
$("input, select").on("change", (function () {
    var titleFont = $("#adminFontTitle").val(),
        textFont = $("#adminFontText").val(),
        css = "@import url('https://fonts.cdnfonts.com/css/" + titleFont + "');",
        css = "@import url('https://fonts.cdnfonts.com/css/" + textFont + "');",
        colors, css = "#site{background-color:" + (colors = core.colorVariants($("#adminBackgroundColor").val())).normal + ";}",
        colors, colors, colors, colors;
    css += "body, .row > div {font:" + $("#adminFontTextSize").val() + " '" + textFont + "', sans-serif;}", css += "body h1, h2, h3, h4, h5, h6 {font-family:'" + titleFont + "', sans-serif; color:" + $("#adminColorTitle").val() + ";}", css += "body:not(.editorWysiwyg),span .zwiico-help {color:" + $("#adminColorText").val() + ";}", css += "input[type='checkbox']:checked + label::before,.speechBubble{ background-color:" + (colors = core.colorVariants($("#adminColorButton").val())).normal + ";}", css += ".speechBubble::before {border-color:" + colors.normal + " transparent transparent transparent;}", css += ".button {background-color:" + colors.normal + ";color:" + colors.text + ";}.button:hover {background-color:" + colors.darken + ";color:" + colors.text + ";}.button:active {background-color:" + colors.veryDarken + ";color:" + colors.text + ";}", css += ".button.buttonGrey {background-color: " + (colors = core.colorVariants($("#adminColorGrey").val())).normal + ";color:" + colors.text + ";}.button.buttonGrey:hover {background-color:" + colors.darken + ";color:" + colors.text + "}.button.buttonGrey:active {background-color:" + colors.veryDarken + ";color:" + colors.text + ";}", css += ".button.buttonRed {background-color: " + (colors = core.colorVariants($("#adminColorRed").val())).normal + ";color:" + colors.text + ";}.button.buttonRed:hover {background-color:" + colors.darken + ";color:" + colors.text + "}.button.buttonRed:active {background-color:" + colors.veryDarken + ";color:" + colors.text + "}", css += ".button.buttonGreen, button[type=submit] {background-color: " + (colors = core.colorVariants($("#adminColorGreen").val())).normal + ";color: ;color:" + colors.text + "}.button.buttonGreen:hover, button[type=submit]:hover {background-color: " + colors.darken + ";color:" + colors.text + ";}.button.buttonGreen:active, button[type=submit]:active {background-color:" + colors.veryDarken + ";color:" + colors.text + "}";
    var colors = core.colorVariants($("#adminBackGroundBlockColor").val());
    css += ".block {border: 1px solid " + $("#adminBorderBlockColor").val() + ";}.block h4 {background-color: " + colors.normal + ";color:" + colors.text + ";}", css += "input[type=email],input[type=text],input[type=password],select:not(#bar select),textarea:not(.editorWysiwyg),.inputFile{background-color: " + colors.normal + ";color:" + colors.text + ";border: 1px solid " + $("#adminBorderBlockColor").val() + ";}", $("#themePreview").remove(), $("<style>").attr("type", "text/css").attr("id", "themePreview").text(css).appendTo("head")
})), $("#configAdminReset").on("click", (function () {
    var _this = $(this);
    return core.confirm("Êtes-vous sûr de vouloir réinitialiser à son état d'origine le thème de l'administration ?", (function () {
        $(location).attr("href", _this.attr("href"))
    }))
}));