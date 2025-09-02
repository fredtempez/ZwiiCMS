<?php $moduleData['download'] = [
    'add' => $this->getInput('profilAddDownloadAdd', helper::FILTER_BOOLEAN),
    'edit' => $this->getInput('profilAddDownloadEdit', helper::FILTER_BOOLEAN),
    'delete' => $this->getInput('profilAddDownloadDelete', helper::FILTER_BOOLEAN),
    'option' => $this->getInput('profilAddDownloadOption', helper::FILTER_BOOLEAN),
    'comment' => $this->getInput('profilAddDownloadComment', helper::FILTER_BOOLEAN),
    'commentApprove' => $this->getInput('profilAddDownloadCommentApprove', helper::FILTER_BOOLEAN),
    'commentDelete' => $this->getInput('profilAddDownloadCommentDelete', helper::FILTER_BOOLEAN),
    'commentDeleteAll' => $this->getInput('profilAddDownloadCommentDeleteAll', helper::FILTER_BOOLEAN),
    'categoryManage' => $this->getInput('profilAddDownloadCategories', helper::FILTER_BOOLEAN),
    'categoryEdit' => $this->getInput('profilAddDownloadCategoryEdit', helper::FILTER_BOOLEAN),
    'categoryDelete' => $this->getInput('profilAddDownloadCategoryDelete', helper::FILTER_BOOLEAN),
    'deleteAllStats' => $this->getInput('profilAddDownloadCommentDeleteAllStats', helper::FILTER_BOOLEAN),
    'config' => $this->getInput('profilAddDownloadAdd', helper::FILTER_BOOLEAN) ||
        $this->getInput('profilAddDownloadEdit', helper::FILTER_BOOLEAN) ||
        $this->getInput('profilAddDownloadDelete', helper::FILTER_BOOLEAN) ||
        $this->getInput('profilAddDownloadOption', helper::FILTER_BOOLEAN) ||
        $this->getInput('profilAddDownloadComment', helper::FILTER_BOOLEAN) ||
        $this->getInput('profilAddDownloadCommentApprove', helper::FILTER_BOOLEAN) ||
        $this->getInput('profilAddDownloadCommentDelete', helper::FILTER_BOOLEAN) ||
        $this->getInput('profilAddDownloadCommentDeleteAll', helper::FILTER_BOOLEAN),
];