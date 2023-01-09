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
$("#userEditGroup").on("change",(function(){$(".userEditGroupDescription").hide(),$("#userEditGroupDescription"+$(this).val()).show(),$("#userEditGroup option:selected").val()<0?$("#userEditLabelAuth").css("display","none"):$("#userEditLabelAuth").css("display","inline-block")})).trigger("change"),$(document).ready((function(){"1"===$("#userEditGroup").val()?$("#userEditMemberFiles").slideDown():$("#userEditMemberFiles").slideUp((function(){$("#userEditFiles").prop("checked",!1).trigger("change")}))})),$("#userEditGroup").on("change",(function(){"1"===$("#userEditGroup").val()?$("#userEditMemberFiles").slideDown():$("#userEditMemberFiles").slideUp((function(){$("#userEditFiles").prop("checked",!1).trigger("change")}))})).trigger("change");