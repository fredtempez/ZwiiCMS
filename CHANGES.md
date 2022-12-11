# Changelog

## Version 11.5.13
### Corrections :
- Erreur de lien metaImages.
- Compatibilité PHP 8.1 du gestionnaire de fichiers.
### Modification
- TinyMCE, URL absolues, transformation autorisée en URL relative si effectuée manuellement.

## Version 11.5.12
### Modifications
- TinyMCE, Les URL relatives posent des problèmes lorsque le contenu de la page est lu hors l'URL de base. Les URL deviennent absolues, la transformation automatique inactivée.

## Version 11.5.11
### Corrections :
- Génération du flux RSS dans le module blog, URL des miniatures incorrects.
- Login, dépréciation de fonction avec php 8.1

## Version 11.5.10
### Correction :
- Dysfonctionnement de la classe strftime, setlocale mal défini.

## Version 11.5.09
### Corrections :
- Problème de génération de l'exemple du site.
- Dépréciations de fonctions PHP 8.1

## Version 11.5.08
### Corrections :
- Bugs divers et dépréciations PHP 8.1
- Ajout d'une classe spécifique strftime suite à sa dépréciation.

## Version 11.5.07
### Correction :
- Création du dossier des fontes personnalisées en cas d'absence.
### Amélioration :
- Détection d'une mise à jour.

## Version 11.5.06
### Corrections :
- Défaut d'affichage de la barre des membres dans la zone de menu.
- Chargement à l'unité des fichiers déposés dans le gestionnaire de fichiers suite à l'utilisation d'une dernière version de la librairie jquery. L'utilisation de la version 1.12.4 livrée avec le gestionnaire de fichiers corrige le problème. Cette librairie est néanmoins chargée en local par dialog.php
### Amélioration :
- Recherche d'une mise à jour en ligne effectuée réellement une fois pas jour lorsqu'un administrateur est connecté.

## Version 11.5.05
### Correction :
- Validation de la fenêtre de consentement au cookie envoyant vers une page inconnue lorsque l'URL contient plusieurs éléments (exemple : article d'un blog)
### Amélioration :
- Traitement des erreurs dans la gestion des fontes et de l'ajout d'une nouvelle fonte.

## Version 11.5.04
### Corrections :
- Édition d'une page : bug de sélection d'un module absent.
- Rechercher dans le site : impossibilité de rechercher dans le contenu des modules (news, blog et download)
### Amélioration :
- Récupération de la capture d'écran du site, 5 tentatives d'appels de l'API Google sont effectuées avant de retourner un échec.

## Version 11.5.03
### Correction :
- Bug de la génération des feuilles de style des fontes, nouvelle correction.
### Améliorations :
- Responsive File Manager (RFM), les scripts externes et les feuilles de style sont chargées à partir du site et non d'un CDN (jquery, fabric, filesaver et jplayer). Cette modification accélère le primo chargement de la fenêtre des fichiers.
- Fenêtre Lity agrandie à 90% de la largeur de la page, ce réglage s'applique également à RFM ainsi qu'à l'éditeur d'image intégrée.
- Edition d'une page contenant un module effacé sur le disque, la modification et l'effacement de la page sont autorisés.

## Version 11.5.02
### Corrections :
- Bug de la génération des feuilles de style des fontes.
- Bug dans le cookie de consentement lorsque le port n'est pas 80.

## Version 11.5.01
### Modifications :
- Restauration du bouton d'installation d'une archive de module depuis le store.

## Version 11.5.00
### Corrections :
- Ajout d'une nouvelle page, le nom court n'est pas défini.
- Bug de la fonction de copie interne utilisée lors de l'installation de la copie de thème, etc..
### Modifications :
- Le module de recherche analyse les descriptions du module Download (Téléchargement).
- Prise en compte des modifications liées à la mise à jour du module Download (Téléchargement), actualisation du changement de structure 'posts' remplace 'items'
- Restauration de la fonction de téléchargement à partir du store.

## Version 11.4.02
### Modification :
- Liste des fontes, contrôle de validité amélioré.

## Version 11.4.01
### Corrections :
- Défaut de chargement des fontes locales (ex: fichiers woff).
- Un clic sur le bouton de validation du panneau RGPD envoyait systématiquement vers la page d'accueil.
- Chargement des anciens fichiers d'aide absents.

## Version 11.4.00
### Nouveautés :
- Compatibilité avec PHP 8.1
- Prise en charge des fontes Web Safe. Les fontes initiales sont transférées dans les fontes optionnelles, donc effaçables.
- Toutes les fontes en ligne sont désormais acceptées quel que soit le CDN, Google Fonte (avec preconnect),  CDN Fontes ou autres.
- Désormais, les URL internes sont relatives, cela signifie qu'elles ne contiendront plus le domaine et le chemin d'accès au site. Cela permettra le déplacement d'un site d'un hébergement à un autre, d'un dossier d'hébergement à un autre, sans avoir à convertir les adresses internes. Les données d'un site mis à jour et importées d'une version antérieures sont automatiquement converties. En conséquence, le bloc de conversion de la fenêtre d'import est supprimé.
- Suppression temporaire de l'option d'installation d'un module, il faudra passer par une connexion FTP pour cela. Cette fonctionnalité a été réécrite pour la version 12.
### Améliorations :
- Configuration de la bannière, modalité d'affichage de la taille d'image recommandée et affichage des dimensions de l'image.
- Edition d'une page, le nom court se complète automatiquement.
- Configuration de la connexion, une option autorise l'affichage de la page de connexion lorsqu'une page de gestion du site est demandée:  'user', 'theme', 'config', 'edit', 'translate', 'addon'.
- L'option de réécriture d'URL n'est pas plus active avec le serveur Nginx.
- Galerie, version 3.5 :
    - Nouvelle structure anticipée sur la version 12, le formulaire d'ajout de la galerie est séparé de la liste des galeries du module.
    - Lorsque la galerie n'en contient qu'**une seule galerie**, elle peut être affichée directement, la liste des galeries étant ignorée. Pour cela, activer cette option dans les options de la galerie.
    - Le contenu de la page peut désormais être affiché avec le contenu de la galerie sélectionnée. Ce paramètre se gère au niveau de chaque galerie.
    - Déplacement du bouton de retour à la liste des galeries en bas de l'écran.
### Corrections :
- URL Rewrite Apache, bug d'interprétation d'activation de la réécriture d'URL lorsque des données ont été inscrites après la ligne servant de délimiteur  *# URL rewriting* dans le fichier htaccess.
- Module Galerie : correction de bugs, tri des images, erreurs d'affectation.
- Module Blog : taille recommandée de l'image erronée lorsque la largeur de l'écran est réglée sur fluide (100%).
- Gestion des pages : positionnement dans le menu accessoire ou dans le menu standard.
- Safari sur Mac, bug avec les cookies qui ne sont pas stockés.
- Nettoyage du code.
### Mise à jour :
- TableDND, script JQUERY de tri de tables utilisé par la galerie passe en version 1.0.5
- PHPMailer 6.6.0

## Version 11.3.07
### Correction :
    - Module galerie, option plein écran inopérante.
### Amélioration :
    - Module galerie, lorsque le module ne contient qu'une galerie, la page listant les galeries est omise.
### Modification :
    - Neutralisation du téléchargement depuis le catalogue.

## Version 11.3.06
### Corrections :
    - Bug d'affichage des blocs de présentation dans la configuration du site.
    - Double déclaration d'une fonte locale.
### Améliorations :
    - Sauvegarde des fontes avec le thème.
    - Une fonte Websafe remplace une fonte locale dont le fichier n'est pas disponible.

## Version 11.3.05
### Correction :
    - Dossier du fichier de fontes non créé empêchant la création du fichier des appels de fontes.

## Version 11.3.04
### Correction :
    - Duplication d'id dans le menu.
### Amélioration :
    - Chargement des fontes optimisé, le dossier data/fonts contient un nouveau fichier fonts.html contenant les url des fontes à télécharger. Ce fichier est généré à chaque modification du thème.


## Version 11.3.03
### Modifications :
    - Suppression du thème administration dans le menu du thème.
    - Position d'une page dans le menu accessoire, ordre des pages dans le menu de sélection.
    - Boutons d'aide dans la page de sélection des fontes.


## Version 11.3.02
### Corrections :
     - Importation d'une police sur cdnFonts impossible, nom de fonction incorrect.
     - Thème moderne, url de l'image corrigé
     - Thème, import d'un thème sauvegardé, conversion des fontes Google.


## Version 11.3.01
### Corrections :
    - Gestionnaire de fichier, chevauchement d'icônes en multi sélection et aides non traduites.
    - Fontes : utilisation d'une adresse d'import de fonte HTTPS


