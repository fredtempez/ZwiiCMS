/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

var core = {};

/**
 * Crée un message d'alerte
 */
core.alert = function (text) {
    var lightbox = lity(function ($) {
        return $("<div>")
            .addClass("lightbox")
            .append(
                $("<span>").text(text),
                $("<div>")
                .addClass("lightboxButtons")
                .append(
                    $("<a>")
                    .addClass("button")
                    .text("Ok")
                    .on("click", function () {
                        lightbox.close();
                    })
                )
            )
    }(jQuery));
    // Validation de la lightbox avec le bouton entrée
    $(document).on("keyup", function (event) {
        if (event.keyCode === 13) {
            lightbox.close();
        }
    });
    return false;
};

/**
 * Génère des variations d'une couleur
 */
core.colorVariants = function (rgba) {
    rgba = rgba.match(/\(+(.*)\)/);
    rgba = rgba[1].split(", ");
    return {
        "normal": "rgba(" + rgba[0] + "," + rgba[1] + "," + rgba[2] + "," + rgba[3] + ")",
        "darken": "rgba(" + Math.max(0, rgba[0] - 15) + "," + Math.max(0, rgba[1] - 15) + "," + Math.max(0, rgba[2] - 15) + "," + rgba[3] + ")",
        "veryDarken": "rgba(" + Math.max(0, rgba[0] - 20) + "," + Math.max(0, rgba[1] - 20) + "," + Math.max(0, rgba[2] - 20) + "," + rgba[3] + ")",
        "text": core.relativeLuminanceW3C(rgba) > .22 ? "#222" : "#DDD"
    };
};

/**
 * Crée un message de confirmation
 */
core.confirm = function (text, yesCallback, noCallback) {
    var lightbox = lity(function ($) {
        return $("<div>")
            .addClass("lightbox")
            .append(
                $("<span>").text(text),
                $("<div>")
                .addClass("lightboxButtons")
                .append(
                    $("<a>")
                    .addClass("button grey")
                    .text("<?php echo helper::translate('Non');?>")
                    .on("click", function () {
                        lightbox.options('button', true);
                        lightbox.close();
                        if (typeof noCallback !== "undefined") {
                            noCallback();
                        }
                    }),
                    $("<a>")
                    .addClass("button")
                    .text("<?php echo helper::translate('Oui');?>")
                    .on("click", function () {
                        lightbox.options('button', true);
                        lightbox.close();
                        if (typeof yesCallback !== "undefined") {
                            yesCallback();
                        }
                    })
                )
            )
    }(jQuery));
    // Callback lors d'un clic sur le fond et sur la croix de fermeture
    lightbox.options('button', false);
    $(document).on('lity:close', function (event, instance) {
        if (
            instance.options('button') === false &&
            typeof noCallback !== "undefined"
        ) {
            noCallback();
        }
    });
    // Validation de la lightbox avec le bouton entrée
    $(document).on("keyup", function (event) {
        if (event.keyCode === 13) {
            lightbox.close();
            if (typeof yesCallback !== "undefined") {
                yesCallback();
            }
        }
    });
    return false;
};

/**
 * Scripts à exécuter en dernier
 */
core.end = function () {
    /**
     * Modifications non enregistrées du formulaire
     */
    var formDOM = $("form");
    // Ignore :
    // - TinyMCE car il gère lui même le message
    // - Les champs avec data-no-dirty
    var inputsDOM = formDOM.find("input:not([data-no-dirty]), select:not([data-no-dirty]), textarea:not(.editorWysiwyg):not([data-no-dirty])");
    var inputSerialize = inputsDOM.serialize();
    $(window).on("beforeunload", function () {
        if (inputsDOM.serialize() !== inputSerialize) {
            message = "<?php echo helper::translate('Les modifications que vous avez apportées ne seront peut-être pas enregistrées.');?>";
            return message;
        }
    });
    formDOM.submit(function () {
        $(window).off("beforeunload");
    });
};
$(function () {
    core.end();
});

