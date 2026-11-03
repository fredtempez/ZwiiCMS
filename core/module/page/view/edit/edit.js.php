/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @authorFrédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.fr/
 */

/**
 * Bouton « Édition en plein écran » / « Quitter le plein écran » :
 * l'affichage change en rechargeant la page, donc on demande confirmation
 * si des modifications n'ont pas été enregistrées.
 */
var pageEditDirty = false;
$("#pageEditForm").on("input change", function() {
	pageEditDirty = true;
});
$("#pageEditFullScreen").on("click", function(event) {
	var _this = $(this);
	var dirty = pageEditDirty;
	if (!dirty && window.tinymce && tinymce.editors) {
		for (var i = 0; i < tinymce.editors.length; i++) {
			if (tinymce.editors[i].isDirty && tinymce.editors[i].isDirty()) {
				dirty = true;
				break;
			}
		}
	}
	if (!dirty) {
		return true; // pas de modification : navigation normale
	}
	event.preventDefault();
	core.confirm(<?php
echo json_encode(Helper::translate('Les modifications non enregistrées seront perdues. Changer d\'affichage ?')); ?>, function() {
		$(location).attr("href", _this.attr("href"));
	});
	return false;
});

/**
 * Confirmation de suppression
*/
$("#pageEditDelete").on("click", function() {
	var _this = $(this);
	var message_delete = "<?php
echo Helper::translate('Confirmer la suppression de la page'); ?>";
	return core.confirm(message_delete, function() {
		$(location).attr("href", _this.attr("href"));
	});
});

/**
 * MULTI-MODULE : gestion de la liste des modules de la page.
 * Trois zones :
 *  - haut  : un seul module (position implicite "top"),   input pageModulesTopModuleId
 *  - milieu: modules multiples (position implicite "free"), lignes pageModulesFreeModuleId<row>
 *  - bas   : un seul module (position implicite "bottom"), input pageModulesBottomModuleId
 * Chaque ligne de la zone centrale porte : pageModulesFreeModuleId<row>,
 * pageModulesFreeInstanceId<row> (id existant) et pageModulesFreeModuleIdOld<row>
 * (module d'origine, pour l'avertissement en cas de changement).
 * Le champ caché pageModulesLevel[] reçoit l'ordre courant des lignes du milieu au submit.
 */
var PAGE_MODULE_LIST_ID = "#pageModulesListContainer";

// Page hôte (id) : scope des clés d'instance des zones fixes et libres
// (`moduleId-<pageId>-<zone>`), afin qu'elles soient UNIQUES PAR PAGE. Deux pages
// peuvent ainsi héberger le même module dans la même zone avec des données
// distinctes. Doit rester identique au calcul serveur (page::buildInstanceKey).
var PAGE_INSTANCE_SCOPE = <?php
echo json_encode((string) $this->getUrl(2)); ?>;

// Noms réels des modules (id -> libellé affiché). Le libellé servi par le
// serveur est désormais le nom réel seul (page::$moduleIds ne contient plus le
// suffixe "(id)"). Le nettoyage résiduel reste sans effet mais garantit la
// compatibilité si un libellé contenait encore "(…)".
// Injecté depuis page::$moduleIds afin d'éviter un second rendu serveur.
var MODULE_NAMES = <?php
$moduleNames = []; foreach (page::$moduleIds as $moduleNameId => $moduleNameLabel) { if ($moduleNameId === '') { continue; } $moduleNames[$moduleNameId] = trim(preg_replace('/\s*\([^)]*\)\s*$/', '', $moduleNameLabel)); } echo json_encode($moduleNames); ?>;

// Identifiants des modules autorisant PLUSIEURS instances sur une même page.
// Règle calculée côté serveur (Core::isMultiInstanceAllowed) : MULTIMODULE = true,
// sauf si SINGLEINSTANCE = true ramène le module à une seule instance. Pour tous
// les autres modules (mono-instance), un module déjà sélectionné dans une autre
// zone est désactivé afin d'éviter un doublon non rendu par le routeur.
var MULTIMODULE_IDS = <?php
echo json_encode(page::$multiModuleIds); ?>;

// --------------------------------------------------------------------------
// SHORTCODES DE MODULES (zone libre) : le code [MODULE:<instanceId>] permet de
// positionner un module à un endroit précis du contenu. L'instanceId est
// pré-généré côté client (voir buildInstanceId) :
//   - zones fixes (top/bottom) : `moduleId-<zone>` (ex. `gallery-top`) ;
//   - zone libre (free)        : `moduleId-free-<uniq>`, où `<uniq>` est un
//     JETON UNIQUE, y compris pour la PREMIÈRE instance (ex. `gallery-free-k3f9`).
// Le client FAIT AUTORITÉ : la clé est transmise au serveur (champ caché
// pageModulesFreeInstanceId<row>) qui la réutilise telle quelle. L'id d'une
// instance ne dépend donc JAMAIS des autres instances (ni de leur ordre, ni de
// leur nombre), ce qui rend le shortcode stable et évite les collisions de
// préfixe lors de la substitution dans le contenu de la page.
// --------------------------------------------------------------------------

// Lignes de la zone centrale (source de vérité pour le panneau d'aide)
function pageModuleRows() {
	return $(PAGE_MODULE_LIST_ID + " .pageModuleRow");
}

// Récupère l'input caché portant l'instanceId d'une ligne
function pageModuleInstanceInput($row) {
	return $row.find("input[id^='pageModulesFreeInstanceId']");
}

// Génère un JETON UNIQUE court, sans tiret (pour ne pas interférer avec la
// détection de zone par suffixe). Le jeton est utilisé pour la zone libre :
// chaque instance — y compris la première — reçoit `moduleId-free-<uniq>`.
// Base36 sur 4 caractères : espace suffisant pour rendre toute collision
// négligeable, sans dépendre de l'état des autres instances de la page.
function uniqueInstanceToken() {
	return Math.random().toString(36).slice(2, 6);
}

