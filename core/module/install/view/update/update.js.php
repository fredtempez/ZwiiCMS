function step(i, data) {
    var errors = [
        "<?php echo helper::translate('Préparation de la mise à jour'); ?>",
        "<?php echo helper::translate('Téléchargement et validation de l\'archive'); ?>",
        "<?php echo helper::translate('Installation'); ?>",
        "<?php echo helper::translate('Configuration'); ?>"
    ];
    $(".installUpdateProgressText").hide();
    $(".installUpdateProgressText[data-id=" + i + "]").show();

    $("body").css("cursor", "wait");

    $.ajax({
        type: "POST",
        url: "<?php echo helper::baseUrl(false); ?>?install/steps",
        data: {
            step: i,
            data: data
        },
        success: function (result) {
            setTimeout(function () {
                if (4 === i) {
                    $("#installUpdateSuccess").show();
                    $("body").css("cursor", "default");
                    $("#installUpdateEnd").removeClass("disabled");
                    $("#installUpdateProgress").hide();
                } else {
                    step(i + 1, result.data);
                }
            }, 2000);
        },
        error: function (xhr) {
            console.log(i);
            console.log(xhr.responseText);
            console.log(errors);
            
            // Vérification du code d'erreur HTTP pour gérer la déconnexion
            if (xhr.status === 401 || xhr.status === 403) {
                alert("Votre session a expiré. Veuillez vous reconnecter.");
                window.location.href = "/login"; // Redirige vers la page de connexion
            } else {
                // Appel de la fonction de gestion d'erreur
                showError(i, xhr.responseText, errors);
            }
        }
    });
}

function showError(step, message, errors) {
    $("body").css("cursor", "default");
    $("#installUpdateErrorStep").text(errors[step] + " (étape n°" + step + ")");
    $("#installUpdateError").show();
    $("#installUpdateEnd").removeClass("disabled");
    $("#installUpdateProgress").hide();

    // Vérifier si l'accolade ouvrante est trouvée et qu'elle n'est pas en première position
    if (typeof message !== 'object') {

        // Trouver la position du premier "{" pour repérer le début du tableau
        const startOfArray = message.indexOf('{');

        if (startOfArray !== -1 && startOfArray > 0) {
            // Extraire le message du warning jusqu'au début du tableau
            const warningMessage = message.substring(0, startOfArray).trim();

            // Extraire le tableau JSON entre les accolades
            const jsonString = message.substring(startOfArray);
            
            try {
                const jsonData = JSON.parse(jsonString);

                // Afficher les résultats si le parsing JSON est réussi
                if (jsonData) {
                    $("#installUpdateErrorMessage").html("<strong>Détails de l'erreur :</strong><br> " +
                        jsonData.data.replace(/^"(.*)"$/, '$1') +
                        "<br>" +
                        warningMessage.replace(/<[^p].*?>/g, ""));
                }
            } catch (e) {
                // Afficher un message générique en cas d'erreur de parsing
                console.error("Erreur de parsing JSON : ", e);
                $("#installUpdateErrorMessage").html("Une erreur inattendue est survenue lors du traitement des détails de l'erreur.");
            }
        } else {
            // Si pas de JSON détecté, afficher le message brut
            $("#installUpdateErrorMessage").html("Message d'erreur : " + message);
        }
    } else {
        $("#installUpdateErrorMessage").html(message);
    }
}

$(window).on("load", function () {
    step(1, null);
});
