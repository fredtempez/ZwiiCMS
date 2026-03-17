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
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */
trait Tools {

	/**
	 * Accède à une partie l'url ou à l'url complète
	 * @param int $key Clé de l'url
	 * @return string|null
	 */
	public function getUrl($key = null)
	{
		// Url complète
		if ($key === null) {
			return $this->url;
		}
		// Une partie de l'url
		else {
			$url = explode('/', $this->url);
			return array_key_exists($key, $url) ? $url[$key] : null;
		}
	}

    	/**
	 * Crée une miniature d'une image avec gestion de plusieurs formats (JPEG, PNG, GIF, WebP, AVIF, SVG)
	 * La fonction gère automatiquement la transparence pour les formats PNG et GIF
	 * 
	 * Exemple d'utilisation :
	 * // Création d'une miniature de 300px de large
	 * $thumbPath = $core->makeThumb('chemin/vers/image.jpg', null, 300);
	 * 
	 * @param string $src Chemin absolu vers le fichier image source
	 * @param string|null $dest (Optionnel) Chemin complet où enregistrer la miniature.
	 *                        Si null, généré automatiquement dans un dossier 'thumb' parallèle au dossier source.
	 * @param int $desired_width Largeur souhaitée pour la miniature en pixels.
	 *                         La hauteur est calculée proportionnellement.
	 *                         Ignorée pour les fichiers SVG qui sont simplement copiés.
	 * 
	 * @return string|bool Chemin complet de la miniature créée en cas de succès,
	 *                    false en cas d'échec (fichier source inexistant, erreur de traitement, etc.)
	 * 
	 * @throws Exception En cas d'erreur lors du traitement de l'image
	 * @since 1.0.0
	 */
	public static function makeThumb($src, $dest = null, $desired_width = 200)
	{
		// Vérifier si le fichier source existe
		if (!file_exists($src)) {
			error_log("Fichier source introuvable: $src");
			return false;
		}

		// Générer automatiquement le chemin de destination si non fourni
		if ($dest === null) {
			$srcInfo = pathinfo($src);
			$destDir = str_replace('source', 'thumb', $srcInfo['dirname']);
			if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
				error_log("Impossible de créer le répertoire de destination: $destDir");
				return false;
			}
			$dest = $destDir . '/' . self::THUMBS_SEPARATOR . strtolower($srcInfo['basename']);
		}

		$fileInfo = pathinfo($dest);
		$extension = strtolower($fileInfo['extension'] ?? '');
		$mime_type = mime_content_type($src);

		// Gestion des fichiers SVG (copie simple sans redimensionnement)
		if ($extension === 'svg' || $mime_type === 'image/svg+xml') {
			return copy($src, $dest) ? $dest : false;
		}

