function step(i, data) {
    var errors = [
        "<?php
echo Helper::translate('Préparation de la mise à jour'); ?>",
        "<?php
echo Helper::translate('Téléchargement et validation de l\'archive'); ?>",
        "<?php
echo Helper::translate('Installation'); ?>",
        "<?php
echo Helper::translate('Configuration'); ?>"
    ];
    $(".installUpdateProgressText").hide();
    $(".installUpdateProgressText[data-id=" + i + "]").show();

    $("body").css("cursor", "wait");

    $.ajax({
        type: "POST",
        url: "<?php
echo Helper::baseUrl(false); ?>?install/steps",
        data: {
            step: i,
            data: data
        },
        success: function (result) {
            setTimeout(function () {
                if (4 === i) {
                    $("#installUpdateSuccess").show();
                    $("body").css("cursor", "default");
                    $("#installUpdateProgress").hide();
                    // Popup post-mise à jour (message téléchargé depuis le serveur)
                    if (result.updateMessage && String(result.updateMessage).trim() !== '') {
                        $("#installUpdateMessageBody").html(result.updateMessage);
                        $("#installUpdateMessage").css("display", "flex");
                    } else {
                        $("#installUpdateEnd").removeClass("disabled");
                    }
                } else {
                    step(i + 1, result.data);
                }
            }, 2000);
        },
        error: function (xhr) {
            console.log(i);
            console.log(xhr.responseText);
            console.log(errors);
            showError(i, xhr.responseText, errors);

            // Vérification du code d'erreur HTTP pour gérer la déconnexion
            if (xhr.status === 401 || xhr.status === 403) {

                alert("Votre session a expiré. Veuillez vous reconnecter. La réactivation de la réécriture des URL a échoué.");
                window.location.href = "?user/login"; // Redirige vers la page de connexion
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

    var friendly = null;

    // Si le message est déjà un objet
    if (typeof message === 'object' && message !== null) {
        friendly = message.data || message.message || null;
    } else if (typeof message === 'string') {
        // Tentative de parsing JSON direct
        try {
            var parsed = JSON.parse(message.trim());
            friendly = parsed.data || parsed.message || null;
        } catch (e) {
            // Pas du JSON pur : chercher un objet JSON dans le message
            var startOfArray = message.indexOf('{');
            if (startOfArray !== -1) {
                var jsonString = message.substring(startOfArray);
                try {
                    var parsed2 = JSON.parse(jsonString);
                    friendly = parsed2.data || parsed2.message || null;
                } catch (e2) {
                    friendly = null;
                }
            }
        }
    }

    if (friendly) {
        // Retirer les guillemets entourant la chaîne si nécessaire
        friendly = String(friendly).replace(/^"(.*)"$/, '$1');
        $("#installUpdateErrorMessage").html("<strong>Détails de l'erreur :</strong><br> " + friendly);
    } else {
        $("#installUpdateErrorMessage").html("Message d'erreur : " + message);
    }
}

$(document).ready(function () {
    $("#installUpdateMessageClose").on("click", function (e) {
        e.preventDefault();
        $("#installUpdateMessage").css("display", "none");
        $("#installUpdateEnd").removeClass("disabled");
    });
});

$(window).on("load", function () {
    step(1, null);
});
