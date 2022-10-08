function step(i, data) {
    var errors = ["<?php echo helper::translate('Préparation de la mise à jour'); ?>", "<?php echo helper::translate('Téléchargement et validation de l\'archive'); ?>", "<?php echo helper::translate('Installation'); ?>", "<?php echo helper::translate('Configuration'); ?>"];
    $(".installUpdateProgressText").hide(), $(".installUpdateProgressText[data-id=" + i + "]").show(), $.ajax({
        type: "POST",
        url: "<?php echo helper::baseUrl(false); ?>?install/steps",
        data: {
            step: i,
            data: data
        },
        success: function (result) {
            setTimeout((function () {
                !0 === result.success ? 4 === i ? ($("#installUpdateSuccess").show(), $("#installUpdateEnd").removeClass("disabled"), $("#installUpdateProgress").hide()) : step(i + 1, result.data) : ($("#installUpdateErrorStep").text(errors[i]), $("#installUpdateError").show(), $("#installUpdateEnd").removeClass("disabled"), $("#installUpdateProgress").hide(), console.error(result), $("#installUpdateErrorMessage").text(result.replace(/<[^p].*?>/g, "")))
            }), 2e3)
        },
        error: function (xhr) {
            $("#installUpdateErrorStep").text(errors[1]), $("#installUpdateError").show(), $("#installUpdateEnd").removeClass("disabled"), $("#installUpdateProgress").hide(), console.error(xhr.responseText), $("#installUpdateErrorMessage").text(xhr.responseText.replace(/<[^p].*?>/g, ""))
        }
    })
}
$(window).on("load", step(1, null));