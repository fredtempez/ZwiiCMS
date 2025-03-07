<?php $moduleData['geogallery'] = [
	'add' => $this->getInput('profilEditGeogalleryAdd', helper::FILTER_BOOLEAN),
	'edit' => $this->getInput('profilEditGeogalleryEdit', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditGeogalleryDelete', helper::FILTER_BOOLEAN),
	'theme' => $this->getInput('profilEditGeogalleryTheme', helper::FILTER_BOOLEAN),
	'config' => $this->getInput('profilEditGeogalleryAdd', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGeogalleryEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGeogalleryDelete', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditGeogalleryTheme', helper::FILTER_BOOLEAN)
];