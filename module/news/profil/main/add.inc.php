<?php $moduleData['news'] = [
    'add' => $this->getInput('profilAddNewsAdd', helper::FILTER_BOOLEAN),
    'edit' => $this->getInput('profilAddNewsEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddNewsDelete', helper::FILTER_BOOLEAN),
    'option' => $this->getInput('profilAddNewsOption', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddNewsAdd', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddNewsEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddNewsDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddNewsOption', helper::FILTER_BOOLEAN)
];