/**
 * Initialisation de Tippy
 */
$(document).ready(function () {

  // Tooltip des attributs title
  tippy("[data-tippy-content]", {
    arrow: true,
    placement: "top"
  });

  // Pour les images map, pas de flèche, bulle haut suivant le curseur    
  tippy('img[title], a[title]:not(.button), area[title]', {

    content(reference) {
      const title = reference.getAttribute('title')
      reference.removeAttribute('title')
      return title
    },

    placement: "top",
    followCursor: true,
    animation: "fade",
    animateFill: true
  });

  // Tooltip des aides 
  tippy('a.button[title]', {
    content(reference) {
      const title = reference.getAttribute('title')
      reference.removeAttribute('title')
      return title
    },
    delay: [1000,250],
    placement: "bottom",
    followCursor: false,
    arrow: true,
    animateFill: true,
    arrowType: "round",
  });

  // Pour les images map, pas de flèche, bulle haut suivant le curseur
  tippy("#image-map", {
    placement: "top",
    followCursor: true,
    animation: "fade",
    animateFill: true
  });
});