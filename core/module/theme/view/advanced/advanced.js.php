/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */
$("#themeAdvancedCss").on("change keydown keyup",(function(){$("#themePreview").remove(),$("<style>").attr("type","text/css").attr("id","themePreview").text($(this).val()).appendTo("head")})),$("#themeAdvancedReset").on("click",(function(){var _this=$(this);return core.confirm("Êtes-vous sûr de vouloir réinitialiser à son état d'origine la personnalisation avancée ?",(function(){$(location).attr("href",_this.attr("href"))}))}));