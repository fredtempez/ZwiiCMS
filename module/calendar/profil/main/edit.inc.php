<?php $moduleData['calendar'] = [
	'add' => $this->getInput('profilEditCalendarAdd', helper::FILTER_BOOLEAN),
	'edit' => $this->getInput('profilEditCalendarEdit', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditCalendarDelete', helper::FILTER_BOOLEAN),
	'config' => $this->getInput('profilEditCalendarAdd', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditCalendarEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditCalendarTheme', helper::FILTER_BOOLEAN)
];