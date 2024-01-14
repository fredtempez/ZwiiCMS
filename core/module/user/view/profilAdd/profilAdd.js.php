/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */


$(document).ready(function () {

    // Désactive les éléments liés au blog
    if (!$("#profilAddBlogComment").prop("checked")) {
        $(".blogAddCommentOptions").prop("disabled", true);
        $(".blogAddCommentOptions").slideUp();
    } else {
        $(".blogAddCommentOptions").slideDown();
    }

    // Désactive les éléments liés au blog
    if (!$("#profilAddDownloadComment").prop("checked")) {
        $(".downloadAddCommentOptions").prop("disabled", true);
        $(".downloadAddCommentOptions").slideUp();
    } else {
        $(".downloadAddCommentOptions").slideDown();
    }

    // Désactive les éléments liés au blog
    if (!$("#profilAddDownloadCategories").prop("checked")) {
        $(".downloadAddCategoryOptions").prop("disabled", true);
        $(".downloadAddCategoryOptions").slideUp();
    } else {
        $(".downloadAddCategoryOptions").slideDown();
    }


    // À chaque inversion de l'état du checkbox avec l'id "profilAddFileManager", désactive ou active tous les éléments de la classe "filemanager" en fonction de l'état
    $("#profilAddFileManager").change(function () {
        if (!$(this).is(':checked')) {
            $(".filemanager").prop("disabled", true);
        } else {
            $(".filemanager").prop("disabled", false);
        }
    });

    // Désactive la gestion des pages pour les membres
    $('#profilAddGroup').change(function () {
        ;
        if ($(this).val() === '1') {
            $('.containerPage').slideUp();
        } else {
            $('.containerPage').slideDown();
        }
    });

    // Gérer l'évènement sur les commentaires du blog
    $("#profilAddBlogComment").change(function () {
        if (!$(this).is(':checked')) {
            $(".blogAddCommentOptions").slideUp();
        } else {
            $('.blogAddCommentOptions input[type="checkbox"]').prop('checked', false);
            $(".blogAddCommentOptions").slideDown();
        }
    });

    // Gérer l'évènement sur les commentaires du blog
    $("#profilAddDownloadComment").change(function () {
        if (!$(this).is(':checked')) {
            $(".downloadAddCommentOptions").slideUp();
        } else {
            $('.downloadAddCommentOptions input[type="checkbox"]').prop('checked', false);
            $(".downloadAddCommentOptions").slideDown();
        }
    });

    // Gérer l'évènement sur les commentaires du blog
    $("#profilAddDownloadCategories").change(function () {
        if (!$(this).is(':checked')) {
            $(".downloadAddCategoryOptions").slideUp();
        } else {
            $('.downloadAddCategoryOptions input[type="checkbox"]').prop('checked', false);
            $(".downloadAddCategoryOptions").slideDown();
        }
    });

    // Gérer l'évènement affichage des
    $("#profilAddPageModule").change(function () {
        if (!$(this).is(':checked')) {
            $(".containerModule").slideUp();
            // Décocher les checkboxes dans la classe .containerModule
            $('.containerModule input[type="checkbox"]').prop('checked', false);
        } else {
            $(".containerModule").slideDown();
        }
    });

    // Gérer l’évènement de modification de la checkbox #profilAddPageEdit
    $('#profilAddPageEdit').change(function () {
        if ($(this).is(':checked')) {
            // Activer les autres checkboxes
            $('#profilAddPageModule, #profilAddPagecssEditor, #profilAddPagejsEditor').prop('disabled', false);
        } else {
            // Désactiver les autres checkboxes
            $('#profilAddPageModule, #profilAddPagecssEditor, #profilAddPagejsEditor').prop('checked', false).prop('disabled', true);
            // Désactiver les modules et tout décocher
            $(".containerModule").slideUp();
            $('.containerModule input[type="checkbox"]').prop('checked', false);
        }
    });


});
