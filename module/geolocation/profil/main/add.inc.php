<?php $moduleData['geolocation'] = [
    'add' => $this->getInput('profilAddGeolocationAdd', helper::FILTER_BOOLEAN),
    'edit' => $this->getInput('profilAddGeolocationEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddGeolocationDelete', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddGeolocationAdd', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGeolocationEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGeolocationDelete', helper::FILTER_BOOLEAN)
];