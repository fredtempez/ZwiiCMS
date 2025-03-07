<?php $moduleData['calendar'] = [
    'add' => $this->getInput('profilAddCalendarAdd', helper::FILTER_BOOLEAN),
    'edit' => $this->getInput('profilAddCalendarEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddCalendarDelete', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddCalendarAdd', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddCalendarEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddCalendarTheme', helper::FILTER_BOOLEAN)
];