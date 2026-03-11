<?php

class core extends common
{
	/**
	 * Constructeur du coeur
	 */
	public function __construct()
	{
		parent::__construct();

		// 1. Initialisation Système (CSRF, Timezone, Nettoyage, Backup)
		$this->initSystem();

		// 2. Gestion des thèmes et CSS (déléguée à theme)
		require_once 'core/class/asset.class.php';
		new asset();
	}

	/**
	 * Gère l'initialisation système : Sécurité, Timezone, Nettoyage, Backup
	 */
	private function initSystem()
	{
		// Token CSRF
		if (empty($_SESSION['csrf'])) {
			$_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(64));
		}

		// Fuseau horaire
		common::$timezone = $this->getData(['config', 'timezone']);
		date_default_timezone_set(common::$timezone);

		// Supprime les fichiers temporaires
		$lastClearTmp = mktime(0, 0, 0);
		if ($lastClearTmp > $this->getData(['core', 'lastClearTmp']) + 86400) {
			$iterator = new DirectoryIterator(common::TEMP_DIR);
			foreach ($iterator as $fileInfos) {
				if (
					$fileInfos->isFile() &&
					$fileInfos->getBasename() !== '.htaccess' &&
					$fileInfos->getBasename() !== '.gitkeep'
				) {
					@unlink($fileInfos->getPathname());
				}
			}
			$this->setData(['core', 'lastClearTmp', $lastClearTmp]);
		}

