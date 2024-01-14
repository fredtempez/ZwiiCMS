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

/**
 * Ajout d'un champ
 */
function add(inputUid, input) {
	// Nouveau champ
	var newInput = $($("#formConfigCopy").html());
	// Ajout de l'ID unique aux champs
	newInput.find("a, input, select").each(function() {
		var _this = $(this);
		_this.attr({
			id: _this.attr("id").replace("[]", "[" + inputUid + "]"),
			name: _this.attr("name").replace("[]", "[" + inputUid + "]")
		});
	});
	newInput.find("label").each(function() {
		var _this = $(this);
		_this.attr("for", _this.attr("for").replace("[]", "[" + inputUid + "]"));
	});
	// Attribue les bonnes valeurs
	if(input) {
		// Nom du champ
		newInput.find("[name='formConfigName[" + inputUid + "]']").val(input.name);
		// Type de champ
		newInput.find("[name='formConfigType[" + inputUid + "]']").val(input.type);
		// Largeur du champ
		newInput.find("[name='formConfigWidth[" + inputUid + "]']").val(input.width);
		// Valeurs du champ
		newInput.find("[name='formConfigValues[" + inputUid + "]']").val(input.values);
		// Champ obligatoire
		newInput.find("[name='formConfigRequired[" + inputUid + "]']").prop("checked", input.required);
	}
	// Ajout du nouveau champ au DOM
	$("#formConfigInputs")
		.append(newInput.hide())
		.find(".formConfigInput").last().show();
	// Cache le texte d'absence de champ
	$("#formConfigNoInput:visible").hide();
	// Check le type
	$(".formConfigType").trigger("change");
	// Actualise les positions
	position();
}

/**
 * Afficher/cacher les options supplémentaires
 */
 $(document).on("click", ".formConfigMoreToggle", function() {

	$(this).parents(".formConfigInput").find(".formConfigMore").slideToggle();
	$(this).parents(".formConfigInput").find(".formConfigMoreLabel").slideToggle();
});

/**
 * Calcul des positions
 */
function position() {
	$("#formConfigInputs").find(".formConfigPosition").each(function(i) {
		$(this).val(i + 1);
	});
}

/**
 * Ajout des champs déjà existant
 */
var inputUid = 0;
var inputs = <?php echo json_encode($this->getData(['module', $this->getUrl(0), 'input'])); ?>;
if(inputs) {
	var inputsPerPosition = <?php echo json_encode(helper::arrayColumn($this->getData(['module', $this->getUrl(0), 'input']), 'position', 'SORT_ASC')); ?>;
	$.each(inputsPerPosition, function(id) {
		add(inputUid, inputs[id]);
		inputUid++;
	});
}


/**
 * Crée un nouveau champ à partir des champs cachés
 */
$("#formConfigAdd").on("click", function() {
	add(inputUid);
	inputUid++;
});

/**
 * Actions sur les champs
 */

// Validation auto après ajout d'un champ
$("a#formConfigAdd.button").click(function () {
	$("#formConfigForm").submit();
});

// Tri entre les champs
sortable("#formConfigInputs", {
	forcePlaceholderSize: true,
	containment: "#formConfigInputs",
	handle: ".formConfigMove"
});
$("#formConfigInputs")
	// Actualise les positions
	.on("sortupdate", function() {
		position();
	})
	// Suppression du champ
	.on("click", ".formConfigDelete", function() {
		var inputDOM = $(this).parents(".formConfigInput");
		// Cache le champ
		inputDOM.hide();
		// Supprime le champ
		inputDOM.remove();
		// Affiche le texte d'absence de champ
		if($("#formConfigInputs").find(".formConfigInput").length === 0) {
			$("#formConfigNoInput").show();
		}
		// Actualise les positions
		position();
	})
	// Affiche/cache le champ "Valeurs" en fonction des champs cachés
	.on("change", ".formConfigType", function() {
		var _this = $(this);
		switch (_this.val()) {
			case "select":
				_this.parents(".formConfigInput").find("label[for*=formConfigRequired]").show();
				_this.parents(".formConfigInput").find(".formConfigValuesWrapper").slideDown();
				_this.parents(".formConfigInput").find(".formConfigLabelWrapper").slideUp();
				break;
			case  "label":
				_this.parents(".formConfigInput").find("label[for*=formConfigRequired]").hide();
				_this.parents(".formConfigInput").find(".formConfigLabelWrapper").slideDown();
				_this.parents(".formConfigInput").find(".formConfigValuesWrapper").slideUp();
				break;
			default:
				_this.parents(".formConfigInput").find("label[for*=formConfigRequired]").show();
				_this.parents(".formConfigInput").find(".formConfigValuesWrapper").slideUp();
				_this.parents(".formConfigInput").find(".formConfigLabelWrapper").slideUp();
		}
	});
// Simule un changement de type au chargement de la page
$(".formConfigType").trigger("change");
