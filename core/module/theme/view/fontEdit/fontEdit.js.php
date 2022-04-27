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
    if( $('input[name=fontEditFontImported]').is(':checked') ){
        $('#containerfontEditFile').hide();
        $('#containerfontEditUrl').show();
    }


    if( $('input[name=fontEditFontFile]').is(':checked') ){
        $('#containerfontEditFile').show();
        $('#containerfontEditUrl').hide();
    }

});


/**
 * Mode téléchargement en ligne de la fonte ou installation locale
 */
 $("input, select").on("change", function() {

        if( $('input[name=fontEditFontImported]').is(':checked') ){
            $('input[name=fontEditFontFile]').prop('checked', false);
            $('#containerfontEditFile').hide();
            $('#containerfontEditUrl').show();
        } else {
            $('input[name=fontEditFontFile]').prop('checked', true);
        }


        if( $('input[name=fontEditFontFile]').is(':checked') ){
            $('input[name=fontEditFontImported]').prop('checked', false);
            $('#containerfontEditFile').show();
            $('#containerfontEditUrl').hide();
        } else {
            $('input[name=fontEditFontImported]').prop('checked', true);
        }

});
