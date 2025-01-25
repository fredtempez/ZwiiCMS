/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

// Cache jQuery objects for better performance
const $pageEditDelete = $("#pageEditDelete");
const $pageEditModuleId = $("#pageEditModuleId");
const $pageEditModuleIdOld = $("#pageEditModuleIdOld");
const $pageEditModuleIdOldText = $("#pageEditModuleIdOldText");
const $pageEditGroup = $("#pageEditGroup");
const $pageEditGroupProfil = $(".pageEditGroupProfil");
const $pageEditContentContainer = $("#pageEditContentContainer");
const $pageEditExtensionContainer = $("#pageEditExtensionContainer");
const $pageEditPositionContainer = $("#pageEditPositionContainer");
const $pageEditLayoutContainer = $("#pageEditLayoutContainer");
const $pageEditPermissionContainer = $("#pageEditPermissionContainer");
const $pageEditModuleConfig = $("#pageEditModuleConfig");
const $pageModulePositionWrapper = $("#pageModulePositionWrapper");
const $pageEditContentWrapper = $("#pageEditContentWrapper");
const $pageEditHideTitleWrapper = $("#pageEditHideTitleWrapper");
const $pageEditBlockLayout = $("#pageEditBlockLayout");
const $pageEditBlock = $("#pageEditBlock");
const $pageEditBarLeftWrapper = $("#pageEditBarLeftWrapper");
const $pageEditBarRightWrapper = $("#pageEditBarRightWrapper");
const $pageEditMenu = $("#pageEditMenu");
const $pageEditbreadCrumbWrapper = $("#pageEditbreadCrumbWrapper");
const $pageEditModuleIdWrapper = $("#pageEditModuleIdWrapper");
const $pageEditDisplayMenuWrapper = $("#pageEditDisplayMenuWrapper");
const $pageTypeMenuWrapper = $("#pageTypeMenuWrapper");
const $pageEditSeoWrapper = $("#pageEditSeoWrapper");
const $pageEditAdvancedWrapper = $("#pageEditAdvancedWrapper");
const $pageEditHideMenuSideWrapper = $("#pageEditHideMenuSideWrapper");
const $pageEditHideMenuChildrenWrapper = $("#pageEditHideMenuChildrenWrapper");
const $pageEditParentPageId = $("#pageEditParentPageId");
const $pageEditDisable = $("#pageEditDisable");
const $pageEditExtraPosition = $("#pageEditExtraPosition");
const $pageEditPosition = $("#pageEditPosition");
const $pageEditHideTitle = $("#pageEditHideTitle");
const $pageTypeMenu = $("#pageTypeMenu");
const $pageIconUrlWrapper = $("#pageIconUrlWrapper");
const $pageEditTitle = $("#pageEditTitle");
const $pageEditShortTitle = $("#pageEditShortTitle");

// Confirmation de suppression
$pageEditDelete.on("click", function() {
    return core.confirm($("#pageEditDataContainer").data("translate-delete"), () => {
        $(location).attr("href", $(this).attr("href"));
    });
});

// Gestion du changement de module
$pageEditModuleId.on("change", protectModule);

function protectModule() {
    const oldModule = $pageEditModuleIdOld.val();
    const newModule = $pageEditModuleId.val();
    if (oldModule && oldModule !== newModule) {
        core.confirm($("#pageEditDataContainer").data("translate-module-delete") + " " + $pageEditModuleIdOldText.val(), () => {
            $(location).attr("href", $(this).attr("href"));
        }, () => {
            $pageEditModuleId.val(oldModule);
        });
    }
}