// Génère une clé d'instance :
//   - zones fixes (top/bottom) : `moduleId-<pageId>-<zone>` (une seule instance) ;
//   - zone libre (free)        : `moduleId-<pageId>-free-<uniq>` (jeton unique,
//     y compris pour la première instance).
// Le préfixe `<pageId>` rend la clé UNIQUE PAR PAGE : deux pages plaçant le même
// module dans la même zone fixe n'ont plus la même clé, donc plus les mêmes
// données ni le même dossier. Le suffixe de zone (`-top` / `-bottom`) est conservé
// pour que getInstancePosition() en déduise la zone. Aucune collecte des autres
// instances n'est nécessaire : l'id ne dépend donc jamais de l'ordre ni du nombre
// d'instances déjà présentes.
function buildInstanceId(moduleId, zone) {
	if (!moduleId) { return ""; }
	zone = zone || "free";
	var pageKey = (typeof PAGE_INSTANCE_SCOPE !== "undefined") ? PAGE_INSTANCE_SCOPE : "";
	var base = pageKey ? (moduleId + "-" + pageKey) : moduleId;
	if (zone !== "free") { return base + "-" + zone; }
	return base + "-free-" + uniqueInstanceToken();
}

// Libellé réel d'un module à partir de la map MODULE_NAMES
function pageModuleRealName(moduleId) {
	if (!moduleId || typeof MODULE_NAMES === "undefined") { return ""; }
	return MODULE_NAMES[moduleId] || moduleId;
}

// (Re)construit le panneau d'aide des shortcodes depuis les lignes existantes.
// Le panneau n'affiche que les lignes ayant un module ET un instanceId.
// Les boutons « Insérer » / « Copier » sont clonés depuis des modèles rendus par
// Template::button (#pageModuleShortcutInsertTpl / #pageModuleShortcutCopyTpl),
// afin de réutiliser le style .button du thème (un <button> brut serait trop grand).
function refreshPageModuleShortcuts() {
	var $list = $("#pageModuleShortcutList");
	if (!$list.length) { return; }
	$list.empty();
	var count = 0;

	// Modèles de boutons (Template::button), clonés pour chaque entrée
	var $insertTpl = $("#pageModuleShortcutInsertTpl");
	var $copyTpl = $("#pageModuleShortcutCopyTpl");
	var $configTpl = $("#pageModuleShortcutConfigTpl");

	pageModuleRows().each(function () {
		var $row = $(this);
		var moduleId = $row.find("select.pageModuleSelect").val();
		if (!moduleId) { return; }
		var instanceId = pageModuleInstanceInput($row).val();
		if (!instanceId) { return; }
		count++;

		var code = "[MODULE:" + instanceId + "]";
		var name = pageModuleRealName(moduleId);
		var rowKey = $row.attr("data-row");

		var $item = $("<div>").addClass("pageModuleShortcut").attr("data-instance", instanceId);
		$("<span>").addClass("pageModuleShortcutName").text(name).appendTo($item);
		$("<code>").addClass("pageModuleShortcutCode").text(code).appendTo($item);

		// Bouton « Insérer » : clone du modèle Template::button (id retiré pour
		// éviter les doublons ; la classe .pageModuleShortcutInsert est conservée
		// et déjà portée par le modèle).
		if ($insertTpl.length) {
			$insertTpl.clone()
				.removeAttr("id")
				.attr("data-code", code)
				.appendTo($item);
		}
		// Bouton « Copier » : clone du modèle Template::button
		if ($copyTpl.length) {
			$copyTpl.clone()
				.removeAttr("id")
				.attr("data-code", code)
				.appendTo($item);
		}
		// Bouton « Configurer » : clone du modèle Template::button. Porte la ligne
		// source (data-row) pour déclencher le même flux que le bouton engrenage
		// de l'onglet Extension (enregistrement puis ouverture de la config).
		if ($configTpl.length) {
			$configTpl.clone()
				.removeAttr("id")
				.attr("data-code", code)
				.attr("data-row", rowKey)
				.attr("data-instance", instanceId)
				.appendTo($item);
		}

		$item.appendTo($list);
	});

	// Masque le panneau entier s'il n'y a aucun module libre à insérer
	$("#pageModuleShortcutHelp").toggle(count > 0);
	// Affiche le message d'aide interne si aucune entrée (théorique)
	$("#pageModuleShortcutEmpty").toggle(count === 0);
}

// Copie dans le presse-papiers avec repli pour les navigateurs sans API clipboard
function copyToClipboard(text, callback) {
	if (navigator.clipboard && navigator.clipboard.writeText) {
		navigator.clipboard.writeText(text).then(callback, function () {
			fallbackCopyToClipboard(text, callback);
		});
	} else {
		fallbackCopyToClipboard(text, callback);
	}
}

function fallbackCopyToClipboard(text, callback) {
	var $tmp = $("<textarea>").val(text)
		.css({ position: "absolute", left: "-9999px" })
		.appendTo("body");
	$tmp[0].select();
	try {
		document.execCommand("copy");
		if (callback) { callback(); }
	} catch (err) { /* ignoré */ }
	$tmp.remove();
}

// Génère un index de ligne unique (évite les collisions après suppressions)
function nextPageModuleRowKey() {
	var maxKey = -1;
	$(PAGE_MODULE_LIST_ID + " .pageModuleRow").each(function () {
		var k = parseInt($(this).attr("data-row"), 10);
		if (!isNaN(k) && k > maxKey) { maxKey = k; }
	});
	return maxKey + 1;
}

