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
            // Balance tout dans la console
            console.log("Erreur à l'étape :", i);
            console.log("Détails de l'erreur :", xhr.responseText);
            console.log("Messages d'erreurs :", errors);
            // Appel de la nouvelle fonction d'erreur
            showError(i, errors);
        }
    });
}

function showError(step, errors) {
    $("body").css("cursor", "default");
    $("#installUpdateErrorStep").text(errors[step] + " (étape n°" + step + ")");
    $("#installUpdateError").show();
    $("#installUpdateEnd").removeClass("disabled");
    $("#installUpdateProgress").hide();

    // Affiche un message générique demandant de consulter la console
    $("#installUpdateErrorMessage").html("Une erreur est survenue. Veuillez consulter la console du navigateur pour plus de détails.");
}

$(window).on("load", function () {
    step(1, null);
});
