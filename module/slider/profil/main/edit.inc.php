<?php $moduleData['slider'] = [
    'delete' => $this->getInput('profilEditSliderDelete', helper::FILTER_BOOLEAN),
    'theme' => $this->getInput('profilEditSliderTheme', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilEditSliderEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditSliderDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditSliderTheme', helper::FILTER_BOOLEAN)
];