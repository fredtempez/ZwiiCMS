<?php $moduleData['news'] = [
	'add' => $this->getInput('profilEditNewsAdd', helper::FILTER_BOOLEAN),
	'edit' => $this->getInput('profilEditNewsEdit', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditNewsDelete', helper::FILTER_BOOLEAN),
	'option' => $this->getInput('profilEditNewsOption', helper::FILTER_BOOLEAN),
	'config' => $this->getInput('profilEditNewsAdd', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditNewsEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditNewsEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditNewsOption', helper::FILTER_BOOLEAN)
];