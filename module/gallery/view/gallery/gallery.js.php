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
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

/**
 * Galerie d'image
 * SLB est activé pour tout le site
 * Le script est isolé dans une fonction et limité au conteneur de cette instance :
 * plusieurs galeries peuvent coexister sur la même page sans partager leurs images.
 */
(function () {
	'use strict';

	// Identifiant de cette instance (identifiant du module sur la page)
	const uid = '<?php
echo preg_replace('/[^A-Za-z0-9_-]/', '', (string) $this->getUrl(0)); ?>';

	$(document).ready(function () {
		const $container = $('#pictureContainer-' + uid);
		if ($container.length === 0) {
			return;
		}

		// Visionneuse limitée aux images de cette galerie
		new SimpleLightbox('#pictureContainer-' + uid + ' .galleryGalleryPicture', {
			captionSelector: "self",
			captionType: "data",
			captionsData: "caption",
			closeText: "&times;"
		});

		// Démarre en mode plein écran : image de couverture, à défaut la première image
		if ($container.hasClass('fullScreen')) {
			let $start = $container.find('a.galleryHomePicture').first();
			if ($start.length === 0) {
				$start = $container.find('a.galleryGalleryPicture').first();
			}
			if ($start.length) {
				$start[0].click();
			}
		}
	});
})();
