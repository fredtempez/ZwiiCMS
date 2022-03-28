/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

$( document ).ready(function() {


	/**
	 * Tri de la galerie avec drag and drop
	 */
	$("#galleryTable").tableDnD({
		onDrop: function(table, row) {
			$("#galleryConfigFilterResponse").val($.tableDnD.serialize());
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
});


/**
 * Tri dynamique des galeries
 */

function sortGalleries() {
	var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/sortGalleries";
	var data = $("#galleryConfigFilterResponse").val();
	$.ajax({
		type: "POST",
		url: url ,
		data: {
			response : data
		}
	});
}