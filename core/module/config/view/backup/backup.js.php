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
        $("#configBackupSubmit").addClass("disabled").prop("disabled", true);
        e.preventDefault();
        /**
        if ($("input[name=configBackupOption]").is(':checked')) {
            $('body').css('cursor', 'wait');
        }
        */
        var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/backup";
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize(),
            success: function(data){
                $('body').css('cursor', 'default');
                core.alert("La sauvegarde a été générée avec succès.");
            },
            error: function(data){
                $('body').css('cursor', 'default');
                core.alert("Une erreur s'est produite, la sauvegarde n'a pas été générée !");
            },
            complete: function(){
                $("#configBackupSubmit").removeClass("disabled").prop("disabled", false);
            }
        });
    });

    /**
     * Aspect de la souris
    */
    $("#configBackupSubmit").click(function(event) {
        $('body').css('cursor', 'wait');
    });
});