// Sérialise l'ordre courant des lignes du milieu dans #pageModulesListInputs
// sous la forme d'inputs pageModulesLevel[] (tableau lu côté PHP par getInput()).
function serializePageModules() {
	var $container = $("#pageModulesListInputs");
	$container.empty();
	$(PAGE_MODULE_LIST_ID + " .pageModuleRow").each(function () {
		$("<input>").attr({
			type: "hidden",
			name: "pageModulesLevel[]",
			value: $(this).attr("data-row")
		}).appendTo($container);
	});
}

// Avertissement lorsqu'on change le module d'une ligne déjà enregistrée.
// $oldInputs / $newSelect : sélecteurs des champs caché "ancien" et de la liste courante.
function protectPageModuleRow($row, $oldInput, $newSelect) {
	var oldModule = $oldInput.val();
	var newModule = $newSelect.val();
	if (oldModule !== "" && oldModule !== undefined && oldModule !== newModule) {
		var message_delete = "<?php
echo Helper::translate('Confirmer la suppression des données de ce module'); ?>";
		core.confirm(message_delete, function () {
			return true;
		}, function () {
			$newSelect.val(oldModule);
			return false;
		});
	}
}

// Active/désactive le bouton config selon la sélection du module.
function updatePageModuleConfigState($select, $button) {
	var moduleSelected = ($select.val() || "") !== "";
	$button.toggleClass("disabled", !moduleSelected);
}

// Met à jour le title (help) des boutons d'une ligne en y ajoutant le code court
// de l'instance (JETON final, ex. `blog-accueil-free-k3f9` -> `k3f9`).
// Ciblage par CLASSE : .pageModuleConfig (engrenage) et .pageModuleDelete (corbeille).
// Code vide -> libellé générique (sans suffixe).
function updatePageModuleRowTitles($scope, instanceId) {
	// Ne conserve que le code à 4 caractères (dernier segment après le dernier tiret)
	var code = (instanceId || "").split("-").pop();
	var suffix = code ? " " + code : "";
	$scope.find(".pageModuleConfig").attr("title", "Configurer le module" + suffix);
	$scope.find(".pageModuleDelete").attr("title", "Effacer le module" + suffix);
}

// Initialise l'état des boutons config pour toutes les lignes du milieu
function refreshPageModuleRowStates() {
	$(PAGE_MODULE_LIST_ID + " .pageModuleRow").each(function () {
		var $row = $(this);
		updatePageModuleConfigState($row.find("select.pageModuleSelect"), $row.find(".pageModuleConfig"));
	});
}

// Indique si un module autorise plusieurs instances sur la page.
// Source de vérité serveur : MULTIMODULE = true, sauf SINGLEINSTANCE = true.
function isMultiModule(moduleId) {
	return MULTIMODULE_IDS.indexOf(moduleId) !== -1;
}

// Tous les sélecteurs de modules de la page (zones fixes ET zone centrale).
function allModuleSelects() {
	return $("select.pageModuleSelect, select.pageModuleFixedSelect");
}

// Recalcule la disponibilité des options de tous les sélecteurs de modules.
// Un module MONO-INSTANCE (MULTIMODULE absent/false, ou SINGLEINSTANCE = true)
// déjà sélectionné dans un autre sélecteur voit son option désactivée : cela
// empêche de créer un doublon qui ne serait pas rendu par le routeur. Les modules
// multi-instance restent toujours disponibles.
function refreshModuleSelectAvailability() {
	// Compte les sélections de chaque module dans tous les sélecteurs.
	var counts = {};
	allModuleSelects().each(function () {
		var v = $(this).val();
		if (v) { counts[v] = (counts[v] || 0) + 1; }
	});
	// Parcourt chaque sélecteur et désactive les options mono-instance déjà prises ailleurs.
	allModuleSelects().each(function () {
		var $select = $(this);
		var own = $select.val();
		$select.find("option").each(function () {
			var v = $(this).val();
			// Option vide ("Aucun") ou module multi-instance : toujours disponible.
			if (!v || isMultiModule(v)) {
				$(this).prop("disabled", false);
				return;
			}
			// Mono-instance : désactivée si le module est déjà choisi ailleurs.
			// L'option actuellement sélectionnée dans CE sélecteur n'est jamais désactivée.
			var takenElsewhere = (counts[v] || 0) - (v === own ? 1 : 0) > 0;
			$(this).prop("disabled", takenElsewhere && v !== own);
		});
	});
}

// Changement du module dans une zone fixe (haut / bas)
$(document).on("change", ".pageModuleFixedSelect", function () {
	var $select = $(this);
	var $zone = $select.closest(".pageModuleFixedZone");
	protectPageModuleRow($zone, $zone.find("input[id^='pageModulesTopModuleIdOld'], input[id^='pageModulesBottomModuleIdOld']"), $select);
	updatePageModuleConfigState($select, $zone.find(".pageModuleFixedConfig"));
	// Met à jour la disponibilité des options (exclusion du doublon mono-instance)
	refreshModuleSelectAvailability();
});

