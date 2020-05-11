/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

/**
 * Modification de l'affichage de l'icône de langues
 */

$("input[name=configSmtpEnable]").on("change", function() {    
    if ($("input[name=configSmtpEnable]").is(':checked')) {
        $(".configSmtpParam").addClass("disabled");
        $(".configSmtpParam").slideDown();        
    } else {
        $(".configSmtpParam").removeClass("disabled");
        $(".configSmtpParam").slideUp();        
    }
});