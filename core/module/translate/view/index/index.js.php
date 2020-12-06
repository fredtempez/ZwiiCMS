
/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @authorFrédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

$(document).ready(function(){
    /*
    * Active le script Google quand une langue est traduite automatiquement
    */
    $("form :input").change(function() {
    $(this).closest('form').data('changed', true);
    if($(this).closest('form').data('changed')) {
        if(   $(this).val() === "script"
            || $(this).val() === "script"
            || $(this).val() === "script"
            || $(this).val() === "script"
            || $(this).val() === "script"
            || $(this).val() === "script"   ) {
            $("#translateScriptGoogle").prop("checked", true);
            }
        }
    });

    /**
     * Désactive la traduction auto des langues lorsque le script est désélectionné
     */
    $("#translateScriptGoogle").on("change", function() {
        if ( $("input[name=translateScriptGoogle]").is(':not(:checked)') ) {
            if ($("#translateDE :selected").val() === "script" ) {
                $("#translateDE").val("none");
            }
            if ($("#translateEN :selected").val() === "script" ) {
                $("#translateEN").val("none");
            }
            if ($("#translateES :selected").val() === "script" ) {
                $("#translateEs").val("none");
            }
            if ($("#translateIT :selected").val() === "script" ) {
                $("#translateIT").val("none");
            }
            if ($("#translateNL :selected").val() === "script" ) {
                $("#translateNL").val("none");
            }
            if ($("#translatePT :selected").val() === "script" ) {
                $("#translatePT").val("none");
            }
            $(".translateGoogleScriptOption").prop("checked", false);
        }
    });

    /**
     * Active le script quand une option est activée
     */
    $(".translateGoogleScriptOption").on("change", function() {
        $("#translateScriptGoogle").prop("checked", true);
    });

});