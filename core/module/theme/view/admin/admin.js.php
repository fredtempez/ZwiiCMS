/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Fred Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */
$(document).ready(function () {

    // Mise à jour de la largeur du site
    function updateAdminWidth() {
        var width = $('#adminSiteWidth').val();
        $('#site').css({
            'max-width': width,
            'transition': 'all 0.3s ease-in-out'
        });
    }

    updateAdminWidth();

    $('#adminSiteWidth').on('change', function () {
        if ($(this).val() === '100%') {
            $('#adminSiteMargin').prop('checked', true);
            $('#adminSiteMarginWrapper').hide();
        } else {
            $('#adminSiteMarginWrapper').show();
        }
        updateAdminWidth();
    });

    $('#adminSiteMargin').on('change', updateAdminWidth);

    $("input, select").on("change", function () {
        var titleFont = $("#adminFontTitle").val();
        var textFont = $("#adminFontText").val();

        // Injection des fontes
        var css = "@import url('https://fonts.cdnfonts.com/css/" + titleFont + "');";
        css += "@import url('https://fonts.cdnfonts.com/css/" + textFont + "');";

        // Couleurs de base
        var colors = core.colorVariants($("#adminBackgroundColor").val());
        css += "#site{background-color:" + colors.normal + ";}";

        css += "body, .row > div {font:" + $("#adminFontTextSize").val() + " '" + textFont + "', sans-serif;}";
        css += "body h1, h2, h3, h4, h5, h6 {font-family:'" + titleFont + "', sans-serif; color:" + $("#adminColorTitle").val() + ";}";
        css += "body:not(.editorWysiwyg),span .zwiico-help {color:" + $("#adminColorText").val() + ";}";

        colors = core.colorVariants($("#adminColorButton").val());
        css += "input[type='checkbox']:checked + label::before,.speechBubble{ background-color:" + colors.normal + ";}";
        css += ".speechBubble::before {border-color:" + colors.normal + " transparent transparent transparent;}";
        css += ".button {background-color:" + colors.normal + ";color:" + colors.text + ";}.button:hover {background-color:" + colors.darken + ";color:" + colors.text + ";}.button:active {background-color:" + colors.veryDarken + ";color:" + colors.text + ";}";

        colors = core.colorVariants($("#adminColorGrey").val());
        css += ".button.buttonGrey {background-color: " + colors.normal + ";color:" + colors.text + ";}.button.buttonGrey:hover {background-color:" + colors.darken + ";color:" + colors.text + "}.button.buttonGrey:active {background-color:" + colors.veryDarken + ";color:" + colors.text + ";}";

        colors = core.colorVariants($("#adminColorRed").val());
        css += ".button.buttonRed {background-color: " + colors.normal + ";color:" + colors.text + ";}.button.buttonRed:hover {background-color:" + colors.darken + ";color:" + colors.text + "}.button.buttonRed:active {background-color:" + colors.veryDarken + ";color:" + colors.text + "}";

        colors = core.colorVariants($("#adminColorGreen").val());
        css += ".button.buttonGreen, button[type=submit] {background-color: " + colors.normal + ";color: ;color:" + colors.text + "}.button.buttonGreen:hover, button[type=submit]:hover {background-color:" + colors.darken + ";color:" + colors.text + ";}.button.buttonGreen:active, button[type=submit]:active {background-color:" + colors.veryDarken + ";color:" + colors.text + "}";

        colors = core.colorVariants($("#adminBackGroundBlockColor").val());
        css += ".block {border: 1px solid " + $("#adminBorderBlockColor").val() + ";}.block h4 {background-color: " + colors.normal + ";color:" + colors.text + ";}";
        css += "input[type=email],input[type=text],input[type=password],select:not(#bar select),textarea:not(.editorWysiwyg),.inputFile{background-color:" + colors.normal + ";color:" + colors.text + ";border: 1px solid " + $("#adminBorderBlockColor").val() + ";}";

        // Injection de l'aperçu
        $("#themePreview").remove();
        $("<style>")
            .attr("type", "text/css")
            .attr("id", "themePreview")
            .text(css)
            .appendTo("head");
    });

    $("#configAdminReset").on("click", function () {
        var _this = $(this);
        return core.confirm("Êtes-vous sûr de vouloir réinitialiser à son état d'origine le thème de l'administration ?", function () {
            $(location).attr("href", _this.attr("href"));
        });
    });
});
