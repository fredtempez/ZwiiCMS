<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
class upload extends common
{
	const VERSION = '0.3';
	const REALNAME = 'Téléversement';
	const DATADIRECTORY = '';  // Contenu localisé inclus par défaut (page.json et module.json)

	public static $actions = [
		'index' => self::ROLE_MEMBER,
		'dirs' => self::ROLE_EDITOR,
		'config' => self::ROLE_EDITOR,
		'data' => self::ROLE_EDITOR,
		'delete' => self::ROLE_EDITOR,
		'deleteAll' => self::ROLE_EDITOR,
		'download' => self::ROLE_EDITOR,
	];

	public static $attempts = [
		0 => 'Illimitée',
		1 => '1',
		2 => '2',
		3 => '3',
		4 => '4',
		5 => '5',
	];

	public static $sizes = [
		0 => 'Illimitée',
		50000 => '50 Kb',
		500000 => '500 Kb',
		1000000 => '1 Mb',
		10000000 => '10 Mb',
		50000000 => '50 Mb',
		100000000 => '100 Mb',
		500000000 => '500 Mb',
		100000000 => '100 Mb',
	];

	public static $multiple = [
		0=> 'Illimité',
		1 => '1',
		2 => '2',
		3 => '3',
		4 => '4',
		5 => '5',
		6 => '6',
		7 => '7',
		8 => '8',
		9 => '9',
		10 => '10',
	];

	public static $datas = [];

	public static $sharePath = [];

	public function index()
	{
		if ($this->isPost()) {
			// Variables par défaut de réussite
			$state = true;
			$notification = 'Fichiers remis avec succès.';

			// Récupération des données des fichiers uploadés
			$files = $_FILES['uploadInputFile'] ?? null;
			$targetDir = $this->getData(['module', $this->getUrl(0), 'config', 'path']);

			// Vérification initiale des fichiers
			if (!isset($files['name']) || (is_array($files['name']) && empty($files['name']))) {
				$notification = "Aucun fichier n'a été téléversé.";
				$state = false;
			} else {
				// Conversion en tableau de fichiers si un seul fichier
				if (!is_array($files['name'])) {
					$files = array(
						'name' => array($files['name']),
						'type' => array($files['type']),
						'tmp_name' => array($files['tmp_name']),
						'error' => array($files['error']),
						'size' => array($files['size'])
					);
				}

				// Vérification du nombre maximum de fichiers
				$maxFiles = $this->getData(['module', $this->getUrl(0), 'config', 'maxFiles']);
				$existingFilesCount = count($this->getData(['module', $this->getUrl(0), 'data', $this->getUser('id'), 'files']) ?? []);
				$newFilesCount = count($files['name']);
				$totalFilesCount = $existingFilesCount + $newFilesCount;

				if ($maxFiles > 0 && $totalFilesCount > $maxFiles) {
					$notification = sprintf('Le nombre total de fichiers (%d) dépasse la limite autorisée (%d). Vous avez déjà déposé %d fichier(s).', 
					$totalFilesCount, 
					$maxFiles,
					$existingFilesCount
					);
					$state = false;
				} else {
					// Vérification de la taille totale
					$totalSize = array_sum($files['size']);
					$maxSize = $this->getData(['module', $this->getUrl(0), 'config', 'maxSize']);
					if ($maxSize > 0 && $totalSize > $maxSize) {
						$notification = sprintf('Le poids total des fichiers dépasse la taille de %s', self::$sizes[$maxSize]);
						$state = false;
					} else {
						// Vérification des tentatives (une seule fois)
						$maxAttempt = $this->getData(['module', $this->getUrl(0), 'config', 'maxAttempt']);
						if ($maxAttempt > 0 && $this->getData(['module', $this->getUrl(0), 'data', $this->getUser('id'), 'attempt']) >= $maxAttempt) {
							$notification = sprintf('Dépassement du nombre de remises autorisées, %s tentative%s', $maxAttempt, ($maxAttempt > 1 ? 's' : ''));
							$state = false;
						} else {
							// Traitement de chaque fichier
							$existingFiles = $this->getData(['module', $this->getUrl(0), 'data', $this->getUser('id'), 'files']) ?? [];

							foreach ($files['name'] as $key => $name) {
								$file = array(
									'name' => $files['name'][$key],
									'type' => $files['type'][$key],
									'tmp_name' => $files['tmp_name'][$key],
									'error' => $files['error'][$key],
									'size' => $files['size'][$key]
								);

								// Vérification des erreurs de base
								if (!$this->validateUploadedFile($file, $notification)) {
									$state = false;
									break;
								}

								// Traitement du fichier
								if (!$this->processUploadedFile($file, $targetDir, $existingFiles, $notification)) {
									$state = false;
									break;
								}
							}

							// Si tout s'est bien passé, mise à jour de la base
							if ($state) {
								$this->setData([
									'module',
									$this->getUrl(0),
									'data',
									[
										$this->getUser('id') => [
											'files' => $existingFiles,
											'uploadDateTime' => time(),
											'attempt' => !is_null($this->getData(['module', $this->getUrl(0), 'data', $this->getUser('id'), 'attempt']))
												? $this->getData(['module', $this->getUrl(0), 'data', $this->getUser('id'), 'attempt']) + 1
												: 1,
										]
									]
								]);
							}
						}
					}
				}
			}

			// Valeurs en sortie
			$this->addOutput([
				'notification' => $notification,
				'state' => $state
			]);
		}

		self::$datas = $this->getData(['module', $this->getUrl(0), 'config']);

		// Valeurs en sortie pour l'affichage du formulaire
		$this->addOutput([
			'showBarEditButton' => true,
			'showPageContent' => true,
			'view' => 'index'
		]);
	}

