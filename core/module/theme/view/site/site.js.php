/**
 * This file is part of Zwii.
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
 * Aperçu en direct
 */
$("input, select").on("change",function() {

	/**
	* Option de marge si la taille n'est pas fluide
	*/
	if ($('#themeSiteWidth').val() === '100%') {
		$("#themeSiteMarginWrapper").prop("checked", true);
		$("#themeSiteMarginWrapper").hide();
	} else {
		$("#themeSiteMarginWrapper").show();
	}

});
