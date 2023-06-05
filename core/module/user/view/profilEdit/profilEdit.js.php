/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */


$(document).ready(function () {
    // Désactive tous les éléments de la classe "filemanager" si le checkbox avec l'id "profilEditFileManager" est décoché au chargement de la page
    if (!$("#profilEditFileManager").prop("checked")) {
        $(".filemanager").prop("disabled", true);
    }
    // Vérifier l'état initial de la checkbox #profilEditPageEdit
    if ($('#profilEditPageEdit').is(':checked')) {
        // Activer les autres checkboxes
        $('#profilEditPageModule, #profilEditPagecssEditor, #profilEditPagejsEditor').prop('disabled', false);
    } else {
        // Désactiver les autres checkboxes
        $('#profilEditPageModule, #profilEditPagecssEditor, #profilEditPagejsEditor').prop('checked', false).prop('disabled', true);
    }
    if (!$("#profilEditPageModule").is(':checked')) {
        $(".containerModule").slideUp();
    } else {
        $(".containerModule").slideDown();
    }

    // À chaque inversion de l'état du checkbox avec l'id "profilEditFileManager", désactive ou active tous les éléments de la classe "filemanager" en fonction de l'état
    $("#profilEditFileManager").change(function () {
        if (!$(this).is(':checked')) {
            $(".filemanager").prop("disabled", true);
        } else {
            $(".filemanager").prop("disabled", false);
        }
    });

    // Gérer l'évènement affichage des
    $("#profilEditPageModule").change(function () {
        if (!$(this).is(':checked')) {
            $(".containerModule").slideUp();
            // Décocher les checkboxes dans la classe .containerModule
            $('.containerModule input[type="checkbox"]').removeAttr('checked');
        } else {
            $(".containerModule").slideDown();
        }
    });

    // Gérer l’évènement de modification de la checkbox #profilEditPageEdit
    $('#profilEditPageEdit').change(function () {
        if ($(this).is(':checked')) {
            // Activer les autres checkboxes
            $('#profilEditPageModule, #profilEditPagecssEditor, #profilEditPagejsEditor').prop('disabled', false);
        } else {
            // Désactiver les autres checkboxes
            $('#profilEditPageModule, #profilEditPagecssEditor, #profilEditPagejsEditor').prop('checked', false).prop('disabled', true);
            // Désactiver les modules et tout décocher
            $(".containerModule").slideUp();
            $('.containerModule input[type="checkbox"]').removeAttr('checked');
        }
    });

});