## Version 11.3.00
### Nouveautés :
    - Police de caractères :
        - Changement de fournisseur, CdnFonts remplace Google Font.
        - Les polices pourront désormais être téléchargées à partir du site et non du CD grâce à une nouvelle fonctionnalité du thème permet de gérer l'installation des fontes, soit à partir du CDN, soit à partir d'un fichier téléchargé.
    - Pages dans le menu accessoire. Ce menu à affiché à droite de la barre de menu, il est traditionnellement utilisé pour y placer les drapeaux de traduction, le bouton de connexion et de gestion du compte des membres. Il sera désormais possible d'y placer des pages sous la forme d'icônes de préférence.
    - Prise en charge du format webp pour les modules nécessitant des miniatures.
### Améliorations :
    - Thème / Bannière : ergonomie de l'information sur l'image sélectionnée.
    - Identifications des éléments du menu, les pages parents prennent comme id CSS leur id, les pages enfants également et pour classe Id de la page parente.
### Corrections :
    - Thème / site : problème d'aperçu du body ; police du thème admin non chargée.
    - Bugs avec les aperçus des sélecteurs de fontes.
    - Notice générée par l'effacement d'une page sans module.
    - Modules blog et download : filtrage excessif des tags breaking et saut de paragraphe.
    - Mise à jour en ligne, une version  inférieure ne déclenche plus la notification.

## Version 11.2.05
- Corrections :
    - Configuration / localisation : Les champs de cookies ne devraient pas être obligatoires lorsque la case à cocher de consentement des cookies n'est pas sélectionnée.
    - Disparition de l'icône de gestion du compte pour le profil éditeur.

## Version 11.2.04
- Correction  :
    - Affiche une notification de mise à jour si la numérotation de la version en ligne est supérieure à celle installée.

## Version 11.2.03
- Corrections :
    - Addon, bug lors de l'effacement d'un module non initialisé.
    - Flatpickr, le sélecteur de date n'affiche pas l'heure et la minute, réinstallation temporaire de la version 4.6.3.
    - Thème ; pied de page ; option pied de page fixe inopérante.
    - Edition des pages orphelines : "Ne pas afficher" une page contenant des sous-pages provoquait un bug d'affichage dans le menu, la page était malgré tout affichée en fin de menu. Ce problème était causé par les pages enfants dont l'affichage n'était pas modifié. Le correctif cascade l'option "Ne pas afficher" aux sous-pages. La réciproque n'est pas appliquée, il faudra rendre visible les sous-pages d'une page parente qui devient à nouveau visible.
- Modifications :
    - Addons (gestion des modules), le bouton d'accès au store est déplacé à la page de gestion des modules. Quelques étiquettes de boutons sont modifiés.
    - Thème ; pied de page ; options pied de page fixe et alignement avec le contenu déplacées dans les paramètres.
    - Mise à jour en ligne, contrôle de la clé MD5 de l'archive update.tar.gz.

## Version 11.2.02
- Correction :
    - Mise à jour : les noms des étiquettes du popup des cookies n'étaient pas créés.
- Modifications :
    - Configuration du fichier htaccess :
        - Suppression de la redirection forcée vers HTTPS lors de l'activation des URL intelligentes.
    - Configuration :
        - Le numéro de version est désormais affiché dans le bloc Mise à jour.
        - Le bloc Mise à jour dans l'écran de configuration est modifié ; les numéros de version installée et en ligne sont affichés si disponibles.
        - Le libellé du bouton de Réinstaller devient Mettre à jour selon que le numéro de la version installée diffère.
- Système d'aide :
    - Affichage d'un popup d'information sur les boutons qui envoient vers l'aide en ligne.

## Version 11.2.01
- Mises à jour :
    - jQuery v3.6.0
    - Lity v2.4.1
    - Lightbox v2.10.1
    - Faltpickr v4.6.9
    - FavIcon Switcher v1.2.2
- Corrections :
    - Configuration, restauration d'une archive du site :
        - la validation du formulaire sans avoir sélectionné de fichier de sauvegarde provoquait le crash du site.
        - la conversion des URL des ressources ne fonctionnait plus depuis l'externalisation du contenu des pages dans des fichiers séparés.
    - Multi-langues :
        - Bug auto détection du navigateur.
    - Page site map, correction d'erreurs et rénovation de la présentation.
- Modifications :
    - Gestion des cookies :
        - Options de personnalisation du message d'acceptation des cookies, acceptation ou refus du cookie Google Analytics, affichage de la page des mentions légales.
        - Etiquette dans le footer permettant d'afficher la popup des cookies.
    - Thème :
        - Disposition des options de configuration du site.
        - Bannière : le contenu peut être personnalisé à l'aide d'un éditeur. La bannière au-dessus du site peut s'étendre sur la largeur de la page.
    - Pages : il est désormais possible de donner un nom de page court utilisé dans le menu du site, dans les barres latérales et dans les sélecteurs de page (éditeur / lien). En revanche le nom de la page affiché en haut de celle-ci est inchangé. Dans la plupart des cas le titre court sera identique au titre.
    - Les écrans d'aide renvoient vers le site doc.zwiicms.fr
    - Mise en évidence du statut des pages dans la liste de la barre d'administration. Rouge italique = page orpheline ; Orange gras = page inactive.
    - Référencement, l'URL de la page d'accueil (www.site.fr/accueil) est remplacée par la base Url du site (www.site.fr/) afin d'éviter la duplication de contenu.

## Version 11.1.01
- Corrections :
    - Langues : bug de l'utilitaire de  copie de site.
    - Fichier robots.txt non fourni lors les bots ne sont pas autorisés.
    - Page parente ayant des pages enfants non affichées dans le menu : lors du renommage de la page, les pages enfants n'étaient pas actualisées.
    - Bugs de conformités.
- Modifications :
    - Dispositions des écrans du thème : site, menu, pied de page, menu et bannière.

## Version 11.1.00
- Corrections :
    - Problème lié à la migration vers la version 11. Création d'un dossier : les droits d'accès sont positionnés sur 0755 au lieu de la valeur par défaut 0777. Concerne également les modules Formulaire, Galerie, News, Search et d'autres modules optionnels.
    - Données d’initialisation, le numéro de version devient 11000 au lieu de 10602.
    - Thème / Bannière :
        - Bug d'aperçu au déplacement de la bannière et du menu au-dessous ou sous la bannière.
        - Option de menu burger désactivée par la validation de la bannière.
    - Module Formulaire : gestion des réponses, barre de pagination absente.
    - Gestionnaire de fichiers : droit des éditeurs sur les fichiers et les dossiers.
    - Sitemap : génération des URL sans slash.
- Modifications :
    - Langues étrangères :
        - Traduction des étiquettes Carte du site, Rechercher et Mentions légales dans le pied de page.
        - Refonte des pages de configuration et de la page du thème du pied de page.
        - La page courante reste sélectionnée après un clic sur un drapeau.
    - Thème :
        - Choix d'un thème à l'installation.
        - Livraison de quelques thèmes.
        - Pied de page, sélection directe d'une page spéciale sans passer par la configuration du site.
        - Bannière, masquage de la bannière sur les écrans inférieurs à 768px.
        - Menu, affichage du titre ou d'un logo sur les écrans inférieurs à 768px.
    - Configuration avancée / journalisation : une nouvelle option de mise en conformité avec la règlementation française qui impose de tronquer les adresses IP enregistrées dans les journaux. 4 options sont proposées : pas de troncage, troncages niveaux 1, 2 et 3.
    - Module galerie version 3.3, modification de l’initialisation du module.
    - URL intelligentes, ajout de la redirection vers le protocole HTTPS pour éviter les problèmes d'affichage de la barre d'administration.
    - Optimisation du script main.php

## Version 11.0.09
- Modifications :
    - Intégration de la classe layout dans la classe core
    - Gestion multi-langues
        - Traduction rédigée dans une langue européenne
        - Traduction automatique dans une langue européenne
        - Traduction selon la langue navigateur
    - Données de site :
        - Le contenu des pages est désormais stocké en dehors du fichier page.json mais dans des fichiers html placés dans un sous-dossier "content" du dossier de langue.
    - Droits des utilisateurs :
        - Le compte membre pourra disposer d'un répertoire partagé avec les administrateurs et les éditeurs.
    - Configuration du site :
        - Activation et désactivation de la déconnexion automatique empêchant plusieurs sessions avec le même compte.
        - Suppression de l'option articles par pages désormais intégrées aux modules.
        - Suppression de l'option de backup individuel des fichiers de données, pour l'activer manuellement, créez un fichier vide .backup dans site/data
        - Nouvelle option permettant de transférer les fichiers de sauvegarde automatiques dans le gestionnaire de fichiers.
        - Référencement :
            - Mise à jour du générateur de siteMap 4.3.2
            - Correction d'un bug avec la génération du site map
            - Ajout d'une option de configuration autorisant le référencement du site par les moteurs de recherche.
- Module News 3.4 :
    - Changement de nom, Actualités -> News
    - La version abrégée des articles dépend du nombre de caractères au lieu de la hauteur de la div. L'effet flou inutile est aussi supprimé.
    - Personnalisation du style, encadrement, et couleur de fond des blocs d'articles.


## version 10.6.03
- Correction :
    - Module actualités (news), version 3.3, le flux RSS affiche l'ensemble des articles plutôt que ceux de la page courante.
