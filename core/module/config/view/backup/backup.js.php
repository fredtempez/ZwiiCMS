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
    $("#configBackupForm").submit( function(e){
        //$("#configBackupSubmit").addClass("disabled").prop("disabled", true);
        e.preventDefault();
        var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/backup";
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize(),
            success: function(data){
                $('body, .button').css('cursor', 'default');
                core.alert("La sauvegarde a été générée avec succès.");
            },
            error: function(data){
                $('body, .button').css('cursor', 'default');
                core.alert("Une erreur s'est produite, la sauvegarde n'a pas été générée !");
            },
            complete: function(){
                $("#configBackupSubmit").removeClass("disabled").prop("disabled", false);
                $("#configBackupSubmit").removeClass("uniqueSubmission").prop("uniqueSubmission", false);
                $("#configBackupSubmit span").removeClass("zwiico-spin animate-spin");
                $("#configBackupSubmit span").addClass("zwiico-check zwiico-margin-right").text("Sauvegarder");
            }
        });
    });


    /**
     * Confirmation de sauvegarde complète
     */
    $("#configBackupSubmit").on("click", function() {
        if ($("input[name=configBackupOption]").is(':checked')) {
            return core.confirm("Une sauvegarde avec le contenu du gestionnaire de fichier peut prendre du temps à générer. Confirmez-vous ?", function() {
                //$(location).attr("href", _this.attr("href"));
                $('body, .button').css('cursor', 'wait');
                $('form#configBackupForm').submit();
            });
        }
    });

});

