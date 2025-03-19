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
 * Galerie d'image
 * SLB est activé pour tout le site
 */
var b = new SimpleLightbox('.galleryGalleryPicture', {
	captionSelector: "self",
	captionType: "data",
	captionsData: "caption",
	closeText: "&times;"
});

$( document ).ready(function() {
	// Démarre en mode plein écran
	if ( $("#pictureContainer").hasClass("fullScreen") ) {
		$('a#homePicture')[0].click();
	}
	
 });
