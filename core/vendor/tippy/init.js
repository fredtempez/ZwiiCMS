/**
 * Initialisation de Tippy
 */
$(document).ready(function() {
	// Tooltip des aides
	tippy(".helpButton", {
        arrow: true,
        arrowType: "round",
		placement: "top"
	});
	// Tooltip des attributs title
	tippy("[data-tippy-content]", {
		arrow: true,
		placement: "top"
    });
    // Pour les images map, pas de flèche, bulle haut suivant le curseur    
  
    tippy('img[title], a[title], area[title]', {
      
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
    // Pour les images map, pas de flèche, bulle haut suivant le curseur
    tippy("#image-map", {
        placement: "top",
        followCursor: true,
        animation: "fade",
        animateFill: true
    });
});
