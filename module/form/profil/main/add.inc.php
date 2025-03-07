<?php $moduleData['form'] = [
    'option' => $this->getInput('profilAddFormOption', helper::FILTER_BOOLEAN),
    'data' => $this->getInput('profilAddFormData', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddFormDelete', helper::FILTER_BOOLEAN),
    'deleteAll' => $this->getInput('profilAddFormDeleteAll', helper::FILTER_BOOLEAN),
    'export2csv' => $this->getInput('profilAddFormExport2csv', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddFormOption', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddFormData', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddFormDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddFormDeleteAll', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddFormExport2csv', helper::FILTER_BOOLEAN)
];