// Initialisation 
// Ajouter l'attribut data-lity afin de faire la liaison avec lity
$(document).ready(function() {
    $("a[rel='data-lity']").attr(
        "data-lity",""
    );
    $(".lity-content").addClass("notranslate");
});