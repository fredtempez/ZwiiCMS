<?php $moduleData['upload'] = [
    'download' => $this->getInput('profilAddUploadDownload', helper::FILTER_BOOLEAN),
    'data' => $this->getInput('profilAddUploadData', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddUploadDelete', helper::FILTER_BOOLEAN),
    'deleteAll' => $this->getInput('profilAddUploadDeleteAll', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddUploadConfig', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddUploadDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddUploadDeleteAll', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddUploadDownload', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilAddUploadData', helper::FILTER_BOOLEAN)
];