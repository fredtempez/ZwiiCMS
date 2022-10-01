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
$(document).ready((function(){$("input[name=fontEditFontImported]").is(":checked")&&($("#containerfontEditFile").hide(),$("#containerfontEditUrl").show(),$("#fontEditFontFileWrapper").hide(),$("input[name=fontEditFontImported]").attr("disabled","disabled")),$("input[name=fontEditFontFile]").is(":checked")&&($("#containerfontEditFile").show(),$("#containerfontEditUrl").hide(),$("#fontEditFontImportedWrapper").hide(),$("input[name=fontEditFontFile]").attr("disabled","disabled"))}));