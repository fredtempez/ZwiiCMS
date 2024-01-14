/**
 * This file is part of Zwii.
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

$(document).ready(function(){
	// Menu fixe à afficher
	if($("#themeMenuPosition").val() === 'top') {
		$("#themeMenuPositionFixed").slideDown();
	}
	else {
		$("#themeMenuPositionFixed").slideUp(function() {
			$("#themeMenuFixed").prop("checked", false).trigger("change");
		});
	}

	// Option de menu à afficher
	if($("#themeMenuPosition").val() === 'site-first' || $(this).val() === 'site-second') {
		$("#themeMenuPositionOptions").slideDown();
	}
	else {
		$("#themeMenuPositionOptions").slideUp(function() {
			$("#themeMenuMargin").prop("checked", false).trigger("change");
		});
	}

});


/**
 * Aperçu en direct
 */
$("input, select").on("change", function() {
	// Import des polices de caractères
	var menuFont = $("#themeMenuFont :selected").val();
	var menuFontText = $("#themeMenuFont :selected").text();
	var css = "@import url('https://fonts.cdnfonts.com/css/" + menuFont + "');";
	var colors = core.colorVariants($("#themeMenuBackgroundColor").val());
	// Couleurs du menu
	css += "nav,nav.navLevel1 a{background-color:" + colors.normal + "}";
	css += "nav a,#toggle span,nav a:hover{color:" + $("#themeMenuTextColor").val() + "}";
	css += "nav a:hover{background-color:" + colors.darken + "}";
	if ($("#themeMenuActiveColorAuto").is(':checked')) {
		css += "nav a:hover{background-color:" + colors.veryDarken + ";color:" + $('#themeMenuActiveTextColor').val() + ";}";
	} else {
		css += "nav a:hover{background-color:" +  $("#themeMenuActiveColor").val() +  ";color:" + $('#themeMenuActiveTextColor').val() + ";}";
	}
	// sous menu
	var colors = core.colorVariants($("#themeMenuBackgroundColorSub").val());
	css += 'nav .navSub a{background-color:' + colors.normal + '}';
	// Taille, hauteur, épaisseur et capitalisation de caractères du menu
	css += "#toggle span,#menu a{padding:" + $("#themeMenuHeight").val() + ";font-family:'" + menuFontText  + "',sans-serif;font-weight:" + $("#themeMenuFontWeight").val() + ";font-size:" + $("#themeMenuFontSize").val() + ";text-transform:" + $("#themeMenuTextTransform").val() + "}";
	// Alignement du menu
	css += "#menu{text-align:" + $("#themeMenuTextAlign").val() + "}";
	// Marge
	if($("#themeMenuMargin").is(":checked")) {
		if(
			<?php echo json_encode($this->getData(['theme', 'menu', 'position']) === 'site-first'); ?>
			|| <?php echo json_encode($this->getData(['theme', 'menu', 'position']) === 'site-second'); ?>
		) {
			css += 'nav{padding: 10px 10px 0 10px}';
		}
		else {
			css += 'nav{padding:0 10px;}';
		}
	}
	else {
		css += 'nav{margin:0;}';
	}
    if(
		<?php echo json_encode($this->getData(['theme', 'menu', 'position']) === 'top'); ?>
	) {
		css += 'nav{padding:0 10px;}';
	}

	// Position du menu
	switch($("#themeMenuPosition").val()) {
		case 'hide':
			$("nav").hide();
			break;
		case 'site-first':
			$("nav").show().prependTo("#site");
			break;
		case 'site-second':
			if(<?php echo json_encode($this->getData(['theme', 'header', 'position']) === 'site'); ?>) {
				$("nav").show().insertAfter("header");
			}
			else {
				$("nav").show().prependTo("#site");
			}
			break;
		case 'body-first':
			$("nav").show().insertAfter("#bar");
			$("#menu").removeClass('container-large');
			$("nav").removeAttr('id');
			$("#menu").addClass('container');
			break;
		case 'body-second':
			if(<?php echo json_encode($this->getData(['theme', 'header', 'position']) === 'body'); ?>) {
				$("nav").show().insertAfter("header");
			}
			else {
				$("nav").show().insertAfter("#bar");
			}
			$("nav").removeAttr('id');
			break;
		case 'top':
			$("nav").show().insertAfter("#bar");
			$("#menu").removeClass('container');
			$("#menu").addClass('container-large');
			$("nav").attr('id','#navfixedconnected');
			break;
		case 'site':
			$("nav").show().prependTo("#site");
			break;
	}

	//  Largeur étendue
	if ($("#themeMenuWide").val() === 'none') {
		$("#menu").removeClass();
	} else {
		$("#menu").addClass("container");
	}

	// Ajout du css au DOM
	$("#themePreview").remove();
	$("<style>")
		.attr("type", "text/css")
		.attr("id", "themePreview")
		.text(css)
		.appendTo("head");


});
//
// Lien de connexion (addClass() et removeClass() au lieu de hide() et show() car ils ne conservent pas le display-inline: block; de #themeMenuLoginLink)
$("#themeMenuLoginLink").on("change", function() {
	if($(this).is(":checked")) {
		$("#menuLoginLink").removeClass('displayNone');
	}
	else {
		$("#menuLoginLink").addClass('displayNone');
	}
}).trigger("change");

// Affiche / Cache les options de la position
$("#themeMenuPosition").on("change", function() {
	if($(this).val() === 'site-first' || $(this).val() === 'site-second') {
		$("#themeMenuPositionOptions").slideDown();
	}
	else {
		$("#themeMenuPositionOptions").slideUp(function() {
			$("#themeMenuMargin").prop("checked", false).trigger("change");
		});
	}
}).trigger("change");

// Affiche / Cache les options du menu fixe
$("#themeMenuPosition").on("change", function() {
	if($(this).val() === 'top') {
		$("#themeMenuPositionFixed").slideDown();
	}
	else {
		$("#themeMenuPositionFixed").slideUp(function() {
			$("#themeMenuFixed").prop("checked", false).trigger("change");
		});
	}
}).trigger("change");

// Affiche la sélection de couleur auto
$("#themeMenuActiveColorAuto").on("change", function() {
	if ($(this).is(':checked') ) {
		$("#themeMenuActiveColorWrapper").slideUp();
	} else {
		$("#themeMenuActiveColorWrapper").slideDown();
	}
}).trigger("change");

// Affiche / Cache la sélection du logo pour le menu burger
$("#themeMenuBurgerContent").on("change", function() {
	if($(this).val() === 'logo') {
		$("#themeMenuBurgerLogoId").slideDown();
	}
	else {
		$("#themeMenuBurgerLogoId").slideUp();
	}
}).trigger("select");