- Modifications :
    - Installation  : livraison des fichiers de thème du site de test dans une archive afin de ne plus différencier les archives d'installation et de mise à jour.
    -  Mises à jour : les mises à jour s'effectue à partir de la forge plutôt que sur le site zwiicms.fr


## version 10.6.02
- Correction :
    - Débordement  lors du téléchargement des fichiers journaux.

## version 10.6.01
Corrections :
    - Champs de formulaire, uniformisation de la hauteur (select, input, etc) identique à celle des boutons à 9px.
    - Module search : texte d'aide dans la zone de saisie non pris en compte.
Modifications :
    - News 3.2 : ajout au sélecteur de date d'un bouton d'effacement identique à celui du sélecteur de fichiers.
    - News 3.2 : décoloration du texte, modification de l'effet blur.

## version 10.6.00
Cette version apporte aux modules une autonomie complète par rapport au noyau.
    - mise à jour interne (fonction update)
    - feuille de style propres aux instances (fonction init)
- Modifications :
    - Nombre d'éléments ou d'articles par page : l'option est propre aux modules et n'est plus gérées dans la configuration du CMS.
    - Module Search : en l'absence de texte dans le bouton Rechercher, une loupe est affichée.
    - Module News : présentation des articles en colonnes.

## version 10.5.04
- Modifications :
    - Sitemap : tri des articles de blog selon la date de publication

## version 10.5.03
- Correction :
    - Options de configuration SMTP invisibles.
- Modification :
    - Libellé des erreurs 403 et 404.

## version 10.5.02
- Corrections :
    - Erreur de positionnement de marge dans le thème du site.
    - Erreur de syntaxe html dans les liens des flux RSS. S'ouvrent désormais dans un nouvel onglet.

## version 10.5.01
- Modifications :
    - Rechargement du fichier de style common.css et du jeu d'icônes du système après une mise à jour.
    - News 2.3 : ajout de la signature du rédacteur dans le module news (actualités, idem module blog)
    - Les bulles d'aide de page sont désormais contenues dans la page (modules langues v11 et addon).
Corrections :
    - Mode maintenance, titre non masqué d'une page personnalisée.
    - Ajustements des formats de dates dans les fichiers de backup.

## version 10.5.00
**A partir de cette version, les versions de php inférieures à 7.2 ne sont plus supportées.**
- Nouveauté :
    - Gestion des modules dans l'interface d'administration. Cette nouvelle fonctionnalité permet d'ajouter des modules en les téléchargeant et de les supprimer. Cette nouvelle fonctionnalité évite de recourir au FTP.  Les pages qui utilisent un module peuvent être exportées avec toutes leurs données, elles pourront ensuite être importées dans un site Zwii contenant ce même module.
- Corrections :
    - Mise à jour de la classe SiteMapGenerator 4.3.1
    - La classe SiteMapGenerator prend en charge la création/modification du fichier robots.txt
    - Si un proxy est défini dans la configuration, le sitemap n'est pas soumis aux moteurs de recherche afin d'éviter un timeout trop important.
- Modifications :
    - Modules blog et news, bouton d'édition des articles en mode connecté allégé.

## version 10.4.06
- Mise à jour :
    - Annulation de la mise à jour SiteMapGenerator 4.3.1 et réinstallation de la version initiale.
- Modification :
    - Méthode Curl pour la lecture de données par Url.

## version 10.4.05
- Mise à jour :
    - SiteMapGenerator 4.3.1
- Modifications :
    - Bouton de remontée, position plus haute et zindex augmenté.
- Corrections :
    - Marges du pied de page fixe placé en dehors du site.
    - TinyMCE couleurs du sélecteur de paragraphe et de headers lorsque le fond est transparent.
    - Thème administration, couleur du lien dans un bloc H4.

## version 10.4.04
- Correction :
    - Module Blog : balise non fermée dans les commentaires.
- Modifications :
    -Constantes de modules.

## version 10.4.03
- Corrections :
    - En-tête html : absence de la langue.
    - Suppression de la balise sémantique <article>.
    - Génération image Opengraph, mauvaise redirection.
    - Nouvelle structure de données articles de blog dans le sitemap.

## version 10.4.02
- Corrections :
    - Thème : aperçu du site amélioré.
    - Thème : rétablissement du contrôle de l'import d'une version ancienne d'un thème.
    - Éditeur de texte : couleur de fond parasite quand une image en arrière-plan est sélectionnée.

## version 10.4.01
Corrections :
    - Module form, erreur de syntaxe.
    - Chargement d'un thème, désactivation du contrôle des clés.

## version 10.4.00
Corrections :
    - Bug de pages non accessibles dans le menu suite à la création d'entrées fantômes dans le fichier de données de modules.
    - Nettoyage du fichier des données de modules des entrées fantômes.
    - Thème : déformation des images en arrière-plan dans les modes responsives cover et contain lorsque la longueur de la page change. L'image en arrière-plan est désormais placée dans la balise html et l'option fixe s'active lorsque cover ou contain sont sélectionnés.
    - Thème : déformation du sélecteur de fichiers dans certains formats de page.
    - Module Form : mauvais affichage du guillemet et de l'apostrophe dans les noms des champs lors de l'édition.
    - Thème : faille CSRF, protection de la réinitialisation des fichiers de thème.
- Modifications :
    - Filtrage des URL générées par facebook (FBCLID) occasionnant une erreur 404
    - Captcha arithmétique, activation recommandée dans la configuration.
    - Module User
        - Pour les articles de blog et de news, choix de la signature, nom+prenom ; nom+prenom ; id ; pseudo
        - Importation d'une liste d'utilisateur dans un fichier plat (CSV).
    - Module Blog :
        - Texte du commentaire enrichi.
        - Nombre maximal de caractère par commentaire.
        - Gestion des commentaires article par article.
        - Suppression des commentaires en masse.
        - Limiter l'édition des articles et des commentaires à l'id de l'éditeur
        - Approbation des commentaires
    - Gestion des thèmes :
        - Bouton de réinitialisation avec confirmation
    - Amélioration de la structure du flux RSS.


