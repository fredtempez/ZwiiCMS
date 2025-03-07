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

$(document).ready(function () {

  // Centrage de la carte et niveau de zoom
  const jsonOptions = '<?php echo json_encode($module::$galleriesCenter); ?>';
  const objOptions = JSON.parse(jsonOptions);


  // Initialisation de la carte
  const map = L.map('map').setView([objOptions.lat, objOptions.long], objOptions.zoom - 1);

  // Ajouter une couche de tuiles OpenStreetMap
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);


  // Les données PHP converties en JSON pour JavaScript
  const json = '<?php echo json_encode($module::$galleries); ?>';
  const obj = JSON.parse(json);


  // Ajouter les marqueurs à la carte
  obj.forEach(function (location) {
    const marker = L.marker([location.lat, location.long], {
      title: location.label
    });
    marker.addTo(map);
    marker.bindPopup('<p>' + location.label + '</p><a href="' + location.img + '" data-lity><img src="' + location.thumb + '" alt="Thumbnail" class="thumbnail"></a>', {
      minWidth: 150,
      maxWidth: 150,
      minHeight: 150
    });
  });
});