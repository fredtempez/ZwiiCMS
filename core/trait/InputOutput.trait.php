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

use Zwii\Cms\Core;
use Zwii\Cms\Helper;

trait InputOutput
{
	/**
	 * Ajoute une notice de champ obligatoire
	 * @param string $key Clef du champ
	 */
	public function addRequiredInputNotices($key)
	{
		// La clef est un tableau
		if (preg_match('#\[(.*)\]#', $key, $secondKey)) {
			$firstKey = explode('[', $key)[0];
			$secondKey = $secondKey[1];
			if (empty($this->input['_POST'][$firstKey][$secondKey])) {
				Core::$inputNotices[$firstKey . '_' . $secondKey] = Helper::translate('Obligatoire');
			}
		}
		// La clef est une chaine
		elseif (empty($this->input['_POST'][$key])) {
			Core::$inputNotices[$key] = Helper::translate('Obligatoire');
		}
	}

	/**
	 * Ajoute les valeurs en sortie
	 * @param array $output Valeurs en sortie
	 */
	public function addOutput($output)
	{
		$this->output = array_merge($this->output, $output);
	}



	/**
	 * Écrit les données dans un fichier avec plusieurs tentatives d'écriture et verrouillage
	 *
	 * @param string $filename Le nom du fichier
	 * @param string $data Les données à écrire dans le fichier
	 * @param int $flags Les drapeaux optionnels à passer à la fonction file_put_contents
	 * @return bool True si l'écriture a réussi, sinon false
	 */
	function secure_file_put_contents($filename, $data, $flags = 0)
	{
		// Validation des paramètres
		if (empty($filename) || !is_string($filename)) {
			return false;
		}

		// Vérifier que le dossier parent existe et est accessible en écriture
		$dir = dirname($filename);
		if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
			return false;
		}

		if (!is_writable($dir)) {
			return false;
		}

		// Initialise le compteur de tentatives
		$attempts = 0;
		$maxAttempts = 5;

		// Effectue jusqu'à 5 tentatives d'écriture
		while ($attempts < $maxAttempts) {
			// Essaye d'écrire les données dans le fichier avec verrouillage exclusif
			$write_result = file_put_contents($filename, $data, LOCK_EX | $flags);

			// Vérifie si l'écriture a réussi
			if ($write_result !== false) {
				// Vérification supplémentaire : lire le fichier pour s'assurer de l'intégrité
				$read_data = file_get_contents($filename);
				if ($read_data === $data) {
					return true;
				}
			}

			// Incrémente le compteur de tentatives
			$attempts++;

			// Pause progressive entre les tentatives
			usleep(100000 * $attempts); // 100ms, 200ms, 300ms, etc.
		}

