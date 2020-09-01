/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

$( document).ready(function() {
    $("#configBackupForm").submit( function(e){
        $("#configBackupSubmit").addClass("disabled").prop("disabled", true);
        e.preventDefault();
        if ($("input[name=configBackupOption]").is(':checked')) {
            $("body").addClass("loading");
            $(".modal").addClass("alertMessage");
        }
        var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/backup";
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize(),
            success: function(data){
                $("body").removeClass("loading");
                core.alert("La sauvegarde a été générée avec succès.");
            },
            error: function(data){
                $("body").removeClass("loading");
                core.alert("Une erreur s'est produite, la sauvegarde n'a pas été générée !");
            },
            complete: function(){
                if ($("input[name=configBackupOption]").is(':checked')) {
                    $(".modal").removeClass("alertMessage");
                }
                $("#configBackupSubmit").removeClass("disabled").prop("disabled", false);
            }
        });
    });
});

