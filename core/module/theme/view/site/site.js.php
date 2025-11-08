/**
 * This file is part of Zwii.
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

/**
 * Aperçu en direct
 */
$(document).ready(function() {
    // Fonction pour mettre à jour la largeur
    function updateWidth() {
        var width = $('#themeSiteWidth').val();
        var margin = $('#themeSiteMargin').is(':checked');
        
        // Appliquer la largeur au conteneur #site
        $('#site').css({
            'max-width': width,
            'margin': margin ? '0 auto' : '0',
            'transition': 'all 0.3s ease-in-out'
        });
    }
    
    // Appliquer les valeurs initiales
    updateWidth();
    
    // Au changement de largeur
    $('#themeSiteWidth').on('change', function() {
        // Gestion de la marge automatique pour 100%
        if ($(this).val() === '100%') {
            $('#themeSiteMargin').prop('checked', true);
            $('#themeSiteMarginWrapper').hide();
        } else {
            $('#themeSiteMarginWrapper').show();
        }
        
        updateWidth();
    });
    
    // Au changement de la case à cocher de marge
    $('#themeSiteMargin').on('change', updateWidth);
});

