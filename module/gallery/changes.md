# Version 7.5
- Utilisation des constantes Core pour la largeur des vignettes (THUMBS_WIDTH).

# Version 7.4
- Compatibilité php 8.5
- Corrige l'affichage « nom de la galerie sans les images » pour une page dupliquée : la duplication copie le dossier de données de l'instance d'origine, dont la feuille de style est limitée à l'ancienne instance. La feuille de style est maintenant vérifiée (présente et limitée à l'instance) à chaque affichage et régénérée si besoin.
- Le chemin de la feuille de style est déduit de l'identifiant de l'instance et non plus de la valeur enregistrée, qui peut désigner une autre instance après une copie.
- Génération de la feuille de style : une valeur de thème absente ou vide est remplacée par la valeur par défaut (une variable CSS vide supprimait la hauteur des vignettes) ; theme() transmet directement les réglages saisis.
# Version 7.3
- Autorise plusieurs modules Galerie sur une même page : feuille de style du thème limitée au conteneur de l'instance (jeton #scope# dans les modèles CSS, migration des données en 3.2), identifiants HTML et script de la visionneuse propres à chaque instance, index des galeries lisant les données de son instance.
- sortGalleries() et sortPictures() : permission d'édition requise, vérification de l'existence de la galerie et des images, seules les positions sont écrites (l'option showPageContent n'est plus perdue après un tri), noms de fichiers encodés correctement décodés, suppression du code de test.
- theme() : fusionne les réglages avec le thème existant et supprime la clé showUniqueGallery sans effet.
- Miniatures : seul le segment « source » de la racine du gestionnaire de fichiers est remplacé par « thumb », les dossiers dont le nom contient « source » ne sont plus altérés.
- add() : l'image de couverture est enregistrée avec le nom réel du fichier.
- La classe des valeurs par défaut est renommée galleryDefaultData (la classe theme existe aussi dans le cœur), versionData initialisé à 3.2.
# Version 7.2
- Ajoute la constante MULTIMODULE pour autoriser la présence de plusiers modules dans une même page.
# Version 7.1
- Corrige un warning après ajout d'une galerie.
# Version 7.0
- La liste des dossiers des champs de sélection est désormais générée côté serveur, suppression de l'appel Ajax et de son rafraîchissement automatique.
# Version 6.0
- Réécriture de la méthode générant l'index des galeries et l'affichage d'une galerie.
# Version 5.2
- Corrige le chemin des ressources insérés par des membres éditeurs.
# Version 5.1
- Renforcement des contrôles de la méthode add().
# Version 5.0
- Les dossiers proposés par le module sont ceux autotisés par le profil.
# Version 4.5
- Supprime une message de debogage.
# Version 4.4
- Numéro d'ordre des galleries.
# Version 4.3
- Remplacement du nom générique de classe dans les vues.
# Version 4.2
- Contrôle de la variable de session liée au contenu. Evite des erreurs lorsque plusieurs onglets sont ouverts.
# Version 4.1
- Termes des commandes de profils
# Version 4.0
- Gestion des permissions intégrée dans le module
# Version 3.9
- Bloque l'effacement de la galerie selon le profil
- Masque le code de vérification
# Version 3.8
- Version compare null, dataversion not initialize
- Bug de positionnement des boutons de retour
# Version 3.7
- Multilinguisme
# Version 3.61
- Corrige le retour de $success  en lecture écriture de fichier
# Version 3.6
- Appel de la feuille de style si présente