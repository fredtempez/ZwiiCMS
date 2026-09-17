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
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */

/**
 * Affichage conditionnel des options selon le rapport de dimensions.
 *
 *  - Automatique  : seule la largeur est utilisée (hauteur = ratio naturel de l'image)
 *  - Hauteur fixe : la hauteur fixe s'applique, le ratio est masqué
 *  - Ratio fixe   : le ratio s'applique, la hauteur fixe est masquée
 *  L'ajustement (remplir / contenir) n'a de sens qu'avec une hauteur fixe ou un ratio.
 */
$(document).ready(function () {

    function sliderThemeToggleDimensions() {
        var mode = $("#sliderThemeHeightMode").val();

        // Hauteur fixe : uniquement en mode "fixed"
        $("#sliderThemeHeightPxCol").toggleClass("displayNone", mode !== "fixed");

        // Ratio : uniquement en mode "ratio"
        $("#sliderThemeHeightRatioCol").toggleClass("displayNone", mode !== "ratio");

        // Ajustement : uniquement en mode "fixed" ou "ratio"
        $("#sliderThemeFitModeCol").toggleClass("displayNone", mode !== "fixed" && mode !== "ratio");
    }

    // État initial (cohérent avec le rendu serveur, évite un clignotement)
    sliderThemeToggleDimensions();

    // Réagit au changement du rapport de dimensions
    $("#sliderThemeHeightMode").on("change", function () {
        sliderThemeToggleDimensions();
    });

});
