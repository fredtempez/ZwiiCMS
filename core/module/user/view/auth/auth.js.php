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
    $('#userAuthKey').on('input', function () {
        // Récupère la valeur du champ
        let input = $(this).val();
        
        // Supprime tous les caractères non numériques
        input = input.replace(/\D/g, '');
        
        // Limite à 6 caractères maximum
        if (input.length > 6) {
            input = input.substring(0, 6);
        }

        // Met à jour la valeur du champ
        $(this).val(input);
    });
});

