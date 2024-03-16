

$(document).ready(function() {
    // Gérer le clic sur les éléments avec la classe toggle
    $('.toggle').click(function() {
        // Trouver le prochain élément de type ul avec la classe sub-items
        var subItems = $(this).next('ul.sub-items');
        // Toggle pour afficher ou cacher les sous-éléments
        subItems.slideToggle();
    });
});