	/**
	 * Valide un fichier téléversé
	 */
	private function validateUploadedFile($file, &$notification)
	{
		if (
			empty($file['name']) ||
			empty($file['type']) ||
			!isset($file['tmp_name']) ||
			empty($file['tmp_name']) ||
			!is_uploaded_file($file['tmp_name']) ||
			!isset($file['error']) ||
			$file['error'] !== UPLOAD_ERR_OK ||
			!isset($file['size']) ||
			$file['size'] <= 0
		) {
			if (!isset($file['error'])) {
				$notification = "Erreur lors du téléversement d'un fichier.";
			} else {
				$notification = $this->getUploadErrorMessage($file['error'], $file);
			}
			return false;
		}
		return true;
	}

	/**
	 * Traite un fichier téléversé
	 */
	private function processUploadedFile($file, $targetDir, &$existingFiles, &$notification)
	{
		$filename = basename($file['name']);

		// Création du dossier si nécessaire
		if (!is_dir($targetDir . $this->getUser('id'))) {
			mkdir($targetDir . $this->getUser('id'), 0777, true);
		}

		$targetPath = $targetDir . $this->getUser('id') . '-' . $filename;

		if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
			$notification = sprintf('Erreur lors du déplacement du fichier %s.', $file['name']);
			return false;
		}

		// Mise à jour ou ajout du fichier
		$fileExists = false;
		foreach ($existingFiles as $key => $existingFile) {
			if ($existingFile['path'] === $targetPath) {
				$existingFiles[$key] = [
					'filename' => $filename,
					'path' => $targetPath,
					'size' => $file['size'],
					'type' => $file['type']
				];
				$fileExists = true;
				break;
			}
		}

		if (!$fileExists) {
			$existingFiles[] = [
				'filename' => $filename,
				'path' => $targetPath,
				'size' => $file['size'],
				'type' => $file['type']
			];
		}

