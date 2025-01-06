# Notes de mises à jour

## Version 13.5.02
**Correction**
- La réinitialisation du mot de passe ne fonctionnait pas. L'échec du changement de mot de passe est enregistré dans les journal de Zwii.

## Version 13.5.01
**Correction**
- Configuration du site, le message de formulaire non soumis non affiché.

## Version 13.5.00
**Améliorations :**
- Après un changement d'onglet dans la page de configuration, la page ne se recharge plus. Le dernier onglet affiché avant un submit est mémorisé dans les vues de l'utilisateur.
- Réactivation de l'édition des dialogues des langues.
- Nouveau bouton de test de bon fonctionnement du serveur SMTP afin de valider la double authentification.
- Validation de la connexion au site avec un code adressé par email. L'option est disponible depuis la configuration du site, onglet connexion. Elle s'active par groupe montant, exemple "éditeur" pour éditeurs et administrateurs.
- Optimisation du chargement des variables de classe.
- Suppression de redondance de déclaration des charset.
- Méthode de backup optimisée.

**Corrections :**
- Corrige un défaut d'affichage du bouton d'édition d'une page contenant un module ayant été supprimé sans passer par l'interface de gestion (FTP).
- Corrige un bug de changement de mot de passe pour les comptes non administrateurs.
- Blog 7.12, corrige un bug d'affichage des articles lorsque le thème Moderne est sélectionné.
- Corrige un dysfonctionnement de la fonction de tronquage qui perturbait l'affichage des articles de blog.
- Activation de la mémorisation de l'onglet actif dans la configuration après validation du formulaire ou visite d'une autre page du site.
- À l'installation, le bouton back mémorise la langue sélectionnée à la première étape.
- Corrige un bug de mise à jour en ligne du fichier des langues.

## Versions 13.4.00
** Améliorations :**
- Change le mode d'authentification, le hash du mot de passe n'est plus stocké dans un cookie.
- Améliore les performances des opérations d'écriture.
- Le sélecteur de fichier affiche le chemin d'accès du fichier présent dans le champ dans le gestionnaire de fichier.
- Connexion persistante renforcée.
- Script Datatables.net filtrage des éléments, nombre d'éléments et position sur l'écran.
- Slider 7.2, le dossier sélectionné est affiché par défaut dans la page update.
- Augmente la dimension des miniatures après le transfert dans RFM.
- Search 3.3 n'effectue qu'une seule lecture du fichier module.json en prévention des bots agressifs.
- Modification du contenu de robots.txt afin de n'autoriser que les moteurs de recherche et d'interdire les bots.
- Suppression des cookies mémorisant le dernier onglet affiché dans l'édition de la page et dans la configuration du site. Cette information est désormais stockée dans la fiche de l'utilisateur connecté.

**Corrections :**
- Change les paramètres du cookie de consentement.
- Isole la session dans l'onglet actif.
- Edition de page, delete et duplicate renvoyant vers une mauvaise page.
- Supprime un warning à la création d'une page.
- Bouton de génération du site inopérant.
- Affichage intempestif des boutons de navigation de pages dans les vues des modules.


## Versions 13.3.06
** Corrections : **
- Corrige un bug de sécurité dans la gestion des profils.
- Répare le bouton d'effacement en mode édition d'une page. 
- Corrige la feuille de style du slider les balises H1, H3 et A.
- Corrige l'absence de contenu de page lorsque le module est en position libre et que le mot clé [MODULE] n'a pas été inséré.
- Corrige l'option "Rester connecter sur ce navigateur' dont la connexion est désormais réellement persistante.
- Supprime un slash à la fin de l'URL du catalogue. 
- Eviter un warning lors de la création d'une nouvelle page.

## Versions 13.3.05
** Corrections : **
- Corrige la génération des miniatures au format avif et webp.
- Corrige le filtre FLOAT du helper qui supprimait la virgule flottante.
- Corrige des bugs dans le module Slider qui passe en version 7.0, ajout d'une option d'étiquette sous les images.

** Modifications : **
- Mise à jour du module News 5.9, taille d'un bouton.
- Mise à jour du module Blog 7.10, bloque la soumission d'un commentaire vide.
- Ajoute les filtres DATE et TIME pour l'affichage correct des champs de formulaire.

## Versions 13.3.04

** Correction : **
- Mauvaise génération du descripteur d'un module lors de sa sauvegarde. 

## Versions 13.3.03

