/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */


/** 
 * Option par défaut du sélecteur de mode
 */
 $(document).ready(function(){
    $('input[name=fontEditFontImported]').prop('checked', true);
    $('input[name=fontEditFontUrl]').prop('checked', false);
    $('#containerfontEditFile').hide();
});


/**
 * Mode téléchargement en ligne de la fonte ou installation locale
 */
$("input[name=fontEditFontImported]").on("click", function() {
    if( $('input[name=fontEditFontImported]').is(':checked') ){
        $('input[name=fontEditFontFile]').prop('checked', false);
    } else {
        $('input[name=fontEditFontFile]').prop('checked', true);
    }
    $('#containerfontEditFile').hide();
    $('#containerfontEditUrl').show();
});

$("input[name=fontEditFontFile]").on("click", function() {
    if( $('input[name=fontEditFontFile]').is(':checked') ){
        $('input[name=fontEditFontImported]').prop('checked', false);
    } else {
        $('input[name=fontEditFontImported]').prop('checked', true);
    }
    $('#containerfontEditFile').show();
    $('#containerfontEditUrl').hide();
});
