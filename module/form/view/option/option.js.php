


/*
* Affiche/cache les options de la case à cocher du mail
*/
$("#formOptionMailOptionsToggle").on("change", function() {
   if($(this).is(":checked")) {
       $("#formOptionMailOptions").slideDown();
   }
   else {
       $("#formOptionMailOptions").slideUp(function() {
           $("#formOptionGroup").val("");
           $("#formOptionSubject").val("");
           $("#formOptionMail").val("");
           $("#formOptionUser").val("");
       });
   }
}).trigger("change");

/**
* Affiche/cache les options de la case à cocher de la redirection
*/
$("#formOptionPageIdToggle").on("change", function() {
   if($(this).is(":checked")) {
       $("#formOptionPageIdWrapper").slideDown();
   }
   else {
       $("#formOptionPageIdWrapper").slideUp(function() {
           $("#formOptionPageId").val("");
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
   if ($("#formOptionSignature").val() !== "text") {
       $("#formOptionLogoWrapper").addClass("disabled");
       $("#formOptionLogoWrapper").slideDown();
       $("#formOptionLogoWidthWrapper").addClass("disabled");
       $("#formOptionLogoWidthWrapper").slideDown();
   } else {
       $("#formOptionLogoWrapper").removeClass("disabled");
       $("#formOptionLogoWrapper").slideUp();
       $("#formOptionLogoWidthWrapper").removeClass("disabled");
       $("#formOptionLogoWidthWrapper").slideUp();
   }
});

/**
* Masquer ou afficher la sélection du logo
*/
var formOptionSignatureDOM = $("#formOptionSignature");
formOptionSignatureDOM.on("change", function() {
   if ($(this).val() !== "text") {
           $("#formOptionLogoWrapper").addClass("disabled");
           $("#formOptionLogoWrapper").slideDown();
           $("#formOptionLogoWidthWrapper").addClass("disabled");
           $("#formOptionLogoWidthWrapper").slideDown();
   } else {
           $("#formOptionLogoWrapper").removeClass("disabled");
           $("#formOptionLogoWrapper").slideUp();
           $("#formOptionLogoWidthWrapper").removeClass("disabled");
           $("#formOptionLogoWidthWrapper").slideUp();
   }
});