/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 *
 * Module Zwii agenda développé par Sylvain Lelièvre
 * Utilise le package Fullcalendar 
 * FullCalendar Core Package v4.3.1
 * Docs & License: https://fullcalendar.io/
 * (c) 2019 Adam Shaw
 **/

$(document).ready(function () {

	/* Pour liaison entre constiables php et javascript dans index.js.php */

	const connected = '<?php echo $this->getUser("password") === $this->getInput("ZWII_USER_PASSWORD"); ?>';

	// Integer: largeur MAXI du diaporama, en pixels. Par exemple : 800, 920, 500
	var maxwidth = '<?php echo $this->getData(["module", $this->getUrl(0),"config","maxiWidth"]); ?>';

	//Fullcalendar : instanciation, initialisations
	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		header: {
			left: 'dayGridMonth,dayGridWeek',
			center: 'title',
			right: 'today,prev,next'
		},
		titleFormat: {
			month: 'long',
			year: 'numeric'
		},
		columnHeaderFormat: {
			weekday: 'long'
		},
		plugins: ['dayGrid', 'interaction'],
		locale: 'fr',
		defaultView: '<?php echo $this->getData(["module", $this->getUrl(0), "vue", "vueagenda"]) ;?>',
		defaultDate: '<?php echo $this->getData(["module", $this->getUrl(0), "vue", "debagenda"]) ;?>',
		selectable: true,
		editable: true,
		//afficher les évènements à partir d'un fichier JSON
		events: '<?php echo $module::DATAMODULE."data/".$this->getUrl(0); ?>' + '_visible/events.json',
		//créer un évènement
		dateClick: function (info) {
			if (connected) {
				window.open('<?php echo helper::baseUrl() . $this->getUrl(0); ?>' + '/da:' + info.dateStr + 'vue:' + info.view.type + 'deb:' + calendar.formatIso(info.view.currentStart), '_self');
			}
		},
		//Lire, modifier, supprimer un évènement
		eventClick: function (info) {
			if (connected) {
				window.open('<?php echo helper::baseUrl() . $this->getUrl(0); ?>' + '/id:' + info.event.id + 'vue:' + info.view.type + 'deb:' + calendar.formatIso(info.view.currentStart), '_self');
			} else {
				// Extraire les informations de l'événement
				var eventId = info.event.id;
				var eventTitle = info.event.title;
				var eventStart = info.event.start;
				var eventEnd = info.event.end;
				var eventDescription = info.event.extendedProps.description; // Exemple d'attribut personnalisé

				// Afficher les informations dans la popup
				showPopup(eventId, eventTitle, eventStart, eventEnd, eventDescription);
			}
		}
	});

	//Déclaration de la fonction wrapper pour déterminer la largeur du div qui contient l'agenda et le bouton gérer : index_wrapper
	$.wrapper = function () {
		// Adaptation de la largeur du wrapper en fonction de la largeur de la page client et de la largeur du site
		// 10000 pour la sélection 100%
		if (maxwidth != 10000) {
			var wclient = document.body.clientWidth,
				largeur_pour_cent,
				largeur,
				largeur_section,
				wsection = getComputedStyle(site).width,
				wcalcul;
			switch (wsection) {
				case '750px':
					largeur_section = 750;
					break;
				case '960px':
					largeur_section = 960;
					break;
				case '1170px':
					largeur_section = 1170;
					break;
				default:
					largeur_section = wclient;
			}

			// 20 pour les margin du body / html, 40 pour le padding intérieur dans section	
			if (wclient > largeur_section + 20) {
				wcalcul = largeur_section - 40
			} else {
				wcalcul = wclient - 40
			};
			largeur_pour_cent = Math.floor(100 * (maxwidth / wcalcul));
			if (largeur_pour_cent > 100) {
				largeur_pour_cent = 100;
			}
			largeur = largeur_pour_cent.toString() + "%";

			console.log(largeur);

			$("#index_wrapper").css('width', largeur);
		} else {
			$("#index_wrapper").css('width', "100%");
		}
		//La taille du wrapper étant défini on peut l'afficher
		$("#index_wrapper").css('visibility', "visible");
	};

	$.wrapper();
	calendar.render();

	$(window).resize(function () {
		$.wrapper();
		calendar.render();
	});

	$('.close-btn').on('click', function () {
		$('#eventPopup').fadeOut(); // Utilise fadeOut pour une fermeture en fondu
	});

    // Fermer le popup en cliquant à l'extérieur de '#eventPopup'
    $(document).on('mousedown', function(event) {
        // Vérifie si le clic a été fait à l'extérieur de '#eventPopup'
        if (!$(event.target).closest('#eventPopup').length) {
            // Si le popup est visible, le fermer
            if ($('#eventPopup').is(':visible')) {
                $('#eventPopup').fadeOut();
            }
        }
    });

});


/** Fonction pour affiche la popup */
function showPopup(eventId, eventTitle, eventStart, eventEnd, eventDescription) {
	// Utilisation de l'API Date native pour formater les dates de début et de fin
	var formattedStart = eventStart ? eventStart.toLocaleString('fr-FR', {
		day: '2-digit',
		month: '2-digit',
		year: 'numeric',
		hour: '2-digit',
		minute: '2-digit'
	}) : 'N/A';
	var formattedEnd = eventEnd ? eventEnd.toLocaleString('fr-FR', {
		day: '2-digit',
		month: '2-digit',
		year: 'numeric',
		hour: '2-digit',
		minute: '2-digit'
	}) : 'N/A';

	// Remplir les éléments de la popup avec les informations de l'événement
	$('#popupEventTitle').text(eventTitle);
	$('#popupEventStart').text(formattedStart);
	$('#popupEventEnd').text(formattedEnd);

	// Afficher la popup
	$('#eventPopup').fadeIn();
}

/* Refermer la popup */
function closePopup() {
	document.querySelector('.popup').style.display = 'none';
}