** Corrections :**
- Le bug d'édition des pages avec Firefox est corrigé grâce à la suppression d'une commande forçant le chargement lassif des images dans core.js.php
- Dans la configuration, l'option Apache URL intelligente ne s'active que si le serveur est Apache et que le module Rewriter est actif. Ce qui exclut les autres serveurs non compatibles comme Nginx, Caddy etc.
- L'ajout d'un slash en fin d'adresse avec la réécriture active provoquait une mauvaise détermination des adresses des images dans TinyMCE. Résolution : une directive htaccess supprime tous les slashes en fin d'adresse.
- Lorsque la page est ouverte en édition, un clic sur le bouton édition dans la barre d'administration affiche une erreur, le lien étant incorrect. Afin d'éviter cette erreur et une redondance, le bouton d'édition est masqué lorsque la page est éditée.
- Quand des éléments inutiles sont ajoutés à l'adresse d'une page, une erreur 403 est levée.

## Versions 13.3.01 - 13.2.02

Livraison des modules blogs et news corrigeant un problème de flux RSS avec des méta vides.

## Version 13.3.00

Cette modification évite les problèmes d'édition de langues différentes dans des onglets différents du même navigateur.

## Version 13.2.02

Corrige un warning quand un module blog ou news ne contient pas d'article.

## Version 13.2.01

### Correction

Modification de la fonction d'écriture des données de la classe jsonDB dans le but de s'assurer de l'intégrité des données écrites. Un trafic intense en pointe sur des fichiers volumineux et sur un serveur peu puissant pouvait occasionner des erreurs d'écriture ou un mauvais formatage des données json.

## Version 13.1.08

### Corrections

- Corrige des erreurs quand une page parente ou des pages enfants ont des permissions limitées.
- Module Search 3.1 : initialisation du module après installation dans une page sans configuration par l'utilisateur.

### Améliorations

- Sauvegarde de l'état des sélecteurs dans les tables des fontes et des utilisateurs.
- Ajoute des contrôles d'intégrité des bases de données Json lors des opérations de chargement et de sauvegarde.
- Fournit une interface pour le contrôle des sauvegardes automatisées et de leur nettoyage par script CRON.

## Version 13.1.07

### Corrections

- Corrige une dépréciation de la sortie de la fonction Usort dans RFM.
- Module slider 6.4 : corrige plusieurs bugs dans les fonctions de tri

## Version 13.1.06

### Corrections

- Ajout d'utilisateur, étiquette non sauvegardée.
- Slider, corrige un tri non pris en compte.

## Version 13.1.05

### Corrections

- Corrige définitivement la fonction delete de la classe dot.
- Branche de mise à jour de la version 13.1.04 incorrecte.

## Version 13.1.04

### Corrections

- Evite une notice dans l'onglet social de la configuration lorsque l'image opengraph n'est pas spécifiée.
- Corrige une erreur dans la classe dot (fonction delete appelée statiquement)
- Ne redirige pas vers une page d'erreur (403 ou 404) après un login.

### Améliorations

- Mise à jour en ligne :restauration de la réécriture dans le fichier .htaccess de la racine, utilisation d'un fichier drapeau *.rewrite* pour conserver l'état de la réécriture afin de dédier la variable ```data``` (XHR) aux messages d'erreur.
- Journalise les erreurs de mise à jour automatique.


## Version 13.1.03

### Corrections

- Corrige un format de date dans la génération du sitemap.
- Limite l'affichage de l'icône du gestionnaire de fichiers dans le menu aux membres simples.
- Module blog ; format d'une variable de temps.
- Modules Blog et news, corrige un bug de paramètre de localisation erroné.
- Mot de passe oublié, une variable non définie affichant un warning.

### Améliorations

- Thèmes ; fontes : les fontes attribuées sont dans le thème administrateur sont prises en compte dans le blocage de l'effacement.
- Optimisation du code de discrimination.

## Version 13.1.02

### Corrections

- Thème ; fontes : corrige un problème lors de l'installation et de la désinstallation d'une fonte sous forme de fichier woff.
- Thème ; fontes : corrige un bug de redirection vers la liste des fontes après édition ou ajout.
- Utilisateur ; ajout : corrige la non prise en compte de la langue.
- Supprime le script imagemap non tenu à jour et bogué.

### Amélioration

- Thèmes ; fontes : filtrage et tri des fontes installées.

## Version 13.1.01

### Améliorations

- Cette version supprime le sélecteur de thème lors de l'installation.
- Modules news et blog :
  - Nouvelle option ajoutant un bouton de retour dans la page de l'article.
  - La mise en forme du bloc signature est homogénéisée.
