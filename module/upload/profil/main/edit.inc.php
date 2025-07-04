<?php $moduleData['upload'] = [
    'download' => $this->getInput('profilEditUploadDownload', helper::FILTER_BOOLEAN),
    'data' => $this->getInput('profilEditUploadData', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilEditUploadDelete', helper::FILTER_BOOLEAN),
    'deleteAll' => $this->getInput('profilEditUploadDeleteAll', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilEditUploadConfig', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditUploadDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditUploadDeleteAll', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditUploadDownload', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditUploadData', helper::FILTER_BOOLEAN)
];