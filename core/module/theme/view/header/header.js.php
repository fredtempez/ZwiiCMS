/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license GNU General Public License, version 3
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2021, Frédéric Tempez
 * @link http://zwiicms.fr/
 */

$(document).ready(function(){
	$("header").css("line-height", "");
	$("header").css("height", "");
});

/**
 * Aperçu en direct
 */
$("input, select").on("change", function() {

	// Récupérer la taille de l'image

	var tmpImg = new Image();

	tmpImg.onload = function() {
		// Informations affichées
		$("#themeHeaderImageHeight").html(tmpImg.height + "px");
		$("#themeHeaderImageWidth").html(tmpImg.width + "px");
		$("#themeHeaderImageRatio").html(tmpImg.width / tmpImg.height);

		// Limiter la hauteur à 600 px
		if (tmpImg.height > 600) {
			tmpImgHeight = 600;
		} else {
			tmpImgHeight = tmpImg.height;
		}

		//Modifier la dropdown liste si une image n'est pas sélectionnée
		if ($("#themeHeaderImage").val() !== "" ) {
			// Une image est ajoutée ou changée
			if ($("#themeHeaderHeight option").length === 5) {
				// Pas d'image précédemment on ajoute l'option
				$("#themeHeaderHeight ").prepend('<option selected="selected" value="0"> Hauteur de l\'image sélectionnée </option>');
			} 
			// Modifier la valeur
			$("#themeHeaderHeight option:eq(0)").val(tmpImgHeight + "px");
			// Modifier l'option
			$("#themeHeaderHeight option:eq(0)").html("Hauteur de l\'image sélectionnée (" + tmpImgHeight + "px)");
		}
	};

	if ($("#themeHeaderImage").val() === "" &&
		$("#themeHeaderHeight option").length === 6 ) {
		$("#themeHeaderHeight option:eq(0)").remove();
	}

	tmpImg.src= "<?php echo helper::baseUrl(false); ?>" + "site/file/source/" + $("#themeHeaderImage").val();

	// Import des polices de caractères
	var headerFont = $("#themeHeaderFont").val();
	var css = "@import url('https://fonts.googleapis.com/css?family=" + headerFont + "');";

	// Couleurs, image, alignement et hauteur de la bannière
	css += "header{background-color:" + $("#themeHeaderBackgroundColor").val() + ";text-align:" + $("#themeHeaderTextAlign").val() + ";";

	if ($("#themeHeaderImage").val()) {
		// Une image est sélectionnée
		css += "background-image:url('<?php echo helper::baseUrl(false); ?>site/file/source/" + $("#themeHeaderImage").val() + "');background-repeat:" + $("#themeHeaderImageRepeat").val() + ";background-position:" + $("#themeHeaderImagePosition").val() + ";";
		css += "background-size:" + $("#themeHeaderImageContainer").val() + ";";
	// Pas d'image sélectionnée
	} else {
		// Désactiver l'option responsive
		css += "background-image:none;";
	}

	css += "line-height:" + $("#themeHeaderHeight").val() + ";height:" + $("#themeHeaderHeight").val() + "}";


	// Taille, couleur, épaisseur et capitalisation du titre de la bannière
	css += "header span{color:" + $("#themeHeaderTextColor").val() + ";font-family:'" + headerFont.replace(/\+/g, " ") + "',sans-serif;font-weight:" + $("#themeHeaderFontWeight").val() + ";font-size:" + $("#themeHeaderFontSize").val() + ";text-transform:" + $("#themeHeaderTextTransform").val() + "}";
	// Cache le titre de la bannière
	
	if($("#themeHeaderTextHide").is(":checked")) {
		$("header #themeHeaderTitle").hide();
	}
	else {
		$("header #themeHeaderTitle").show();
	}
	
	// Marge
	if($("#themeHeaderMargin").is(":checked")) {
		if(<?php echo json_encode($this->getData(['theme', 'menu', 'position']) === 'site-first'); ?>) {
			css += 'header{margin:0 20px}';
		}
		else {
			css += 'header{margin:20px 20px 0 20px}';
		}
	}
	else {
		css += 'header{margin:0}';
	}
	// Position de la bannière
	var headerPosition = $("#themeHeaderPosition").val();
	var menuPosition = <?php echo json_encode($this->getData(['theme', 'menu', 'position'])); ?>;
	console.clear();
	console.log ("menu :" + menuPosition);
	console.log ("header :" + headerPosition);
	switch(headerPosition) {
		case 'hide':
			$("header").hide();
			break;
		case 'site':
			if( menuPosition === 'site-first') {
				$("header").show().insertAfter("nav");
			}
			else {
				$("header").show().prependTo("#site");
				// Supprime le margin en trop du menu
				if(<?php echo json_encode($this->getData(['theme', 'menu', 'margin'])); ?>) {
					css += 'nav{margin:0 20px}';
				}
				if (menuPosition === 'site-second') {
					$("nav").show().prependTo("#site");
				}
			}
			break;
		case 'body':
			if( menuPosition === 'body-first') {
				$("header").show().insertAfter("nav");
			}
			else {
				$("header").show().insertAfter("#bar");
			}
			if ( menuPosition === "site-second") {
				$("nav").show().insertBefore("header");
			}
			if( menuPosition === 'top') {
				$("header").show().insertAfter("nav");
			}
			break;
	}

	// Ajout du css au DOM
	$("#themePreview").remove();
	$("<style>")
		.attr("type", "text/css")
		.attr("id", "themePreview")
		.text(css)
		.appendTo("head");
}).trigger("change");



// Affiche / Cache les options de l'image du fond
$("#themeHeaderImage").on("change", function() {
	if($(this).val()) {
		$("#themeHeaderImageOptions").slideDown();
	}
	else {
		$("#themeHeaderImageOptions").slideUp(function() {
			$("#themeHeaderTextHide").prop("checked", false).trigger("change");
		});
	}
}).trigger("change");

// Affiche / Cache les options de la position
$("#themeHeaderPosition").on("change", function() {
	if($(this).val() === 'site') {
		$("#themeHeaderPositionOptions").slideDown();
	}
	else {
		$("#themeHeaderPositionOptions").slideUp(function() {
			$("#themeHeaderMargin").prop("checked", false).trigger("change");
		});
	}
}).trigger("change");

// Affiche / Cache les options de la bannière cliquable si pas masquée
$("#themeHeaderPosition").on("change", function() {
	if($(this).val() === 'hide') {
		$("#themeHeaderShow").slideUp(function() {
			$("#themeHeaderlinkHome").prop("checked", false).trigger("change");
		});		
	}
	else {
		$("#themeHeaderShow").slideDown();
	}
}).trigger("change");