## Version 10.3.13
Modifications :
    - Système de données (JsonDB) :
        - Verrouillage des fichiers de données ouverts en écriture.
        - Message d'erreur littéral.
        - Sauvegarde des fichiers de données après un effacement et une écriture.
        - Identifiant des noms de ressources (id de page , d'utilisateur, etc..) composés de nombres , remplacement du caractère de préfixe "i" par "_".
    - Google Analytics, option d'anonymisation.
    - Procédure de connexion : les erreurs de captcha sont comptabilisées comme des échecs, allégement des messages d'information. Echecs de connexion, informations plus précises dans le journal de connexion.
    - TinyMCE : ajout des scripts possibles.
Corrections :
    - Notification de commentaire, remplacement du nom de la page par le titre de l'article.
    - Thème : couleur du texte au survol d'un bouton standard.

## Version 10.3.12
Correction :
    - Impossibilité de lister les commentaires des articles de blog dans la fenêtre de gestion.

## Version 10.3.11
Modification :
    - Message sur l'utilisation des cookies
Correction :
    - Recherche inopérante dans les articles des blogs.

## Version 10.3.10
Corrections :
    - Conflit page inactive et droit d'un membre.
    - Module de recherche, correction dans les pages enfants.
    - Module formulaire, perte des données en cas d'édition du formulaire ou des champs.
Modification :
    - TinyMCE nettoyage init.js d'options non supportées.

## Version 10.3.09
Corrections :
    - Configuration : persistance de l'ouverture des blocs.
    - Réinitialisation du mot de passe :
        - Remise à zéro du timer après renouvellement du mot de passe.
        - Affichage de le fenêtre "Nouveau mot de passe" allégée.
        - Redirection sur la page d'accueil.
    - Modules news et blog : transparence icône RSS.
    - Position de l'écran de configuration

## Version 10.3.08
Corrections :
    - Notification de mise à jour d'update bloqué
    - Backup  : double commentaire entraînant un bug d'affichage

## Version 10.3.07
- Petites corrections
- Bouton format dans TinyMCE

## version 10.3.06
- Correction :
    - Édition de page avec module, le changement de mise en page désactive le bouton d'option du module.
- Modification :
    - Modules News et Blog : ajout de l'option flux RSS. L'option est activée par défaut.

## version 10.3.05
- Correction :
    - Thème : import d'un thème d'administration impossible.

## version 10.3.04
- Corrections :
    - Warning lors d'une primo installation.
    - Position des entrées de menu sur les écrans de smartphone.
    - Thème : import d'un thème, d'administration impossible.
    - Thème : import d'un thème, nettoyage du dossier tmp.
    - Thème administration : modification de l'appel du code CSS permettant une mise à jour du thème sans vider le cache.
    - Thème du menu : alignement inopérant ; arrière-plan semi-transparent non uniforme.
    - Supprime les warning lors d'une primo installation.
    - Inversion des icônes + et - dans la configuration
- Améliorations :
    - Thème menu : couleurs du sous-menu.
    - Mise en page du corps des mails de notification.
    - Paramètres de localisation.
    - Maintenance terminée, redirection vers l'accueil.
    - Marge haute dans les vues en mode light.
- Modifications :
    - Changement de noms de classe :
        - navLevel1 devient navMain
        - navLevel2 devient navSub
- Mise à jour :
    - TinyMCE 4.9.11

## version 10.3.03
- Correction :
    - Localisation, affichage des accents, LC_TIME devient LC_ALL
- Modifications :
    - Configuration des captchas. Addition simple de 0 à 9.
    - Une icône remplace le lien Connexion dans le menu et dans le pied de page.
    - Barre de membre déplacée à droite de la barre de menu.

## version 10.3.02
- Corrections :
    - Icône de pied de page github manquante.
    - Mauvaise redirection après changement de mot de passe d'un membre.
- Modifications :
    - Nouvelles images de captcha.
    - Option de configuration, captcha demandé à la connexion.

    - Méthode d'encodage UTF8.

## version 10.3.01
- Corrections :
    - Configuration du site :
        - Filtrage des pages affichées dans la configuration et initialisation après une mise à jour.
        - Pages de recherche et des mentions vides, modification de la valeur en l'absence de choix.
        - Libellés des pages d'erreur non sélectionnées "Page par défaut"
    - Erreur de position du menu fixe en haut de page des membres simples et dans après une déconnexion automatique
- Modifications :
    - Thème : import d'une archive de thème, vérification de la cohérence du contenu avant son installation.
    - Configuration : suppression du bloc des versions, affichage de la version de ZwiiCMS dans le bloc Informations générales.

## version 10.3.00
- Corrections :
    - Incrémentation de l'id de page bloquée lorsque deux pages ont le même nom.
    - Login : l'option "Se souvenir de moi" est fonctionnelle.
    - Menu : déplacement de la classe "active".
    - Le titre dans la configuration du module non affiché si le titre de la page est masqué.
    - Masque de saisie : formulaire validé malgré la présence d'une notice d'erreur
    - Classe jsonDb, suppression de la réinitialisation de la structure de données en cas d'absence du fichier.
- Modifications :
    - Noyau :
        - Mise en cache des données des modules.
    - Module recherche :
        - La recherche dans le site devient un module externe plutôt qu'un module interne ;
        - Diverses corrections optimisations permettant une recherche à l'aide de plusieurs mot-clés.
    - Module galerie :
        - Les données du thème de galerie sont désormais stockées de manière unique, un seul thème par site pour toutes les galeries d'un même site.
    - Configuration du site :
        - Pages 403 (accès interdit), 404 (page introuvable) et site en maintenance personnalisables
        - Sauvegarde du site dans une archive : animation d'attente avec message de confirmation ou d'erreur ; le nom de l'archive prend le nom du sous-domaine s'il existe.
    - Captcha :  addition présentée en lettres sous la forme d'images, réponse en chiffres ; correction du nom de la fonction (captcha en captcha).
    - Page :
        - Duplication d'une page.
- Mise à jour :
    - Script favicon-switcher 1.2.2

## version 10.2.09
- Correction :
    - Sécurisation de la fonction d'enregistrement des données.

## version 10.2.08
- Correction :
    - Bug pageId, régression corrigée.

## version 10.2.07
- Correction :
    - Défaut de chargement de flatpickr dans le module formulaire qui passe en version 2.4

## version 10.2.06
- Corrections :
    - Anticipation de la dépréciation de l'option de cookie samesite=none.
    - Warning : absence de fichier map dans le thème TinyMCE lightgray.

## version 10.2.05
- Correction :
    - Champ date non affiché sous Chrome.

## version 10.2.04
- Mise à jour :
    - Flatpickr 4.6.3
- Correction :
    - Connexion avec un compte inexistant, notification incorrecte.
- Modifications :
    - Position de l'icône d'ouverture et de fermeture des blocs.
    - Thème administration, bouton standard couleur du texte au survol.

## version 10.2.03
- Corrections :
    - Les entrées de menu disposent d'une classe par groupe de parent en lieu et place des ids.
    - Édition du compte de l'utilisateur, empêcher le pré-remplissage de l'ancien mot de passe.
    - Reformulation du mail de confirmation d'inscription.
    - Champ de sélection de fichiers, suppression de la couleur des URL lors d'un survol
- Modifications :
    - Sécurisation des deux cookies d'authentification (options httpOnly et secure).
    - La couleur du texte des headers avec un lien est celle des titres et non des liens.

## version 10.2.02
- Corrections :
    - Problème d'affichage du gestionnaire de fichier sous Safari.
    - Configuration, favicon impossibles à sélectionner.

## version 10.2.01
- Corrections :
    - Optimisation et correction de l'algorithme de contrôle d'accès.
    - Erreur des noms de champ barre des membres dans le pied de page.
    - Génération de l'image tag, amélioration du code et du message d'erreur.
    - Édition de page, erreur lors de la sélection d'une icône de menu.
    - Problème lors de l'installation, impossibilité d'obtenir l'écran de configuration.

## version 10.2.00
- Mise à jour :
    - jQuery v3.5.1
- Nouveautés :
    - Gestion des accès concurrents :
        - deux utilisateurs ne peuvent accéder en modification à la même page du site ou de configuration
        - la connexion d'un utilisateur sur un autre poste ou navigateur déconnecte la session précédente.
        - sécurisation du login
        - journalisation de l'utilisation du site
    - Écran de configuration et d'édition des pages, les blocs sont pliables et dépliables afin d'alléger l'occupation sur l'écran. Le statut des blocs (fermés ; ouverts) est persistante au cours de la session.
- Modifications :
    - Thème, les sélecteurs de couleur affiche la valeur RGBa d'une couleur différente de celle de la sélection.
    - Thème de l'administration, amélioration du rendu.
    - Image tag :  adaptations suite à la modification de l'API Google.
    - Installation automatique d'une mise à jour en ligne : un nouvelle option de configuration permet de conserver
    le fichier htaccess afin de préserver les modifications nécessaires à certains hébergeurs.
    - Suppression de la barre de membre (membres simples) et déport des options dans le menu.
    - Module Blog 2.02 : homogénéisation des interfaces.
    - Module Gallery 2.26 : largeur proportionnelle des images.
- Corrections :
    - Configuration, favicon clair et sombre : le bouton d'effacement initialise les deux champs.
    - Amélioration de l'adaptation aux thèmes sombres.
    - Erreur bouton Retour lors de l'édition du compte par un membre simple ou un éditeur.

## version 10.1.04
- Correction :
    - Warning après modification du thème du site.

## version 10.1.03
- Mise à jour :
    - Responsive File Manager : chargement impossible de certaines images JPEG.

## version 10.1.02
- Corrections :
    - free.fr : désactivation totale de la fonction de récupération de données en ligne (update, image tag, etc..)
    - Image Tag absente : non régénérée au lancement de la configuration du site, image masquée dans si absente.

## version 10.1.01
- Correction :
    - Extension image tag.

## version 10.1.00
- Nouveautés :
    - Distinction entre le thème du site et celui de l'administration. Sauvegarde et restauration de l'un ou de  l'autre.
    - Thème du site :
        - Amélioration de l'aperçu du thème du site et de body.
        - Couleur de l'encadrement et la bordure des blocs.
        - Couleur du texte de la page active
    - Menu : les entrées de menu disposent d'un id afin de faciliter la personnalisation CSS
- Corrections :
    - Configuration SMTP : sur-cryptage du mot de passe.
    - Warning dans la génération du sitemap en l'absence d'article.
    - Quelques corrections liées à l'hébergeur Free.
    - Configuration: sauvegarde automatique non enregistrée.
    - Warning lors de la création du dossier thème
- Modifications :
    - Optimisation des opérations de disque, mise en cache en lecture des données de pages. Aucun cache en écriture.
    - Compatibilité des URL avec Microsoft IIS (c)
- Mise à jour :
    - TinyMCE 4.9.10

## version 10.0.092
- Nouveautés :
    - Compatibilité avec l'hébergeur free.fr
    - Configuration :
        - Options de réglage d'un serveur SMTP pour l'envoi des emails.
        - Édition des scripts pour head et body dans une fenêtre dédiée.
    - Thème :
        - Thème des boutons des pages d'administration.
- Modification :
    - Masque de configuration : changement de libellés.
- Scripts externes:
    - Suppression du script fullPage.js
    - Ajout de l'extension SMTP de PHPMailer

## version 10.0.091
- Mises à jour :
    - SimpleLightBox v2.1.4
    - TinyMCE v4.9.9
    - PHPMailer 6.1.5
- Améliorations :
    - Architecture de stockage des données.
        - Les données sont désormais stockées dans des fichiers distincts (core, config, theme, user, page et module).
        - Les données relatives aux pages et aux modules sont stockées dans un dossier localisé fr par défaut en préparation de la version multi-langues.
    - Gestion des données :
        - Le système ne conserve plus en mémoire l'intégralité des données de site comme dans les versions précédentes.
        - Les données du site sont chargées à la demande au lieu d'être lues dans leur intégralité.
        - Les mises à jour et effacements sont appliquées en direct sur le disque.
- Modifications :
    - Module gallery optimisé, tri dynamique, choix du thème.
    - Module blog présentation optimisée avec options de position de l'image, la méta-description est le contenu de l'article.
    - Chargement paresseux des images.
    - Édition de page : suppression de l'option d'ouverture dans une lity.
    - Protection des données des modules en cas de changement lors de l'édition d'une page.
Corrections de bug :
    - Mise à jour automatique : procédure modifiée, désactivée si allow_url_fopen = off sur le serveur

## version 9.2.28
- Corrections :
    - Mise à jour auto fonctionnelle
    - Décalage du thème hors de l'écran

## version 9.2.27
- Corrections :
    - Pages d'administration, thème spécifique
        - Thème inactif lorsque la réécriture est activée.
        - Couleur du texte des boutons gris dans l'interface d'administration.
    - TinyMCE :
        - La taille de la police et la couleur sont celles définies dans le thème du site.
        - Zone d'édition, padding de 5 px à gauche et à droite.
    - Barre, sélecteur de page : couleur et taille fixe de la police.
    - Supprimer les pointillés lors d'un clic sur une page dans le menu sous Firefox.

## version 9.2.26
- Corrections :
    - Amélioration de la gestion du thème administration.
    - TinymCE : n'affiche plus l'arrière-plan du site mais la couleur du fond de la page.
    - TinymCE : Menu sticky + options de barre d'outils

## version 9.2.25
- Corrections :
    - Décalage du site dans SimpleLightbox.
    - Zindex du pied de page en position fixe, sous la barre de consentement aux cookies.
- Modifications :
    - Désactivation de l'application du thème dans les pages d'administration. Création d'un aperçu dans Thème Site.
    - Optimisation configuration simpleLightBox.
    - Syntaxe colorée dans TinyMCE Codemirror.
    - Configuration barre d'outils et menu.
    - Module news : déplacement des styles dans common.css. Couleur de police de la signature dans custom.css-

## version 9.2.24
- Corrections :
    - Mauvaise configuration de SimpleLightBox
    - Thème : marges du menu en position en-dehors du site

## version 9.2.23
- Nouveautés :
    - Configuration du réseau : proxy http ou tcp sans authentification.
    - Menu burger remplacé pour une croix quand ouvert.
- Corrections :
    - Fonction magic_quotes dépréciée supprimée.
    - Mise à jour en ligne :
        - problème lors du stockage de décompte de la date de dernière vérification.
        - réinitialisation du décompte de vérification lors de l'activation de l'option.
    - Thème, Menu :
        - Problème avec le menu fixe en-dehors du site et la barre d'outils de TinyMCE sous le menu. Solution, en édition de page l'option de menu fixe est temporairement désactivée.
        - Alignement avec le contenu du menu dans le site incorrecte.
        - Disparition de la position et de l'alignement du menu.
- Modifications :
    - TinyMCE : libellé des fonctions  "Afficher dans"
    - TinyMCE : nouvelle organisation de la barre d'outils.
    - Module Form : option permettant d'ajouter le premier mail dans le formulaire au message de notification (Reply To) afin de répondre directement au message.
    - Configuration du site :
        - bouton affichant le numéro de la version en ligne.
        - uniformisation de la position des champs de saisie.
    - Galerie : position du champ de tri des images.
- Mise à jour :
    - SimpleLightBox passe en version 2.1.2

## version 9.2.22
- Modifications :
    - Aperçu de la police dans les sélecteurs.
    - Gestion du canal de mise à jour selon la version installée
    - Module Blog : position des boutons d'édition de l'article au-dessus des commentaires.
    - Module Gallery :
        - choix de la vignette d'album
        - Ordres de tri des images d'une galerie, ascendant, descendant ou sans
        - Tri, ordre naturel de la galerie et des images de la galerie
- Mises à jour :
    - Configurations Code Mirror pour TinyMCE et standalone

## version 9.2.21
- Correction :
    - Footer / Texte personnalisé : suppression des sauts de ligne et de paragraphe.
- Modification :
    - Thème / Body, couleurs de l'icône retour en haut de page perso personnalisables.

## version 9.2.20
- Corrections :
    - Footer / Texte personnalisé : problème d'alignement des colonnes
    - Variable non déclarée dans main.php provoquant un warning

## version 9.2.19
- Corrections :
    - CSS : marge bouton InputDelete.
    - Texte de la page des mentions légales du site d'exemple.

## version 9.2.18
- Corrections :
    - Conformité balise p dans span (footer).
    - Petites corrections.
- Modifications :
    - Installation par défaut : livraison d'une page de mentions légales.
    - Image du fond (body), options responsive cover et contain.
    - Réseaux sociaux, icône Youtube chaîne ou utilisateur.
    - Pied de page, position fixe dans le site.
    - Pied de page, option d'affichage des mentions légales disponible uniquement si une page est sélectionnée.

## version 9.2.17
- Correction :
    - Affiche le nom du la page plutôt que son id dans le fil d'ariane

## version 9.2.16
- Optimisation :
    - Sauvegarde manuelle des données de site (dossiers file et data).
- Modification :
    - Stocke la réécriture d'url dans baseUrl en cas de changement d'arborescence lors d'un transfert de site.
- Correction :
    - Problème lors de la mise à jour de la variable dataVersion.

## version 9.2.15
- Corrections :
    - Sauvegarde des données de site.
    - Couleur du titre de site dans le menu réduit.
    - L'effet de couleur de fond personnalisé d'une page sélectionnée dans le menu est limité aux pages parents.
- Améliorations :
    - Affichage du contenu seul d'une page du site dans une popup Lity sans menu, bannière et pied de page.
    - Éditeur de texte ; effet accordéon, les accordéons peuvent être tous refermés.
    - Thème ; menu : lorsque le menu est réduit, le titre du site peut être inséré à la gauche du menu burger.

## version 9.2.14
- Mise à jour :
    - Script d'upload du gestionnaire de fichiers
- Modifications :
    - Thème : optimisation des masques de saisie pour le site en largeur 750px.
- Corrections :
    - Thème : gestion d'erreur lors de l'import d'un thème issu d'une version inférieure.

## version 9.2.13
- Corrections :
    - Gestionnaire de fichiers, modifications des paramètres des miniatures.
    - Filtrage du nom des pages dans la fenêtre d'édition des pages.
    - Format de date dans le module Blog
    - Module Form :
        - correction des options de champ pour le type étiquette
- Modifications :
    - Suppression d'options inutiles dans l'édition d'une page de type de barre latérale.
    - Module Form :
        - édition  : champs d’options condensés
        - édition : ordre des champs dans le sélecteur

## version 9.2.12
- Modifications
    - TinyMCE :
        - Ajout d'un template effet accordéon.
        - Supprimer le filtrage des éléments.
        - Supprimer le forçage de l'affichage des médias à 100%
        - Activer le dimensionnement des médias
    - Module Form :
        - Étiquette de séparation
        - Checkbox retourne un astérisque plutôt que 1
    - Thème - Menu :
        - Couleur de fond de la page sélectionnée
        - Effet bord arrondi, page sélectionnée

## version 9.2.11
- Corrections :
    - Marge du pied de page par défaut 5px
    - Installation sans site exemple : suppression des barres latérales
    - Édition de page :
        - Affichage de l'option Fil d'ariane alors que le titre est masqué.
        - Page parente, l'option "ne pas afficher les pages enfants dans le menu horizontal" est incompatible avec une page désactivée : désactivation et masquage lorsque la page est désactivée.
        - Mauvais encodage des titres de pages perturbant l'affichage des caractères spéciaux ( ex: apostrophes ).
- Modifications :
    - Recherche d'une mise à jour en ligne, s'effectue une fois par jour et devient optionnelle.
    - Amélioration de l'écran d'édition des pages.
    - iframe responsive

## version 9.2.10
- Modifications préparatoires à la version 10 :
    - Lors de l'installation, stockage de l'url de base dans l'éventualité de la restauration d'un backup et de son installation dans une autre arborescence.
    - Modification des clés identifiant les légendes du module Gallery : suppression du point de séparation du nom de fichier de l'extension.
- Modifications :
    - Thème, bannière : nouvelle option de hauteur calculée à partir de la dimension de l'image sélectionnée.
    - Thème, bannière : informations sur l'image sélectionnée (largeur et hauteur).
    - Thème, pied de page :  réactivation de l'aperçu.
- Corrections :
    - Thème, bannière : problème empêchant la bannière d'être cliquable lorsque la hauteur "responsive" de la bannière était sélectionnée.
    - Responsive File manager : erreur empêchant l'extraction d'une archive ZIP.
- Mise à jour :
    - CodeMirror 5.49.2 et modification des modules installés

## version 9.2.09
- Corrections :
    - Module Formulaire, erreur lors de l'envoi d'un premier formulaire
    - Thème Pied de page , désactivation de l'aperçu du texte personnalisé

## Version 9.2.08
- Correction :
    - Édition de page : bug empêchant le paramétrage d'un module après un changement de gabarit.
- Modification :
    - Aide de l'édition des pages

## Version 9.2.07
- Modification :
    - Balise <object> responsive
    - Placement possible de tous les modules
    - Commande de placement libre des modules et du menu latéral [MENU] et [MODULE]

## Version 9.2.06
- Correction :
    - Validation html
    - Syntaxe du fichier robots.txt

## Version 9.2.05
- Correction :
    - Suppression totale de Swiper (dossier source et template Tinymce)

## Version 9.2.04
- Correction :
    - Conserver htaccess dans le dossier temp lors du nettoyage
- Suppression :
    - Swiper

## Version 9.2.03
- Corrections :
    - Menu fixe en dehors du site :
        - overlay du sous-menu activé au-dessus de la page
        - impossibilité de sélectionner un élément sous un sous-menu
    - Modules : les modes de gestion s'affichent en pleine page - réécriture du code.
    - Syntaxe du fichier main.php

## Version 9.2.02
- Correction :
    - Gestion d'erreur lors de l'installation automatisée d'une mise à jour

## Version 9.2.01
- Corrections :
    - Sauvegarde du thème : prise en compte du fichier custom.css
    - Édition de page : libellés
    - Thème ; footer : marges du pied de page placé hors du site
    - Thème ; footer : aperçu du texte personnalisé

## Version 9.2.00
- Nouveautés :
    - Module de recherche dans le pied de page
    - Mentions légales dans le pied de page
    - Les pages "Recherche" et "Plan du site" peuvent être appelées à partir de TinyMCE dans le menu lien.
    - Le gabarit du pied de page peut se paramétrer en colonnes et en lignes, de 1 à 3 blocs.
    - Gabarit de page, présentation asymétrique des barres latérales : 33% - 50% - 16% et inversement
- Améliorations :
    - Gestion des sous-menus : suppression de l'option de masquage des pages dans le menu horizontal
    - Remise à plat et homogénéisation des masques d'édition des pages, footer et header
    - TinyMCE la fenêtre lien propose le sitemap et le module de recherche
- Correction :
    - Menu : alignement avec le contenu, couleur de l'arrière-plan

## Version 9.1.14
- Correction :
    - Validation w3C : espace manquant

## Version 9.1.13
- Corrections :
    - Erreur du sitemap.xml lorsqu'un blog ne contient pas d'article.
    - OpenGraph : erreur lors de la suppression de l'imagette si absente.

## Version 9.1.12
- Amélioration :
    - Contrôle d'erreur dans la gestion de l'imagette OpenGraph
- Correction :
    - Sitemap.xml : prendre en compte les sous-pages d'une page parente masquée

## Version 9.1.11
- Correction :
    - Générateur de sitemap.xml, correction de syntaxe.

## Version 9.1.10
- Améliorations :
    - Page sitemap et sitemap.xml : les articles de blog avec le statut brouillon sont masqués.
    - Sitemap.xml : ajout de la date de publication des articles.
    - Réseau social : Github.
- Correction :
    - Suppression du ? dans les URLs vers les fichiers sitemap  de robots.txt

## Version 9.1.09
- Améliorations :
    - Mise en page petits écrans, modification des marges
    - Configuration du site : scripts dans header et body
    - Nouvel écran de configuration
    - Ajoute la compression gzip et deflate dans htaccess
    - Sitemap (page et sitemap.xml) revu et corrigé :
        - Prends en compte les articles de blog
        - Affiche les pages désactivées sans lien
        - Prends en compte les droits de l'utilisateur
- Corrections :
    - Déclaration de localisation manquante dans mail.php
    - Bug avec le formulaire
    - Désactivation url upload dans RFM

## Version 9.1.08
- Corrections :
    - Validation du code html et du CSS commun
    - Réécriture activée après chaque mise à jour auto.
- Modifications :
    - Thème 100%  fluide sans marge
    - Écran de smartphone (ex : iPhone 6) : adaptation de la barre d'administration : le username est masqué et la taille des icônes est augmentée
    - Chemins vers les données dans des constantes
    - Modèles de bannières de plusieurs dimensions
    - Hauteur de police par défaut 13px
- Mises à jour :
    - TinyMCE 4.9.4
    - PHPMailer 6.07
    - Jquery 3.4.1

## Version 9.1.07
- Correction :
    - Ajout d'un utilisateur : autres contrôles avant envoi d'un mail de confirmation
- Suppression :
    - Include de script.inc.php et head.inc.html dans main.php

## Version 9.1.06
- Corrections :
    - Ajout d'un utilisateur : pas d'envoi du mail de confirmation si les mots de passe ne sont pas identiques.
    - Mise à jour automatique : effacement des archives téléchargées
    - Z-index des sous-menus augmentés à 8 ; problème d'affichage avec codemirror
- Modification :
    - Include de script.inc.php et head.inc.html dans main.php

## Version 9.1.05
- Correction :
    - Site par défaut : lien Zwii masqué du menu horizontal
- Modifications :
    - Présentation de l'édition des pages
    - Largeur dynamique du bouton envoyer dans le formulaire
    - Lien dans le footer vers le site Zwii
    - Redirection, écran de confirmation

## Version 9.1.04
- Corrections :
    - Edition de page : problème mise en page
    - Module Form (v1.9) : position et largeur des boutons
    - Thème Pied de page : problème d'affichage
    - Thème Site : boutons tronqués en 750px : 750px = 0.8em
- Modification :
    - Aperçu de la bannière en mode responsive

## Version 9.1.03
- Corrections :
    - Edition de page : modification de libellés, masquage d'options petites corrections
    - Installation par défaut : chemin vers la bannière
    - Image dans le fond du site option automatique

## Version 9.1.02
- Correction :
    - Suppression Include

## Version 9.1.01
- Modifications :
    - Amélioration de l'algorithme de gestion des barres
    - Script Google Analytics
    - Menu : effet de surimpression pages filles
    - Réorganisation de l'écran d'édition des pages
    - Blog : notification hiérarchique lors de la rédaction d'un commentaire
    - Form : notification hiérarchique de la réception d'un message
    - Thème header : hauteur proportionnelle de la bannière (responsive)
- Ajouts :
    - Menu dans une barre latérale : intégral ou sous-menu de la page parente
    - Option d'apparition des pages dans le menu latéral ou le menu principal
    - Option de chargement d'un modèle de site à l'installation
    - Option de masquage des pages enfants dans le menu principal
    - Petits écrans, ordre des blocs : Page - Barre Gauche - Barre Droite
    - Intégration de la classe Swiper http://idangero.us/swiper/
    - Intégration de l'URL canonical
    - Icône de suppression des pages dans la barre d'administration
    - Gestion du sitemap.xml et du robots.txt
- Corrections :
    - Form : option de redirection

## Version 9.0.21
- Mise à jour :
    - Code Mirror v5.46
- Corrections :
    - Liens de l'éditeur de page : impossibilité de sélectionner un lien vers une page parente
    - Export des données du site, problème lors de la création de l'arborescence.

## Version 9.0.20
- Correction :
    - Footer : Taille de la police du numéro de version

## Version 9.0.19
- Correction :
    - Alignement du menu

## Version 9.0.18
- Correction :
    - État par défaut du numéro de version mal récupéré

## Version 9.0.17
- Mises à jour :
    - simpleLightBox 1.17.0
- Correction :
    - Marges pour les petits écrans en mode connecté
    - Ajustement CSS du pied de page
    - Harmonisation du contenu des bulles d'aide
- Modifications :
    - Ajout du numéro de version dans le pied de page activable dans la configuration du thème
    - Désactivation Aviary dans Responsive FileManager

## Version 9.0.16
- Correction :
    - Nom de page constitué de caractères filtrés empêchant la création d'un Id valide.
    - Module Gallery : bouton de fermeture sous Edge

## Version 9.0.15
- Corrections :
    - Débordement dans le pied de page quand le copyright est à droite
- Modifications :
    - Petits écrans, menu d'administration icônes plus grandes
    - Masquage de l'icône de gestion du compte

## Version 9.0.14
- Corrections :
    - Débordement dans le pied de page quand le copyright est à droite
- Modifications :
    - Petits écrans, menu d'administration plus icônes plus grandes
    - Masquage de l'icône de gestion du compte

## Version 9.0.13
- Modifications :
    - Paramètre Tippy : ajouter area[title]
    - SimpleLightbox : bug d'affichage sous Edge, erreur signalée mais corrigée dans Zwii

## Version 9.0.12
- Corrections :
    - Configuration de Tippy pour l'utilisation de l'argument title dans les balises a et img. Data-tippy-content reste un argument reconnu
    - Bug de la redirection lorsque un dossier porte le nom d'une page, le contrôle de cohérence est déplacé dans page.

## Version 9.0.11
- Corrections :
    - Marges du pied de page
    - Tippy par défaut pour l'argument Title
    - Disparition du menu lorsque dans le site et que la bannière est déplacée hors du site

## Version 9.0.10
- Corrections :
    - Google + non effacé

## Version 9.0.09
- Corrections :
    - Nettoyage du code, petites corrections.

## Version 9.0.08
- Modifications :
    - Core : les données par défaut ne sont chargées qu'à installation afin d'alléger l'empreinte mémoire du noyau
    - Prise en compte de la taille des petits écrans, suppression des marges
    - Backup theme.json avant une mise à jour automatique
    - Réorganisation des écrans de paramétrage du thème, ordre de saisie, bulles d'aide et nouveaux libellés
    - SimpleLightBox : miniatures cliquables permettant de parcourir toutes les images d'une page (comme dans la galerie)
    - Barre d'administration : pages inactives en orange.
- Ajouts :
    - Thèmes : pied de page choix de police et de styles
- Corrections :
    - Faille XSS : liens de connexion encadrés par STRIP_TAGS
    - TinyMCE : désactivation du thème mobile ne fonctionnait pas sur ipad et iphone
    - Blog 1.3 : image en tête d'article correctement affichée avec effet responsive.
    - TinyMCE : taille des miniatures générées par défaut 480 x 320 en vue d'un affichage correct dans le module blog
    - Pied de page : correction d'un problème d'affichage sur des écrans inférieurs à 992px

## Version 9.0.07
- Correction :
    - Disparition du menu quand la bannière est masquée
- Modifications :
    - Barre d'administration : pages organisées

## Version 9.0.06
- Correction :
    - Configuration des modes de codemirror
- Modifications :
    - TinyMCE : libellés fenêtre des liens

## Version 9.0.05
- Modifications :
    - Thème :
        - nouvelle position du menu dans le site quand la bannière est au-dessus.
        - Simplification et ordre des libellés position du menu par rapport à la bannière
    - Éditeur de texte, scrolle lorsque l'éditeur est ouvert, la barre d'outil se colle sous la barre d'administration.
    - TinyMCE :
        - liste des pages du site dans la fenêtre des liens
        - option lightbox pour l'affichage d'images ou de liens
        usages : https://sorgalla.com/lity/

## Version 9.0.04
- Corrections :
    - Module form 1.6 :
        - erreur lors de la non sélection d'un groupe
        - captcha inefficace
    - Pour les testeurs : la mise à jour automatique n'est plus proposée lors d'une régression, lorsque le numéro de version en ligne est inférieur à celui de la version installée.
- Ajout :
    - Redimensionnement des images map : permet d'obtenir des images map fonctionnelles lorsque les dimensions de l'image sont réduites par le thème ou la taille de l'écran.
        - La carte peut être générée par https://www.image-map.net/
        - Article (en) : https://blog.travismclarke.com/project/imagemap/
        - Git : https://github.com/clarketm/image-map


## Version 9.0.03
- Corrections :
    - Erreur de mise à jour des options du menu lors du déplacement du header
    - Sélection par défaut d'une page de type barre
    - Données par défaut : suppression des doubles quotes

## Version 9.0.02
- Correction :
    - Mauvais affichage des bulles TIPPY, remplacement des balises TITLE


## Version 9.0.01
- Modifications :
    - Abandon de l'envoi masqué des mails du formulaire
    - Effacement Google+ des réseaux sociaux
    - Rétablissement du background du header
    - Opération sur un mauvais type affichant une notice
- Correction :
    - La bannière hors site cliquable replacée dans le header
    - Hauteur du footer hors site non appliquée


## Version 9.0.00
- Modification :
    - Stockage distinct du thème et des autres données (core, config, page, module et users ) avec import des données d'une version 8
    - Les thèmes :
        - Exporter un thème (avec les images) sous forme d'une archive ZIP à télécharger ou stocker dans  le gestionnaire de fichiers.
        - Importer un thème à partir des fichiers
        - Désactivation de la couleur d'arrière-plan du header lors de l'insertion d'une image
        - Nouvelle option de position fixe du menu type Facebook lorsque le menu et en haut de page et hors du site
        - Nettoyage des images effacées
    - Gabarits de pages : deux barres latérales, une à droite ou à gauche contenant des informations fixes.
    - Libellé Modérateur devient Editeur
    - Editeur de texte :
        - VisualBlocks dans TinyMCE
        - CodeMirror dans TinyMCE
    - Affichage de la version proposée dans la popup de mise à jour
    - Module Formulaire :
        - Case à cocher dans les formulaires
        - Bouton d'export au format CSV
        - Bouton effacer toutes les données
        - Notification d'un membre ou email libre
    - Edition de page :
        - masquage des options inutiles selon le module
        - nouvelle option : fil d'ariane des pages filles
    - Barre d'administration fixe

Correctif :
	- amélioration contre mesure CSRF
	- Erreur dans la procédure d'update suite à un ancien numéro de versions sur 4 digits

Mise à jour :
	- TinyColoPicker
	- PhpMailer 6.0.6
	- Responsive FileManager version 9.14.0
	- Flatpickr version 4.5.2
	- Normalize.css version 8.0.1
	- Tippy version 3.3.0

## Version 8.5.9
* Correction :
    - Module Form : faille CSRF gestion data
    - Problème empêchant la suppression d'une galerie
* Modification :
    - Module Form : Bouton tout effacer

## Version 8.5.8
* Correction :
    - Erreur dans la procédure d'update suite à un ancien numéro de versions sur 4 digits

## Version 8.5.7
* Correction :
    - Message d'erreur ecran modification du compte

## Version 8.5.6
* Correction :
    - Destruction de la session au logout
    - Thème : aperçu de la modification de la barre de menu au-dessus du site
* Modification :
    - Mise à jour RFM 9.14
    - Amélioration de la contre mesure CRSF
    - Libellé dans TinyMCE (gabarit)
    - Setlocal modification des paramètres FR

## Version 8.5.5
* Correction :
    - Faille CSRF lors de l'effacement d'un membre
    - Faille CSRF lors de l'effacement d'une galerie
    - Faille CSRF lors de l'effacement d'un article de blog
    - Faille CSRF lors de l'effacement d'un article de news
    - Taille de la police dans le footer impossible à modifier

## Version 8.5.4
* Correction :
    - Faille CSRF lors de l'effacement d'une page

## Verison 8.5.3
* Modification :
    - Config bouton de génération de la capture de l'écran OpenGraph
* Correction :
    - Appel de la génération de la capture d'écran OpenGraph quand le fichier est absent
    - CSS pour le footer des blocs et non des éléments
        - \#footersite, \#footerbody : bloc footer dans et hors site
        - \#footersite, \#footerbody a : liens du bloc footer  dans et hors site
        - Bloc des colonnes dans et hors site :
            - \#footersiteLeft, \#footerbodyLef
            - \#footersiteCenter, \#footerbodyCenter
            - \#footersiteRight, \#footerbodyRight


## Version 8.5.2
* Correction :
    - Thème menu : aperçu quand le menu est au-dessus et en-dehors du site

## Version 8.5.1
* Correction :
    - Nom de variable incorrect

## Version 8.5.0
* Correction :
    - Suppression popup active par défaut dans le menu
    - Suppression option de titre de page dans le menu Icone + Texte
* Modification :
    - Thème du menu : sélection de la police de caractère

## Version 8.4.9
* Correction :
    - Adresse d'une page inactive
* Modification :
    - Blog : masquer une image dans l'article tout en conservant la miniature dans l'index

## version 8.4.8
* Correction :
    - Fautes de frappe

## Version 8.4.7
* correction :
    - Chaine de mise à jour des variables internes

## Version 8.4.6
* corrections :
    - Encodage des dates dans la liste des articles news et blog
    - Variable itemsperPage stockée dans le mauvais type

## Version 8.4.5
* corrections :
    - nettoyage du code core.php
    - W3C ajout de balise title manquante
    - Inversion de deux balises dans Socials

## Version 8.4.4
* Correction :
    - Valeur par défaut et d'update des éléments du footer dans les blocks

## Version 8.4.3
* Correction :
    - URL incorrecte dans Metaimage
    - Erreur dans la génération du sitemap
    - Taille du texte de la bannnière maximale relative (vmax)
    - Préfixe des en-têtes html pour OpenGraph
    - Balise Titre dans Socials
    - Conformité W3C des URL dans socials

## Version 8.4.2
* Correction :
    - Modifications de la présentation des en-têtes d'articles de Blog et de News
    - Format du mois au format long et en français

## Version 8.4.1
* Correction :
    - Erreur de type empêchant l'affichage des articles du blog (nombre d'articles par page)

