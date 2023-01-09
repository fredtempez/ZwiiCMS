/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2023, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
$("input[name=fontAddFontImported]").on("click",(function(){$("input[name=fontAddFontImported]").is(":checked")?$("input[name=fontAddFontFile]").prop("checked",!1):$("input[name=fontAddFontFile]").prop("checked",!0),$("#containerFontAddFile").hide(),$("#containerFontAddUrl").show()})),$("input[name=fontAddFontFile]").on("click",(function(){$("input[name=fontAddFontFile]").is(":checked")?$("input[name=fontAddFontImported]").prop("checked",!1):$("input[name=fontAddFontImported]").prop("checked",!0),$("#containerFontAddFile").show(),$("#containerFontAddUrl").hide()}));