- Profil des membres, corrige une impossibilité d'autoriser l'accès au gestionnaire de fichiers pour les membres non administrateurs.

### Corrections

- Corrige une mauvaise mise en forme du journal des évènements.
- Autres petites corrections.

## Version 13.1.00

### Améliorations

- La gestion des utilisateurs bénéficie de nouvelles fonctionnalités :
  - Un champ étiquette optionnel peut être attribué aux utilisateurs, il contient des mots clés séparés par des espaces. Ces mots clés permettent de filtrer les utilisateurs.
  - Un champ de recherche dynamique agissant sur l'ensemble des colonnes du tableau filtre les lignes.
  - Le nombre d'éléments affichés est dynamique.
  - La procédure d'import tient compte du champ étiquette et ajoute le champ profil.
- Affecte une icône home aux boutons de retour.
- Ajoute un lien de retour aux pages d'erreur 403 et d'erreur 404.

### Corrections

- Corrige un problème de permission non spécifiée dans un profil.
- Corrige des messages de dépréciation de fonctions dans RFM.
- Corrige un message de dépréciation lié à l'absence éventuel de titre court dans la génération du sitemap.
- Corrige la génération de la liste des pages selon la langue sélectionnée pour les liens TinyMCE
- Met à jour les profils dans le slider.
- Petites corrections.

## Version 13.0.08

### Corrections

-Corrige un bug dans la génération du sitemap.

## Version 13.0.07

### Corrections

- Module Slider (version 6.1), conformité de la largeur de l'image en pleine largeur lorsque le site est en largeur 100%.
- Gestion des extensions, création du dossier "Modules" absent lors de la copie de l'archive d'un module dans le gestionnaire de fichiers.
- Corrige un bug dans la génération du sitemap lorsque le site ne contient pas de page visible.
- Evite les messages de warning si le store est inaccessible.

## Améliorations