// Changement de module sur une ligne du milieu : avertissement si instance existante,
// puis mise à jour de l'état du bouton de configuration.
// Délégation sur la classe .pageModuleSelect ; on ignore les selects des zones
// fixes (qui portent aussi la classe pageModuleSelect) via la présence d'une ligne.
$(document).on("change", "select.pageModuleSelect", function () {
	var $row = $(this).closest(".pageModuleRow");
	if (!$row.length) { return; }
	protectPageModuleRow($row, $row.find("input[id^='pageModulesFreeModuleIdOld']"), $row.find("select[id^='pageModulesFreeModuleId']"));

	// Instances : (re)génère l'instanceId quand le module change.
	// L'instanceId est la clé qui servira au shortcode [MODULE:<instanceId>].
	var moduleId = $(this).val();
	var $instanceInput = pageModuleInstanceInput($row);
	var oldModule = $row.find("input[id^='pageModulesFreeModuleIdOld']").val();

	if (!moduleId) {
		// Aucun module : pas d'instance
		$instanceInput.val("");
		$row.attr("data-instance", "");
	} else if (moduleId !== oldModule || !$instanceInput.val()) {
		// Nouveau module sur la ligne : nouvelle clé d'instance (zone "free")
		var newInstanceId = buildInstanceId(moduleId, "free");
		$instanceInput.val(newInstanceId);
		$row.attr("data-instance", newInstanceId);
		// Aligne la référence « ancien module » pour ne pas re-déclencher l'avertissement
		$row.find("input[id^='pageModulesFreeModuleIdOld']").val(moduleId);
	}

	updatePageModuleConfigState($row.find("select.pageModuleSelect"), $row.find(".pageModuleConfig"));
	// Reflète l'instance courante dans les titres des boutons de la ligne
	updatePageModuleRowTitles($row, $instanceInput.val());
	// Met à jour la disponibilité des options (exclusion du doublon mono-instance)
	refreshModuleSelectAvailability();
	// Rafraîchit le panneau d'aide (nom + code de la ligne)
	refreshPageModuleShortcuts();
});

// Suppression d'une ligne du milieu
$(document).on("click", PAGE_MODULE_LIST_ID + " .pageModuleDelete", function (e) {
	e.preventDefault();
	var $row = $(this).closest(".pageModuleRow");
	$row.slideUp(150, function () {
		$(this).remove();
		// Retire l'entrée correspondante du panneau d'aide
		refreshPageModuleShortcuts();
		// Le module libéré redevient disponible dans les autres sélecteurs
		refreshModuleSelectAvailability();
	});
	return false;
});

// Configuration d'un module d'une ligne du milieu : enregistre la page puis ouvre la config.
$(document).on("click", PAGE_MODULE_LIST_ID + " .pageModuleConfig", function (e) {
	e.preventDefault();
	var $row = $(this).closest(".pageModuleRow");
	$("#pageModuleConfigRow").val($row.attr("data-row"));
	$("#pageEditModuleRedirect").val(1);
	$("#pageEditForm").trigger("submit");
	return false;
});

// Configuration d'un module d'une zone fixe (haut / bas) : la ligne est identifiée
// par la clé spéciale "top" ou "bottom".
$(document).on("click", ".pageModuleFixedConfig", function (e) {
	e.preventDefault();
	var $zone = $(this).closest(".pageModuleFixedZone");
	var rowKey = $zone.find("input[id^='pageModulesTopModuleIdOld']").length ? "top" : "bottom";
	$("#pageModuleConfigRow").val(rowKey);
	$("#pageEditModuleRedirect").val(1);
	$("#pageEditForm").trigger("submit");
	return false;
});

// Ajout d'une ligne à partir du template
$(document).on("click", "#pageModuleAdd", function (e) {
	e.preventDefault();
	var template = $("#pageModuleTemplate").html();
	if (!template) { return false; }
	var rowKey = nextPageModuleRowKey();
	var html = template.replace(/__ROW__/g, rowKey);
	var $new = $(html);
	$(PAGE_MODULE_LIST_ID).append($new);
	refreshPageModuleRowStates();
	// Nouvelle ligne sans module : titres génériques (instance pas encore générée)
	updatePageModuleRowTitles($new, "");
	refreshModuleSelectAvailability();
	refreshPageModuleShortcuts();
	$new.find("select[id^='pageModulesFreeModuleId']").first().trigger("focus");
	return false;
});

// Copie du shortcode d'un module dans le presse-papiers
$(document).on("click", ".pageModuleShortcutCopy", function (e) {
	e.preventDefault();
	var code = $(this).attr("data-code") || "";
	var $button = $(this);
	var message = "<?php
echo Helper::translate('Copié'); ?>";
	copyToClipboard(code, function () {
		var original = $button.text();
		$button.text(message);
		setTimeout(function () { $button.text(original); }, 1500);
	});
	return false;
});

// Configuration d'un module depuis le panneau des shortcodes (onglet Contenu).
// Même flux que le bouton engrenage de l'onglet Extension : enregistre la page
// puis ouvre la configuration du module sélectionné (identifié par data-row).
$(document).on("click", ".pageModuleShortcutConfig", function (e) {
	e.preventDefault();
	var rowKey = $(this).attr("data-row");
	if (rowKey === undefined || rowKey === null || rowKey === "") { return false; }
	$("#pageModuleConfigRow").val(rowKey);
	$("#pageEditModuleRedirect").val(1);
	$("#pageEditForm").trigger("submit");
	return false;
});

// Insertion du shortcode au curseur dans l'éditeur de contenu de la page
$(document).on("click", ".pageModuleShortcutInsert", function (e) {
	e.preventDefault();
	var code = $(this).attr("data-code") || "";
	insertPageModuleShortcut(code);
	return false;
});

