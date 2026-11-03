<?php
namespace Zwii\Cms; /**
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
class maintenance extends Core { public static $actions = [ 'index' => self::ROLE_VISITOR ]; public function index() { if ($this->getData(['config', 'maintenance']) == False) { header('Location:' . Helper::baseUrl()); exit(); } if ( $this->getData(['locale', 'page302']) !== 'none' and $this->getData(['page', $this->getData(['locale', 'page302'])]) ) { $this->addOutput([ 'display' => self::DISPLAY_LAYOUT_LIGHT, 'title' => $this->getData(['page', $this->getData(['locale', 'page302']), 'hideTitle']) ? '' : $this->getData(['page', $this->getData(['locale', 'page302']), 'title']), 'content' => $this->getPage($this->getData(['locale', 'page302']), self::$siteContent), 'view' => 'index' ]); } else { $this->addOutput([ 'display' => self::DISPLAY_LAYOUT_LIGHT, 'title' => Helper::translate('Maintenance en cours...'), 'view' => 'index' ]); } } }
