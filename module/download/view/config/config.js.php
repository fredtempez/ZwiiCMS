/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */


$(document).ready(function () {

	// Confirmation de suppression - gérée dans drawCallback de DataTables

	$('#dataTables').DataTable({
		language: {
			url: "core/vendor/datatables/fr_FR.json"
		},
		order: [[0, 'desc']],
		locale: 'fr',
		stateSave: true,
		info: true,
		buttons: [
			{
				extend: 'csv',
				text: '<i class="zwiico-code"></i>',
				titleAttr: 'Exporter les données au format CSV',
			},
			{
				extend: 'copy',
				text: '<i class="zwiico-docs"></i>',
				titleAttr: 'Copier dans le presse papier',
			},
			{
				extend: 'print',
				text: '<i class="zwiico-print"></i>',
				titleAttr: 'Imprimer ou générer un PDF',
			}
		],
		drawCallback: function () {
			// Attacher l'événement aux boutons de suppression après chaque redraw
			$('.downloadConfigDelete').off('click').on('click', function (e) {
				e.preventDefault();
				var _this = $(this);
				var href = _this.attr('href') || _this.data('href') || _this.closest('a').attr('href');
				core.confirm("Êtes-vous sûr de vouloir supprimer cet item ?", function () {
					window.location.href = href;
				});
				return false;
			});
		},
		dom: '<"top"lBf>rt<"bottom"p>',
		"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Tout"]],
		"columnDefs": [
			{
				targets: 3,
				type: 'datetime',
				searchable: false,
				render: function (data, type, row) {
					if (type === 'display') {
						if (typeof data === 'number' || !isNaN(data)) {
							return moment(Number(data) * 1000).format('DD/MM/YYYY HH:mm');
						} else {
							return data;
						}
					}
					// Pour le tri, retournez la valeur au format ISO
					return moment(Number(data) * 1000).toISOString();
				},
			targets: [6, 7, 8],
			searchable: false,
			orderable: false
			},
		]
	});
});