// Insère du code dans l'éditeur de contenu de la page.
// Utilise TinyMCE si l'éditeur est initialisé (insertion au curseur),
// sinon insère dans le textarea à la position du curseur.
function insertPageModuleShortcut(code) {
	var editorId = "pageEditContent";

	// Cas TinyMCE : insertion au niveau du curseur
	if (typeof tinymce !== "undefined" && tinymce.get(editorId)) {
		var editor = tinymce.get(editorId);
		editor.focus();
		editor.execCommand("mceInsertContent", false, code);
		// Place le curseur juste après le code inséré
		editor.selection.collapse(false);
		return true;
	}

	// Repli : insertion directe dans le textarea à la position du curseur
	var el = document.getElementById(editorId);
	if (el) {
		var start = el.selectionStart != null ? el.selectionStart : el.value.length;
		var end = el.selectionEnd != null ? el.selectionEnd : el.value.length;
		var before = el.value.substring(0, start);
		var after = el.value.substring(end);
		el.value = before + code + after;
		var caret = start + code.length;
		el.selectionStart = caret;
		el.selectionEnd = caret;
		el.focus();
		// Notifie jQuery d'un changement de valeur
		$(el).trigger("change");
		return true;
	}

	core.alert("<?php
echo Helper::translate('Éditeur de contenu introuvable.'); ?>");
	return false;
}


/**
 * Initialisation de la variable pageLayout globale
 */
var pageLayout = "<?php
echo $this->getData(['user', $this->getUser('id'), 'view', 'page']);?>";

// Définir une valeur par défaut si pageLayout n'est pas défini
if (!pageLayout || pageLayout === "") {
    pageLayout = "content";
}

/**
 * Paramètres par défaut au chargement
 */
$( document ).ready(function() {
    // Changement de profil
    $(".pageEditGroupProfil").hide();
    $("#pageEditGroupProfil" + $("#pageEditRole").val()).show();

	$("#pageEditRole").on("change", function () {
		$(".pageEditGroupProfil").hide();
		$("#pageEditGroupProfil" + $(this).val()).show();
	});

	/**
	 * Sélection des onglets
	 */
	// Tout cacher
	$("#pageEditContentContainer").hide();
	$("#pageEditExtensionContainer").hide();
	$("#pageEditPositionContainer").hide();
	$("#pageEditLayoutContainer").hide();
	$("#pageEditPermissionContainer").hide();
	// Afficher la bonne tab
	$("#pageEdit" + capitalizeFirstLetter(pageLayout) + "Container").show();
	$("#pageEdit" + capitalizeFirstLetter(pageLayout) + "Button").addClass("activeButton");

	/*
	* Enleve le menu fixe en édition de page
	*/
	$("nav").removeAttr('id');

	/**
	* MULTI-MODULE : liste ordonnable des modules de la page (SortableJS)
	*/
	if (document.getElementById("pageModulesListContainer") && typeof Sortable !== "undefined") {
		Sortable.create(document.getElementById("pageModulesListContainer"), {
			handle: ".pageModuleHandle",
			animation: 150,
			// Réordonne le panneau d'aide pour refléter l'ordre des lignes
			onEnd: function () {
				refreshPageModuleShortcuts();
			}
		});
	}
	// Initialise l'état des boutons config selon le module sélectionné (lignes + zones fixes)
	refreshPageModuleRowStates();
	$(".pageModuleFixedZone").each(function () {
		var $zone = $(this);
		updatePageModuleConfigState($zone.find("select.pageModuleFixedSelect"), $zone.find(".pageModuleFixedConfig"));
	});
	// Désactive les modules mono-instance déjà sélectionnés (exclusion du doublon)
	refreshModuleSelectAvailability();
	// Construit le panneau d'aide des shortcodes à partir des lignes existantes
	refreshPageModuleShortcuts();

	/**
	* Masquer et démasquer la sélection des barres
	*/
	switch ($("#pageEditBlock").val()) {
		case "bar":
		case "12":
			$("#pageEditBarLeftWrapper").removeClass("disabled");
			$("#pageEditBarLeftWrapper").slideUp();
			$("#pageEditBarRightWrapper").removeClass("disabled");
			$("#pageEditBarRightWrapper").slideUp();
			break;
		case "3-9":
		case "4-8":
			$("#pageEditBarLeftWrapper").addClass("disabled");
			$("#pageEditBarLeftWrapper").slideDown();
			$("#pageEditBarRightWrapper").removeClass("disabled");
			$("#pageEditBarRightWrapper").slideUp();
			break;
		case "9-3":
		case "8-4":
			$("#pageEditBarLeftWrapper").removeClass("disabled");
			$("#pageEditBarLeftWrapper").slideUp();
			$("#pageEditBarRightWrapper").addClass("disabled");
			$("#pageEditBarRightWrapper").slideDown();
			break;
		case "3-6-3":
		case "2-7-3":
		case "3-7-2":
			$("#pageEditBarLeftWrapper").addClass("disabled");
			$("#pageEditBarLeftWrapper").slideDown();
			$("#pageEditBarRightWrapper").addClass("disabled");
			$("#pageEditBarRightWrapper").slideDown();
			break;
	};
	if ($("#pageEditBlock").val() === "bar") {
			$("#pageEditMenu").removeClass("disabled");
			$("#pageEditMenu").hide();
			$("#pageEditHideTitleWrapper").removeClass("disabled");
			$("#pageEditHideTitleWrapper").slideUp();
			$("#pageEditbreadCrumbWrapper").removeClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideUp();
			$("#pageEditDisplayMenuWrapper").addClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideDown();
			$("#pageTypeMenuWrapper").removeClass("disabled");
			$("#pageTypeMenuWrapper").slideUp();
			$("#pageEditSeoWrapper").removeClass("disabled");
			$("#pageEditSeoWrapper").slideUp();
			$("#pageEditAdvancedWrapper").removeClass("disabled");
			$("#pageEditAdvancedWrapper").slideUp();
			$(".navSelect").slideUp();

	} else {
			$("#pageEditDisplayMenuWrapper").removeClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideUp();
	}

	/**
	* Masquer ou afficher le chemin de fer
	* Quand le titre est masqué
	*/
	if ($("input[name=pageEditHideTitle]").is(':checked') ||
		  $("#pageEditParentPageId").val() === "" )  {

			$("#pageEditbreadCrumbWrapper").removeClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideUp();
	} else {
		if ($("#pageEditParentPageId").val() !== "") {
			$("#pageEditbreadCrumbWrapper").addClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideDown();
		}
	}

	/**
	* Masquer ou afficher la sélection de l'icône
	*/
	if ($("#pageTypeMenu").val() !== "text") {
		$("#pageIconUrlWrapper").addClass("disabled");
		$("#pageIconUrlWrapper").slideDown();
	} else {
		$("#pageIconUrlWrapper").removeClass("disabled");
		$("#pageIconUrlWrapper").slideUp();
	}

	/**
	* Cache les options de masquage dans les menus quand la page n'est pas affichée.
	*/
	if ($("#pageEditPosition").val() === "0" ) {
			$("#pageEditHideMenuSideWrapper").removeClass("disabled");
			$("#pageEditHideMenuSideWrapper").slideUp();
	} else {
			$("#pageEditHideMenuSideWrapper").addClass("disabled");
			$("#pageEditHideMenuSideWrapper").slideDown();
	}

	/**
	* Cache l'option de masquage des pages enfants
	*/
	if ($("#pageEditParentPageId").val() !== "") {
		  $("#pageEditHideMenuChildrenWrapper").removeClass("disabled");
			$("#pageEditHideMenuChildrenWrapper").slideUp();
		}	else {
			$("#pageEditHideMenuChildrenWrapper").addClass("disabled");
			$("#pageEditHideMenuChildrenWrapper").slideDown();
		}

	/**
	 * Cache le l'option "ne pas afficher les pages enfants dans le menu horizontal" lorsque la page est désactivée
	 */
	if ($("#pageEditDisable").is(':checked') ) {
		$("#pageEditHideMenuChildrenWrapper").removeClass("disabled");
		$("#pageEditHideMenuChildrenWrapper").slideUp();
	} else {
		$("#pageEditHideMenuChildrenWrapper").addClass("disabled");
		$("#pageEditHideMenuChildrenWrapper").slideDown();
	}

	/**
	 * Liste des pages pour le menu accessoire
	 */
		if ($("#pageEditExtraPosition").val() == 1 ) {
		var positionDOM = $("#pageEditPosition");
		var positionInitial = <?php
echo $this->getData(['page',$this->getUrl(2),"position"]); ?>;
		buildPagesList(true);
		$("#pageEditPosition").val(positionInitial);
	}


});


 	// Gestion des évènements
	//--------------------------------------------------------------------------------------

	/**
     * Transmet le bouton de l'onglet sélectionné avant la soumission
     */
		$('#pageEditForm').on('submit', function () {
			$('#containerSelected').val(pageLayout);
			// Synchronise l'ordre des modules avant envoi
			serializePageModules();
		});

	/**
     *  Sélection de la  page de configuration à afficher
     */
		 $("#pageEditContentButton").on("click", function () {
			pageLayout = "content";
			$("#pageEditContentContainer").show();
			$("#pageEditExtensionContainer").hide();
			$("#pageEditPositionContainer").hide();
			$("#pageEditLayoutContainer").hide();
			$("#pageEditPermissionContainer").hide();
			$("#pageEditContentButton").addClass("activeButton");
			$("#pageEditExtensionButton").removeClass("activeButton");
			$("#pageEditPositionButton").removeClass("activeButton");
			$("#pageEditLayoutButton").removeClass("activeButton");
			$("#pageEditPermissionButton").removeClass("activeButton");
		});
		$("#pageEditPositionButton").on("click", function () {
			pageLayout = "position";
			$("#pageEditContentContainer").hide();
			$("#pageEditExtensionContainer").hide();
			$("#pageEditPositionContainer").show();
			$("#pageEditLayoutContainer").hide();
			$("#pageEditPermissionContainer").hide();
			$("#pageEditContentButton").removeClass("activeButton");
			$("#pageEditExtensionButton").removeClass("activeButton");
			$("#pageEditPositionButton").addClass("activeButton");
			$("#pageEditLayoutButton").removeClass("activeButton");
			$("#pageEditPermissionButton").removeClass("activeButton");
		});
		$("#pageEditExtensionButton").on("click", function () {
			pageLayout = "extension";
			$("#pageEditContentContainer").hide();
			$("#pageEditExtensionContainer").show();
			$("#pageEditPositionContainer").hide();
			$("#pageEditLayoutContainer").hide();
			$("#pageEditPermissionContainer").hide();
			$("#pageEditContentButton").removeClass("activeButton");
			$("#pageEditExtensionButton").addClass("activeButton");
			$("#pageEditPositionButton").removeClass("activeButton");
			$("#pageEditLayoutButton").removeClass("activeButton");
			$("#pageEditPermissionButton").removeClass("activeButton");
		});
		$("#pageEditLayoutButton").on("click", function () {
			pageLayout = "layout";
			$("#pageEditContentContainer").hide();
			$("#pageEditExtensionContainer").hide();
			$("#pageEditPositionContainer").hide();
			$("#pageEditLayoutContainer").show();
			$("#pageEditPermissionContainer").hide();
			$("#pageEditContentButton").removeClass("activeButton");
			$("#pageEditExtensionButton").removeClass("activeButton");
			$("#pageEditPositionButton").removeClass("activeButton");
			$("#pageEditLayoutButton").addClass("activeButton");
			$("#pageEditPermissionButton").removeClass("activeButton");
		});
		$("#pageEditPermissionButton").on("click", function () {
			pageLayout = "permission";
			$("#pageEditContentContainer").hide();
			$("#pageEditExtensionContainer").hide();
			$("#pageEditPositionContainer").hide();
			$("#pageEditLayoutContainer").hide();
			$("#pageEditPermissionContainer").show();
			$("#pageEditContentButton").removeClass("activeButton");
			$("#pageEditExtensionButton").removeClass("activeButton");
			$("#pageEditPositionButton").removeClass("activeButton");
			$("#pageEditLayoutButton").removeClass("activeButton");
			$("#pageEditPermissionButton").addClass("activeButton");
		});

/**
 * Cache le l'option "ne pas afficher les pages enfants dans le menu horizontal" lorsque la page est désactivée
 */
var pageEditDisableDOM = $("#pageEditDisable");
pageEditDisableDOM.on("change", function() {
	if ($(this).is(':checked') ) {
		$("#pageEditHideMenuChildrenWrapper").removeClass("disabled");
		$("#pageEditHideMenuChildrenWrapper").slideUp();
		$("#pageEditHideMenuChildren").prop("checked", false);
	} else {
		$("#pageEditHideMenuChildrenWrapper").addClass("disabled");
		$("#pageEditHideMenuChildrenWrapper").slideDown();
	}
});


/**
* Cache les options de masquage dans les menus quand la page n'est pas affichée.
*/
var pageEditPositionDOM = $("#pageEditPosition");
pageEditPositionDOM.on("change", function() {
	if ($(this).val()  === "0" ) {
		$("#pageEditHideMenuSideWrapper").removeClass("disabled");
		$("#pageEditHideMenuSideWrapper").slideUp();
	} else {
		$("#pageEditHideMenuSideWrapper").addClass("disabled");
		$("#pageEditHideMenuSideWrapper").slideDown();
	}
});

/**
 * Masquer et démasquer la sélection des barres
 */
var pageEditBlockDOM = $("#pageEditBlock");
pageEditBlockDOM.on("change", function() {
	switch ($(this).val()) {
		case "bar":
		case "12":
			$("#pageEditBarLeftWrapper").removeClass("disabled");
			$("#pageEditBarLeftWrapper").slideUp();
			$("#pageEditBarRightWrapper").removeClass("disabled");
			$("#pageEditBarRightWrapper").slideUp();
			break;
		case "3-9":
		case "4-8":
			$("#pageEditBarLeftWrapper").addClass("disabled");
			$("#pageEditBarLeftWrapper").slideDown();
			$("#pageEditBarRightWrapper").removeClass("disabled");
			$("#pageEditBarRightWrapper").slideUp();
			break;
		case "9-3":
		case "8-4":
			$("#pageEditBarLeftWrapper").removeClass("disabled");
			$("#pageEditBarLeftWrapper").slideUp();
			$("#pageEditBarRightWrapper").addClass("disabled");
			$("#pageEditBarRightWrapper").slideDown();
			break;
		case "3-6-3":
		case "2-7-3":
		case "3-7-2":
			$("#pageEditBarLeftWrapper").addClass("disabled");
			$("#pageEditBarLeftWrapper").slideDown();
			$("#pageEditBarRightWrapper").addClass("disabled");
			$("#pageEditBarRightWrapper").slideDown();
			break;
	}
	if ($(this).val() === "bar") {
			$("#pageEditMenu").removeClass("disabled");
			$("#pageEditMenu").hide();
			$("#pageEditHideTitleWrapper").removeClass("disabled");
			$("#pageEditHideTitleWrapper").slideUp();
			$("#pageTypeMenuWrapper").removeClass("disabled");
			$("#pageTypeMenuWrapper").slideUp();
			$("#pageEditSeoWrapper").removeClass("disabled");
			$("#pageEditSeoWrapper").slideUp();
			$("#pageEditAdvancedWrapper").removeClass("disabled");
			$("#pageEditAdvancedWrapper").slideUp();
			$("#pageEditbreadCrumbWrapper").removeClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideUp();
			$("#pageEditDisplayMenuWrapper").addClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideDown();
			$(".navSelect").slideUp();

	} else {
			$("#pageEditMenu").addClass("disabled");
			$("#pageEditMenu").show();
			$("#pageEditHideTitleWrapper").addClass("disabled");
			$("#pageEditHideTitleWrapper").slideDown();
			$("#pageTypeMenuWrapper").addClass("disabled");
			$("#pageTypeMenuWrapper").slideDown();
			$("#pageEditSeoWrapper").addClass("disabled");
			$("#pageEditSeoWrapper").slideDown();
			$("#pageEditAdvancedWrapper").addClass("disabled");
			$("#pageEditAdvancedWrapper").slideDown();
			$("#pageEditDisplayMenuWrapper").removeClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideUp();
			$(".navSelect").slideDown();
			if ($("#pageEditParentPageId").val() !== "") {
				$("#pageEditbreadCrumbWrapper").addClass("disabled");
				$("#pageEditbreadCrumbWrapper").slideDown();
				$("#pageEditExtraPositionWrapper").slideDown();
	}
	}
});




/**
 * Masquer ou afficher le chemin de fer
 * Quand le titre est masqué
 */
var pageEditHideTitleDOM = $("#pageEditHideTitle");
pageEditHideTitleDOM.on("change", function() {
	if ($("input[name=pageEditHideTitle]").is(':checked'))  {
			$("#pageEditbreadCrumbWrapper").removeClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideUp();
	} else {
		if ($("#pageEditParentPageId").val() !== "") {
			$("#pageEditbreadCrumbWrapper").addClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideDown();
		}
	}
});


/**
 * Masquer ou afficher le chemin de fer
 * Quand la page n'est pas mère et que le menu n'est pas masqué
 */
var pageEditParentPageIdDOM = $("#pageEditParentPageId");
pageEditParentPageIdDOM.on("change", function() {
	var parentSelected = $(this).val();

	if ($(this).val() === "" &&
		!$('input[name=pageEditHideTitle]').is(':checked') ) {
			$("#pageEditbreadCrumbWrapper").removeClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideUp();
			$("#pageEditExtraPositionWrapper").slideUp();
	} else {
			$("#pageEditbreadCrumbWrapper").addClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideDown();
			$("#pageEditExtraPositionWrapper").slideDown();

	}
	if ($(this).val() !== "") {
		  $("#pageEditHideMenuChildrenWrapper").removeClass("disabled");
			$("#pageEditHideMenuChildrenWrapper").slideUp();
			$("#pageEditExtraPositionWrapper").slideUp();
	}	else {
			$("#pageEditHideMenuChildrenWrapper").addClass("disabled");
			$("#pageEditHideMenuChildrenWrapper").slideDown();
			$("#pageEditExtraPositionWrapper").slideDown();
	}

});



/**
 * Masquer ou afficher la sélection de l'icône
 */
var pageTypeMenuDOM = $("#pageTypeMenu");
pageTypeMenuDOM.on("change", function() {
	if ($(this).val() !== "text") {
			$("#pageIconUrlWrapper").addClass("disabled");
			$("#pageIconUrlWrapper").slideDown();
	} else {
			$("#pageIconUrlWrapper").removeClass("disabled");
			$("#pageIconUrlWrapper").slideUp();
	}
});

/**
 * Duplication du champ Title dans Short title
 */
$("#pageEditTitle").on("input", function() {
	$("#pageEditShortTitle").val($(this).val());
});

/**
 * Actualise la liste de pages lorsque le menu accessoire est sélectionné
 */
// Initialise à Début si le menu accessoire est sélectionné

$("#pageEditExtraPosition").on("change", function() {
	if ($("#pageEditExtraPosition").val() == 1 ) {
		buildPagesList(true);
	} else {
		buildPagesList(false);
		//$("#pageEditParentPageId").trigger("change");
	}
});
/**
 * Affiche les pages en fonction de la page parent dans le choix de la position
 */
$("#pageEditParentPageId").on("change", function() {
	buildPagesList(false);
}).trigger("change");

/**
 * Construit un select contenant la liste des pages du site.
 */

function buildPagesList(extraPosition) {
	var hierarchy = <?php
echo json_encode($this->getHierarchy()); ?>;
	var pages = <?php
echo $module->getPageInfo(); ?>;
	var positionInitial = <?php
echo $this->getData(['page',$this->getUrl(2),"position"]); ?>;
	var extraPosition = $("#pageEditExtraPosition").val();
	var positionDOM = $("#pageEditPosition");
	var message_none = "<?php
echo Helper::translate('Ne pas afficher'); ?>";
	var message_begin = "<?php
echo Helper::translate('Au début'); ?>";
	var message_after = "<?php
echo Helper::translate('Après'); ?>";
	positionDOM.empty().append(
		$("<option>").val(0).text(message_none),
		$("<option>").val(1).text(message_begin)
	);
	var parentSelected = $("#pageEditParentPageId").val();
	var positionSelected = 0;
	var positionPrevious = 1;

	// Aucune page parent sélectionnée
	if(parentSelected === "") {
		// Liste des pages sans parents
		for(var key in hierarchy) {
			if(hierarchy.hasOwnProperty(key) ) {
				// Sélectionne la page avant s'il s'agit de la page courante
				if(key === "<?php
echo $this->getUrl(2); ?>") {
					positionSelected = positionPrevious;
				}
				// Sinon ajoute la page à la liste
				else {
					// Enregistre la position de cette page afin de la sélectionner si la prochaine page de la liste est la page courante
					if (extraPosition == pages[key].extraPosition ) {
						positionPrevious++;
						// Ajout à la liste
						positionDOM.append(
							$("<option>").val(positionPrevious).html(message_after + " \"" + (pages[key].title) + "\"")
						);
					}

				}
			}
		}
		if (positionInitial === 0) {
			positionSelected = 0;
		}
	}
	// Une page parent est sélectionnée
	else {
		// Liste des pages enfants de la page parent
		for(var i = 0; i < hierarchy[parentSelected].length; i++) {
			// Pour page courante sélectionne la page précédente (pas de - 1 à positionSelected à cause des options par défaut)
			if(hierarchy[parentSelected][i] === "<?php
echo $this->getUrl(2); ?>") {
				positionSelected = positionPrevious;
			}
			// Sinon ajoute la page à la liste
			else {
				// Enregistre la position de cette page afin de la sélectionner si la prochaine page de la liste est la page courante
				positionPrevious++;
				// Ajout à la liste
				positionDOM.append(
					$("<option>").val(positionPrevious).html(message_after + " \"" + (pages[hierarchy[parentSelected][i]].title) + "\"")
				);
			}
		}
	}
	// Sélectionne la bonne position
	positionDOM.val(positionSelected);
};

// Nouvelle fonction pour calculer la profondeur
function calculatePageDepth(pageId) {
	var hierarchy = <?php
echo json_encode($this->getHierarchy()); ?>;

	function findDepth(currentId, currentDepth) {
		if (currentId === "") return currentDepth;

		for (var parentId in hierarchy) {
			if (hierarchy[parentId].includes(currentId)) {
				return findDepth(parentId, currentDepth + 1);
			}
		}
		return currentDepth;
	}

	return findDepth(pageId, 0);
}

// Fonction pour recharger les options du sélecteur parent
function refreshParentOptions(excludePageId) {
	$.ajax({
		url: "<?php
echo Helper::baseUrl(); ?>page/getParentOptions",
		type: "POST",
		data: {
			excludePageId: excludePageId || ""
		},
		success: function(response) {
			try {
				var options = JSON.parse(response);
				var select = $("#pageEditParentPageId");
				var currentValue = select.val();

				// Vider le select
				select.empty();

				// Ajouter les nouvelles options
				$.each(options, function(value, text) {
					select.append($('<option></option>').val(value).text(text));
				});

				// Restaurer la sélection précédente
				select.val(currentValue);

				// Déclencher le change pour valider la profondeur
				select.trigger("change");
			} catch (e) {
				console.error("Erreur lors du rechargement des options:", e);
			}
		},
		error: function(xhr, status, error) {
			console.error("Erreur AJAX:", error);
		}
	});
}



// Define function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