		// État de l'écriture
		return false;
	}

	/**
	 * Accède à une valeur des variables http (ordre de recherche en l'absence de type : _COOKIE, _POST)
	 * @param string $key Clé de la valeur
	 * @param int $filter Filtre à appliquer à la valeur
	 * @param bool $required Champ requis
	 * @return mixed
	 */
	public function getInput($key, $filter = Helper::FILTER_STRING_SHORT, $required = false)
	{
		// La clef est un tableau
		if (preg_match('#\[(.*)\]#', $key, $secondKey)) {
			$firstKey = explode('[', $key)[0];
			$secondKey = $secondKey[1];
			foreach ($this->input as $type => $values) {
				// Champ obligatoire
				if ($required) {
					$this->addRequiredInputNotices($key);
				}
				// Check de l'existence
				// Également utile pour les checkbox qui ne retournent rien lorsqu'elles ne sont pas cochées
				if (
					array_key_exists($firstKey, $values) and
					array_key_exists($secondKey, $values[$firstKey])
				) {
					// Retourne la valeur filtrée
					if ($filter) {
						return Helper::filter($this->input[$type][$firstKey][$secondKey], $filter);
					}
					// Retourne la valeur
					else {
						return $this->input[$type][$firstKey][$secondKey];
					}
				}
			}
		}
		// La clef est une chaîne
		else {
			foreach ($this->input as $type => $values) {
				// Champ obligatoire
				if ($required) {
					$this->addRequiredInputNotices($key);
				}
				// Check de l'existence
				// Également utile pour les checkbox qui ne retournent rien lorsqu'elles ne sont pas cochées
				if (array_key_exists($key, $values)) {
					// Retourne la valeur filtrée
					if ($filter) {
						return Helper::filter($this->input[$type][$key], $filter);
					}
					// Retourne la valeur
					else {
						return $this->input[$type][$key];
					}
				}
			}
		}
		// Sinon retourne null
		return Helper::filter(null, $filter);
	}

	/**
	 * Check qu'une valeur est transmise par la méthode _POST
	 * Check la présence du jeston CSRF pour éviter les attaques de type Cross-Site Request Forgery
	 * Check que l'utilisateur est connecté pour éviter les attaques de type Cross-Site Request Forgery ciblant les visiteurs
	 * @return bool
	 */
	public function isPost()
	{
			return ($this->isConnected() && $this->checkCSRF() && $this->input['_POST'] !== []);
	}



	/**
	 * Envoi un mail - VERSION AVEC LOGS D'ERREURS
	 * @param string|array $to Destinataire
	 * @param string $subject Sujet
	 * @param string $content Contenu
	 * @param string|null $replyTo Adresse de réponse
	 * @param string $from Adresse d'expédition
	 * @return bool|string True si succès, message d'erreur sinon
	 */
	public function sendMail($to, $subject, $content, $replyTo = null, $serverEmail = null, $file_name = null)
	{
		// 1. Initialisation des variables par défaut
		$serverName = $this->getData(['locale', 'title']);
		$serverDomain = parse_url(Helper::baseUrl(false), PHP_URL_HOST);

		// 2. Validation de sécurité
		if (empty($to) || empty($subject) || empty($content)) {
			$this->saveLog('Échec sendMail : Paramètres manquants.', true);
			return 'Paramètres manquants';
		}

		// 3. Préparation du Layout HTML
		ob_start();
		include 'core/layout/mail.php';
		$layout = ob_get_clean();

		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
		$mail->CharSet = 'UTF-8';

		try {
			// 4. Configuration SMTP Robuste
			if ($this->getdata(['config', 'smtp', 'enable']) === true) {
				$mail->isSMTP();
				$mail->Host = $this->getdata(['config', 'smtp', 'host']);
				$smtpPort = (int) $this->getdata(['config', 'smtp', 'port']);
				$mail->Port = $smtpPort;
				$mail->SMTPAuth = (bool) $this->getData(['config', 'smtp', 'auth']);

				if ($mail->SMTPAuth) {
					// Chiffrement depuis la configuration (ssl, tls ou vide)
					$mail->SMTPSecure = $this->getData(['config', 'smtp', 'secure']);
					$mail->Username = $this->getData(['config', 'smtp', 'username']);
					$storedPassword = $this->getData(['config', 'smtp', 'password']);
					$smtpHost = $this->getData(['config', 'smtp', 'host']);
					
					// Déchiffrer le password avec le host comme clé (voir config.php ligne 533)
					$mail->Password  = Helper::decrypt($storedPassword, $smtpHost);
				
				}
			}

			// 5. Identité de l'expéditeur (Crucial pour Gmail)
			// Le 'setFrom' doit être une adresse liée à votre domaine, pas 'localhost'
			$mail->setFrom($serverEmail, $serverName);
			$mail->addReplyTo($replyTo ?? $serverEmail, $serverName);

			// Return-Path : l'adresse vers laquelle les erreurs de livraison sont renvoyées
			// Valider l'email avant d'assigner pour éviter "Invalid address (Sender)" 
			if (filter_var($serverEmail, FILTER_VALIDATE_EMAIL)) {
				$mail->Sender = $serverEmail;
			}

			// 6. Destinataires
			$destinatairesArray = is_array($to) ? $to : explode(',', $to);
			foreach ($destinatairesArray as $email) {
				$cleanEmail = trim($email);
				if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
					$mail->addAddress($cleanEmail);
				}
			}

			// 7. Contenu
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $layout;
			$mail->AltBody = strip_tags($content);

			// 8. Headers Anti-Spam (RFC compliance)
			$mail->addCustomHeader('List-Unsubscribe', '<mailto:' . $serverEmail . '?subject=unsubscribe>');
			$mail->XMailer = ' '; // On cache la version de PHPMailer pour la sécurité

			// 9. Pièce jointe
			if (!empty($file_name)) {
				$filePath = self::FILE_DIR . 'uploads/' . $file_name;
				if (file_exists($filePath)) {
					$mail->addAttachment($filePath);
				}
			}

			// 10. Envoi avec capture d'erreur explicite
			$sendResult = $mail->send();
			if (!$sendResult) {
				$this->saveLog('SMTP Send failed: ' . $mail->ErrorInfo, true);
			}
			return $sendResult;

		} catch (Exception $e) {
			$this->saveLog('Erreur PHPMailer : ' . $mail->ErrorInfo, true);
			return false;
		}
	}


	/**
	 * Corrige l'adresse email smtpFrom pour correspondre au domaine du site
	 * @param string $smtpFrom Adresse email saisie
	 * @return string Adresse email avec le domaine corrigé
	 */
	protected function getCorrectedSmtpFrom($smtpFrom)
	{
		// Si l'email est vide ou invalide, retourner tel quel
		if (empty($smtpFrom) || !filter_var($smtpFrom, FILTER_VALIDATE_EMAIL)) {
			return $smtpFrom;
		}

		// Extraire le domaine du site
		$baseUrl = Helper::baseUrl(false);
		$parsedUrl = parse_url($baseUrl);
		$hostname = isset($parsedUrl['host']) ? $parsedUrl['host'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');

		if (empty($hostname)) {
			return $smtpFrom;
		}

		// Normalisation
		$hostname = strtolower($hostname);
		$hostname = preg_replace('#^www\.#', '', $hostname);

		// Extraction du domaine principal
		$hostParts = explode('.', $hostname);

		if (count($hostParts) >= 2) {
			$siteDomain = implode('.', array_slice($hostParts, -2));
		} else {
			$siteDomain = $hostname;
		}

		// Extraire les parties de l'email
		$emailParts = explode('@', $smtpFrom);
		if (count($emailParts) !== 2) {
			return $smtpFrom;
		}

		$localPart = $emailParts[0];
		$emailDomain = strtolower($emailParts[1]);

		// Correction localhost -> domaine réel
		if (
			($emailDomain === 'localhost' || strpos($emailDomain, '127.0.0.1') !== false) &&
			($siteDomain !== 'localhost' && strpos($siteDomain, '127.0.0.1') === false)
		) {
			return $localPart . '@' . $siteDomain;
		}

		// Si déjà correct (insensible à la casse)
		if (
			strcasecmp($emailDomain, $siteDomain) === 0 ||
			strcasecmp($emailDomain, 'www.' . $siteDomain) === 0
		) {
			return $smtpFrom;
		}

		// Remplacer le domaine
		return $localPart . '@' . $siteDomain;
	}

		/**
	 * Journalisation avec gestion de la taille maximale et compression
	 */
	public function saveLog($message = '', $force = false)
	{
		// Chemin du fichier journal
		$logFile = self::DATA_DIR . 'journal.log';

		// Vérifier la taille du fichier
		if (file_exists($logFile) && filesize($logFile) > self::LOG_MAXSIZE) {
			$this->rotateLogFile();
		}

		// Création de l'entrée de journal
		$dataLog = Helper::dateUTF8('%Y%m%d', time(), self::$i18nUI) . ';'
			. Helper::dateUTF8('%H:%M', time(), self::$i18nUI) . ';';
		$dataLog .= Helper::getIp($this->getData(['config', 'connect', 'anonymousIp'])) . ';';
		$dataLog .= empty($this->getUser('id')) ? 'visitor;' : $this->getUser('id') . ';';
		$dataLog .= $message ? $this->getUrl() . ';' . $message : $this->getUrl();
		$dataLog .= PHP_EOL;

		// Écriture dans le fichier si la journalisation est activée
		if (
			$this->getData(['config', 'connect', 'log'])
			|| $force
		) {
			file_put_contents($logFile, $dataLog, FILE_APPEND);
		}
	}

		/**
	 * Gère la rotation et la compression des fichiers journaux
	 * @return bool True si la rotation a réussi, false sinon
	 */
	private function rotateLogFile(): bool
	{
		$logFile = self::DATA_DIR . 'journal.log';

		// 1. Vérifications préliminaires
		if (!is_readable($logFile) || !is_writable(dirname($logFile))) {
			trigger_error('Fichier journal inaccessible ou dossier non inscriptible', E_USER_WARNING);
			return false;
		}

		// 2. Vérifier si rotation nécessaire (taille minimale)
		if (filesize($logFile) < 1024) { // < 1KB
			return true; // Pas besoin de rotation
		}

		// 3. Verrouillage du fichier pour éviter les race conditions
		$lockFile = fopen($logFile, 'r');
		if (!flock($lockFile, LOCK_EX | LOCK_NB)) {
			fclose($lockFile);
			trigger_error('Fichier journal déjà utilisé par un autre processus', E_USER_WARNING);
			return false;
		}

		$tempFile = null;
		try {
			// 4. Décaler d'abord les anciennes archives
			for ($i = self::LOG_MAXARCHIVE - 1; $i > 0; $i--) {
				$oldFile = self::DATA_DIR . 'journal-' . $i . '.log.gz';
				$newFile = self::DATA_DIR . 'journal-' . ($i + 1) . '.log.gz';

				// Si le fichier existe, le déplacer ou le supprimer
				if (file_exists($oldFile)) {
					if ($i >= self::LOG_MAXARCHIVE - 1) {
						if (!unlink($oldFile)) {
							trigger_error('Impossible de supprimer l\'ancienne archive: ' . $oldFile, E_USER_WARNING);
						}
					} else if (!rename($oldFile, $newFile)) {
						trigger_error(sprintf('Échec du déplacement de %s vers %s', $oldFile, $newFile), E_USER_WARNING);
					}
				}
			}

			// 5. Créer une copie temporaire pour la compression
			$tempFile = tempnam(sys_get_temp_dir(), 'zwii_log_');
			if (!copy($logFile, $tempFile)) {
				throw new \RuntimeException('Échec de la copie temporaire du journal');
			}

			// 6. Compresser L'ANCIEN contenu (sécurisé - AVANT de vider le fichier)
			$gzFile = self::DATA_DIR . 'journal-1.log.gz';
			$gz = @gzopen($gzFile, 'w9');
			if ($gz === false) {
				throw new \RuntimeException('Impossible d\'ouvrir le fichier de destination pour la compression');
			}

			$source = fopen($tempFile, 'r');
			if ($source === false) {
				gzclose($gz);
				throw new \RuntimeException('Impossible de lire le fichier source pour la compression');
			}

			// Utiliser stream_copy_to_stream pour de meilleures performances
			stream_copy_to_stream($source, $gz);

			fclose($source);
			gzclose($gz);

			// 7. Vérifier le succès de la compression AVANT de vider le fichier
			if (!file_exists($gzFile) || filesize($gzFile) === 0) {
				throw new \RuntimeException('La compression du journal a échoué');
			}

			// 8. SEULEMENT MAINTENANT vider le fichier journal principal (sécurisé)
			if (file_put_contents($logFile, '') === false) {
				throw new \RuntimeException('Impossible de vider le fichier journal');
			}

			// 9. Nettoyer le fichier temporaire
			@unlink($tempFile);
			$tempFile = null;

			// 10. Libérer le verrou
			flock($lockFile, LOCK_UN);
			fclose($lockFile);

			return true;

		} catch (\Exception $e) {
			// Nettoyer en cas d'erreur
			if ($tempFile) {
				@unlink($tempFile);
			}
			if (isset($lockFile)) {
				flock($lockFile, LOCK_UN);
				fclose($lockFile);
			}

			trigger_error('Erreur lors de la rotation du journal: ' . $e->getMessage(), E_USER_WARNING);
			return false;
		}
	}

}