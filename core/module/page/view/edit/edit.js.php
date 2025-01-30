/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @authorFrédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2025, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

/**
 * Confirmation de suppression
*/
 $("#pageEditDelete").on("click", function() {
	var _this = $(this);
	var message_delete = "<?php echo helper::translate('Confirmer la suppression de la page'); ?>";
	return core.confirm(message_delete, function() {
		$(location).attr("href", _this.attr("href"));
	});
});

$("#pageEditModuleId").on("change", function() {
	protectModule();
});

function protectModule() {
	var oldModule = $("#pageEditModuleIdOld").val();
	var oldModuleText =  $("#pageEditModuleIdOldText").val();
	var newModule = $("#pageEditModuleId").val();
	if ( oldModule !== "" &&
		 oldModule !== newModule) {
		var _this = $(this);
		var message_delete = "<?php echo helper::translate('Confirmer la suppression des données du module'); ?>";
		core.confirm(message_delete + " " + oldModuleText,
				function() {
					$(location).attr("href", _this.attr("href"));
					return true;
				},
				function() {
					$("#pageEditModuleId").val(oldModule);
					return false;
				}
		);
	}
}


/**
* Paramètres par défaut au chargement
*/
$( document ).ready(function() {

	// Changement de profil
	$(".pageEditGroupProfil").hide();
	$("#pageEditGroupProfil" + $("#pageEditGroup").val()).show();

	$("#pageEditGroup").on("change", function () {
		$(".pageEditGroupProfil").hide();
		$("#pageEditGroupProfil" + $(this).val()).show();
	});


	/**
	 * Sélection des onglets
	 */
	var pageLayout = "<?php echo $this->getData(['user', $this->getUser('id'), 'view', 'page']);?>";
	// Non défini, valeur par défaut
	if (pageLayout == "") {
		 pageLayout = "content";
	 }
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
	* Bloque/Débloque le bouton de configuration au changement de module
	* Affiche ou masque la position du module selon le call_user_func
	*/
	if($("#pageEditModuleId").val() === "") {
		$("#pageEditModuleConfig").addClass("disabled");
		/*$("#pageEditContentContainer").hide();*/

	}
	else {
		$("#pageEditModuleConfig").removeClass("disabled");
		/*$("#pageEditContentContainer").hide();*/
		$("#pageEditBlock option[value='bar']").remove();
	}

	/**
	* Masquer et affiche la sélection de position du module
	*/
	if( $("#pageEditModuleId").val() === "redirection" ||
		$("#pageEditModuleId").val() === "" ) {
		$("#pageModulePositionWrapper").removeClass("disabled");
		$("#pageModulePositionWrapper").slideUp();
	}
	else {
		$("#pageModulePositionWrapper").addClass("disabled");
		$("#pageModulePositionWrapper").slideDown();
	}


	/**
	* Masquer et démasquer le contenu pour les modules code et redirection
	*/
	if(  $("#pageEditModuleId").val() === "redirection") {
		$("#pageEditContentWrapper").removeClass("disabled");
		$("#pageEditContentWrapper").slideUp();
	} else {
		$("#pageEditContentWrapper").addClass("disabled");
		$("#pageEditContentWrapper").slideDown();
	}
	/**
	* Masquer et démasquer le masquage du titre pour le module redirection
	*/
	if( $("#pageEditModuleId").val() === "redirection" ) {
		$("#pageEditHideTitleWrapper").removeClass("disabled");
		$("#pageEditHideTitleWrapper").hide();
		$("#pageEditBlockLayout").removeClass("disabled");
		$("#pageEditBlockLayout").hide();

	} else {
		$("#pageEditHideTitleWrapper").addClass("disabled");
		$("#pageEditHideTitleWrapper").show();
		$("#pageEditBlockLayout").addClass("disabled");
		$("#pageEditBlockLayout").show();
	}
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
			$("#pageEditModuleIdWrapper").removeClass("disabled");
			$("#pageEditModuleIdWrapper").slideUp();
			$("#pageEditModuleConfig").removeClass("disabled");
			$("#pageEditModuleConfig").slideUp();
			$("#pageEditDisplayMenuWrapper").addClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideDown();
			$("#pageTypeMenuWrapper").removeClass("disabled");
			$("#pageTypeMenuWrapper").slideUp();
			$("#pageEditSeoWrapper").removeClass("disabled");
			$("#pageEditSeoWrapper").slideUp();
			$("#pageEditAdvancedWrapper").removeClass("disabled");
			$("#pageEditAdvancedWrapper").slideUp();
			$(".navSelect").slideUp();
			/*
			$("#pageEditBlockLayout").removeClass("col6");
			$("#pageEditBlockLayout").addClass("col12");
			*/

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
		var positionInitial = <?php echo $this->getData(['page',$this->getUrl(2),"position"]); ?>;
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
		});

	/**
     *  Sélection de la  page de configuration à afficher
     */
		 $("#pageEditContentButton").on("click", function () {
			pageLayout = "locale";
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
 * Bloque/Débloque le bouton de configuration au changement de module
 * Affiche ou masque la position du module selon le call_user_func
 */
var pageEditModuleIdDOM = $("#pageEditModuleId");
pageEditModuleIdDOM.on("change", function() {
	if($(this).val() === "") {
		$("#pageEditModuleConfig").addClass("disabled");
		$("#pageEditBlock").append('<option value="bar">Barre latérale</option>');
	}
	else {
		$("#pageEditModuleConfig").removeClass("disabled");
		$("#pageEditBlock option[value='bar']").remove();
	}
});



/**
 * Masquer et affiche la sélection de position du module
 *
 * */
var pageEditModuleIdDOM = $("#pageEditModuleId");
pageEditModuleIdDOM.on("change", function() {
	if( $(this).val() === "redirection" ||
		$(this).val() === "") {
		$("#pageModulePositionWrapper").removeClass("disabled");
 		$("#pageModulePositionWrapper").slideUp();
	}
	else {
		$("#pageModulePositionWrapper").addClass("disabled");
		$("#pageModulePositionWrapper").slideDown();
	}
});




/**
 * Masquer et démasquer le contenu pour les modules code et redirection
 */
var pageEditModuleIdDOM = $("#pageEditModuleId");
pageEditModuleIdDOM.on("change", function() {
	if( $(this).val() === "redirection") {
		$("#pageEditContentWrapper").removeClass("disabled");
		$("#pageEditContentWrapper").slideUp();
	}
	else {
		$("#pageEditContentWrapper").addClass("disabled");
		$("#pageEditContentWrapper").slideDown();
	}
});



/**
 * Masquer et démasquer le masquage du titre pour le module redirection
 */
var pageEditModuleIdDOM = $("#pageEditModuleId");
pageEditModuleIdDOM.on("change", function() {
	if( $(this).val() === "redirection") {
		$("#pageEditHideTitleWrapper").removeClass("disabled");
		$("#pageEditHideTitleWrapper").slideUp();
		$("#pageEditBlockLayout").removeClass("disabled");
		$("#pageEditBlockLayout").slideUp();
	}
	else {
		$("#pageEditHideTitleWrapper").addClass("disabled");
		$("#pageEditHideTitleWrapper").slideDown();
		$("#pageEditBlockLayout").addClass("disabled");
		$("#pageEditBlockLayout").slideDown();
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
			$("#pageEditModuleIdWrapper").removeClass("disabled");
			$("#pageEditModuleIdWrapper").slideUp();
			$("#pageEditModuleConfig").removeClass("disabled");
			$("#pageEditModuleConfig").slideUp();
			$("#pageEditDisplayMenuWrapper").addClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideDown();
			$(".navSelect").slideUp();
			/*
			$("#pageEditBlockLayout").removeClass("col6");
			$("#pageEditBlockLayout").addClass("col12");
			*/
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
			$("#pageEditModuleIdWrapper").addClass("disabled");
			$("#pageEditModuleIdWrapper").slideDown();
			$("#pageEditModuleConfig").slideDown();
			$("#pageEditDisplayMenuWrapper").removeClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideUp();
			$(".navSelect").slideDown();
			if ($("#pageEditParentPageId").val() !== "") {
				$("#pageEditbreadCrumbWrapper").addClass("disabled");
				$("#pageEditbreadCrumbWrapper").slideDown();
				$("#pageEditExtraPositionWrapper").slideDown();
	} else {
			}
			if ($("#pageEditModuleId").val() === "") {
				$("#pageEditModuleConfig").addClass("disabled");
			} else {
				$("#pageEditModuleConfig").removeClass("disabled");
			}
			/*
			$("#pageEditBlockLayout").removeClass("col12");
			$("#pageEditBlockLayout").addClass("col6");
			*/
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
 * Soumission du formulaire pour éditer le module
 */
$("#pageEditModuleConfig").on("click", function() {
		$("#pageEditModuleRedirect").val(1);
		$("#pageEditForm").trigger("submit");
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
	var hierarchy = <?php echo json_encode($this->getHierarchy()); ?>;
	var pages = <?php echo $module->getPageInfo(); ?>;
	var positionInitial = <?php echo $this->getData(['page',$this->getUrl(2),"position"]); ?>;
	var extraPosition = $("#pageEditExtraPosition").val();
	var positionDOM = $("#pageEditPosition");
	var message_none = "<?php echo helper::translate('Ne pas afficher'); ?>";
	var message_begin = "<?php echo helper::translate('Au début'); ?>";
	var message_after = "<?php echo helper::translate('Après'); ?>";
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
				if(key === "<?php echo $this->getUrl(2); ?>") {
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
			if(hierarchy[parentSelected][i] === "<?php echo $this->getUrl(2); ?>") {
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

// Define function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}