## Version 8.4.0
* Modifications :
	- Footer dans 3 blocs contenant dans l'ordre : Texte, Réseaux sociaux, Copyright
	- Pagination variable du nombre d'articles par page (news, blog et form)
	- Position des modules Galerie et Form dans une page ; haut ; bas ou libre avec les doubles crochets insérés dans l'article []
    - Prise en compte des balises OpenGraph obligatoires title , description, type et images
    - Modification de la position des boutons retour et éditer lors de l'affichage d'un article si connecté
    - Mise en forme de la composition des articles et des news
    - Suppression du message de l'édition des redirections

* Corrections :
    - Accès aux pages désactivées par le sitemap
    - Réduction du temps d'affichage des notifications
    - Image responsive en en-tête de l'article d'un blog
    - Mise à jour du gestionnaire de fichiers en version 9.13.1

## version 8.3.13 :
* Modifications :
    - Bannière "responsive", nouvelles options de positionnement
    - Bouton Edit dans Blog
    - Options de position des menus selon la position de la bannière
    - Bouton Edition dans un article du blog
    - Balise ALT dans les images du menu
    - Correction RFM


## version 8.3.12 :
* Modification :
    - bouton de retour dans la page d'un article de blog
* Correction :
    - miniatures des exemples
## version 8.3.11 :
* Modifications :
    - Thème : menu et sous menu sous forme de texte ou d'image (avec ou sans bulle)
    - Thème : nouvelle option permettant de cliquer sur la bannière afin de revenir à la racine du site
    - Thème : le menu peut être positionné en haut et hors de site sur la largeur de l'écran
    - Page : nouvelle option permettant désactiver une page dans le menu. Cette option permet soit  de mettre une page en maintenance tout en la laissant active dans le menu, soit de créer une entrée de menu principal sans contenu
    - nouvelle option :  la bannière devient cliquable et renvoie vers la page d'accueil
    - nom des dossiers des images exemples
