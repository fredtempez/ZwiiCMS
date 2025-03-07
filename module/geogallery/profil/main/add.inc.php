<?php $moduleData['geogallery'] = [
    'add' => $this->getInput('profilAddGeogalleryAdd', helper::FILTER_BOOLEAN),
    'edit' => $this->getInput('profilAddGeogalleryEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddGeogalleryDelete', helper::FILTER_BOOLEAN),
    'theme' => $this->getInput('profilAddGeogalleryTheme', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddGeogalleryAdd', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGeogalleryEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGeogalleryDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddGeogalleryTheme', helper::FILTER_BOOLEAN)
];