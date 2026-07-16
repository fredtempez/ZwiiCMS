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
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International 
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */
class redirection extends common { const VERSION = '2.2'; const REALNAME = 'Redirection'; const DATADIRECTORY = ''; public static $actions = [ 'config' => self::ROLE_EDITOR, 'index' => self::ROLE_VISITOR ]; public function config() { if ( $this->getUser('permission', __CLASS__, __FUNCTION__) === true && $this->isPost() ) { $this->setData(['module', $this->getUrl(0), 'url', $this->getInput('redirectionConfigUrl', helper::FILTER_URL, true)]); $this->addOutput([ 'redirect' => helper::baseUrl() . $this->getUrl(), 'notification' => helper::translate('Modifications enregistrées'), 'state' => true ]); } $this->addOutput([ 'title' => helper::translate('Configuration du module'), 'view' => 'config' ]); } public function index() { if ( $this->isConnected() === true && $this->getUser('role') >= self::ROLE_EDITOR && $this->getUrl(1) !== 'force' ) { $this->addOutput([ 'display' => self::DISPLAY_LAYOUT_BLANK, 'title' => '', 'view' => 'index' ]); } else { $this->setData(['module', $this->getUrl(0), 'count', helper::filter($this->getData(['module', $this->getUrl(0), 'count']) + 1, helper::FILTER_INT)]); $this->addOutput([ 'redirect' => $this->getData(['module', $this->getUrl(0), 'url']), 'state' => true ]); } } }
