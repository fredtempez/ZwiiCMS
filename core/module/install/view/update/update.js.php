function step(i, data) {
    var errors = ["<?php echo helper::translate('Préparation de la mise à jour'); ?>", "<?php echo helper::translate('Téléchargement et validation de l\'archive'); ?>", "<?php echo helper::translate('Installation'); ?>", "<?php echo helper::translate('Configuration'); ?>"];
    $(".installUpdateProgressText").hide(), $(".installUpdateProgressText[data-id=" + i + "]").show();

    $("body").css("cursor", "wait");

    $.ajax({
        type: "POST",
        url: "<?php echo helper::baseUrl(false); ?>?install/steps",
        data: {
            step: i,
            data: data
        },
        success: function (result) {
            setTimeout((function () {
                if (4 === i) {
                    $("#installUpdateSuccess").show();
                    $("body").css("cursor", "default");
                    $("#installUpdateEnd").removeClass("disabled");
                    $("#installUpdateProgress").hide();
                } else {
                    step(i + 1, result.data);
                }
            }), 2e3)
        },
        error: function (xhr) {
            // Balance tout dans la console
            console.log(i);
            console.log(xhr.responseText);
            console.log(errors);
            // Appel de la fonction de gestion d'erreur
            showError(i, xhr.responseText, errors);
        }
    });
}

function showError(step, message, errors) {
    $("body").css("cursor", "default");
    $("#installUpdateErrorStep").text(errors[step] + " (étape n°" + step + ")");
    $("#installUpdateError").show();
    $("#installUpdateEnd").removeClass("disabled");
    $("#installUpdateProgress").hide();

    if (typeof message !== 'object') {
        const startOfArray = message.indexOf('{');

        // Vérifier que l'accolade existe et n'est pas en première position
        if (startOfArray !== -1 && startOfArray > 0) {
            const warningMessage = message.substring(0, startOfArray).trim();
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
                // En cas d'erreur de parsing, afficher un message générique
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