		// Gestion des sauvegardes automatiques
		$lastBackup = mktime(0, 0, 0);
		if (
			$this->getData(['config', 'autoBackup']) &&
			$lastBackup > $this->getData(['core', 'lastBackup']) + 86400 &&
			$this->getData(['user'])
		) {
			if (helper::autoBackup(common::BACKUP_DIR, ['backup', 'tmp', 'file'])) {
				$this->setData(['core', 'lastBackup', $lastBackup]);

				// Nettoyage des anciennes sauvegardes (> 30 jours)
				$iterator = new DirectoryIterator(common::BACKUP_DIR);
				$now = time();
				foreach ($iterator as $file) {
					if (
						$file->isFile() &&
						$file->getBasename() !== '.htaccess' &&
						$file->getMTime() + (86400 * 30) < $now
					) {
						@unlink($file->getPathname());
					}
				}
			}
		}
	}

	/**
	 * Auto-chargement des classes
	 * @param string $className Nom de la classe à charger
	 */
	public static function autoload($className)
	{

		$classPath = strtolower($className) . '/' . strtolower($className) . '.php';
		// Module du coeur
		if (is_readable('core/module/' . $classPath)) {
			require 'core/module/' . $classPath;
		}
		// Module
		elseif (is_readable(common::MODULE_DIR . $classPath)) {
			require common::MODULE_DIR . $classPath;
		}
		// Librairie
		elseif (is_readable('core/vendor/' . $classPath)) {
			require 'core/vendor/' . $classPath;
		}
	}

	/**
	 * Routage des modules
	 */
	public function router()
	{

		$layout = new layout($this);

		// Installation
		if (
			$this->getData(['user']) === []
			and $this->getUrl(0) !== 'install'
		) {
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'install');
			exit();
		}

		// Journalisation
		$this->saveLog();

		// Force la déconnexion des membres bannis ou d'une seconde session
		if (
			$this->isConnected() === true
			and ($this->getUser('role') === common::ROLE_BANNED
				or ($_SESSION['csrf'] !== $this->getData(['user', $this->getUser('id'), 'accessCsrf'])
					and $this->getData(['config', 'connect', 'autoDisconnect']) === true)
			)
		) {
			$user = new user;
			$user->logout();
		}
		// Mode maintenance
		if (
			$this->getData(['config', 'maintenance'])
			and in_array($this->getUrl(0), ['maintenance', 'user']) === false
			and $this->getUrl(1) !== 'login'
			and ($this->isConnected() === false
				or ($this->isConnected() === true
					and $this->getUser('role') < common::ROLE_ADMIN
				)
			)
		) {
			// Déconnexion
			$user = new user;
			$user->logout();
			// Redirection
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'maintenance');
			exit();
		}

		// Check l'accès à la page
		$access = null;
		if ($this->getData(['page', $this->getUrl(0)]) !== null) {
			if (
				$this->getData(['page', $this->getUrl(0), 'role']) === common::ROLE_VISITOR
				|| ($this->isConnected() === true
					// and $this->getUser('role') >= $this->getData(['page', $this->getUrl(0), 'role'])
					// Modification qui tient compte du profil de la page
					&& ($this->getUser('role') * 10 + $this->getUser('profil')) >= ($this->getData(['page', $this->getUrl(0), 'role']) * 10 + $this->getData(['page', $this->getUrl(0), 'profil']))
				)
			) {
				$access = true;
			} else {
				if (
					// Test pour vérifier que la paged'accueil est bien définie
					// Afin d'éviter un bouclage en cas d'incohérence dans les données
					is_string($this->getData(['locale', 'homePageId']))
					&& $this->getUrl(0) === $this->getData(['locale', 'homePageId'])
				) {
					$access = 'login';
				} else {
					$access = false;
				}
			}
			// Empêcher l'accès aux pages désactivées par URL directe
			if (
				($this->getData(['page', $this->getUrl(0), 'disable']) === true
					and $this->isConnected() === false
				) or ($this->getData(['page', $this->getUrl(0), 'disable']) === true
					and $this->isConnected() === true
					and $this->getUser('role') < common::ROLE_EDITOR
				)
			) {
				$access = false;
			}
			// Lève une erreur si l'url est celle d'une page avec des éléments surnuméraires  https://www.site.fr/page/truc
			if (
				array_key_exists($this->getUrl(0), $this->getData(['page']))
				and $this->getUrl(1)
				and $this->getData(['page', $this->getUrl(0), 'moduleId']) === ''
			) {
				$access = false;
			}
		}

		/**
		 * Contrôle si la page demandée est en édition ou accès à la gestion du site
		 * conditions de blocage :
		 * - Les deux utilisateurs qui accèdent à la même page sont différents
		 * - les URLS sont identiques
		 * - Une partie de l'URL fait partie  de la liste de filtrage (édition d'un module etc..)
		 * - L'édition est ouverte depuis un temps dépassé, on considère que la page est restée ouverte et qu'elle ne sera pas validée
		 */
		// Récupérer les données utilisateurs une seule fois
		$usersData = $this->getData(['user']) ?: [];
		$accessInfo['userName'] = '';
		$accessInfo['pageId'] = '';

		// Vérifier si l'utilisateur est connecté et que ce n'est pas une page de cours/swap
		if (!empty($usersData) && $this->getUser('id')) {
			$currentUrl = $this->getUrl();
			$currentTime = time();
			$currentUserId = $this->getUser('id');

			foreach ($usersData as $userId => $userData) {
				if (!isset($userData['accessUrl']) || $userId === $currentUserId) {
					continue;
				}

				$urlParts = explode('/', $userData['accessUrl']);
				$isConcurrentAccess = !empty(array_intersect($urlParts, common::$concurrentAccess));
				$isAccessTimerValid = $currentTime < ($userData['accessTimer'] ?? 0) + common::ACCESS_TIMER;

				if ($userData['accessUrl'] === $currentUrl && $isConcurrentAccess && $isAccessTimerValid) {
					$access = false;
					$accessInfo['userName'] = ($userData['lastname'] ?? '') . ' ' . ($userData['firstname'] ?? '');
					$accessInfo['pageId'] = end($urlParts);
					break; // On sort dès qu'on trouve un accès concurrent
				}
			}
		}
		// Accès concurrent stocke la page visitée
		if (
			$this->isConnected() === true
			&& $this->getUser('id')
			&& !$this->isPost()
		) {
			$this->setData(['user', $this->getUser('id'), 'accessUrl', $this->getUrl()], false);
			$this->setData(['user', $this->getUser('id'), 'accessTimer', time()]);
		}
		// Breadcrumb
		$title = $this->getData(['page', $this->getUrl(0), 'title']);
		if (
			!empty($this->getData(['page', $this->getUrl(0), 'parentPageId'])) &&
			$this->getData(['page', $this->getUrl(0), 'breadCrumb'])
		) {
			$title = '<a href="' . helper::baseUrl() .
				$this->getData(['page', $this->getUrl(0), 'parentPageId']) .
				'">' .
				ucfirst($this->getData(['page', $this->getData(['page', $this->getUrl(0), 'parentPageId']), 'title'])) .
				'</a> &#8250; ' .
				$this->getData(['page', $this->getUrl(0), 'title']);
		}


		// Importe le style de la page principale
		$inlineStyle[] = $this->getData(['page', $this->getUrl(0), 'css']) === null ? '' : $this->getData(['page', $this->getUrl(0), 'css']);
		// Importe le script de la page principale
		$inlineScript[] = $this->getData(['page', $this->getUrl(0), 'js']) === null ? '' : $this->getData(['page', $this->getUrl(0), 'js']);

		// Importe le contenu, le CSS et le script des barres
		$contentRight = $this->getData(['page', $this->getUrl(0), 'barRight']) ? $this->getPage($this->getData(['page', $this->getUrl(0), 'barRight']), common::$siteContent) : '';
		$inlineStyle[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'css']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'css']);
		$inlineScript[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'js']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barRight']), 'js']);
		$contentLeft = $this->getData(['page', $this->getUrl(0), 'barLeft']) ? $this->getPage($this->getData(['page', $this->getUrl(0), 'barLeft']), common::$siteContent) : '';
		$inlineStyle[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'css']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'css']);
		$inlineScript[] = $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'js']) === null ? '' : $this->getData(['page', $this->getData(['page', $this->getUrl(0), 'barLeft']), 'js']);


		// Importe la page simple sans module ou avec un module inexistant
		if (
			$this->getData(['page', $this->getUrl(0)]) !== null
			and ($this->getData(['page', $this->getUrl(0), 'moduleId']) === ''
				or !class_exists($this->getData(['page', $this->getUrl(0), 'moduleId']))
			)
			and $access
		) {

			// Importe le CSS de la page principale

			$this->addOutput([
				'title' => $title,
				'content' => $this->getPage($this->getUrl(0), common::$siteContent),
				'metaDescription' => $this->getData(['page', $this->getUrl(0), 'metaDescription']),
				'metaTitle' => $this->getData(['page', $this->getUrl(0), 'metaTitle']),
				'typeMenu' => $this->getData(['page', $this->getUrl(0), 'typeMenu']),
				'iconUrl' => $this->getData(['page', $this->getUrl(0), 'iconUrl']),
				'disable' => $this->getData(['page', $this->getUrl(0), 'disable']),
				'contentRight' => $contentRight,
				'contentLeft' => $contentLeft,
				'inlineStyle' => $inlineStyle,
				'inlineScript' => $inlineScript,
			]);
		}
		// Importe le module
		else {
			// Id du module, et valeurs en sortie de la page s'il s'agit d'un module de page

			if ($access and $this->getData(['page', $this->getUrl(0), 'moduleId'])) {
				$moduleId = $this->getData(['page', $this->getUrl(0), 'moduleId']);

				// Construit un meta absent
				$metaDescription = $this->getData(['page', $this->getUrl(0), 'moduleId']) === 'blog' && !empty($this->getUrl(1)) && in_array($this->getUrl(1), $this->getData(['module']))
					? strip_tags(substr($this->getData(['module', $this->getUrl(0), 'posts', $this->getUrl(1), 'content']), 0, 159))
					: $this->getData(['page', $this->getUrl(0), 'metaDescription']);

				// Importe le CSS de la page principale
				$pageContent = $this->getPage($this->getUrl(0), common::$siteContent);

				$this->addOutput([
					'title' => $title,
					// Meta description = 160 premiers caractères de l'article
					'content' => $pageContent,
					'metaDescription' => $metaDescription,
					'metaTitle' => $this->getData(['page', $this->getUrl(0), 'metaTitle']),
					'typeMenu' => $this->getData(['page', $this->getUrl(0), 'typeMenu']),
					'iconUrl' => $this->getData(['page', $this->getUrl(0), 'iconUrl']),
					'disable' => $this->getData(['page', $this->getUrl(0), 'disable']),
					'contentRight' => $contentRight,
					'contentLeft' => $contentLeft,
					'inlineStyle' => $inlineStyle,
					'inlineScript' => $inlineScript,
				]);
			} else {
				$moduleId = $this->getUrl(0);
				$pageContent = '';
			}

			// Check l'existence du module
			if (class_exists($moduleId)) {
				/** @var common $module */
				$module = new $moduleId;

				// Check l'existence de l'action
				$action = '';
				$ignore = true;
				if (!is_null($this->getUrl(1))) {
					foreach (explode('-', $this->getUrl(1)) as $actionPart) {
						if ($ignore) {
							$action .= $actionPart;
							$ignore = false;
						} else {
							$action .= ucfirst($actionPart);
						}
					}
				}
				$action = array_key_exists($action, $module::$actions) ? $action : 'index';
				if (array_key_exists($action, $module::$actions)) {
					$module->$action();
					$output = $module->output;
					// Check le rôle de l'utilisateur
					if (
						($module::$actions[$action] === common::ROLE_VISITOR
							or ($this->isConnected() === true
								and $this->getUser('role') >= $module::$actions[$action]
								and $this->getUser('permission', $moduleId, $action)
							)
						)
						and $output['access'] === true
					) {
						// Enregistrement du contenu de la méthode POST lorsqu'une notice est présente
						if (common::$inputNotices) {
							foreach ($_POST as $postId => $postValue) {
								if (is_array($postValue)) {
									foreach ($postValue as $subPostId => $subPostValue) {
										common::$inputBefore[$postId . '_' . $subPostId] = $subPostValue;
									}
								} else {
									common::$inputBefore[$postId] = $postValue;
								}
							}
						}
						// Sinon traitement des données de sortie qui requiert qu'aucune notice ne soit présente
						else {
							// Notification
							if ($output['notification']) {
								if ($output['state'] === true) {
									$notification = 'ZWII_NOTIFICATION_SUCCESS';
								} elseif ($output['state'] === false) {
									$notification = 'ZWII_NOTIFICATION_ERROR';
								} else {
									$notification = 'ZWII_NOTIFICATION_OTHER';
								}
								$_SESSION[$notification] = $output['notification'];
							}
							// Redirection
							if ($output['redirect']) {
								http_response_code(301);
								header('Location:' . $output['redirect']);
								exit();
							}
						}
						// Données en sortie applicables même lorsqu'une notice est présente
						// Affichage
						if ($output['display']) {
							$this->addOutput([
								'display' => $output['display']
							]);
						}
						// Contenu brut
						if ($output['content']) {
							$this->addOutput([
								'content' => $output['content']
							]);
						}
						// Contenu par vue
						elseif ($output['view']) {
							// Chemin en fonction d'un module du coeur ou d'un module
							$modulePath = in_array($moduleId, common::$coreModuleIds) ? 'core/' : '';
							// CSS
							$stylePath = $modulePath . common::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.css';
							if (file_exists($stylePath)) {
								$this->addOutput([
									'style' => file_get_contents($stylePath)
								]);
							}
							if ($output['style']) {
								$this->addOutput([
									'style' => file_get_contents($output['style'])
								]);
							}

							// JS
							$scriptPath = $modulePath . common::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.js.php';
							if (file_exists($scriptPath)) {
								ob_start();
								include $scriptPath;
								$this->addOutput([
									'script' => ob_get_clean()
								]);
							}
							// Vue
							$viewPath = $modulePath . common::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.php';
							if (file_exists($viewPath)) {
								ob_start();
								include $viewPath;
								$modpos = $this->getData(['page', $this->getUrl(0), 'modulePosition']);
								if ($modpos === 'top') {
									$this->addOutput([
										'content' => ob_get_clean() . ($output['showPageContent'] ? $pageContent : '')
									]);
								} elseif ($modpos === 'free' && strstr($pageContent, '[MODULE]')) {
									if (strstr($pageContent, '[MODULE]', true) === false) {
										$begin = strstr($pageContent, '[]', true);
									} else {
										$begin = strstr($pageContent, '[MODULE]', true);
									}
									if (strstr($pageContent, '[MODULE]') === false) {
										$end = strstr($pageContent, '[]');
									} else {
										$end = strstr($pageContent, '[MODULE]');
									}
									$cut = 8;
									$end = substr($end, -strlen($end) + $cut);
									$this->addOutput([
										'content' => ($output['showPageContent'] ? $begin : '') . ob_get_clean() . ($output['showPageContent'] ? $end : '')
									]);
								} else {
									$this->addOutput([
										'content' => ($output['showPageContent'] ? $pageContent : '') . ob_get_clean()
									]);
								}
							}
						}
						// Librairies
						if ($output['vendor'] !== $this->output['vendor']) {
							$this->addOutput([
								'vendor' => array_merge($this->output['vendor'], $output['vendor'])
							]);
						}

						// Titre de la vue
						if ($output['title'] !== null) {
							$this->addOutput([
								'title' => $output['title']
							]);
						}
						
						// Affiche le bouton d'édition de la page dans la barre de membre
						if ($output['showBarEditButton']) {
							$this->addOutput([
								'showBarEditButton' => $output['showBarEditButton']
							]);
						}
					}
				}
			}
		}
		// Erreurs
		if ($access === 'login') {
			http_response_code(302);
			header('Location:' . helper::baseUrl() . 'user/login/');
			exit();
		}
		// Redirection vers la page de connexion si la page nécessite une authentification et que l'utilisateur n'est pas connecté
		if ($access === null) {
			// Envoyer vers la page de connexion
			if (
				// Vérifie si la redirection après connexion est activée dans la configuration
				$this->getData(['config', 'connect', 'redirectLogin']) ===  true
				// Vérifie que l'utilisateur n'est pas connecté
				&& $this->isConnected() === false
				// Vérifie que l'URL actuelle n'est pas déjà sur le contrôleur 'user'
				&& $this->getUrl(0) !== 'user'
				// Vérifie que l'URL actuelle n'est pas sur l'action 'login'
				&& $this->getUrl(1) !== 'login'
				// Vérifie que la page actuelle nécessite une authentification (est dans la liste des accès concurrents)
				&& in_array($this->getUrl(0), self::$concurrentAccess)
			) {
				http_response_code(302);
				header('Location:' . helper::baseUrl() . 'user/login/');
				exit();
			}
		}
		// Page protégée
		if ($access === false) {
			// Redirections 403 classiques
			if ($accessInfo['userName']) {
				http_response_code(403);
				$this->addOutput([
					'title' => 'Accès verrouillé',
					'content' => template::speech('<p>' . sprintf(helper::translate('La page %s est ouverte par l\'utilisateur %s</p><p><a style="color:inherit" href="javascript:history.back()">%s</a></p>'), $accessInfo['pageId'], $accessInfo['userName'], helper::translate('Retour')))

				]);
			} else {
				if (
					$this->getData(['locale', 'page403']) !== 'none'
					and $this->getData(['page', $this->getData(['locale', 'page403'])])
				) {
					http_response_code(302);
					header('Location:' . helper::baseUrl() . $this->getData(['locale', 'page403']));
				} else {
					http_response_code(403);
					$this->addOutput([
						'title' => 'Accès interdit',
						'content' => template::speech('<p>' . helper::translate('Vous n\'êtes pas autorisé à consulter cette page (erreur 403)') . '</p><p><a style="color:inherit" href="javascript:history.back()">' . helper::translate('Retour') . '</a></p>')
					]);
				}
			}
		} elseif ($this->output['content'] === '') {
			http_response_code(404);
			// Pour éviter une 404, bascule dans l'espace correct si la page existe dans cette langue.
			// Parcourir les espaces
			foreach (common::$languages as $langId => $value) {;
				if (
					// l'espace existe
					is_dir(common::DATA_DIR . $langId) &&
					file_exists(common::DATA_DIR . $langId . '/page.json')
				) {
					// Lire les données des pages en respectant la configuration de JsonDb
					$pagesId = $this->fetchDataFile('page', $langId, 'page');
					if (
						// La page existe
						is_array($pagesId) &&
						array_key_exists($this->getUrl(0), $pagesId)
					) {
						// Basculer
						$_SESSION['ZWII_SITE_CONTENT'] = $langId;
						header('Refresh:0; url=' . helper::baseUrl() . $this->getUrl());
						exit();
					}
				}
			}
			if (
				$this->getData(['locale', 'page404']) !== 'none'
				and $this->getData(['page', $this->getData(['locale', 'page404'])])
			) {
				header('Location:' . helper::baseUrl() . $this->getData(['locale', 'page404']));
			} else {
				$this->addOutput([
					'title' => 'Page indisponible',
					'content' => template::speech('<p>' . helper::translate('La page demandée n\'existe pas ou est introuvable (erreur 404)') . '</p><p><a style="color:inherit" href="javascript:history.back()">' . helper::translate('Retour') . '</a></p>')
				]);
			}
		}
		// Mise en forme des métas
		if ($this->output['metaTitle'] === '') {
			if ($this->output['title']) {
				$this->addOutput([
					'metaTitle' => strip_tags($this->output['title']) . ' - ' . $this->getData(['locale', 'title'])
				]);
			} else {
				$this->addOutput([
					'metaTitle' => $this->getData(['locale', 'title'])
				]);
			}
		}
		if ($this->output['metaDescription'] === '') {
			$this->addOutput([
				'metaDescription' => $this->getData(['locale', 'metaDescription'])
			]);
		}
		switch ($this->output['display']) {
				// Layout brut
			case common::DISPLAY_RAW:
				echo $this->output['content'];
				break;
				// Layout vide
			case common::DISPLAY_LAYOUT_BLANK:
				require 'core/layout/blank.php';
				break;
				// Affichage en JSON
			case common::DISPLAY_JSON:
				header('Content-Type: application/json');
				echo json_encode($this->output['content']);
				break;
				// RSS feed
			case common::DISPLAY_RSS:
				header('Content-type: application/rss+xml; charset=UTF-8');
				echo $this->output['content'];
				break;
				// Layout allégé
			case common::DISPLAY_LAYOUT_LIGHT:
				ob_start();
				require 'core/layout/light.php';
				$content = ob_get_clean();
				// Convertit la chaîne en UTF-8 pour conserver les caractères accentués
				$content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
				// Supprime les espaces, les sauts de ligne, les tabulations et autres caractères inutiles
				$content = preg_replace('/[\t ]+/u', ' ', $content);
				echo $content;
				break;
				// Layout principal
			case common::DISPLAY_LAYOUT_MAIN:
				ob_start();
				require 'core/layout/main.php';
				$content = ob_get_clean();
				// Convertit la chaîne en UTF-8 pour conserver les caractères accentués
				$content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
				// Supprime les espaces, les sauts de ligne, les tabulations et autres caractères inutiles
				$content = preg_replace('/[\t ]+/u', ' ', $content);
				echo $content;
				break;
		}
	}
}