- Les administrateurs ont la possibilité de forcer un changement de mot de passe sans avoir à connaître le mot de passe en vigueur.
- Gestion des profils des modules, les termes sont plus précis (*Ajouter un article* au lieu d'*Ajouter*).
- Inversion de l'ordre des boutons dans la fenêtre de login sur les petits écrans.
- Modifications de la barre d'outils et du menu de l'éditeur de texte TinyMCE.

## Version 13.0.06

### Corrections

- Corrige le warning déclenché lorsque les boutons de navigation sont placés dans une page orpheline.
- Supprime les largeurs d'écran en pourcentages inférieures à 100%.

## Version 13.0.05

### Correction

- Adresse d'envoi des emails non spécifiée entraînant un rejet de l'envoi.

### Améliorations

- Ajoute des filtres des membres, groupes/profils, prénoms et noms commençant par telle lettre.
- Restaure la gestion d'erreur à l'étape 4 de la mise à jour automatique.
- Nouvelles tailles d'écran en pourcentages de 70% à 100% par pas de 5%.
- Déplacement de la fonction signature de la fonction core.php.
- Doublement de la taille des opérateurs dans le captcha de la fenêtre de login.

## Version 13.0.04

### Corrections

- Corrige un bug de sécurité. Lorsqu'un profil dispose des droits d'accès au gestionnaire de fichiers et qu'aucun dossier est sélectionné, la racine du site était affichée.
- Corrige un problème d'affichage des commentaires des profils dans l'édition d'un compte.
- Erreur d'édition d'un profil de niveau 1, exemple membre simple.
- Le profil de membre simple affichait le gestionnaire de fichiers dans tous les cas.
- Importation d'utilisateurs en masse, le bouton de téléchargement d'un modèle était inopérant.

### Améliorations

- Supprime la gestion d'erreur à l'étape 4 de la mise à jour automatique.
- Modifie l'URL de téléchargement des mises à jour.
- Améliore l'affichage des dates lorsque le site est affiché dans une langue étrangère.

## Version 13.0.03

### Améliorations

- Déplacement du bouton de gestion des langues à la droite du sélecteur de langues dans la barre d'administration. Le sélecteur de langue est toujours affiché même si le français est la seule langue disponible.
- Suppression d'appels inutiles à une fonction de contrôle CSRF.
- Supprime les fonctions liées à la gestion des données des modules contenant des bugs variés.

# Corrections

- Message de réinitialisation de mot de passe non envoyé.
- Complète le message d'erreur lorsque des modules PHP sont absents.
- Les liens dans le pied de page prennent la couleur définie dans le site.
- Module form 4.1 : corrige un email non envoyé après validation d'un formulaire.
- Module blog Version 7.1 : permission lors de la validation d'un formulaire

## Version 13.0.02

### Corrections

- Bug de renommage de la base de données des fontes

## Version 13.0.01

### Corrections

- Mauvaise présentation de l'icône devant les pages enfants dans la liste de liens de TinyMCE.
- Module redirection : édition  de la page ou du module impossible.

## Version 13.0.00

### Améliorations

- Gestion des profils d'utilisateurs dans les groupes de membres et d'éditeurs (modérateurs). Les profils définissent avec précision les autorisations d'accès à toutes les fonctions du CMS.
- Améliore la gestion de la base de données et la génération du fichier de journalisation, stockage des données JSON, forçage au format objet.
- Erreurs fictives pendant la mise à jour en ligne, améliorations du dialogue AJAX entre PHP et JQUERY avec un affichage précis des erreurs.
- L'ajout d'une langue de contenu initialise les données de la langue.
- Format d'image avif si supporté par la version installée de php.
- Remplacement du service ScreenShot API par un sélecteur manuel ; affiche les paramètres d'images recommandées et ceux de l'image sélectionnée.
- Nouvelles options de page qui autorise un déplacement latéral dans la hiérarchie du menu à l'aide de deux boutons personnalisables parmi 3 modèles.

### Corrections

- Correction de bugs mineurs dans la sauvegarde et la suppression des modules installés.
- Problèmes de mise à jour depuis les versions 11.
- Dépréciations liées à php 8.n

## Version 12.4.00

- L'ID de session n'est plus transmise dans l'URL, les modules distribués ont été actualisés.
- Corrections de bug dans le module Blog, merci de consulter le fichier changes.log du module.

## Version 12.3.11

- Interdit la création d'une langue autrement que par un administrateur.

## Version 12.3.10

- Edition d'un utilisateur, affiche correctement la langue de l'interface dans l'édition d'un utilisateur.
- Mise à jour du fichier dialog.php de Responsive File Manager.
- Vulnérabilité dans ajax_call.php CVE-2020-10567, désactivation de TUI Editor et de la fonction save_image.

## Version 12.3.09

### Corrections

- Corrige le filtrage des modules orphelins.
- Corrige l'installation en langue étrangère non prise en compte depuis 12.3.08
- Corrige le bug d'affichage avec le module de recherche.

### Modifications

- Autorise la modification de la langue du site par défaut.
- Traduction de "Motorisé par" dans la personnalisation des données de la langue du site.

## Version 12.3.08

- Amélioration du code liée à la traduction du contenu du CMS.
- Implémentation d'un message d'avertissement de suppression d'une langue de site ou de contenu.
- Correction des dialogues de traduction.
- Correction dans le footer de l'activation d'une page spéciale non désignée.
- Thème : simplification des valeurs de sélection, la valeur remplace une désignation, 80% au lieu de Petit.
- Thème : suppression des bulles d'aides trop verbeuses.

## Version 12.3.07

### Corrections

- Corrige un affichage erroné en fin d'installation
- Corrige une erreur 404 lors de l'accès à une page dans une langue étrangère et bascule dans le format de langue correct.
- Permets l'affichage des pages orphelines dans les redirections du formulaire, module désormais en version 3.8.

### Amélioration

- Les caractères spéciaux dans le mot de passe sont reconnus.

## Version 12.3.06

- Compléments de traduction.

## Version 12.3.05

- Corrige un problème de stockage des paramètres de la localisation.

## Version 12.3.04

### Corrections

- Corrige un défaut d'actualisation de la liste des pages et du site map lorsque la page change d'id.
- Serveur SMTP :
  - Corrige le décryptage du mot de passe SMTP.
  - Corrige un défaut d'encodage UTF-8 du sujet du mail et du titre du site.
- Traduction du message de compte bloqué.

### Améliorations

- Module Blog :
  - Aspect de la liste des articles présenté en tableau avec un bouton "Lire la suite" agrémenté d'un effet de flou.
  - Des tailles de masquage du texte des articles plus importantes sont proposées.
- Comptes de réseaux sociaux :
  - Sont ajoutés Steam, Twitch, Vimeo et Reddit.
  - Des icônes accompagnent le nom des réseaux dans la configuration.

## Version 12.3.03

- Corrige le problème d'affichage lors de l'édition d'une page contenant une feuille style commentée.
- Corrige des problèmes d'interprétation des scripts intégrés dans une page.
- Améliore l'affichage des erreurs lors de la mise à jour automatique. L'échec de la vérification de la clé MD5SUM de l'archive d'installation provoque l'arrêt de l'installation au lieu d'un message en fin d'installation.

## Version 12.3.02

- Amélioration de l'obfuscation.
- Corrige la limitation de 500 caractères des scripts JS et du style CSS stockés avec la page.

## Version 12.3.01

### Améliorations

- Prise en charge PHP 8.2
- "Minification" de la sortie HTML.
- Envoi d'eMail, PHPMailer :
  - Mise à jour PHPMailer 6.7.1, support PHP 8 ;
  - Personnalisation de l'adresse de l'expéditeur ;
  - Prise en charge des langues de l'interface ;
  - Correction d'un mauvais fonctionnement de la configuration  d'un serveur SMTP personnalisée différent de celui de l'hôte.
- Amélioration de la prise en charge des mises à jour en ligne.
- Activation du bouton de mise à jour dans la barre d'administration lorsque le menu de configuration est ouvert est qu'une mise à jour en ligne est détectée.
- Gestion des langues :
  - Le numéro de version d'une langue est le numéro de version de base de données
  - A l'installation ou lors de l'accès à la fenêtre des langues, les dialogues sont actualisés.
  - La fonction d'édition des langues de l'UI est neutralisée.
- Contrôle des prérequis, Zwii ne démarre pas si la version de PHP n'est pas conforme ou si un module PHP nécessaire n'est pas installé, si les fichiers de sécurité htaccess sont manquants.

### Corrections

- Suppression des appels Google Analytics lors du chargement du gestionnaire de fichiers (RFM) dans TUI-image.
- Mauvais affichage du script ou du CSS déclarés dans une page.
- Choix de la langue dans TinyMCE et CodeMirror.
- Mauvaise application des fontes dans l'administration du thème.
- Corrige une mauvaise lecture du type de fonte éditée.
- Correction de petits bugs.

### Nouveautés

- Remplacement du sélecteur de date Flatpickr par le sélecteur HTML 5 qui autorise les formats suivants : date, time, week, month, datetime-local.
- Paramétrage du délai de recherche automatique d'une mise à jour, tous les jours, deux jours, quatre jours, toutes les semaines, tous les mois.

## Version 12.2.04

- Référencement incorrect de la langue grecque dans la base centrale.

## Version 12.2.03

- Corrections de bugs consécutifs au changement de format de languages.json

## Version 12.2.02 (version non publiée)

### Corrections

- Gestion des plugins (modules) :
  - Corrige un bug dans l'acquisition des données du store.
  - Corrige un bug d'analyse des modules installés.
  - Corrige un bug dans l'installation d'un module (dataDirectory).
- Langues étrangères (v4) : corrige l'absence de spécificateur %s dans les traductions occasionnant des plantages lorsqu'une langue étrangère est active.

## Version 12.2.01 (version non publiée)

### Correction

- Bug majeur lors de l'installation d'une version fraiche, erreur lors de la création de la base de données des langues.

### Amélioration

- Gestion des erreurs d'écritures à l'aide d'un contrôle des données écrites sur le disque. Cinq tentatives se terminent par un arrêt en cas d'impossibilité d'enregistrer les données.

## Version 12.2.00 (version non publiée)

### Nouveautés

- Traduction des modules en anglais, grec, espagnol, italien et portugais.

### Amélioration

- Aspect des menus affichés sur les écrans de petites tailles.

### Corrections

- Corrige une bannière non cliquable lorsque placée hors du site.
- Corrige un débordement dans le pied de page le module form 3.4.
- Corrige une erreur de génération des archives des modules lorsque Zwii est exécuté sur une machine Windows.

## Version 12.1.01

### Correction

- Défaut d'initialisation du générateur de nombre aléatoire utilisé pour le choix des nombres du captcha.

## Version 12.1.00

### Corrections

- Corrige l'activation non autorisée d'une version en langue étrangère du site.
- Corrige un problème de prise en compte des scripts et des feuilles de style intégrés à la page ou au site.
- Corrige une erreur fatale avec un argument float au lieu d'int dans mt_srand.

### Améliorations

- Changement du nom de formulaire de gestion des langues (multilangue devient multilingue).
- Présence d'un sélecteur de langue de contenu dans la barre d'administration.
- Traductions de l'interface d'administration, ajout de nouvelles fonctionnalités :
  - Installation et mise à jour d'une langue depuis un magasin en ligne ;
  - Téléchargement d'une langue modifiée avec l'éditeur intégré ;
  - Suppression d'une langue ;
  - Ajout du Grec.
- Langues disponibles pour l'interface, ajout du Grec et de l'Hébreu.

## Version 12.0.09

### Corrections

- Gestion des extensions (plugin)
  - Impossibilité d'effectuer uen installation depuis le store
  - Erreur d'affichage du statut des modules installés dans la page du store.

### Améliorations

- Langues de l'interface
  - Ajoute un utilitaire permettant leur mise à jour à partir des modèles livrés. A venir, une fonction gérant les langues modifiées.
  - Corrige des langues de l'interface, italien et portugais.

## Version 12.0.08

### Nouveautés

- Compatibilité PHP 8.1
- Module addon refondu et renommé plugin.
- Gestion des langues :
  - Définition de la langue de l'interface à l'installation.
  - Définition de la langue du site parmi 28 langues.
  - Module de gestion des langues refondu.
- Edition des pages :
  - Nouvelle présentation de l'édition des pages et de la configuration du site.
  - Feuille de style et script attachés à la page.
- Réorganisation des formulaires de saisie.
- Google :
  - Suppression du script Google Analytics
  - Remplacement du script de génération de la capture d'écran par ScreenshotAPI <https://app.screenshotapi.net/>
  - Suppression de la traduction automatique par le script Google Translate

### Modifications

- Amélioration du thème admin de base, modifications du jeu d'icônes.
- Mise à jour automatisée, affichage de l'erreur en cas d'échec.
- Suppression du support de l'import à partir d'une version 9, y compris pour la restauration des sauvegardes.
- Optimisation du chargement des bases de données, mise à jour des scripts jsonDB et dot.
- Connexion persistante, l'activation de la case à cocher *Rester connecté sur ce navigateur* ne ferme pas la session lorsque le navigateur est fermé.

## Version 11.5.13

### Corrections

- Erreur de lien metaImages.
- Compatibilité PHP 8.1 du gestionnaire de fichiers.

### Modification

TinyMCE, URL absolues, transformation autorisée en URL relative si effectuée manuellement.

## Version 11.5.12

- TinyMCE, Les URL relatives posent des problèmes lorsque le contenu de la page est lu hors l'URL de base. Les URL deviennent absolues, la transformation automatique inactivée.

## Version 11.5.11

### Corrections

- Génération du flux RSS dans le module blog, URL des miniatures incorrects.
- Login, dépréciation de fonction avec php 8.1

## Version 11.5.10

### Correction

- Dysfonctionnement de la classe strftime, setlocale mal défini.

## Version 11.5.09

### Corrections

- Problème de génération de l'exemple du site.
- Dépréciations de fonctions PHP 8.1

## Version 11.5.08

### Corrections

- Bugs divers et dépréciations PHP 8.1
- Ajout d'une classe spécifique PHP81_BC\strftime suite à sa dépréciation.

## Version 11.5.07

### Correction

- Création du dossier des fontes personnalisées en cas d'absence.

### Amélioration

- Détection d'une mise à jour.

## Version 11.5.06

### Corrections

- Défaut d'affichage de la barre des membres dans la zone de menu.
- Chargement à l'unité des fichiers déposés dans le gestionnaire de fichiers suite à l'utilisation d'une dernière version de la librairie jquery. L'utilisation de la version 1.12.4 livrée avec le gestionnaire de fichiers corrige le problème. Cette librairie est néanmoins chargée en local par dialog.php

### Amélioration

- Recherche d'une mise à jour en ligne effectuée réellement une fois pas jour lorsqu'un administrateur est connecté.

## Version 11.5.05

### Correction

- Validation de la fenêtre de consentement au cookie envoyant vers une page inconnue lorsque l'URL contient plusieurs éléments (exemple : article d'un blog)

