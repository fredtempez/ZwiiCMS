/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

/**
 * Tri dynamique de la galerie
 */
$( document ).ready(function() {
	$("#galleryTable").tableDnD({		
		onDrop: function(table, row) {
			$("#galleryConfigFilterResponse").val($.tableDnD.serialize());
		},
		serializeRegexp:  "[^\_]*$"
	});
});

// Activer le bouton de tri uniquement après un tri
$("#galleryTable").click(function() {
	if ($("#galleryConfigFilterResponse").val() != "") {
		$(":input[type='submit']").prop('disabled', false);
	}
});

/**
 * Confirmation de suppression
 */
$(".galleryConfigDelete").on("click", function() {
	var _this = $(this);
	return core.confirm("Êtes-vous sûr de vouloir supprimer cette galerie ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});



/**
 * Liste des dossiers
 */
var oldResult = [];
var directoryDOM = $("#galleryConfigDirectory");
var directoryOldDOM = $("#galleryConfigDirectoryOld");
function dirs() {
	$.ajax({
		type: "POST",
		url: "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/dirs",
		success: function(result) {
			if($(result).not(oldResult).length !== 0 || $(oldResult).not(result).length !== 0) {
				directoryDOM.empty();
				for(var i = 0; i < result.length; i++) {
					directoryDOM.append(function(i) {
						var option = $("<option>").val(result[i]).text(result[i]);
						if(directoryOldDOM.val() === result[i]) {
							option.prop("selected", true);
						}
						return option;
					}(i))
				}
				oldResult = result;
			}
		}
	});
}
dirs();
// Actualise la liste des dossiers toutes les trois secondes
setInterval(function() {
	dirs();
}, 3000);

/**
 * Stock le dossier choisi pour le re-sélectionner en cas d'actualisation ajax de la liste des dossiers
 */
directoryDOM.on("change", function() {
	directoryOldDOM.val($(this).val());
});

