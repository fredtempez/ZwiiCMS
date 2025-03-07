<!-- Agenda dans un wrapper pour contrôler la taille-->
<div id="index_wrapper">
	<!--Affiche l'agenda-->
	<div id='calendar'> </div>
    <div id="eventPopup" style="display: none;">
        <div class="popup-content">
			<span class="close-btn">&times;</span>
            <p><span class="event-title" id="popupEventTitle"></span>
            <p><span class="event-details" id="popupEventStart"></span></p>
            <p><span class="event-details" id="popupEventEnd"></span></p>
        </div>
    </div>
	<?php echo template::formOpen('index_events'); ?>
	<?php echo template::formClose(); ?>
</div>