### Amélioration

- Traitement des erreurs dans la gestion des fontes et de l'ajout d'une nouvelle fonte.

## Version 11.5.04

### Corrections

- Édition d'une page : bug de sélection d'un module absent.
- Rechercher dans le site : impossibilité de rechercher dans le contenu des modules (news, blog et download)

### Amélioration

- Récupération de la capture d'écran du site, 5 tentatives d'appels de l'API Google sont effectuées avant de retourner un échec.

## Version 11.5.03

### Correction

- Bug de la génération des feuilles de style des fontes, nouvelle correction.

### Améliorations

- Responsive File Manager (RFM), les scripts externes et les feuilles de style sont chargées à partir du site et non d'un CDN (jquery, fabric, filesaver et jplayer). Cette modification accélère le primo chargement de la fenêtre des fichiers.
- Fenêtre Lity agrandie à 90% de la largeur de la page, ce réglage s'applique également à RFM ainsi qu'à l'éditeur d'image intégrée.
- Edition d'une page contenant un module effacé sur le disque, la modification et l'effacement de la page sont autorisés.

## Version 11.5.02

### Corrections

- Bug de la génération des feuilles de style des fontes.
- Bug dans le cookie de consentement lorsque le port n'est pas 80.

## Version 11.5.01

### Modifications