/**
 * Ajoute une notice
 */
core.noticeAdd = function (id, notice) {
    $("#" + id + "Notice").text(notice).removeClass("displayNone");
    $("#" + id).addClass("notice");
};

/**
 * Supprime une notice
 */
core.noticeRemove = function (id) {
    $("#" + id + "Notice").text("").addClass("displayNone");
    $("#" + id).removeClass("notice");
};

/**
 * Scripts à exécuter en premier
 */
core.start = function () {
    /**
     * Remonter en haut au clic sur le bouton
     */
    var backToTopDOM = $("#backToTop");
    backToTopDOM.on("click", function () {
        $("body, html").animate({
            scrollTop: 0
        }, "400");
    });
    /**
     * Affiche / Cache le bouton pour remonter en haut
     */
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 200) {
            backToTopDOM.fadeIn();
        } else {
            backToTopDOM.fadeOut();
        }
    });
    /**
     * Cache les notifications
     */
    var notificationTimer;
    $("#notification")
        .on("mouseenter", function () {
            clearTimeout(notificationTimer);
            $("#notificationProgress")
                .stop()
                .width("100%");
        })
        .on("mouseleave", function () {
            // Disparition de la notification
            notificationTimer = setTimeout(function () {
                $("#notification").fadeOut();
            }, 3000);
            // Barre de progression
            $("#notificationProgress").animate({
                "width": "0%"
            }, 3000, "linear");
        })
        .trigger("mouseleave");
    $("#notificationClose").on("click", function () {
        clearTimeout(notificationTimer);
        $("#notification").fadeOut();
        $("#notificationProgress").stop();
    });

    /**
     * Traitement du formulaire cookies
     */
    $("#cookieForm").submit(function (event) {

        // Variables des cookies
        var getUrl = window.location;
        var domain = "domain=" + getUrl.hostname + ";";
        var e = new Date();
        e.setFullYear(e.getFullYear() + 1);
        var expires = "expires=" + e.toUTCString();

        // Stocke le cookie d'acceptation
        document.cookie = "ZWII_COOKIE_CONSENT=true;samesite=strict;" + domain + expires;
    });


    /**
     * Fermeture de la popup des cookies
     */
    $("#cookieConsent .cookieClose").on("click", function () {
        $('#cookieConsent').addClass("displayNone");
    });

    /**
     * Commande de gestion des cookies dans le footer
     */

    $("#footerLinkCookie").on("click", function () {
        $("#cookieConsent").removeClass("displayNone");
    });

    /**
     * Affiche / Cache le menu en mode responsive
     */
    var menuDOM = $("#menu");
    $("#toggle").on("click", function () {
        menuDOM.slideToggle();
    });
    $(window).on("resize", function () {
        if ($(window).width() > 768) {
            menuDOM.css("display", "");
        }
    });



    /**
     * Choix de page dans la barre de membre
     */
    $("#barSelectPage").on("change", function () {
        var pageUrl = $(this).val();
        if (pageUrl) {
            $(location).attr("href", pageUrl);
        }
    });

    /**
     * Champs d'upload de fichiers
     */
    // Mise à jour de l'affichage des champs d'upload
    $(".inputFileHidden").on("change", function () {
        var inputFileHiddenDOM = $(this);
        var fileName = inputFileHiddenDOM.val();
        if (fileName === "") {
            //fileName = "Sélectionner un fichier";
            fileName = "<?php echo helper::translate('Sélectionner un fichier');?>";
            $(inputFileHiddenDOM).addClass("disabled");
        } else {
            $(inputFileHiddenDOM).removeClass("disabled");
        }
        inputFileHiddenDOM.parent().find(".inputFileLabel").text(fileName);
    }).trigger("change");
    // Suppression du fichier contenu dans le champ
    $(".inputFileDelete").on("click", function () {
        $(this).parents(".inputWrapper").find(".inputFileHidden").val("").trigger("change");
    });
    // Suppression de la date contenu dans le champ
    $(".inputDateDelete").on("click", function () {
        $(this).parents(".inputWrapper").find(".datepicker").val("").trigger("change");
    });
    // Confirmation de mise à jour
    $("#barUpdate").on("click", function () {
        message = "<?php echo helper::translate('Mettre à jour') . ' ?';?>";
        return core.confirm(message, function () {
            $(location).attr("href", $("#barUpdate").attr("href"));
        });
    });
    // Confirmation de déconnexion
    $("#barLogout").on("click", function () {
        message = "<?php echo helper::translate('Se déconnecter') . '?';?>";
        return core.confirm(message, function () {
            $(location).attr("href", $("#barLogout").attr("href"));
        });
    });
    /**
     * Bloque la multi-soumission des boutons
     */
    $("form").on("submit", function () {
        $(this).find(".uniqueSubmission")
            .addClass("disabled")
            .prop("disabled", true)
            .empty()
            .append(
                $("<span>").addClass("zwiico-spin animate-spin")
            )
    });
    /**
     * Check adresse email
     */
    $("[type=email]").on("change", function () {
        var _this = $(this);
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if (pattern.test(_this.val())) {
            core.noticeRemove(_this.attr("id"));
        } else {
            message = "<?php echo helper::translate('Format incorrect');?>";
            core.noticeAdd(_this.attr("id"), message);
        }
    });

    /**
     * Iframes et vidéos responsives
     */
    var elementDOM = $("iframe, video, embed, object");
    // Calcul du ratio et suppression de la hauteur / largeur des iframes
    elementDOM.each(function () {
        var _this = $(this);
        _this
            .data("ratio", _this.height() / _this.width())
            .data("maxwidth", _this.width())
            .removeAttr("width height");
    });
    // Prend la largeur du parent et détermine la hauteur à l'aide du ratio lors du resize de la fenêtre
    $(window).on("resize", function () {
        elementDOM.each(function () {
            var _this = $(this);
            var width = _this.parent().first().width();
            if (width > _this.data("maxwidth")) {
                width = _this.data("maxwidth");
            }
            _this
                .width(width)
                .height(width * _this.data("ratio"));
        });
    }).trigger("resize");

    /*
     * Header responsive
     */
    $(window).on("resize", function () {
        var responsive = "<?php echo $this->getdata(['theme','header','imageContainer']);?>";
        if (responsive === "cover" || responsive === "contain") {
            var widthpx = "<?php echo $this->getdata(['theme','site','width']);?>";
            var width = widthpx.substr(0, widthpx.length - 2);
            var heightpx = "<?php echo $this->getdata(['theme','header','height']);?>";
            var height = heightpx.substr(0, heightpx.length - 2);
            var ratio = width / height;
            if (($(window).width() / ratio) <= height) {
                $("header").height($(window).width() / ratio);
            }
        }
    }).trigger("resize");



};


