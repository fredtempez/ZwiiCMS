/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

$(document).ready(function() {
    // Vérifie si la variable PHP $time est vide ou non
    var time = '<?php echo $this->getData(["module", $this->getUrl(0), "content", $this->getUrl(2), "time"]); ?>';

    // Initialisation de l'affichage au chargement de la page
    if (time === '') {
        $('#calendarEditAllDay').prop('checked', true);
        $('#calendarEditTimeWrapper').slideUp(); // Masque immédiatement sans animation
    } else {
        $('#calendarEditAllDay').prop('checked', false);
        $('#calendarEditTimeWrapper').slideDown(); // Affiche immédiatement sans animation
    }

    // Ajoute un événement sur le changement de l'état de la checkbox calendarEditAllDay
    $('#calendarEditAllDay').on('change', function() {
        if ($(this).is(':checked')) {
            $('#calendarEditTime').val('');
            $('#calendarEditTimeWrapper').slideUp(); // Masque avec un effet de slide
        } else {
            $('#calendarEditTimeWrapper').slideDown(); // Affiche avec un effet de slide
        }
    });
});