- Restauration du bouton d'installation d'une archive de module depuis le store.

## Version 11.5.00

### Corrections

- Ajout d'une nouvelle page, le nom court n'est pas défini.
- Bug de la fonction de copie interne utilisée lors de l'installation de la copie de thème, etc..

### Modifications

- Le module de recherche analyse les descriptions du module Download (Téléchargement).
- Prise en compte des modifications liées à la mise à jour du module Download (Téléchargement), actualisation du changement de structure 'posts' remplace 'items'
- Restauration de la fonction de téléchargement à partir du store.

## Version 11.4.02

### Modification

- Liste des fontes, contrôle de validité amélioré.

## Version 11.4.01

### Corrections

- Défaut de chargement des fontes locales (ex: fichiers woff).
- Un clic sur le bouton de validation du panneau RGPD envoyait systématiquement vers la page d'accueil.
- Chargement des anciens fichiers d'aide absents.

## Version 11.4.00

### Nouveautés

- Compatibilité avec PHP 8.1
- Prise en charge des fontes Web Safe. Les fontes initiales sont transférées dans les fontes optionnelles, donc effaçables.
- Toutes les fontes en ligne sont désormais acceptées quel que soit le CDN, Google Fonte (avec preconnect),  CDN Fontes ou autres.
- Désormais, les URL internes sont relatives, cela signifie qu'elles ne contiendront plus le domaine et le chemin d'accès au site. Cela permettra le déplacement d'un site d'un hébergement à un autre, d'un dossier d'hébergement à un autre, sans avoir à convertir les adresses internes. Les données d'un site mis à jour et importées d'une version antérieures sont automatiquement converties. En conséquence, le bloc de conversion de la fenêtre d'import est supprimé.
- Suppression temporaire de l'option d'installation d'un module, il faudra passer par une connexion FTP pour cela. Cette fonctionnalité a été réécrite pour la version 12.

