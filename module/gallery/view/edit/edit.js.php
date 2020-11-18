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

/**
 * Liste des dossiers
 */
var oldResult = [];
var directoryDOM = $("#galleryEditDirectory");
var directoryOldDOM = $("#galleryEditDirectoryOld");
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

$('.homePicture').click(function(){ 
	$('.homePicture').prop('checked', false);
	$(this).prop('checked', true);
});

/**
 * Tri dynamique de la galerie
 */

$( document ).ready(function() {

	$("#galleryTable").tableDnD({		
		onDrop: function(table, row) {
			$("#galleryEditFormResponse").val($.tableDnD.serialize());
		},
		onDragStop : function(table, row) {		
			// Sauvegarde le tri
			sortPictures();
			$("#galleryEditFormResponse").val("");
		},
		serializeRegexp:  ""
	});

	if ($("#galleryEditSort").val() !==  "SORT_HAND") {
		$("#galleryTable tr").addClass("nodrag nodrop");
		$(".zwiico-sort").hide();
		$("#galleryTable").tableDnDUpdate();
	} else {
		$("#galleryTable tr").removeClass("nodrag nodrop");
		$(".zwiico-sort").show();
		$("#galleryTable").tableDnDUpdate();
	}

});

$("#galleryEditSort").change(function() {
	if ($("#galleryEditSort").val() !==  "SORT_HAND") {
		$("#galleryTable tr").addClass("nodrag nodrop");
		$(".zwiico-sort").hide();
		$("#galleryTable").tableDnDUpdate();
	} else {
		$("#galleryTable tr").removeClass("nodrag nodrop");
		$(".zwiico-sort").show();
		$("#galleryTable").tableDnDUpdate();
	}
});

/**
 * Tri dynamique des images
 */

function sortPictures() {
	var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/sortPictures";
	var d1 = $("#galleryEditFormResponse").val();
	var d2 = $("#galleryEditFormGalleryName").val();
	//var data = $('#galleryEditForm').serialize();
	$.ajax({
		type: "POST",
		url: url ,
		data: {
			response : d1,
			gallery: d2
		}
	});
}