// Paramètres par défaut au chargement
$(document).ready(function() {
    // Changement de profil
    $pageEditGroupProfil.hide();
    $(`#pageEditGroupProfil${$pageEditGroup.val()}`).show();
    $pageEditGroup.on("change", function() {
        $pageEditGroupProfil.hide();
        $(`#pageEditGroupProfil${$(this).val()}`).show();
    });

    // Sélection des onglets
    const pageLayout = $("#pageEditDataContainer").data("page-layout") || "content";
    $pageEditContentContainer.hide();
    $pageEditExtensionContainer.hide();
    $pageEditPositionContainer.hide();
    $pageEditLayoutContainer.hide();
    $pageEditPermissionContainer.hide();
    $(`#pageEdit${capitalizeFirstLetter(pageLayout)}Container`).show();
    $(`#pageEdit${capitalizeFirstLetter(pageLayout)}Button`).addClass("activeButton");

    // Enleve le menu fixe en édition de page
    $("nav").removeAttr('id');

    // Gestion des modules
    if ($pageEditModuleId.val() === "") {
        $pageEditModuleConfig.addClass("disabled");
    } else {
        $pageEditModuleConfig.removeClass("disabled");
        $pageEditBlock.find("option[value='bar']").remove();
    }

    // Masquer et afficher les éléments en fonction du module sélectionné
    toggleModuleElements($pageEditModuleId.val());

    // Masquer et afficher les éléments en fonction du bloc sélectionné
    toggleBlockElements($pageEditBlock.val());

    // Masquer ou afficher le chemin de fer
    toggleBreadCrumb($pageEditHideTitle.is(':checked'), $pageEditParentPageId.val());

    // Masquer ou afficher la sélection de l'icône
    toggleIconUrl($pageTypeMenu.val());

    // Masquer ou afficher les options de masquage dans les menus
    toggleMenuOptions($pageEditPosition.val(), $pageEditParentPageId.val(), $pageEditDisable.is(':checked'));

    // Liste des pages pour le menu accessoire
    if ($pageEditExtraPosition.val() == 1) {
        buildPagesList(true);
        $pageEditPosition.val($("#pageEditDataContainer").data("position-initial"));
    }
});

// Gestion des événements
$('#pageEditForm').on('submit', function() {
    $('#containerSelected').val(pageLayout);
});

$("#pageEditContentButton, #pageEditPositionButton, #pageEditExtensionButton, #pageEditLayoutButton, #pageEditPermissionButton").on("click", function() {
    const tab = $(this).attr("id").replace("pageEdit", "").replace("Button", "").toLowerCase();
    $pageEditContentContainer.hide();
    $pageEditExtensionContainer.hide();
    $pageEditPositionContainer.hide();
    $pageEditLayoutContainer.hide();
    $pageEditPermissionContainer.hide();
    $(`#pageEdit${capitalizeFirstLetter(tab)}Container`).show();
    $(this).addClass("activeButton").siblings().removeClass("activeButton");
});

$pageEditDisable.on("change", function() {
    toggleMenuOptions($pageEditPosition.val(), $pageEditParentPageId.val(), $(this).is(':checked'));
});

$pageEditPosition.on("change", function() {
    toggleMenuOptions($(this).val(), $pageEditParentPageId.val(), $pageEditDisable.is(':checked'));
});

$pageEditModuleId.on("change", function() {
    toggleModuleElements($(this).val());
});

$pageEditBlock.on("change", function() {
    toggleBlockElements($(this).val());
});

$pageEditHideTitle.on("change", function() {
    toggleBreadCrumb($(this).is(':checked'), $pageEditParentPageId.val());
});

$pageTypeMenu.on("change", function() {
    toggleIconUrl($(this).val());
});

$pageEditTitle.on("input", function() {
    $pageEditShortTitle.val($(this).val());
});

$pageEditExtraPosition.on("change", function() {
    buildPagesList($(this).val() == 1);
});

$pageEditModuleConfig.on("click", function() {
    $("#pageEditModuleRedirect").val(1);
    $("#pageEditForm").trigger("submit");
});

$pageEditParentPageId.on("change", function() {
    buildPagesList(false);
}).trigger("change");

