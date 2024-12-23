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

$( document ).ready(function() {


	/**
	 * Tri de la galerie avec drag and drop
	 */
	$("#galleryTable").tableDnD({
		onDrop: function(table, row) {
			$("#galleryConfigFilterResponse").val($.tableDnD.serialize());
			sortGalleries();
			location.reload();
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