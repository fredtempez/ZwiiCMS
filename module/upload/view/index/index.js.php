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
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionner tous les champs avec la classe fileUpload
        document.querySelectorAll('.fileUpload').forEach(function (input) {
            input.addEventListener('change', function () {
                // Récupérer le placeholder depuis l'attribut data
                const placeholder = this.getAttribute('data-placeholder') || 'Aucun fichier sélectionné';

                // Récupérer le nom du fichier ou utiliser le placeholder
                const fileName = this.files[0] ? this.files[0].name : placeholder;

                // Mettre à jour l'affichage du nom de fichier
                const filenameDisplay = document.getElementById(`${this.id}-filename`);
                if (filenameDisplay) {
                    filenameDisplay.textContent = fileName;
                }
            });
        });
    });
});