* Corrections :
    - bug des commentaires non déposés quand connecté
    - bug présent depuis au moins la version 8.1 et qui faisait boucler l'édition d'une page avec un module de redirection; Après édition, un clic sur retour ou enregistrer renvoie vers la  page d'accueil en édition.
    - affichage d'une erreur 404 si le contenu d'une page est supprimé
    - erreur deans le filemanger si une seule extension demandée
    - corrige les droits sur la rédaction des commentaires
    - nouvelles icones d'exemple pour les menus
## 8.2.9
* Correction  :  filemanger : erreur dans la navigation du filemanager dans la sélection de la favicon
* Modification : on peut effacer le contenu d'une page sans provoquer d'erreur 404
## 8.2.8
* Correction : filemanager problème de lecture d'une seule extension
## 8.2.7
* Correction : gestion des droits sur les commentaires du blog
* Correction : une option en double dans TinyMCE
## 8.2.6
* Ajout : module codesample dans TinyMCE
* Correction : erreur pendant de la récupération des données lorsque plusieurs cases à cocher ne sont pas cochées
* Correction : désactivation automatique de la réécriture d'URL lors de l'enregistrement de la configuration du site
* Correction : iframes et vidéos responsives
* Correction : lien de réinitialisation du mot de passe incorrect
* Correction : backup automatique non fonctionnel
* Correction : mauvaise lightbox de confirmation de suppression d'un utilisateur
* Correction : message de modifications non enregistrées lors de la navigation dans le module galerie
* Correction : aperçu incorrect de certaines polices dans la personnalisation du thème
* Correction : légende toujours visible dans le module galerie même lorsqu'elle est vide
* Correction : impossible de modifier un lien ou un tableau dans TinyMCE
* Correction : champ de sélection de date non fonctionnel
* Correction : images non triées par ordre alphabétique dans le module galerie
* Correction : articles après la première page du module blog non accessibles
* Correction : non suppression des données du module rattaché à une page lors de sa suppression
* Mise à jour : TinyMCE en 4.7.9

