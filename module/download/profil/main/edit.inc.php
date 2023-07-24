<?php $moduleData['download'] = [
	'add' => $this->getInput('profilEditDownloadAdd', helper::FILTER_BOOLEAN),
	'edit' => $this->getInput('profilEditDownloadEdit', helper::FILTER_BOOLEAN),
	'delete' => $this->getInput('profilEditDownloadDelete', helper::FILTER_BOOLEAN),
	'option' => $this->getInput('profilEditDownloadOption', helper::FILTER_BOOLEAN),
	'comment' => $this->getInput('profilEditDownloadComment', helper::FILTER_BOOLEAN),
	'commentApprove' => $this->getInput('profilEditDownloadCommentApprove', helper::FILTER_BOOLEAN),
	'commentDelete' => $this->getInput('profilEditDownloadCommentDelete', helper::FILTER_BOOLEAN),
	'commentDeleteAll' => $this->getInput('profilEditDownloadCommentDeleteAll', helper::FILTER_BOOLEAN),
	'config' => $this->getInput('profilEditDownloadAdd', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditDownloadEdit', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditDownloadDelete', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditDownloadOption', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditDownloadComment', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditDownloadCommentApprove', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditDownloadCommentDelete', helper::FILTER_BOOLEAN) ||
	$this->getInput('profilEditDownloadCommentDeleteAll', helper::FILTER_BOOLEAN)
];