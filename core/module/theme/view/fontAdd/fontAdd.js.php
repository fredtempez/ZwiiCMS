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
    $('input[name=fontAddFontImported]').prop('checked', true);
    $('input[name=fontAddFontUrl]').prop('checked', false);
    $('#containerFontAddUrl').hide();
});


/**
 * Mode téléchargement en ligne de la fonte ou installation locale
 */
$("input[name=fontAddFontImported]").on("click", function() {
    if( $('input[name=fontAddFontImported]').is(':checked') ){
        $('input[name=fontAddFontFile]').prop('checked', false);
    } else {
        $('input[name=fontAddFontFile]').prop('checked', true);
    }
    $('#containerFontAddFile').hide();
    $('#containerFontAddUrl').show();
});

$("input[name=fontAddFontFile]").on("click", function() {
    if( $('input[name=fontAddFontFile]').is(':checked') ){
        $('input[name=fontAddFontImported]').prop('checked', false);
    } else {
        $('input[name=fontAddFontImported]').prop('checked', true);
    }
    $('#containerFontAddFile').show();
    $('#containerFontAddUrl').hide();
});
