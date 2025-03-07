<?php $moduleData['gallery'] = [
	'add' => $this->getInput('profilEditGalleryAdd', helper::FILTER_BOOLEAN),
	'edit' => $this->getInput('profilEditGalleryEdit', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditGalleryDelete', helper::FILTER_BOOLEAN),
	'option' => $this->getInput('profilEditGalleryOption', helper::FILTER_BOOLEAN),
	'theme' => $this->getInput('profilEditGalleryTheme', helper::FILTER_BOOLEAN),
	'config' => $this->getInput('profilEditGalleryAdd', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGalleryEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGalleryDelete', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGalleryOption', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGalleryTheme', helper::FILTER_BOOLEAN)
];