<?php

/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

class sitemap extends common
{
    public static $actions = [
        'index' => self::GROUP_VISITOR
    ];

	public static $siteMap = '';

    /**
     * Plan du site
     */
    public function index()
    {
        $items = '<ul>';
        foreach ($this->getHierarchy(null, true, null) as $parentId => $childIds) {
            $items .= '<li class="pageIcon">';
            if ($this->getData(['page', $parentId, 'disable']) === false  && $this->getUser('group') >= $this->getData(['page', $parentId, 'group'])) {
                $items .= '<a href="' .helper::baseUrl() . $parentId .'">'  .$this->getData(['page', $parentId, 'title']) . '</a>';
               // $items .= '<';
            } else {
                // page désactivée
                $items .= $this->getData(['page', $parentId, 'title']);
            }
			
			// ou articles d'un blog
			if ($this->getData(['page', $parentId, 'moduleId']) === 'blog'  &&
			!empty($this->getData(['module',$parentId, 'posts' ]))) {
				$items .= '<ul>';									
				// Ids des articles par ordre de publication
				$articleIdsPublishedOns = helper::arrayCollumn($this->getData(['module', $parentId,'posts']), 'publishedOn', 'SORT_DESC');
				$articleIdsStates = helper::arrayCollumn($this->getData(['module', $parentId, 'posts']), 'state', 'SORT_DESC');
				$articleIds = [];
				foreach ($articleIdsPublishedOns as $articleId => $articlePublishedOn) {
					if ($articlePublishedOn <= time() and $articleIdsStates[$articleId]) {
						$articleIds[] = $articleId;
					}
				}
				foreach ($articleIds as $articleId => $article) {
					if ($this->getData(['module',$parentId,'posts',$article,'state']) === true) {
						$items .= '<li class="articleIcon">';
						$items .= '<a href="' . helper::baseUrl() . $parentId. '/' . $article . '">' . $this->getData(['module',$parentId,'posts',$article,'title']) . '</a>';
						$items .= '</li>';
					}
				}
				$items .= '</ul>';
			} 
            
            foreach ($childIds as $childId) {
				$items .= '<ul>';
                // Sous-page
				$items .= '<li class="pageIcon">';              
                if ($this->getData(['page', $childId, 'disable']) === false && $this->getUser('group') >= $this->getData(['page', $parentId, 'group'])) {
                    $items .= '<a href="' . helper::baseUrl() . $childId . '">' . $this->getData(['page', $childId, 'title']) . '</a>';
                } else {
                    // page désactivée
                    $items .=  $this->getData(['page', $childId, 'title']);
                }
				$items .= '</li>';


                // Articles d'une sous-page blog                
                if ($this->getData(['page', $childId, 'moduleId']) === 'blog'  &&
                            !empty($this->getData(['module', $childId, 'posts' ]))) {
                    $items .= '<ul>';
                    // Ids des articles par ordre de publication
                    $articleIdsPublishedOns = helper::arrayCollumn($this->getData(['module', $childId,'posts']), 'publishedOn', 'SORT_DESC');
                    $articleIdsStates = helper::arrayCollumn($this->getData(['module', $childId, 'posts']), 'state', 'SORT_DESC');
                    $articleIds = [];
                    foreach ($articleIdsPublishedOns as $articleId => $articlePublishedOn) {
                        if ($articlePublishedOn <= time() and $articleIdsStates[$articleId]) {
                            $articleIds[] = $articleId;
                        }
                    }
                    foreach ($articleIds as $articleId => $article) {
                        if ($this->getData(['module',$childId,'posts',$article,'state']) === true) {
                            $items .= '<li class="articleIcon">';
                            $items .=  '<a href="' . helper::baseUrl() . $childId . '/' . $article . '">' . $this->getData(['module',$childId,'posts',$article,'title']) . '</a>';
                            $items .= '</li>';
                        }
                    }
                    $items .= '</ul>';
                }
			$items .= '</li>';
			// Fin du grand bloc
			$items .= '</ul>';               
            }
        }
        
		self::$siteMap = $items;

        // Valeurs en sortie
        $this->addOutput([
            'title' => 'Plan du site',
            'view' => 'index'
        ]);
    }
}
