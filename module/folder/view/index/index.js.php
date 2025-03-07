
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

$(document).ready(function () {
    // Gérer le clic sur les éléments avec la classe toggle
    $('.toggle').click(function () {
        // Trouver le prochain élément de type ul avec la classe sub-items
        var subItems = $(this).next('ul.sub-items');
        // Toggle pour afficher ou cacher les sous-éléments
        subItems.slideToggle();
    });

    // Gérer le clic sur "Déplier"
    $('#expand').click(function () {
        // Afficher tous les sous-éléments
        $('ul.sub-items').slideDown(); F
    });

    // Gérer le clic sur "Replier"
    $('#collapse').click(function () {
        // Cacher tous les sous-éléments
        $('ul.sub-items').slideUp();
    });

});
