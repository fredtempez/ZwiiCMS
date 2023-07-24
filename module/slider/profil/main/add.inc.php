<?php $moduleData['slider'] = [
    'delete' => $this->getInput('profilAddSliderDelete', helper::FILTER_BOOLEAN),
    'theme' => $this->getInput('profilAddSliderTheme', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddSliderEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddSliderDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddSliderTheme', helper::FILTER_BOOLEAN)
];