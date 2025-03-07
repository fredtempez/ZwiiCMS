<?php $moduleData['suscribe'] = [
    'edit' => $this->getInput('profilAddSuscribeEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddSuscribeDelete', helper::FILTER_BOOLEAN),
    'user' => $this->getInput('profilAddSuscribeUser', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddSuscribeAdd', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddSuscribeEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddSuscribeDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddSuscribeUser', helper::FILTER_BOOLEAN)
];