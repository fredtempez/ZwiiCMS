function step(i, data) {
    var errors = ["<?php echo helper::translate('Préparation de la mise à jour'); ?>", "<?php echo helper::translate('Téléchargement et validation de l\'archive'); ?>", "<?php echo helper::translate('Installation'); ?>", "<?php echo helper::translate('Configuration'); ?>"];
    $(".installUpdateProgressText").hide(), $(".installUpdateProgressText[data-id=" + i + "]").show();
    $.ajax({
        type: "POST",
        url: "<?php echo helper::baseUrl(false); ?>?install/steps",
        data: {
            step: i,
            data: data
        },
        success: function (result) {
             if (result.success != "1") { // Vérification de la propriété "success"
            // Appel de la fonction de gestion d'erreur
                showError(i, result, errors);
                return;
            }
            setTimeout((function () {
                if (4 === i) {
                    $("#installUpdateSuccess").show();
                    $("#installUpdateEnd").removeClass("disabled");
                    $("#installUpdateProgress").hide();
                } else {
                    step(i + 1, result.data);
                }
            }), 2e3)
        },
        error: function (xhr) {
            // Appel de la fonction de gestion d'erreur
            showError(1, xhr.responseText, errors);
        }
    });
}

function showError(step, message, errors) {
    $("#installUpdateErrorStep").text(errors[step]);
    $("#installUpdateError").show();
    $("#installUpdateEnd").removeClass("disabled");
    $("#installUpdateProgress").hide();

    // Vérifier si l'accolade ouvrante est trouvée et qu'elle n'est pas en première position
    if (typeof message !== 'object') {
        
        // Trouver la position du premier "{" pour repérer le début du tableau
        const startOfArray = message.indexOf('{');

        // Extraire le message du warning jusqu'au début du tableau
        const warningMessage = message.substring(0, startOfArray).trim();

        // Extraire le tableau JSON entre les accolades
        const jsonString = message.substring(startOfArray);
        const jsonData = JSON.parse(jsonString);

        // Afficher les résultats
        console.log("Message du warning:", warningMessage);
        console.log("Données du tableau:", jsonData);
        $("#installUpdateErrorMessage").html("<strong>Détails de l'erreur :</strong><br> " + jsonData.data.replace(/^"(.*)"$/, '$1') + "<br>" + warningMessage.replace(/<[^p].*?>/g, ""));
    } else {
        // Si l'accolade ouvrante n'est pas trouvée ou en première position, afficher un message d'erreur
        console.log("Aucune donnée JSON trouvée dans le message d'erreur.");
        // Vous pouvez également faire quelque chose d'autre ici, par exemple, afficher un message à l'utilisateur, etc.
        $("#installUpdateErrorMessage").html(message);
    }
}

$(window).on("load", function () {
    step(1, null);
});