/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

 $( document ).ready(function() {


	/**
	 * Tri de la galerie avec drag and drop
	 */
	$("#galleryTable").tableDnD({
		onDrop: function(table, row) {
			$("#galleryAddFilterResponse").val($.tableDnD.serialize());
		},
		onDragStop : function(table, row) {
			// Affiche le bouton de tri après un déplacement
			//$(":input[type='submit']").prop('disabled', false);
			// Sauvegarde le tri
			sortGalleries();
		},
		// Supprime le tiret des séparateurs
		serializeRegexp:  ""
	});



	/**
	 * Confirmation de suppression
	 */
	$(".galleryAddDelete").on("click", function() {
		var _this = $(this);
		var message = "<?php
echo helper::translate('Supprimer cette galerie ?'); ?>";
		return core.confirm(message, function() {
			$(location).attr("href", _this.attr("href"));
		});
	});

});
