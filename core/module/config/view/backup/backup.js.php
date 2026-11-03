/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */
$(document).ready((function(){$("#configBackupForm").submit((function(e){e.preventDefault();var url="<?php
echo Helper::baseUrl() . $this->getPageId(); ?>/backup",message_success="<?php
echo Helper::translate('Sauvegarde générée avec succès'); ?>",message_error="<?php
echo Helper::translate('Erreur : sauvegarde non générée !'); ?>",message_title="<?php
echo Helper::translate('Sauvegarder'); ?>";$.ajax({type:"POST",url:url,data:$("form").serialize(),success:function(data){$("body, .button").css("cursor","default"),core.alert(message_success)},error:function(data){$("body, .button").css("cursor","default"),core.alert(message_error)},complete:function(){$("#configBackupSubmit").removeClass("disabled").prop("disabled",!1),$("#configBackupSubmit").removeClass("uniqueSubmission").prop("uniqueSubmission",!1),$("#configBackupSubmit span").removeClass("zwiico-spin animate-spin"),$("#configBackupSubmit span").addClass("zwiico-check zwiico-margin-right").text(message_title)}})})),$("#configBackupSubmit").on("click",(function(){if($("input[name=configBackupOption]").is(":checked")){var message_warning="<?php
echo Helper::translate('La sauvegarde des fichiers peut prendre du temps. Continuer ?'); ?>";return core.confirm(message_warning,(function(){$("body, .button").css("cursor","wait"),$("form#configBackupForm").submit()}))}}))}));
