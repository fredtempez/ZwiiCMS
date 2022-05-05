# Changelog

## Version 12.0.00
### Nouveautés :
- Module addon refondu et renommé plugin.
- Réorganisation des écrans pour éviter de mélanger des formulaires avec des champs d'information.
- Edition des pages :
    - Nouvelle présentation de l'édition des pages et de la configuration du site.
    - Feuille de style et script attachés à la page.
### Modifications :
- Amélioration du thème admin de base, modifications du jeu d'icônes.
- Mise à jour automatisée, affichage de l'erreur en cas d'échec.
- Suppression du support de l'import à partir d'une version 9, y compris pour la restauration des sauvegardes.
- Optimisation du chargement des base de données, mise à jour des scripts jsonDB et dot.


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