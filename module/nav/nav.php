<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

class nav extends common
{


    const VERSION = '0.1';
    const REALNAME = 'Navigation';

    public static $actions = [
        'index' => self::GROUP_VISITOR,
        'config' => self::GROUP_EDITOR,
    ];

    public static $previousPage = '';
    public static $nextPage = '';

    public static $iconTemplate = [
        'open' => [
            'left' => 'left-open',
            'right' => 'right-open',
        ],
        'dir' => [
            'left' => 'left',
            'right' => 'right-dir',
        ],
        'big' => [
            'left' => 'left-big',
            'right' => 'right-big',
        ],
    ];

    public static $iconTemplateName = [
        'dir' => 'Triangle',
        'open' => 'Ouverte',
        'big' => 'Flèche',
    ];

    public static $leftButton = 'left';
    public static $rightButton = 'right-dir';

    public function index()
    {
        $hierarchy = array();
        foreach ($this->getHierarchy() as $parentKey => $parentValue) {
            $hierarchy[] = $parentKey;
            foreach ($parentValue as $childKey) {
                $hierarchy[] = $childKey;
            }
        }
        // Parcourir la hiérarchie et rechercher les éléments avant et après
        $elementToFind = $this->getUrl(0);

        // Trouver la clé de l'élément recherché
        $key = array_search($elementToFind, $hierarchy);

        if ($key !== false) {
            // Trouver l'élément précédent
            $previousKey = ($key > 0) ? $key - 1 : null;
            $previousValue = ($previousKey !== null) ? $hierarchy[$previousKey] : null;

            // Trouver l'élément suivant
            $nextKey = ($key < count($hierarchy) - 1) ? $key + 1 : null;
            $nextValue = ($nextKey !== null) ? $hierarchy[$nextKey] : null;

            self::$previousPage = $previousValue;
            self::$nextPage = $nextValue;
        }

        // Jeux d'icônes
        if ($this->getData(['module', $this->getUrl(0), 'iconTemplate'])) {
            self::$leftButton = self::$iconTemplate[$this->getData(['module', $this->getUrl(0), 'iconTemplate'])]['left'];
            self::$rightButton = self::$iconTemplate[$this->getData(['module', $this->getUrl(0), 'iconTemplate'])]['right'];
        }


        // Valeurs en sortie
        $this->addOutput([
            'showBarEditButton' => true,
            'showPageContent' => true,
            'view' => 'index',
        ]);
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
                    'iconTemplate' => $this->getInput('navConfigIconTemplate', null),
                ]
            ]);
            $this->addOutput([
                'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
                'notification' => 'Modifications enregistrées',
                'state' => true
            ]);
        }

        // Valeurs en sortie
        $this->addOutput([
            'title' => 'Configuration du module',
            'view' => 'config'
        ]);

    }

}