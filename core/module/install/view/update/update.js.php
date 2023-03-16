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
            if (!result.success) { // Vérification de la propriété "success"
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
    console.error(message);
    $("#installUpdateErrorMessage").text(message.replace(/<[^p].*?>/g, ""));
}

$(window).on("load", function() {
    step(1, null);
});