core.start();

/**
 * Confirmation de suppression
 */
$("#pageDelete").on("click", function () {
    var _this = $(this);
    message = "<?php echo helper::translate('Confirmez-vous la suppression de cette page ?');?>";
    return core.confirm(message, function () {
        $(location).attr("href", _this.attr("href"));
    });
});


/**
 * Calcul de la luminance relative d'une couleur
 */
core.relativeLuminanceW3C = function (rgba) {
    // Conversion en sRGB
    var RsRGB = rgba[0] / 255;
    var GsRGB = rgba[1] / 255;
    var BsRGB = rgba[2] / 255;
    // Ajout de la transparence
    var RsRGBA = rgba[3] * RsRGB + (1 - rgba[3]);
    var GsRGBA = rgba[3] * GsRGB + (1 - rgba[3]);
    var BsRGBA = rgba[3] * BsRGB + (1 - rgba[3]);
    // Calcul de la luminance
    var R = (RsRGBA <= .03928) ? RsRGBA / 12.92 : Math.pow((RsRGBA + .055) / 1.055, 2.4);
    var G = (GsRGBA <= .03928) ? GsRGBA / 12.92 : Math.pow((GsRGBA + .055) / 1.055, 2.4);
    var B = (BsRGBA <= .03928) ? BsRGBA / 12.92 : Math.pow((BsRGBA + .055) / 1.055, 2.4);
    return .2126 * R + .7152 * G + .0722 * B;
};

