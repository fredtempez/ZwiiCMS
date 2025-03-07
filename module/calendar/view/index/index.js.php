/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

//  https://www.jqueryscript.net/time-clock/animated-calendar-event-gc.html

const jsonOptions = '<?php echo json_encode($module::$calendars, JSON_HEX_APOS); ?>';
const objOptions = JSON.parse(jsonOptions);
const events = generateEvents(objOptions);

// Définir la fonction d'initialisation du calendrier
function initializeCalendar() {
    var calendar = $("#calendar").calendarGC({
        dayBegin: 1,
        dayNames: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        prevIcon: '&#x3c;',
        nextIcon: '&#x3e;',
        //eventIcon: '&#x1F4C5;',
        onPrevMonth: function (e) {
            // Code pour le changement de mois précédent
        },
        onNextMonth: function (e) {
            // Code pour le changement de mois suivant
        },
        events: events,
        onclickDate: function (e, data) {
            const targetDate = formatDate(data.datejs);
            const filteredEvents = $.grep(events, function (event) {
                return formatDate(event.date) === targetDate;
            });

            let eventListHtml = '';

            if (filteredEvents.length > 0) {
                eventListHtml = '<ul>';
                $.each(filteredEvents, function (index, event) {
                    const eventTime = event.eventTime ? event.eventTime : 'toute la journée';
                    eventListHtml += '<li><strong>' + event.eventName + '</strong><br>' + eventTime + '</li>';
                });
                eventListHtml += '</ul>';
                $('#popupDate').html('Événements du ' + formatDateToDMY(targetDate));
            } else {
                $('#popupDate').html('Aucun événement le ' + formatDateToDMY(targetDate));
            }

            $('#eventList').html(eventListHtml);
            $('#eventListPopup').fadeIn();

            $('.close-btn').on('click', function () {
                $('#eventListPopup').fadeOut();
            });

            $('#eventListPopup').on('click', function (e) {
                if ($(e.target).is('.popup-overlay')) {
                    $(this).fadeOut();
                }
            });
        }
    });

    $(document).on('click', '.gc-calendar-month-year', function() {
        const currentDate = new Date();
        const formattedDate = currentDate.toISOString().split('T')[0];
        calendar.setDate(formattedDate);
    });
}

// Exécuter l'initialisation au chargement du document
$(document).ready(function() {
    initializeCalendar();
});

// Définir l'état initial de la largeur de la fenêtre
let wasSmallScreen = $(window).width() <= 600;

$(window).resize(function() {
    // Vérifier la largeur actuelle de la fenêtre
    const isSmallScreen = $(window).width() <= 600;

    // Vérifier si l'état de la largeur a changé (de petit à grand ou de grand à petit)
    if (wasSmallScreen !== isSmallScreen) {
        location.reload(); // Recharge la page
    }

    // Mettre à jour l'état de la largeur pour le prochain redimensionnement
    wasSmallScreen = isSmallScreen;
});



function showPopup(data) {

    const eventName = data.eventName;

    // Extraire la date et l'heure
    const eventDate = data.date.toLocaleDateString(); // Format : 18/08/2024
    
    // Vérifier si l'heure est définie et extraire l'heure si disponible
    const eventTime = data.eventTime === '' ?'Toute la journée' : 'à ' + data.eventTime; // Format : 12:00
    // Injecter les informations dans la popup
    $('#eventName').text(eventName);
    $('#eventDate').text(eventDate);
    $('#eventTime').text(eventTime);

    // Afficher la popup
    $('#eventPopup').fadeIn();

    // Fermer la popup lorsqu'on clique sur le bouton de fermeture
    $('.close-btn').on('click', function () {
        $('#eventPopup').fadeOut();
    });

    // Fermer la popup lorsqu'on clique en dehors du contenu
    $('#eventPopup').on('click', function (e) {
        if ($(e.target).is('.popup-overlay')) {
            $(this).fadeOut();
        }
    });
}


function generateEvents(objOptions) {
    return objOptions.map(function (item) {
        return {
            date: new Date(item.date), // Convertir la chaîne de date en objet Date
            eventTime: item.time,
            eventName: item.eventName,
            className: item.className || '', // Ajouter une classe CSS si disponible
            dateColor: item.dateColor || '', // Ajouter une couleur de date si disponible
            onclick: function (e, data) { // Ajouter une fonction onclick
                showPopup(data);
            }
        };
    });
}

function formatDate(datejs) {
    const year = datejs.getFullYear();
    const month = ('0' + (datejs.getMonth() + 1)).slice(-2); // Ajouter un zéro devant si nécessaire
    const day = ('0' + datejs.getDate()).slice(-2); // Ajouter un zéro devant si nécessaire
    return year + '-' + month + '-' + day;
}

function formatDateToDMY(dateString) {
    // Convertir la chaîne de caractères en objet Date
    const datejs = new Date(dateString);

    // Créer un tableau des noms de mois
    const monthNames = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet',
        'août', 'septembre', 'octobre', 'novembre', 'décembre'
    ];

    // Obtenir le jour, le mois et l'année
    const day = datejs.getDate();
    const month = monthNames[datejs.getMonth()];
    const year = datejs.getFullYear();

    // Retourner la date au format "jour mois année"
    return day + ' ' + month + ' ' + year;
}