<?php $moduleData['download'] = [
    'add' => $this->getInput('profilEditDownloadEdit', helper::FILTER_BOOLEAN),
    'edit' => $this->getInput('profilEditDownloadEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilEditDownloadDelete', helper::FILTER_BOOLEAN),
    'option' => $this->getInput('profilEditDownloadOption', helper::FILTER_BOOLEAN),
    'comment' => $this->getInput('profilEditDownloadComment', helper::FILTER_BOOLEAN),
    'commentApprove' => $this->getInput('profilEditDownloadCommentApprove', helper::FILTER_BOOLEAN),
    'commentDelete' => $this->getInput('profilEditDownloadCommentDelete', helper::FILTER_BOOLEAN),
    'commentDeleteAll' => $this->getInput('profilEditDownloadCommentDeleteAll', helper::FILTER_BOOLEAN),
    'categoryManage' => $this->getInput('profilEditDownloadCategories', helper::FILTER_BOOLEAN),
    'categoryEdit' => $this->getInput('profilEditDownloadCategoryEdit', helper::FILTER_BOOLEAN),
    'categoryDelete' => $this->getInput('profilEditDownloadCategoryDelete', helper::FILTER_BOOLEAN),
    'deleteAllStats' => $this->getInput('profilEditDownloadCommentDeleteAllStats', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilEditDownloadEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditDownloadEdit', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditDownloadDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditDownloadOption', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditDownloadComment', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditDownloadCommentApprove', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditDownloadCommentDelete', helper::FILTER_BOOLEAN) ||
    $this->getInput('profilEditDownloadCommentDeleteAll', helper::FILTER_BOOLEAN),



];