// Fonctions
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/; samesite=lax";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Define function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


$(document).ready(function () {
    /**
     * Affiche le sous-menu quand il est sticky
     */
    $("nav").mouseenter(function () {
        $("#navfixedlogout .navSub").css({
            'pointer-events': 'auto'
        });
        $("#navfixedconnected .navSub").css({
            'pointer-events': 'auto'
        });
    });
    $("nav").mouseleave(function () {
        $("#navfixedlogout .navSub").css({
            'pointer-events': 'none'
        });
        $("#navfixedconnected .navSub").css({
            'pointer-events': 'none'
        });
    });

    /**
     * Chargement paresseux des images et des iframes
     */
    $("img,picture,iframe").attr("loading", "lazy");

    /**
     * Effet accordéon
     */
    $('.accordion').each(function (e) {
        // on stocke l'accordéon dans une variable locale
        var accordion = $(this);
        // on récupère la valeur data-speed si elle existe
        var toggleSpeed = accordion.attr('data-speed') || 100;

        // fonction pour afficher un élément
        function open(item, speed) {
            // on récupère tous les éléments, on enlève l'élément actif de ce résultat, et on les cache
            accordion.find('.accordion-item').not(item).removeClass('active')
                .find('.accordion-content').slideUp(speed);
            // on affiche l'élément actif
            item.addClass('active')
                .find('.accordion-content').slideDown(speed);
        }

        function close(item, speed) {
            accordion.find('.accordion-item').removeClass('active')
                .find('.accordion-content').slideUp(speed);
        }

        // on initialise l'accordéon, sans animation
        open(accordion.find('.active:first'), 0);

        // au clic sur un titre...
        accordion.on('click', '.accordion-title', function (ev) {
            ev.preventDefault();
            // Masquer l'élément déjà actif
            if ($(this).closest('.accordion-item').hasClass('active')) {
                close($(this).closest('.accordion-item'), toggleSpeed);
            } else {
                // ...on lance l'affichage de l'élément, avec animation
                open($(this).closest('.accordion-item'), toggleSpeed);
            }
        });
    });

    /**
     * Icône du Menu Burger
     */
    $("#toggle").click(function () {
        var changeIcon = $('#toggle').children("span");
        if ($(changeIcon).hasClass('zwiico-menu')) {
            $(changeIcon).removeClass('zwiico-menu').addClass('zwiico-cancel');
        } else {
            $(changeIcon).addClass('zwiico-menu');
        };
    });

    /**
     * Remove ID Facebook from URL
     */
    if (/^\?fbclid=/.test(location.search)) {
        location.replace(location.href.replace(/\?fbclid.+/, ""));
    };


    /**
     * Sélection d'une langue du site
     */
    $("select#barSelectLanguage").on("change", function () {
        // La langue courante ne déclenche pas de chargement
        var langSelected = $(this).val();
        var langSelected = langSelected.split("/");
        // Lit le cookie de langue
        var langSession = "<?php echo isset($_SESSION['ZWII_CONTENT']) ? $_SESSION['ZWII_CONTENT'] : '';?>";
        // Découpe l'URL pour exclure le changement de page avec le thème
        var url = window.location;
        var currentUrl = url.href.split("/");
        // Change si différent, corrige le problème avec le thème et le rechargement de la langue.
        if ((currentUrl !== "?theme" ||
                currentUrl !== "theme") &&
            langSelected[6] !== langSession
        ) {
            //$(location).attr("href", langUrl);
            var select = document.getElementById("barSelectLanguage");
            var selectedOption = select.options[select.selectedIndex];
            if (selectedOption.value !== "") {
                window.location = selectedOption.value;            }
        }
    });

});