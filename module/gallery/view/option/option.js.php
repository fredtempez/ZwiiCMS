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

/**
 * Gestion des événements
 */

// Activation des options pour les galeries non uniques
$("#galleryOptionShowUniqueGallery").click(function() {
    if ($(this).prop("checked")) {
        $("#galleryOptionBackPosition, #galleryOptionBackAlign").prop( "disabled", true );
    } else {
        $("#galleryOptionBackPosition, #galleryOptionBackAlign").prop( "disabled", false );
    }
});


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
 
