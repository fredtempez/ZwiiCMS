/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @authorFrédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2020, Frédéric Tempez
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */

/**
 * Confirmation de suppression
 */
$("#pageEditDelete").on("click", function() {
	var _this = $(this);
	return core.confirm("Êtes-vous sûr de vouloir supprimer cette page ?", function() {
		$(location).attr("href", _this.attr("href"));
	});
});

/** 
* Paramètres par défaut au chargement
*/ 
$( document ).ready(function() {
	
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
		$("#pageEditContentContainer").hide();		
	}
	else {
		$("#pageEditModuleConfig").removeClass("disabled");
		$("#pageEditContentContainer").hide();
		$("#pageEditBlock option[value='bar']").remove();		
	}

	/**
	* Masquer et affiche la sélection de position du module
	*/
	if( $("#pageEditModuleId").val() === "redirection" ||
		$("#pageEditModuleId").val() === "" ) {
		$("#configModulePositionWrapper").removeClass("disabled");
		$("#configModulePositionWrapper").slideUp();		 	
	}
	else {
		$("#configModulePositionWrapper").addClass("disabled");
		$("#configModulePositionWrapper").slideDown();		
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
			$("#pageEditBlockLayout").removeClass("col6");
			$("#pageEditBlockLayout").addClass("col12");
						
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
		$("#pageEditContentContainer").slideDown();
		$("#pageEditBlock").append('<option value="bar">Barre latérale</option>');		
	}
	else {
		$("#pageEditModuleConfig").removeClass("disabled");
		$("#pageEditContentContainer").slideUp();
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
		$("#configModulePositionWrapper").removeClass("disabled");
 		$("#configModulePositionWrapper").slideUp();		
	}
	else {
		$("#configModulePositionWrapper").addClass("disabled");
		$("#configModulePositionWrapper").slideDown();				
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
			$("#pageEditBlockLayout").removeClass("col6");
			$("#pageEditBlockLayout").addClass("col12");																			
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
			$("#pageEditModuleConfig").addClass("disabled");
			$("#pageEditModuleConfig").slideDown();	
			$("#pageEditDisplayMenuWrapper").removeClass("disabled");
			$("#pageEditDisplayMenuWrapper").slideUp();													
			if ($("#pageEditParentPageId").val() !== "") {
				$("#pageEditbreadCrumbWrapper").addClass("disabled");
				$("#pageEditbreadCrumbWrapper").slideDown();
			}
			$("#pageEditBlockLayout").removeClass("col12");
			$("#pageEditBlockLayout").addClass("col6");
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
	} else {
			$("#pageEditbreadCrumbWrapper").addClass("disabled");
			$("#pageEditbreadCrumbWrapper").slideDown();			
					
	}
	if ($(this).val() !== "") {
		  $("#pageEditHideMenuChildrenWrapper").removeClass("disabled");
			$("#pageEditHideMenuChildrenWrapper").slideUp();
		}	else {
			$("#pageEditHideMenuChildrenWrapper").addClass("disabled");
			$("#pageEditHideMenuChildrenWrapper").slideDown();
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
 * Soumission du formulaire pour éditer le module
 */
$("#pageEditModuleConfig").on("click", function() {
	$("#pageEditModuleRedirect").val(1);
	$("#pageEditForm").trigger("submit");
});

/**
 * Affiche les pages en fonction de la page parent dans le choix de la position
 */
var hierarchy = <?php echo json_encode($this->getHierarchy()); ?>;

var pages = <?php echo json_encode($this->getData(['page'])); ?>;


// 9.0.07 corrige une mauvaise sélection d'une page orpheline avec enfant
var positionInitial = <?php echo $this->getData(['page',$this->getUrl(2),"position"]); ?>;
// 9.0.07
$("#pageEditParentPageId").on("change", function() {
	var positionDOM = $("#pageEditPosition");
	positionDOM.empty().append(
		$("<option>").val(0).text("Ne pas afficher"),
		$("<option>").val(1).text("Au début")
	);
	var parentSelected = $(this).val();
	var positionSelected = 0;
	var positionPrevious = 1;
	// Aucune page parent selectionnée
	if(parentSelected === "") {
		// Liste des pages sans parents
		for(var key in hierarchy) {
			if(hierarchy.hasOwnProperty(key)) {
				// Sélectionne la page avant s'il s'agit de la page courante
				if(key === "<?php echo $this->getUrl(2); ?>") {
					positionSelected = positionPrevious;
				}
				// Sinon ajoute la page à la liste
				else {
					// Enregistre la position de cette page afin de la sélectionner si la prochaine page de la liste est la page courante
					positionPrevious++;
					// Ajout à la liste
					positionDOM.append(
						$("<option>").val(positionPrevious).html("Après \"" + (pages[key].title) + "\"")
					);
				}
			}
		}
		// 9.0.07 corrige une mauvaise sélection d'une page orpheline avec enfant
		if (positionInitial === 0) {
			positionSelected = 0;
		}
		// 9.0.07
	}
	// Un page parent est selectionnée
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
					$("<option>").val(positionPrevious).html("Après \"" + (pages[hierarchy[parentSelected][i]].title) + "\"")
				);
			}
		}
	}
	// Sélectionne la bonne position
	positionDOM.val(positionSelected);
}).trigger("change");