


/*
* Affiche/cache les options de la case à cocher du mail
*/
$("#formLayoutMailOptionsToggle").on("change", function() {
   if($(this).is(":checked")) {
       $("#formLayoutMailOptions").slideDown();
   }
   else {
       $("#formLayoutMailOptions").slideUp(function() {
           $("#formLayoutGroup").val("");
           $("#formLayoutSubject").val("");
           $("#formLayoutMail").val("");
           $("#formLayoutUser").val("");
       });
   }
}).trigger("change");

/**
* Affiche/cache les options de la case à cocher de la redirection
*/
$("#formLayoutPageIdToggle").on("change", function() {
   if($(this).is(":checked")) {
       $("#formLayoutPageIdWrapper").slideDown();
   }
   else {
       $("#formLayoutPageIdWrapper").slideUp(function() {
           $("#formLayoutPageId").val("");
       });
   }
}).trigger("change");

/**
* Paramètres par défaut au chargement
*/
$( document ).ready(function() {

   /**
   * Masquer ou afficher la sélection du logo
   */
   if ($("#formLayoutSignature").val() !== "text") {
       $("#formLayoutLogoWrapper").addClass("disabled");
       $("#formLayoutLogoWrapper").slideDown();
       $("#formLayoutLogoWidthWrapper").addClass("disabled");
       $("#formLayoutLogoWidthWrapper").slideDown();
   } else {
       $("#formLayoutLogoWrapper").removeClass("disabled");
       $("#formLayoutLogoWrapper").slideUp();
       $("#formLayoutLogoWidthWrapper").removeClass("disabled");
       $("#formLayoutLogoWidthWrapper").slideUp();
   }
});

/**
* Masquer ou afficher la sélection du logo
*/
var formLayoutSignatureDOM = $("#formLayoutSignature");
formLayoutSignatureDOM.on("change", function() {
   if ($(this).val() !== "text") {
           $("#formLayoutLogoWrapper").addClass("disabled");
           $("#formLayoutLogoWrapper").slideDown();
           $("#formLayoutLogoWidthWrapper").addClass("disabled");
           $("#formLayoutLogoWidthWrapper").slideDown();
   } else {
           $("#formLayoutLogoWrapper").removeClass("disabled");
           $("#formLayoutLogoWrapper").slideUp();
           $("#formLayoutLogoWidthWrapper").removeClass("disabled");
           $("#formLayoutLogoWidthWrapper").slideUp();
   }
});