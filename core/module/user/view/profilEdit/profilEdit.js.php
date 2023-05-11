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

    // À chaque inversion de l'état du checkbox avec l'id "profilEditFileManager", désactive ou active tous les éléments de la classe "filemanager" en fonction de l'état
    $("#profilEditFileManager").change(function () {
        if (!$(this).prop("checked")) {
            $(".filemanager").prop("disabled", true);
        } else {
            $(".filemanager").prop("disabled", false);
        }
    });
});
