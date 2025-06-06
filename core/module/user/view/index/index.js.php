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

$(document).ready((function () {
    $(".userDelete").on("click", (function () {
        var _this = $(this);
        return message = "<?php echo helper::translate('Confirmer la suppression de cet utilisateur');?>", core.confirm(message, (function () {
            $(location).attr("href", _this.attr("href"))
        }))
    }));

    $("#userFilterGroup, #userFilterFirstName, #userFilterLastName").change(function () {
        $("#userFilterUserForm").submit();
    });
    // Transmettre la langue au script Datatables.net
    var lang = getCookie('ZWII_UI');
    var languageUrl = 'core/vendor/datatables/' + lang + '.json';
    $('#dataTables').DataTable({
        language: {
            url: languageUrl
        },
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tout"]],
        "columnDefs": [
            {
                target: 5,
                orderable: false,
                searchable: false
            },
            {
                target: 6,
                orderable: false,
                searchable: false
            }
        ]
    });
}));