## 8.2.5
* Ajout : message de confirmation avant la mise à jour
* Ajout : sauvegarde du fichier de données avant une mise à jour
* Ajout : copier / coller avec un clic droit dans TinyMCE
* Ajout : plugin stickytoolbar afin de fixer la barre d'outils dans TinyMCE lors du défilement de la page
* Amélioration : modification du message d'erreur lors d'une mise à jour
* Amélioration : nouveaux textes par défaut à l'installation
* Correction : message de confirmation avant de quitter une page sans enregistrer ne s'affiche pas
* Correction : suppression du plugin legacyoutput qui génère du vieux code à la place du HTML5 dans TinyMCE
* Correction : message d'erreur lors d'une mise à jour avec la réécriture d'URL activée

## 8.2.4
* Ajout : bouton de mise à jour dans la barre utilisateur
* Ajout : mode mobile pour TinyMCE
* Amélioration : rétablissement de la réécriture d'URL après une mise à jour
* Amélioration : affichage des différentes étapes de mise à jour
* Correction : résultat d'une recherche caché par l'overlay de TinyMCE
* Correction : suppression des commentaires d'un article lors du changement de titre
* Mise à jour : TinyMCE en 4.7.8

## 8.2.3
* Ajout : mise à jour automatique de Zwii
* Ajout : surcouche de TinyMCE aux couleurs de Zwii
* Ajout : module pour rétablir le contenu non enregistré dans TinyMCE
* Correction : divers bugs mineurs