function buildPagesList(extraPosition) {
    // Récupération des données depuis les attributs data-*
    const hierarchy = $("#pageEditDataContainer").data("hierarchy");
    const pages = $("#pageEditDataContainer").data("pages");
    const positionInitial = $("#pageEditDataContainer").data("position-initial");
    const currentPage = $("#pageEditDataContainer").data("current-page");
    
    const positionDOM = $pageEditPosition;
    const message_none = $("#pageEditDataContainer").data("translate-none");
    const message_begin = $("#pageEditDataContainer").data("translate-begin");
    const message_after = $("#pageEditDataContainer").data("translate-after");

    positionDOM.empty().append(
        $("<option>").val(0).text(message_none),
        $("<option>").val(1).text(message_begin)
    );

    const parentSelected = $pageEditParentPageId.val();
    let positionSelected = 0;
    let positionPrevious = 1;

    // --- Début de la logique originale ---
    if (parentSelected === "") {
        for (const key in hierarchy) {
            if (hierarchy.hasOwnProperty(key)) {
                if (key === currentPage) {
                    positionSelected = positionPrevious;
                } else {
                    if (extraPosition == pages[key].extraPosition) {
                        positionPrevious++;
                        positionDOM.append(
                            $("<option>").val(positionPrevious).html(`${message_after} "${pages[key].title}"`)
                        );
                    }
                }
            }
        }
        if (positionInitial === 0) {
            positionSelected = 0;
        }
    } else {
        for (let i = 0; i < hierarchy[parentSelected].length; i++) {
            if (hierarchy[parentSelected][i] === currentPage) {
                positionSelected = positionPrevious;
            } else {
                positionPrevious++;
                positionDOM.append(
                    $("<option>").val(positionPrevious).html(`${message_after} "${pages[hierarchy[parentSelected][i]].title}"`)
                );
            }
        }
    }
    // --- Fin de la logique originale ---

    positionDOM.val(positionSelected);
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function toggleModuleElements(moduleId) {
    const isRedirection = moduleId === "redirection";
    const isEmpty = moduleId === "";

    $pageModulePositionWrapper.toggleClass("disabled", !isEmpty && !isRedirection).slideToggle(isEmpty || isRedirection);
    $pageEditContentWrapper.toggleClass("disabled", !isRedirection).slideToggle(isRedirection);
    $pageEditHideTitleWrapper.toggleClass("disabled", !isRedirection).slideToggle(isRedirection);
    $pageEditBlockLayout.toggleClass("disabled", !isRedirection).slideToggle(isRedirection);
}

function toggleBlockElements(blockValue) {
    const isBar = blockValue === "bar";
    const isFullWidth = blockValue === "12";
    const isLeftBar = blockValue === "3-9" || blockValue === "4-8";
    const isRightBar = blockValue === "9-3" || blockValue === "8-4";
    const isDoubleBar = blockValue === "3-6-3" || blockValue === "2-7-3" || blockValue === "3-7-2";

    $pageEditBarLeftWrapper.toggleClass("disabled", !isBar && !isFullWidth && !isRightBar).slideToggle(isBar || isFullWidth || isRightBar);
    $pageEditBarRightWrapper.toggleClass("disabled", !isBar && !isFullWidth && !isLeftBar).slideToggle(isBar || isFullWidth || isLeftBar);

    if (isBar) {
        $pageEditMenu.removeClass("disabled").hide();
        $pageEditHideTitleWrapper.removeClass("disabled").slideUp();
        $pageTypeMenuWrapper.removeClass("disabled").slideUp();
        $pageEditSeoWrapper.removeClass("disabled").slideUp();
        $pageEditAdvancedWrapper.removeClass("disabled").slideUp();
        $pageEditbreadCrumbWrapper.removeClass("disabled").slideUp();
        $pageEditModuleIdWrapper.removeClass("disabled").slideUp();
        $pageEditModuleConfig.removeClass("disabled").slideUp();
        $pageEditDisplayMenuWrapper.addClass("disabled").slideDown();
        $(".navSelect").slideUp();
    } else {
        $pageEditMenu.addClass("disabled").show();
        $pageEditHideTitleWrapper.addClass("disabled").slideDown();
        $pageTypeMenuWrapper.addClass("disabled").slideDown();
        $pageEditSeoWrapper.addClass("disabled").slideDown();
        $pageEditAdvancedWrapper.addClass("disabled").slideDown();
        $pageEditModuleIdWrapper.addClass("disabled").slideDown();
        $pageEditModuleConfig.slideDown();
        $pageEditDisplayMenuWrapper.removeClass("disabled").slideUp();
        $(".navSelect").slideDown();
        if ($pageEditParentPageId.val() !== "") {
            $pageEditbreadCrumbWrapper.addClass("disabled").slideDown();
            $pageEditExtraPositionWrapper.slideDown();
        }
        if ($pageEditModuleId.val() === "") {
            $pageEditModuleConfig.addClass("disabled");
        } else {
            $pageEditModuleConfig.removeClass("disabled");
        }
    }
}

function toggleBreadCrumb(isHideTitleChecked, parentPageId) {
    $pageEditbreadCrumbWrapper.toggleClass("disabled", !isHideTitleChecked && parentPageId !== "").slideToggle(isHideTitleChecked || parentPageId === "");
}

function toggleIconUrl(menuType) {
    $pageIconUrlWrapper.toggleClass("disabled", menuType !== "text").slideToggle(menuType !== "text");
}

function toggleMenuOptions(position, parentPageId, isDisableChecked) {
    $pageEditHideMenuSideWrapper.toggleClass("disabled", position !== "0").slideToggle(position === "0");
    $pageEditHideMenuChildrenWrapper.toggleClass("disabled", parentPageId === "" && !isDisableChecked).slideToggle(parentPageId !== "" || isDisableChecked);
}