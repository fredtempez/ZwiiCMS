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
trait UserPermission { public function getUser($key, $perm1 = null, $perm2 = null) { if ($this->isConnected() === false) { return false; } elseif (is_array($this->user) === false) { return false; } elseif ($key === 'id') { return $_SESSION['ZWII_USER_ID'] ?? false; } elseif ($key === 'permission') { return $this->getPermission($perm1, $perm2); } elseif (array_key_exists($key, $this->user)) { return $this->user[$key]; } else { return false; } } private function getPermission($key1, $key2 = null) { if ($this->getUser('role') === self::ROLE_ADMIN) { return true; } elseif ($this->getUser('role') <= self::ROLE_VISITOR) { return false; } elseif ( $key1 && $key2 && $this->user && $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]) && array_key_exists($key2, $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1])) ) { return $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1, $key2]); } elseif ( $key1 && $this->user && $this->getData(['profil', $this->user['role'], $this->user['profil']]) && array_key_exists($key1, $this->getData(['profil', $this->user['role'], $this->user['profil']])) ) { return $this->getData(['profil', $this->user['role'], $this->user['profil'], $key1]); } else { if (class_exists($key1)) { $module = new $key1; if (array_key_exists($key2, $module::$actions)) { return $this->getUser('role') >= $module::$actions[$key2]; } } return false; } } public function isConnected() { if (is_array($this->user) === false || empty($this->user['authKey'])) { return false; } return $this->user['authKey'] === $this->getInput('ZWII_AUTH_KEY'); } public function getUserPath($userId = null) { $userId = $userId ?? $this->getUser('id'); switch ($this->getData(['user', $userId, 'role'])) { case self::ROLE_ADMIN: return self::FILE_DIR . 'source/'; case self::ROLE_EDITOR: case self::ROLE_MODERATOR: return $this->getPermission('folder', 'path'); default: return 'none'; } } public function getNormalizedFilePath($filePath, $userId = null) { $userId = $userId ?? $this->getUser('id'); if ( empty( $filePath || $this->getUserPath($userId) === 'none' ) ) return null; $userPath = $this->getUserPath($userId); if (strpos($filePath, 'site/file/source/') === 0) { return $filePath; } return $userPath . ltrim($filePath, '/'); } }
