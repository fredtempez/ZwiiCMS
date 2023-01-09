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
function setCookie(name,value,days){var expires="";if(days){var date=new Date;date.setTime(date.getTime()+24*days*60*60*1e3),expires="; expires="+date.toUTCString()}document.cookie=name+"="+(value||"")+expires+"; path=/; samesite=lax"}function getCookie(name){for(var nameEQ=name+"=",ca=document.cookie.split(";"),i=0;i<ca.length;i++){for(var c=ca[i];" "==c.charAt(0);)c=c.substring(1,c.length);if(0==c.indexOf(nameEQ))return c.substring(nameEQ.length,c.length)}return null}function capitalizeFirstLetter(string){return string.charAt(0).toUpperCase()+string.slice(1)}$(document).ready((function(){var pluginLayout=getCookie("pluginLayout");null==pluginLayout&&(pluginLayout="module",setCookie("pluginLayout","module")),console.log(pluginLayout),$("#moduleContainer").hide(),$("#dataContainer").hide(),$("#"+pluginLayout+"Container").show(),$("#plugin"+capitalizeFirstLetter(pluginLayout)+"Button").addClass("activeButton")})),$(".moduleDelete").on("click",(function(){var _this=$(this),message_delete="<?php echo helper::translate('Confirmer la désinstallation du module'); ?>";return core.confirm(message_delete,(function(){$(location).attr("href",_this.attr("href"))}))})),$(".dataDelete").on("click",(function(){var _this=$(this),message_unlink="<?php echo helper::translate('Confirmer la dissociation du module de cette page'); ?>";return core.confirm(message_unlink,(function(){$(location).attr("href",_this.attr("href"))}))})),$("#pluginModuleButton").on("click",(function(){$("#dataContainer").hide(),$("#moduleContainer").show(),$("#pluginModuleButton").addClass("activeButton"),$("#pluginDataButton").removeClass("activeButton"),setCookie("pluginLayout","module")})),$("#pluginDataButton").on("click",(function(){$("#moduleContainer").hide(),$("#dataContainer").show(),$("#pluginModuleButton").removeClass("activeButton"),$("#pluginDataButton").addClass("activeButton"),setCookie("pluginLayout","data")}));