### Améliorations

- Configuration de la bannière, modalité d'affichage de la taille d'image recommandée et affichage des dimensions de l'image.
- Edition d'une page, le nom court se complète automatiquement.
- Configuration de la connexion, une option autorise l'affichage de la page de connexion lorsqu'une page de gestion du site est demandée:  'user', 'theme', 'config', 'edit', 'translate', 'addon'.
- L'option de réécriture d'URL n'est pas plus active avec le serveur Nginx.
- Galerie, version 3.5 :
  - Nouvelle structure anticipée sur la version 12, le formulaire d'ajout de la galerie est séparé de la liste des galeries du module.
  - Lorsque la galerie n'en contient qu'**une seule galerie**, elle peut être affichée directement, la liste des galeries étant ignorée. Pour cela, activer cette option dans les options de la galerie.
  - Le contenu de la page peut désormais être affiché avec le contenu de la galerie sélectionnée. Ce paramètre se gère au niveau de chaque galerie.
  - Déplacement du bouton de retour à la liste des galeries en bas de l'écran.

### Corrections

- URL Rewrite Apache, bug d'interprétation d'activation de la réécriture d'URL lorsque des données ont été inscrites après la ligne servant de délimiteur  *# URL rewriting* dans le fichier htaccess.
- Module Galerie : correction de bugs, tri des images, erreurs d'affectation.
- Module Blog : taille recommandée de l'image erronée lorsque la largeur de l'écran est réglée sur fluide (100%).
- Gestion des pages : positionnement dans le menu accessoire ou dans le menu standard.
- Safari sur Mac, bug avec les cookies qui ne sont pas stockés.
- Nettoyage du code.

### Mise à jour

- TableDND, script JQUERY de tri de tables utilisé par la galerie passe en version 1.0.5
- PHPMailer 6.6.0

## Version 11.3.07

### Correction

    - Module galerie, option plein écran inopérante.