		return true;
	}

	/**
	 * Retourne le message d'erreur approprié
	 */
	private function getUploadErrorMessage($error, $file)
	{
		switch ($error) {
			case UPLOAD_ERR_INI_SIZE:
				return 'Le fichier dépasse la taille maximale autorisée par PHP.';
			case UPLOAD_ERR_FORM_SIZE:
				return 'Le fichier dépasse la taille maximale autorisée par le formulaire.';
			case UPLOAD_ERR_PARTIAL:
				return "Le fichier n'a été que partiellement téléversé.";
			case UPLOAD_ERR_NO_FILE:
				return "Aucun fichier n'a été téléversé.";
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Le dossier temporaire est manquant.';
			case UPLOAD_ERR_CANT_WRITE:
				return "Échec de l'écriture du fichier sur le disque.";
			case UPLOAD_ERR_EXTENSION:
				return 'Une extension PHP a arrêté le téléversement.';
			default:
				if (!is_array($file)) {
					return 'Format de fichier invalide.';
				} elseif (!isset($file['tmp_name'])) {
					return 'Fichier temporaire manquant.';
				} elseif (!is_uploaded_file($file['tmp_name'])) {
					return "Le fichier n'a pas été téléversé correctement via le formulaire.";
				}
				return 'Erreur inconnue lors du téléversement.';
		}
	}

	public function config()
	{
		// Soumission du formulaire
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) === true &&
			$this->isPost()
		) {
			$this->setData([
				'module',
				$this->getUrl(0),
				[
					'config' => [
						'path' => $this->getInput('uploadConfigPath'),
						'label' => $this->getInput('uploadConfigLabel', helper::FILTER_STRING_SHORT, true),
						'placeholder' => $this->getInput('uploadConfigPlaceHolder', helper::FILTER_STRING_SHORT, true),
						'accept' => $this->getInput('uploadConfigAccept', helper::FILTER_STRING_SHORT),
						'uploadText' => $this->getInput('uploadConfigUploadtext', helper::FILTER_STRING_SHORT),
						'maxSize' => $this->getInput('uploadConfigMaxSize', helper::FILTER_INT),
						'maxAttempt' => $this->getInput('uploadConfigAttempt', helper::FILTER_INT),
						'maxFiles' => $this->getInput('uploadConfigMaxFiles', helper::FILTER_INT),
					]
				]
			]);

			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'notification' => helper::translate('Modifications enregistrées'),
				'state' => true
			]);
		}


		// Cas où l'utilisateur n'a pas de droits d'accès
		if (empty($rootPath) || $rootPath === 'none') {
			$sharePath[] = [
				'name' => helper::translate('Aucun dossier partagé'),
				'path' => 'none'
			];
		} else {
			try {
				// Liste des dossiers
				$directories = helper::getSubdirectories($rootPath);

				// Tri et transformation des dossiers
				if (!empty($directories)) {
					ksort($directories);
					foreach ($directories as $key => $path) {
						$sharePath[] = [
							'name' => $key,
							'path' => $path
						];
					}
				}
			} catch (Exception $e) {
				$sharePath[] = [
					'name' => helper::translate('Erreur lors de la lecture des dossiers'),
					'path' => 'none'
				];
			}
		}

		// Valeurs en sortie
		$this->addOutput([
			'title' => helper::translate('Configuration du module'),
			'view' => 'config'
		]);
	}

	public function data()
	{
		// Parcours des données
		foreach ($this->getData(['module', $this->getUrl(0), 'data']) as $userId => $value) {
			// Prépare les listes de fichiers et leurs types
			$fileList = '';
			foreach ($value['files'] as $file) {
				$fileList .= $file['filename'] . ' (' . $file['type'] . ')<br>';
			}
			// Calcule la taille totale
			$totalSize = array_sum(array_column($value['files'], 'size'));

			self::$datas[] = [
				$userId,
				$fileList,
				$this->formatFileSize($totalSize),
				helper::dateUTF8('%d/%m/%Y', $value['uploadDateTime'], self::$i18nUI) . '<br>' . helper::dateUTF8('%H:%M', $value['uploadDateTime'], self::$i18nUI),
				template::button('uploadDataDelete', [
					'class' => 'uploadDataDelete buttonRed',
					'href' => helper::baseUrl() . $this->getUrl(0) . '/delete/' . $userId,
					'value' => template::ico('trash'),
					'help' => 'Effacer'
				])
			];
		}

		// Valeurs en sortie pour l'affichage du formulaire
		$this->addOutput([
			'view' => 'data'
		]);
	}

	public function delete()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true &&
			// Données inexistante
			$this->getData(['module', $this->getUrl(0), 'data', $this->getUrl(2)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Effacer le fichier
			if (file_exists($this->getData(['module', $this->getUrl(0), 'data', $this->getUrl(2), 'path']))) {
				unlink($this->getData(['module', $this->getUrl(0), 'data', $this->getUrl(2), 'path']));
			}
			// Efface de la base
			$this->deleteData(['module', $this->getUrl(0), 'data', $this->getUrl(2)]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
				'notification' => helper::translate('Fichier supprimé'),
				'state' => true
			]);
		}
	}

	public function deleteAll()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Effacer tous les fichiers sur le disque à l'aide d'une boucle avant de supprimer la base
			foreach ($this->getData(['module', $this->getUrl(0), 'data']) as $userId => $value) {
				// Parcourir tous les fichiers de l'utilisateur
				foreach ($value['files'] as $file) {
					if (file_exists($file['path'])) {
						unlink($file['path']);
					}
				}
			}
			// Effacer la base
			$this->setData(['module', $this->getUrl(0), 'data', []]);
			// Valeurs en sortie
			$this->addOutput([
				'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
				'notification' => helper::translate('Fichiers supprimés'),
				'state' => true
			]);
		}
	}

	public function download()
	{
		// Action interdite
		if (
			$this->getUser('permission', __CLASS__, __FUNCTION__) !== true &&
			// Données inexistante
			$this->getData(['module', $this->getUrl(0), 'data', $this->getUrl(2)]) === null
		) {
			// Valeurs en sortie
			$this->addOutput([
				'access' => false
			]);
		} else {
			// Vérifier s'il y a des fichiers à télécharger
			$hasFiles = false;
			foreach ($this->getData(['module', $this->getUrl(0), 'data']) as $value) {
				if (!empty($value['files'])) {
					$hasFiles = true;
					break;
				}
			}

			if (!$hasFiles) {
				$this->addOutput([
					'notification' => 'Aucun fichier disponible pour le téléchargement',
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'state' => false
				]);
				return;
			}

			// Créer un nom de fichier ZIP temporaire unique
			$zipName = 'files_' . date('Y-m-d_H-i-s') . '.zip';
			$zipPath = sys_get_temp_dir() . '/' . $zipName;

			// Créer le zip
			$zip = new ZipArchive();
			if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
				// Parcours des fichiers
				foreach ($this->getData(['module', $this->getUrl(0), 'data']) as $userId => $value) {
					if (isset($value['files']) && is_array($value['files'])) {
						foreach ($value['files'] as $file) {
							if (file_exists($file['path'])) {
								// Ajouter le fichier au ZIP avec le nom d'utilisateur comme préfixe
								$zip->addFile($file['path'], $userId . '_' . $file['filename']);
							}
						}
					}
				}
				$zip->close();

				// Vérifier si le fichier ZIP a été créé
				if (file_exists($zipPath)) {
					// Télécharger le zip
					header('Content-Type: application/zip');
					header('Content-Disposition: attachment; filename="' . $zipName . '"');
					header('Content-Length: ' . filesize($zipPath));
					header('Pragma: public');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Expires: 0');

					readfile($zipPath);
					// Supprimer le fichier temporaire
					unlink($zipPath);
					exit;
				} else {
					$this->addOutput([
						'notification' => 'Erreur lors de la création du fichier ZIP',
						'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
						'state' => false
					]);
				}
			} else {
				$this->addOutput([
					'notification' => 'Erreur lors de la création du fichier ZIP',
					'redirect' => helper::baseUrl() . $this->getUrl(0) . '/data',
					'state' => false
				]);
			}
		}
	}

	public function dirs()
	{
		// Déterminer le $rootPath à explorer
		$rootPath = $this->getUserPath();
		// Liste des dossiers
		$sharePath = helper::getSubdirectories($rootPath);
		// Tri des dossiers sur la clé
		ksort($sharePath);
		$sharePath = array_flip($sharePath);
		// Parcours des dossiers
		foreach ($sharePath as $path => $name) {
			$sharePath[$path] = [
				'name' => $name,
				'path' => $path
			];
		}
		// Valeurs en sortie
		$this->addOutput([
			'display' => self::DISPLAY_JSON,
			'content' => array_values($sharePath)
		]);
	}

	private function formatFileSize($bytes)
	{
		// Units and decimal separator based on language
		if (self::$i18nUI === 'fr_FR') {
			$units = ['octets', 'Ko', 'Mo', 'Go'];
			$number = function ($value) {
				return str_replace('.', ',', number_format($value, 2));
			};
		} else {
			$units = ['bytes', 'KB', 'MB', 'GB'];
			$number = function ($value) {
				return number_format($value, 2);
			};
		}

		$sizes = [1073741824, 1048576, 1024];
		for ($i = 0; $i < count($sizes); $i++) {
			if ($bytes >= $sizes[$i]) {
				return $number($bytes / $sizes[$i]) . ' ' . $units[3 - $i];
			}
		}
		return $bytes . ' ' . $units[0];
	}
}
