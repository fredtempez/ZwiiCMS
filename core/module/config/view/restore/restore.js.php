/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

$( document).ready(function() {

    $('body').css('cursor', 'default');

    /**
     * Avertissement de restauration
     */    
     $("#configRestoreSubmit").click(function(event) {
        if( core.alert("Une restauration complète pourra prendre du temps, merci de patienter.") )
            event.preventDefault();
            $('body').css('cursor', 'wait');            
    });
    
});