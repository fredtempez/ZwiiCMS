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
trait  UserPermission {


    	/**
	 * Récupère les informations de l'utilisateur connecté ou vérifie ses permissions
	 * 
	 * @param string $key Clé de la propriété utilisateur à récupérer. Valeurs spéciales :
	 *                   - 'id' : Retourne l'ID de l'utilisateur depuis la session
	 *                   - 'permission' : Vérifie une permission (utilise $perm1 et $perm2)
	 *                   - Autre clé : Propriété de l'utilisateur (ex: 'role', 'pseudo', 'mail'...)
	 * @param mixed $perm1 Premier paramètre de permission (obligatoire si $key = 'permission')
	 * @param mixed $perm2 Second paramètre de permission optionnel
	 * @return mixed Retourne :
	 *               - string|int|array pour les propriétés utilisateur
	 *               - bool pour les vérifications de permission
	 *               - null si l'utilisateur n'est pas connecté ou la propriété n'existe pas
	 * 
	 * @example 
	 * // Récupérer l'ID de l'utilisateur
	 * $userId = $this->getUser('id');
	 * 
	 * // Vérifier une permission
	 * $canEdit = $this->getUser('permission', 'page', 'edit');
	 * 
	 * // Récupérer une propriété utilisateur
	 * $userRole = $this->getUser('role');
	 */
	public function getUser($key, $perm1 = null, $perm2 = null)
	{
		if ($this->isConnected() === false) {
			return false;
		} elseif (is_array($this->user) === false) {
			return false;
		} elseif ($key === 'id') {
			return $_SESSION['ZWII_USER_ID'] ?? false;
		} elseif ($key === 'permission') {
			return $this->getPermission($perm1, $perm2);
		} elseif (array_key_exists($key, $this->user)) {
			return $this->user[$key];
		} else {
			return false;
		}
	}

	/**
	 * Vérifie les permissions d'un utilisateur selon son rôle et son profil
	 * 
	 * @param string|object $key1 Clé de permission (peut être une classe de module ou une catégorie de permission)
	 * @param string|null $key2 Sous-clé de permission optionnelle (action spécifique ou sous-catégorie)
	 * @return mixed Retourne :
	 *               - true si l'utilisateur est administrateur
	 *               - false si le rôle n'a pas la permission ou si l'utilisateur est un visiteur
	 *               - La valeur de la permission spécifiée si elle existe dans le profil
	 *               - false dans tous les autres cas
	 * 
	 * @example 
	 * // Vérifie si l'utilisateur a la permission d'ajouter un article dans le module blog
	 * $hasPermission = $this->getPermission('blog', 'add');
	 * 
	 * // Vérifie si l'utilisateur a une permission spécifique
	 * $canEdit = $this->getPermission('page', 'edit');
	 * 
	 * // Vérifie une permission sur un module instancié
	 * $module = new blog();
	 * $canConfigure = $this->getPermission($module, 'config');
	 */
	private function getPermission($key1, $key2 = null)
	{
		// Administrateur, toutes les permissions
		if ($this->getUser('role') === self::ROLE_ADMIN) {
			return true;
		} elseif ($this->getUser('role') <= self::ROLE_VISITOR) {  // Role sans autorisation
			return false;
		} elseif (
			// Role avec profil, consultation des autorisations sur deux clés
			$key1 &&
			$key2 &&
			$this->user &&
			$this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]) &&
			array_key_exists($key2, $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]))
		) {
			return $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1, $key2]);
			// Role avec profil, consultation des autorisations sur une seule clé
		} elseif (
			$key1 &&
			$this->user &&
			$this->getData(['profil', $this->user['role'], $this->user['profil']]) &&
			array_key_exists($key1, $this->getData(['profil', $this->user['role'], $this->user['profil']]))
		) {
			return $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]);
		} else {
			// Une permission non spécifiée dans le profil est autorisée selon la valeur de $actions
			if (class_exists($key1)) {
				$module = new $key1;
				if (array_key_exists($key2, $module::$actions)) {
					return $this->getUser('role') >= $module::$actions[$key2];
				}
			}
			return false;
		}
	}

	/**
	 * @return bool l'utilisateur est connecté true sinon false
	 */
	public function isConnected()
	{
		// Vérifie d'abord si l'utilisateur est chargé et a une clé d'authentification
		if (is_array($this->user) === false || empty($this->user['authKey'])) {
			return false;
		}
		// Vérifie que la clé d'authentification correspond
		return $this->user['authKey'] === $this->getInput('ZWII_AUTH_KEY');
	}

    	/**
	 * Retourne le chemin du dossier autorisé pour l'utilisateur selon son rôle et son profil
	 *
	 * @param string $userId L'identifiant de l'utilisateur
	 * @return string Dossier autorisé
	 *
	 * Pour les administrateurs :
	 * - Retourne un accès complet au répertoire source
	 *
	 * Pour les éditeurs :
	 * - Retourne un accès basé sur les paramètres de leur profil
	 * - En mode Campus : utilise homePath ou coursePath
	 * - En mode CMS : utilise le chemin standard
	 * - Retourne 'none' si aucun accès n'est accordé
	 * - Sinon retourne un accès limité à leur espace de contenu
	 */
	public function getUserPath($userId = null)
	{
		// Si aucun userId n'est fourni, utiliser l'id de l'utilisateur courant
		$userId = $userId ?? $this->getUser('id');

		// Vérifier si l'utilisateur est un administrateur
		switch ($this->getData(['user', $userId, 'role'])) {
			case self::ROLE_ADMIN:
				return self::FILE_DIR . 'source/';
			case self::ROLE_EDITOR:
			case self::ROLE_MODERATOR:
				return $this->getPermission('folder', 'path');
			default:
				// retourne un échec
				return 'none';
		}
	}
    
    	/**
	 * Retourne le chemin normalisé d'un fichier selon le rôle utilisateur
	 * 
	 * @param string $filePath Chemin relatif du fichier (depuis site/file/source/)
	 * @param string|null $userId ID utilisateur (optionnel)
	 * @return string URL complète ou chaîne vide si accès refusé
	 */
	public function getNormalizedFilePath($filePath, $userId = null)
	{

		// Récupérer l'ID utilisateur si non fourni
		$userId = $userId ?? $this->getUser('id');

		// Contrôle de la saisie et des accès de l'utilisateur
		// Pas de chemin  ou pas d'accès au filemanager
		if (
			empty(
			$filePath ||
			$this->getUserPath($userId) === 'none'
		)
		)
			return null;

		// Récupérer l'utilisateur et son rôle
		$userPath = $this->getUserPath($userId);

		// Si le chemin commence déjà par 'site/file/source/', on le retourne tel quel
		if (strpos($filePath, 'site/file/source/') === 0) {
			return $filePath;
		}

		// Sinon, on ajoute le chemin de l'utilisateur au début du chemin du fichier
		return $userPath . ltrim($filePath, '/');


	}
}