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
    $("#configBackupForm").submit( function(e){
        e.preventDefault();
        $("body").addClass("loading");
        var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/backup";
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize(),
            complete: function(r, s){
                $("body").removeClass("loading");
                $("body").append("<div id='notification' class='notificationSuccess'>Sauvegarde réalisée avec succès !</div>");
                $("#notification").delay("3000").fadeOut("1000");
            }
        });
    });
});

