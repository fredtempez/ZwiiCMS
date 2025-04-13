/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
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