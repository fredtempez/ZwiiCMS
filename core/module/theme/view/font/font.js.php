/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
$(".themeFontDelete").on("click", (function() {
    var _this = $(this);
    return core.confirm("Êtes-vous sûr de vouloir supprimer cette fonte ?", (function() {
        $(location).attr("href", _this.attr("href"))
    }))
}));
$('#dataTables').DataTable({
    language: {
        url: "core/vendor/datatables/french.json",
    },
    locale: 'fr',
    stateSave: true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tout"]],
    "columnDefs": [{
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