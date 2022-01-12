/**
 * Initialisation du sélecteur de date
 */
$(function() {
	$(".datepicker").flatpickr({
		altInput: true,
		altFormat: "d/m/Y H:i",
		enableTime: true,
		time_24hr: true,
		locale: "fr",
		regional:"fr",	
		dateFormat: "Y-m-d H:i"
	});
});
