<?php $moduleData['gallery'] = [
    'add' => $this->getInput('profilAddGalleryAdd', helper::FILTER_BOOLEAN),
    'edit' => $this->getInput('profilAddGalleryEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddGalleryDelete', helper::FILTER_BOOLEAN),
    'option' => $this->getInput('profilAddGalleryOption', helper::FILTER_BOOLEAN),
    'theme' => $this->getInput('profilAddGalleryTheme', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddGalleryAdd', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGalleryEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGalleryDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGalleryOption', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGalleryTheme', helper::FILTER_BOOLEAN)
];