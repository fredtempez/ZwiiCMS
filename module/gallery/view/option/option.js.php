/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

/**
 * Gestion des événements
 */

// Activation des options pour les galeries non uniques
$("#galleryOptionShowUniqueGallery").click(function() {
    if ($(this).prop("checked")) {
        $("#galleryOptionBackPosition, #galleryOptionBackAlign").prop( "disabled", true );
    } else {
        $("#galleryOptionBackPosition, #galleryOptionBackAlign").prop( "disabled", false );
    }
});