### Amélioration

    - Module galerie, lorsque le module ne contient qu'une galerie, la page listant les galeries est omise.

### Modification

    - Neutralisation du téléchargement depuis le catalogue.

## Version 11.3.06

### Corrections

    - Bug d'affichage des blocs de présentation dans la configuration du site.
    - Double déclaration d'une fonte locale.

### Améliorations

    - Sauvegarde des fontes avec le thème.
    - Une fonte Websafe remplace une fonte locale dont le fichier n'est pas disponible.

## Version 11.3.05

### Correction

    - Dossier du fichier de fontes non créé empêchant la création du fichier des appels de fontes.

## Version 11.3.04

### Correction

    - Duplication d'id dans le menu.

### Amélioration

    - Chargement des fontes optimisé, le dossier data/fonts contient un nouveau fichier fonts.html contenant les url des fontes à télécharger. Ce fichier est généré à chaque modification du thème.

## Version 11.3.03

### Modifications

    - Suppression du thème administration dans le menu du thème.
    - Position d'une page dans le menu accessoire, ordre des pages dans le menu de sélection.
    - Boutons d'aide dans la page de sélection des fontes.

## Version 11.3.02

### Corrections

     - Importation d'une police sur cdnFonts impossible, nom de fonction incorrect.
     - Thème moderne, url de l'image corrigé
     - Thème, import d'un thème sauvegardé, conversion des fontes Google.

## Version 11.3.01

### Corrections

    - Gestionnaire de fichier, chevauchement d'icônes en multi sélection et aides non traduites.
    - Fontes : utilisation d'une adresse d'import de fonte HTTPS

## Version 11.3.00

### Nouveautés

    - Police de caractères :
        - Changement de fournisseur, CdnFonts remplace Google Font.
        - Les polices pourront désormais être téléchargées à partir du site et non du CD grâce à une nouvelle fonctionnalité du thème permet de gérer l'installation des fontes, soit à partir du CDN, soit à partir d'un fichier téléchargé.
    - Pages dans le menu accessoire. Ce menu à affiché à droite de la barre de menu, il est traditionnellement utilisé pour y placer les drapeaux de traduction, le bouton de connexion et de gestion du compte des membres. Il sera désormais possible d'y placer des pages sous la forme d'icônes de préférence.
    - Prise en charge du format webp pour les modules nécessitant des miniatures.

### Améliorations

    - Thème / Bannière : ergonomie de l'information sur l'image sélectionnée.
    - Identifications des éléments du menu, les pages parents prennent comme id CSS leur id, les pages enfants également et pour classe Id de la page parente.

### Corrections

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
    - Étiquette dans le footer permettant d'afficher la popup des cookies.
  - Thème :
    - Disposition des options de configuration du site.
    - Bannière : le contenu peut être personnalisé à l'aide d'un éditeur. La bannière au-dessus du site peut s'étendre sur la largeur de la page.
  - Pages : il est désormais possible de donner un nom de page court utilisé dans le menu du site, dans les barres latérales et dans les sélecteurs de page (éditeur / lien). En revanche le nom de la page affiché en haut de celle-ci est inchangé. Dans la plupart des cas le titre court sera identique au titre.
  - Les écrans d'aide renvoient vers le site doc.zwiicms.fr
  - Mise en évidence du statut des pages dans la liste de la barre d'administration. Rouge italique = page orpheline ; Orange gras = page inactive.
  - Référencement, l'URL de la page d'accueil (<www.site.fr/accueil>) est remplacée par la base Url du site (<www.site.fr/>) afin d'éviter la duplication de contenu.

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
  - Mises à jour : les mises à jour s'effectue à partir de la forge plutôt que sur le site zwiicms.fr

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
  - Éditeur de texte : couleur de fond parasite quand une image en Arrière plan est sélectionnée.

## version 10.4.01

Corrections :
    - Module form, erreur de syntaxe.
    - Chargement d'un thème, désactivation du contrôle des clés.

## version 10.4.00

Corrections :
    - Bug de pages non accessibles dans le menu suite à la création d'entrées fantômes dans le fichier de données de modules.
    - Nettoyage du fichier des données de modules des entrées fantômes.
    - Thème : déformation des images en Arrière plan dans les modes responsives cover et contain lorsque la longueur de la page change. L'image en Arrière plan est désormais placée dans la balise html et l'option fixe s'active lorsque cover ou contain sont sélectionnés.
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
  - Thème du menu : alignement inopérant ; Arrière plan semi-transparent non uniforme.
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
