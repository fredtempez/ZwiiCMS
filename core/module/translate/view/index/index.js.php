/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2022, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */
function setCookie(name,value,days){var expires="";if(days){var date=new Date;date.setTime(date.getTime()+24*days*60*60*1e3),expires="; expires="+date.toUTCString()}document.cookie=name+"="+(value||"")+expires+"; path=/; samesite=lax"}function getCookie(name){for(var nameEQ=name+"=",ca=document.cookie.split(";"),i=0;i<ca.length;i++){for(var c=ca[i];" "==c.charAt(0);)c=c.substring(1,c.length);if(0==c.indexOf(nameEQ))return c.substring(nameEQ.length,c.length)}return null}function capitalizeFirstLetter(string){return string.charAt(0).toUpperCase()+string.slice(1)}$(document).ready((function(){var translateLayout=getCookie("translateLayout");null==translateLayout&&(translateLayout="ui",setCookie("translateLayout","ui")),$("#contentContainer").hide(),$("#uiContainer").hide(),$("#"+translateLayout+"Container").show(),$("#translate"+capitalizeFirstLetter(translateLayout)+"Button").addClass("activeButton")})),$("#translateUiButton").on("click",(function(){$("#contentContainer").hide(),$("#uiContainer").show(),$(this).addClass("activeButton"),$("#translateContentButton").removeClass("activeButton"),setCookie("translateLayout","ui"),$("#translateButtonAddContent").hide(),$("#translateButtonCopyContent").hide()})),$("#translateContentButton").on("click",(function(){$("#uiContainer").hide(),$("#contentContainer").show(),$(this).addClass("activeButton"),$("#translateUiButton").removeClass("activeButton"),setCookie("translateLayout","content"),$("#translateButtonAddContent").show(),$("#translateButtonCopyContent").show()})),$(".translateDelete").on("click",(function(){var _this=$(this),message_delete="<?php echo helper::translate('Confirmer la suppression de cette traduction du site'); ?>";return core.confirm(message_delete,(function(){$(location).attr("href",_this.attr("href"))}))}));