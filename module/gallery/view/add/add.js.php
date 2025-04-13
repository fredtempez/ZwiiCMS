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
		var message = "<?php echo helper::translate('Supprimer cette galerie ?'); ?>";
		return core.confirm(message, function() {
			$(location).attr("href", _this.attr("href"));
		});
	});

});

/**
 * Liste des dossiers
 */
var oldResult = [];
var directoryDOM = $("#galleryAddDirectory");
var directoryOldDOM = $("#galleryAddDirectoryOld");
function dirs() {
    $.ajax({
        type: "POST",
        url: "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/dirs",
        dataType: "json",
        success: function(result) {
            directoryDOM.empty();
            $.each(result, function(index, item) {
                var option = $("<option>").val(item.path).text(item.name);
                if(directoryOldDOM.val() === item.path) {
                    option.prop("selected", true);
                }
                directoryDOM.append(option);
            });
            oldResult = result;
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