		// Chargement de l'image source selon le type
		$source_image = null;
		try {
			switch (strtolower(pathinfo($src, PATHINFO_EXTENSION))) {
				case 'jpeg':
				case 'jpg':
					$source_image = imagecreatefromjpeg($src);
					break;
				case 'png':
					$source_image = imagecreatefrompng($src);
					break;
				case 'gif':
					$source_image = imagecreatefromgif($src);
					break;
				case 'webp':
					$source_image = imagecreatefromwebp($src);
					break;
				case 'avif':
					if (function_exists('imagecreatefromavif')) {
						$source_image = imagecreatefromavif($src);
					}
					break;
				default:
					error_log("Format d'image non supporté: $src");
					return false;
			}

			if (!$source_image) {
				error_log("Impossible de charger l'image source: $src");
				return false;
			}

			$width = imagesx($source_image);
			$height = imagesy($source_image);
			$desired_height = floor($height * ($desired_width / $width));

			// Création de la miniature
			$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

			// Gestion de la transparence pour les PNG et GIF
			if (in_array($extension, ['png', 'gif'])) {
				imagealphablending($virtual_image, false);
				imagesavealpha($virtual_image, true);
				$transparent = imagecolorallocatealpha($virtual_image, 255, 255, 255, 127);
				imagefilledrectangle($virtual_image, 0, 0, $desired_width, $desired_height, $transparent);
			}

			imagecopyresampled(
				$virtual_image,
				$source_image,
				0,
				0,
				0,
				0,
				$desired_width,
				$desired_height,
				$width,
				$height
			);

			// Sauvegarde de la miniature
			$result = false;
			switch ($mime_type) {
				case 'image/jpeg':
				case 'image/jpg':
					$result = imagejpeg($virtual_image, $dest, 85);
					break;
				case 'image/png':
					$result = imagepng($virtual_image, $dest, 8);
					break;
				case 'image/gif':
					$result = imagegif($virtual_image, $dest);
					break;
				case 'image/webp':
					$result = imagewebp($virtual_image, $dest, 85);
					break;
				case 'image/avif':
					if (function_exists('imageavif')) {
						$result = imageavif($virtual_image, $dest, 60);
					}
					break;
			}

			// Nettoyage
			imagedestroy($virtual_image);
			imagedestroy($source_image);

			if ($result && file_exists($dest)) {
				return $dest;
			}

			error_log("Échec de la sauvegarde de la miniature: $dest");
			return false;

		} catch (Exception $e) {
			if (isset($source_image) && (is_resource($source_image) || $source_image instanceof GdImage)) {
				imagedestroy($source_image);
			}
			if (isset($virtual_image) && (is_resource($virtual_image) || $virtual_image instanceof GdImage)) {
				imagedestroy($virtual_image);
			}
			error_log("Erreur lors de la création de la miniature: " . $e->getMessage());
			return false;
		}
	}

    	/**
	 * Génère le chemin d'une miniature à partir d'un chemin source
	 * @param string $sourcePath Chemin vers le fichier source
	 * @param string $prefix Préfixe à ajouter au nom du fichier (par défaut: self::THUMBS_SEPARATOR)
	 * @return string Chemin de la miniature
	 */
	public static function getThumb($sourcePath, $prefix = self::THUMBS_SEPARATOR)
	{
		// Vérifier si le chemin source est vide
		if (empty($sourcePath)) {
			return '';
		}

		// Récupérer les informations du chemin
		$pathInfo = pathinfo($sourcePath);

		// Remplacer 'source' par 'thumb' dans le répertoire
		$dirName = str_replace('source', 'thumb', $pathInfo['dirname']);

		// Construire le nouveau nom de fichier avec le préfixe
		$filename = $pathInfo['filename'];
		$extension = $pathInfo['extension'] ?? '';
		$newFilename = $prefix . $filename . ($extension ? '.' . $extension : '');

		// Retourner le chemin complet de la miniature
		return $dirName . '/' . $newFilename;
	}

    	/**
	 * Effacer un dossier non vide.
	 * @param string URL du dossier à supprimer
	 */
	public function deleteDir($path)
	{
		foreach (new DirectoryIterator($path) as $item) {
			if ($item->isFile())
				@unlink($item->getRealPath());
			if (!$item->isDot() && $item->isDir())
				$this->deleteDir($item->getRealPath());
		}
		return (rmdir($path));
	}


	/*
	 * Copie récursive de dossiers
	 * @param string $src dossier source
	 * @param string $dst dossier destination
	 * @return bool
	 */
	public function copyDir($src, $dst)
	{
		// Ouvrir le dossier source
		$dir = opendir($src);
		// Créer le dossier de destination
		if (!is_dir($dst))
			$success = mkdir($dst, 0755, true);
		else
			$success = true;

		// Boucler dans le dossier source en l'absence d'échec de lecture écriture
		while (
			$success and
			$file = readdir($dir)
		) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . '/' . $file)) {
					// Appel récursif des sous-dossiers
					$s = $this->copyDir($src . '/' . $file, $dst . '/' . $file);
					$success = $s || $success;
				} else {
					$s = copy($src . '/' . $file, $dst . '/' . $file);
					$success = $s || $success;
				}
			}
		}
		return $success;
	}

    	/**
	 * Fonction de parcours des données de module
	 * @param string $find donnée à rechercher
	 * @param string $replace donnée à remplacer
	 * @param array tableau à analyser
	 * @param int count nombres d'occurrences
	 * @return array avec les valeurs remplacées.
	 */
	public function recursive_array_replace($find, $replace, $array, &$count)
	{
		if (!is_array($array)) {
			return str_replace($find, $replace, $array, $count);
		}

		$newArray = [];
		foreach ($array as $key => $value) {
			$newArray[$key] = $this->recursive_array_replace($find, $replace, $value, $c);
			$count += $c;
		}
		return $newArray;
	}

    
	/**
	 * Génère une archive d'un dossier et des sous-dossiers
	 * @param string fileName path et nom de l'archive
	 * @param string folder path à zipper
	 * @param array filter dossiers à exclure
	 */
	public function makeZip($fileName, $folder, $filter = [])
	{
		$zip = new ZipArchive();
		$zip->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		// $directory = 'site/';
		$files = new RecursiveIteratorIterator(
			new RecursiveCallbackFilterIterator(
				new RecursiveDirectoryIterator(
					$folder,
					RecursiveDirectoryIterator::SKIP_DOTS
				),
				function ($fileInfo, $key, $iterator) use ($filter) {
					return $fileInfo->isFile() || !in_array($fileInfo->getBaseName(), $filter);
				}
			)
		);
		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen(realpath($folder)) + 1);
				$zip->addFile($filePath, str_replace('\\', '/', $relativePath));
			}
		}
		$zip->close();
	}

    	/**
	 * Retourne la signature d'un utilisateur
	 */
	public function signature($userId)
	{
		switch ($this->getData(['user', $userId, 'signature'])) {
			case 1:
				return $userId;
			case 2:
				return $this->getData(['user', $userId, 'pseudo']);
			case 3:
				return $this->getData(['user', $userId, 'firstname']) . ' ' . $this->getData(['user', $userId, 'lastname']);
			case 4:
				return $this->getData(['user', $userId, 'lastname']) . ' ' . $this->getData(['user', $userId, 'firstname']);
			default:
				return $this->getData(['user', $userId, 'firstname']);
		}
	}


    /**
	 * Retourne un chemin relatif depuis la racine du site sans la baseUrl
	 */
	public function getRelativePath($path)
	{
		return str_replace(helper::baseUrl(false), '', $path);
	}

}
