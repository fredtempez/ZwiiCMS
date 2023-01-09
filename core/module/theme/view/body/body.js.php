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
$(document).ready((function(){$("#backToTop").css("display","show")})),$("input, select").on("change",(function(){var themeBodyImageSize=$("#themeBodyImageSize").val();"cover"!==themeBodyImageSize&&"contain"!==themeBodyImageSize||$("#themeBodyImageAttachment").val("fixed");var css="html{background-color:"+$("#themeBodyBackgroundColor").val()+"}",themeBodyImage=$("#themeBodyImage").val();themeBodyImage?(css+="html{background-image:url('<?php echo helper::baseUrl(false); ?>site/file/source/"+themeBodyImage+"');background-repeat:"+$("#themeBodyImageRepeat").val()+";background-position:"+$("#themeBodyImagePosition").val()+";background-attachment:"+$("#themeBodyImageAttachment").val()+";background-size:"+$("#themeBodyImageSize").val()+"}",css+="html{background-color:rgba(0,0,0,0);}"):css+="html{background-image:none}",css+="#backToTop {background-color:"+$("#themeBodyToTopBackground").val()+";color:"+$("#themeBodyToTopColor").val()+";}",$("#themePreview").remove(),$("<style>").attr("type","text/css").attr("id","themePreview").text(css).appendTo("head")})),$("#themeBodyImage").on("change",(function(){$(this).val()?$("#themeBodyImageOptions").slideDown():$("#themeBodyImageOptions").slideUp()})).trigger("change");