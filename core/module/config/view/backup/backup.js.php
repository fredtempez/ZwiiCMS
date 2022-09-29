/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

$(document).ready(function () {
    $("#configBackupForm").submit(function (e) {
        //$("#configBackupSubmit").addClass("disabled").prop("disabled", true);
        e.preventDefault();
        var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/backup";
        var message_success = "<?php echo helper::translate('Sauvegarde générée avec succès.'); ?>";
        var message_error = "<?php echo helper::translate('Erreur : sauvegarde non générée !'); ?>";
        var message_title = "<?php echo helper::translate('Sauvegarder'); ?>";
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize(),
            success: function (data) {
                $('body, .button').css('cursor', 'default');
                core.alert(message_success);
            },
            error: function (data) {
                $('body, .button').css('cursor', 'default');
                core.alert(message_error);
            },
            complete: function () {
                $("#configBackupSubmit").removeClass("disabled").prop("disabled", false);
                $("#configBackupSubmit").removeClass("uniqueSubmission").prop("uniqueSubmission", false);
                $("#configBackupSubmit span").removeClass("zwiico-spin animate-spin");
                $("#configBackupSubmit span").addClass("zwiico-check zwiico-margin-right").text(message_title);
            }
        });
    });


    /**
     * Confirmation de sauvegarde complète
     */
    $("#configBackupSubmit").on("click", function () {
        if ($("input[name=configBackupOption]").is(':checked')) {
            var message_warning = "<?php echo helper::translate('La sauvegarde des fichiers peut prendre du temps. Continuer ?'); ?>";
            return core.confirm(message_warning, function () {
                //$(location).attr("href", _this.attr("href"));
                $('body, .button').css('cursor', 'wait');
                $('form#configBackupForm').submit();
            });
        }
    });

});

