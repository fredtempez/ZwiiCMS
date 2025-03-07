$(document).ready(function() {
    // Ajoute un événement sur le changement de l'état de la checkbox calendarAddAllDay
    $('#calendarAddAllDay').on('change', function() {
        if ($(this).is(':checked')) {
            $('#calendarAddTime').val('');
            $('#calendarAddTimeWrapper').slideUp(); // Masque avec un effet de slide
        } else {
            $('#calendarAddTimeWrapper').slideDown(); // Affiche avec un effet de slide
        }
    });
});