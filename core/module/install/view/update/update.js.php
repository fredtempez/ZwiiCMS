/**
 * Exécution des différentes étapes de mise à jour
 */
function step(i, data) {
	// tableau des erreurs
	var errors = [
		"<?php echo template::topic('Préparation de la mise à jour'); ?>",
		"<?php echo template::topic('Téléchargement et validation de l\'archive'); ?>",
		"<?php echo template::topic('Installation'); ?>",
		"<?php echo template::topic('Configuration'); ?>"
	];
	// Affiche le texte de progression
	$(".installUpdateProgressText").hide();
	$(".installUpdateProgressText[data-id=" + i + "]").show();
	// Requête ajax
	$.ajax({
		type: "POST",
		url: "<?php echo helper::baseUrl(false); ?>?install/steps", // Ignore la réécriture d'URL
		data: {
			step: i,
			data: data
		},
		// Succès de la requête
		success: function(result) {
			setTimeout(function() {
				// Succès
				if(result.success === true) {
					// Fin de la mise à jour
					if(i === 4) {
						// Affiche le message de succès
						$("#installUpdateSuccess").show();
						// Déverrouille le bouton "Terminer"
						$("#installUpdateEnd").removeClass("disabled");
						// Cache le texte de progression
						$("#installUpdateProgress").hide();
					}
					// Prochaine étape
					else {
						step(i + 1, result.data);
					}
				}
				// Échec
				else {
					// Affiche le message d'erreur
					$("#installUpdateErrorStep").text(errors[i]);
					$("#installUpdateError").show();
					// Déverrouille le bouton "Terminer"
					$("#installUpdateEnd").removeClass("disabled");
					// Cache le texte de progression
					$("#installUpdateProgress").hide();
					// Affiche le résultat dans la console
					console.error(result);
					$("#installUpdateErrorMessage").text(result.replace( /<[^p].*?>/g, '' ));
				}
			}, 2000);
		},
		// Échec de la requête
		error: function(xhr) {
			// Affiche le message d'erreur
			$("#installUpdateErrorStep").text(errors[1]);
			$("#installUpdateError").show();
			// Déverrouille le bouton "Terminer"
			$("#installUpdateEnd").removeClass("disabled");
			// Cache le texte de progression
			$("#installUpdateProgress").hide();
			// Affiche l'erreur dans la console
			console.error(xhr.responseText);
			$("#installUpdateErrorMessage").text(xhr.responseText.replace( /<[^p].*?>/g, '' ));
		}
	});
}
$(window).on("load", step(1, null));