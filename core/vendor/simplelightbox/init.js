	// Paramètres d'initialisation
	$(document).ready(function() {

		// Ajouter la classe Gallery afin de faire la liaison avec simplelightbox
		$("a[rel='gallery']").addClass(
			"gallery",""
		);

		// Démarrer le script
		var a = new SimpleLightbox('.gallery', { 
			closeText:"&times;",
			captionsData:'alt' 
		});
	});
