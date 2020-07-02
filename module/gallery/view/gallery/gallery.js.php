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
	var fullscreen = <?php echo json_encode($this->getData(['module', $this->getUrl(0), $this->getUrl(1), 'config', 'fullScreen'])); ?>;
	console.log(fullscreen);
	if ( fullscreen === true) {
		$('a#homePicture')[0].click();
	}
 });