## 8.2.2
* Ajout : options avancées des images dans TinyMCE
* Ajout : effet de transition au survol des galeries photos
* Ajout : mode maintenance activable depuis la page de configuration
* Ajout : bordure autour de l'éditeur CSS dans la personnalisation avancée
* Amélioration : optimisation du message de consentement pour les cookies
* Correction : affichage cassé lors de l'ajout d'une image en fin d'article ou news
* Correction : incohérence dans la petite largeur du site
* Correction : erreur dans le script de mise à jour 8.2.0
* Correction : vidéos responsives non fonctionnelles
* Correction : légendes des photos de la galerie invisibles
* Mise à jour : Simplelightbox version 1.11.1

## 8.2.1
* Correction : texte des boutons et des items du menu invisible

## 8.2.0
* Ajout : bouton pour fermer les notifications
* Ajout : barre de progression dans les notifications
* Ajout : flèche pour les items du menu avec sous-menus
* Ajout : personnalisation avancée
* Ajout : nouvelles options de personnalisation
* Ajout : nouvelles tooltips
* Ajout : vidéos et iframes responsives
* Ajout : plugin template afin de créer des colonnes adaptatives dans TinyMCE
* Correction : divers correctifs mineurs
* Mise à jour : Flatpickr version 4.3.2
* Mise à jour : jQuery version 3.3.1
* Mise à jour : Lity version 2.3.0
* Mise à jour : Normalize version 8.0.0
* Mise à jour : TinyMCE version 4.7.7
* Suppression : support multilingue

## 8.1.2
* Correction : popup d'édition du module de redirection visible pour les membres
* Correction : enregistrement des ids et des urls impossible avec des caractères spéciaux
* Mise à jour : ResponsiveFilemanager version 9.12.2

## 8.1.1
* Amélioration : nouvelle méthode de traduction
* Correction : faille de sécurité
* Correction : faute d'orthographe
* Correction : news non publiée lors de la création
* Correction : pas de pagination pour les modules blog et news
* Correction : nombre de caractères max des textareas du générateur de formulaires bloqué à 500
* Correction : impossible d'ajouter un slash dans le titre d'une page

## 8.1.0
* Ajout : message d'erreur en cas d'échec lors d'un envoi d'email
* Ajout : vérification du fichier de données afin d'éviter une corruption
* Ajout : édition des métas des pages
* Ajout : confirmation des lightboxs avec le bouton "Entrée"
* Ajout : suppression des données dans le générateur de formulaires
* Ajout : module blog
* Ajout : module news
* Amélioration : refonte de l'interface
* Amélioration : messages en cas d'absence de contenu dans la galerie et le générateur de formulaires
* Amélioration : optimisation des filtres afin de sécuriser davantage les données enregistrées
* Correction : "Champ obligatoire" dans le module de génération de formulaire invisible
* Correction : changer le titre d'une page supprime le module rattaché
* Correction : impossible de désactiver la sauvegarde automatique des données
* Correction : faille de sécurité au niveau des champs obligatoires

## 8.0.1
* Ajout : mot de passe dans l'email d'installation
* Ajout : redirection vers l'interface de connexion si la page d'accueil est privée
* Ajout : suppression automatique des backups de plus de 30 jours
* Amélioration : suppression de la balise h1 du haut de page afin d'optimiser le référencement des sites
* Correction : mauvais comportement avec les images flottante en fin de page (image coupée)
* Correction : le serveur utilise une adresse email incorrecte pour envoyer des emails (www. en trop)
* Correction : les pages parents cachées s'affichent dans le menu lorsqu'un enfant n'est pas caché
* Correction : bouton de mise à jour toujours visible avec certaines configurations de PHP
* Correction : titre du site toujours visible même lorsqu'il doit être masqué
* Correction : page courante non mise en avant dans le menu lors de son édition
* Correction : texte personnalisé du bouton dans le module formulaire non visible
* Correction : incohérence de casse entre l'identifiant saisi à l'inscription / création de compte et celui envoyé par email
* Mise à jour : jQuery version 3.2.0
* Mise à jour : PHPMailer version 5.5.23
* Mise à jour : ResponsiveFilemanager version 9.11.3

## 8.0.0
Nouvelle version majeure de Zwii.
