<?php $moduleData['geolocation'] = [
	'add' => $this->getInput('profilEditGeolocationAdd', helper::FILTER_BOOLEAN),
	'edit' => $this->getInput('profilEditGeolocationEdit', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditGeolocationDelete', helper::FILTER_BOOLEAN),
	'config' => $this->getInput('profilEditGeolocationAdd', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGeolocationEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGeolocationDelete', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGeolocationOption', helper::FILTER_BOOLEAN)
];