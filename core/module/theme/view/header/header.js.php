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
	
	var css = "";

	// Contenu perso
	if ($("#themeHeaderFeature").val() == "feature") {
		css = "header{height:" + $("#themeHeaderHeight").val() + ";background-position:top; background-repeat: no-repeat;;line-height:1.15;background-color:unset;;background-image:unset;text-align:unset}";
		$("#featureContent").appendTo("header").show();
		$("#themeHeaderTitle").hide();

	}

	// Couleurs, image, alignement et hauteur de la bannière
	if ($("#themeHeaderFeature").val() == "wallpaper") {

		// Masque le contenu perso
		$("#featureContent").hide();
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
			$("#themeHeaderTitle").hide();
		}
		else {
			$("#themeHeaderTitle").show();
		}

		// Marge
		if($("#themeHeaderMargin").is(":checked")) {
			if(<?php echo json_encode($this->getData(['theme', 'menu', 'position']) === 'site-first'); ?>) {
				css += 'header{margin:0 20px}';
			} else {
				css += 'header{margin:20px 20px 0 20px}';
			}
		} else {
			css += 'header{margin:0}';
		}
	}

	// Position de la bannière
	var positionNav = <?php echo json_encode($this->getData(['theme', 'menu', 'position'])); ?>;
	var positionHeader = $("#themeHeaderPosition").val();

	switch(positionHeader) {
		case 'hide':
			$("header").hide();
			$("nav").show().prependTo("#site");
			break;
		case 'site':
			$("header").show().prependTo("#site");
			// Position du menu
			switch (positionNav) {
				case "body-first":
					$("nav").show().insertAfter("header");
					break;
				case "site-first":
					$("nav").show().prependTo("#site");
					// Supprime le margin en trop du menu
					if(<?php echo json_encode($this->getData(['theme', 'menu', 'margin'])); ?>) {
						css += 'nav{margin:0 20px}';
					}
					break;
				case "body-second":
				case "site-second":
					$("nav").show().insertAfter("header");
					// Supprime le margin en trop du menu
					if(<?php echo json_encode($this->getData(['theme', 'menu', 'margin'])); ?>) {
						css += 'nav{margin:0 20px}';
					}
					break;
			}
			break;
		case 'body':
			// Position du menu
			switch (positionNav) {
				case "top":
					$("header").show().insertAfter("nav");
					break;
				case "site-first":
				case "body-first":
					$("header").show().insertAfter("#bar");
					$("nav").show().insertAfter("#bar");
					break;
				case "site-second":
				case "body-second":
					$("header").show().insertAfter("#bar");
					$("nav").show().insertAfter("header");
					break;

			}
	}

	// Largeur du header
	switch ($("#themeHeaderContainer").val()) {
		case "container":
			$("header").removeClass("container-large");
			$("header").addClass("container");
			break;
		case "container-large":
			$("header").removeClass("container");
			$("header").addClass("container-large");
			break;
	}

	// La bannière est cachée, déplacer le menu dans le site
	if (positionHeader === "hide" &&
		(positionNav === "body-first" ||
		 positionNav === "site-first" ||
		 positionNav === "body-second" ||
		 positionNav === "site-second"
		 )) {
			$("nav").show().prependTo("#site");
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

// Affiche / Cache l'option bannière masquée en écran réduit
$("#themeHeaderPosition").on("change", function() {
	if($(this).val() === 'hide') {
		$("#themeHeaderSmallDisplay").slideUp();
	}
	else {
		$("#themeHeaderSmallDisplay").slideDown();
	}
}).trigger("change");

// Affiche les blocs selon le type bannière
$("#themeHeaderFeature").on("change", function() {
	if($(this).val() === 'wallpaper') {
		$(".wallpaperContainer").show();
		$(".featureContainer").hide();
	}
	if($(this).val() === 'feature') {
		$(".featureContainer").show();
		$(".wallpaperContainer").hide();
	}
}).trigger("change");