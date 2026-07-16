/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International 
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */
$(document).ready((function () {
    // Confirmation de suppression - gérée dans drawCallback de DataTables
    
    // Transmettre la langue au script Datatables.net
    var lang = getCookie('ZWII_UI');
    var languageUrl = 'core/vendor/datatables/' + lang + '.json';
    $('#dataTables').DataTable({
        language: {
            url: languageUrl
        },
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tout"]],
        drawCallback: function () {
            // Attacher l'événement aux boutons de suppression après chaque redraw
            $('.themeFontDelete').off('click').on('click', function (e) {
                e.preventDefault();
                var _this = $(this);
                var href = _this.attr('href') || _this.data('href') || _this.closest('a').attr('href');
                core.confirm("Êtes-vous sûr de vouloir supprimer cette fonte ?", function () {
                    window.location.href = href;
                });
                return false;
            });
        },
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
}));
