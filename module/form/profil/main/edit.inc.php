<?php $moduleData['form'] = [
	'config' => $this->getInput('profilEditFormConfig', helper::FILTER_BOOLEAN),
	'option' => $this->getInput('profilEditFormOption', helper::FILTER_BOOLEAN),
	'data' => $this->getInput('profilEditFormData', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditFormDelete', helper::FILTER_BOOLEAN),
	'deleteAll' => $this->getInput('profilEditFormDeleteAll', helper::FILTER_BOOLEAN),
	'export2csv' => $this->getInput('profilEditFormExport2csv', helper::FILTER_BOOLEAN),
];