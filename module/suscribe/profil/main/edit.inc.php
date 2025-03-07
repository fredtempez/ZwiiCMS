<?php $moduleData['suscribe'] = [
	'edit' => $this->getInput('profilEditSuscribeEdit', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditSuscribeDelete', helper::FILTER_BOOLEAN),
	'user' => $this->getInput('profilEditSuscribeUser', helper::FILTER_BOOLEAN),
	'config' => $this->getInput('profilEditSuscribeAdd', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditSuscribeEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditSuscribeDelete', helper::FILTER_BOOLEAN)  ||
    $this->getInput('profilEditSuscribeUser', helper::FILTER_BOOLEAN)
];