<?php
/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */
class redirection extends common { const VERSION = '2.3'; const REALNAME = 'Redirection'; const DATADIRECTORY = ''; const MULTIMODULE = false; const SINGLEINSTANCE = true; public static $actions = [ 'config' => self::ROLE_EDITOR, 'index' => self::ROLE_VISITOR ]; public function config() { if ( $this->getUser('permission', __CLASS__, __FUNCTION__) === true && $this->isPost() ) { $this->setData(['module', $this->getUrl(0), 'url', $this->getInput('redirectionConfigUrl', helper::FILTER_URL, true)]); $this->addOutput([ 'redirect' => helper::baseUrl() . $this->getUrl(), 'notification' => helper::translate('Modifications enregistrées'), 'state' => true ]); } $this->addOutput([ 'title' => helper::translate('Configuration du module'), 'view' => 'config' ]); } public function index() { if ( $this->isConnected() === true && $this->getUser('role') >= self::ROLE_EDITOR && $this->getUrl(1) !== 'force' ) { $this->addOutput([ 'display' => self::DISPLAY_LAYOUT_BLANK, 'title' => '', 'view' => 'index' ]); } else { $this->setData(['module', $this->getUrl(0), 'count', helper::filter((int) $this->getData(['module', $this->getUrl(0), 'count']) + 1, helper::FILTER_INT)]); $this->addOutput([ 'redirect' => (string) $this->getData(['module', $this->getUrl(0), 'url']), 'state' => true ]); } } }
