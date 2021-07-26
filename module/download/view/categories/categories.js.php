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
 * @link http://zwiicms.fr/
 */

/**
 * Confirmation de suppression
 */
    $(".categoriesDelete").on("click", function() {
    var _this = $(this);
    return core.confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?", function() {
        $(location).attr("href", _this.attr("href"));
    });
});

/**
 * Gestion des blocs dépliants
 */
 $( document).ready(function() {

    /**
 * Initialisation des blocs
 */

     var i = [ "params" ];
     $.each(i,function(e) {
         if (getCookie(i[e]) === "true") {
             $("#" + i[e]).find(".zwiico-plus-circled").hide();
             $("#" + i[e]).find(".zwiico-minus-circled").show();
             $("#" + i[e]).find(".blockContainer").show();
         }
     });
 
     /**
      *
      * Blocs dépliants
      */
 
     $("div .block").click(function(e) {
         $(this).find(".zwiico-plus-circled").toggle();
         $(this).find(".zwiico-minus-circled").toggle();
         $(this).find(".blockContainer").slideToggle();
         /*
         * Sauvegarder la position des blocs
         * true = bloc déplié
         */
         document.cookie = $(this).attr('id') + "=" + $(this).find(".zwiico-minus-circled").is(":visible") + ";expires=Fri, 31 Dec 9999 23:59:59 GMT;path=/;SameSite=Lax";
     }).on("click", "span > input, input, textarea, label, option, button, a:not(.inputFile), .blockContainer", function(e) {
         // Empêcher les déclenchements dans les blocs
         e.stopPropagation();
     });

});

/**
* Lire un cookie s'il existe
*/
function getCookie(name) {
var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
return v ? v[2] : null;
}