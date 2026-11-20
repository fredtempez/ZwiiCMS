# Notes de mises à jour

## Version 16.3.00
### 🚀 Améliorations
- Système de message de mise à jour : affichage d'un message téléchargé depuis le serveur de mise à jour dans une popup après l'étape 4, et icône d'information dans la configuration.
### 🛠️ Corrections
- Intégration de constantes pour les délais réseau (CURL_TIMEOUT, CURL_CONNECT_TIMEOUT) et les temporisations d'écriture (FILE_WRITE_MAX_ATTEMPTS, FILE_WRITE_RETRY_DELAY, PAGE_LOCK_SLEEP, INSTALL_STEP_SLEEP), avec commentaires et regroupement par usage.
- Paramètres éditables manuellement dans `core.json` (curlTimeout, curlConnectTimeout, logMaxsize, logMaxarchive, accessTimer, menuDepth, thumbsWidth, maxProfils) avec repli sur la constante.

### Constantes éditables (core.json)

Constante              | Clé core.json        | Valeur par défaut | Usage
-----------------------|----------------------|-------------------|---------------------------------------------------
CURL_TIMEOUT           | curlTimeout          | 30 s              | Temps maximal total d'une requête HTTP (getUrlContents)
CURL_CONNECT_TIMEOUT   | curlConnectTimeout   | 10 s              | Temps maximal pour établir la connexion TCP
LOG_MAXSIZE            | logMaxsize           | 1 048 576 o (1 Mo)| Taille max du journal avant rotation
LOG_MAXARCHIVE         | logMaxarchive        | 5                 | Nombre d'archives de journal conservées
ACCESS_TIMER           | accessTimer          | 1800 s            | Délai d'inactivité avant déconnexion
MENU_DEPTH             | menuDepth            | 5                 | Profondeur maximale des sous-menus
THUMBS_WIDTH           | thumbsWidth          | 640 px            | Largeur des miniatures de galerie
MAX_PROFILS            | maxProfils           | 10                | Nombre maximal de profils utilisateurs


## Version 16.2.04
### 🛠️ Corrections
- Correction de l'accès au gestionnaire de fichiers juste après la connexion (lecture du cookie via la session PHP) afin d'éviter le message "Acces Denied" à l'ouverture des fichiers juste après la connexion.

## Version 16.2.03
### 🛠️ Corrections
- Correction du sélecteur de page parent (référence circulaire).

## Version 16.2.02
### 🛠️ Corrections
- Journalisation forcée pour les erreurs critiques (messagerie, données, instances de modules, migration) même si la journalisation est désactivée.
- Affichage convivial de l'erreur lors de l'étape 2 de la mise à jour (extraction du message JSON au lieu de l'affichage brut).

## Version 16.2.01
### 🛠️ Corrections
- Optimisation des aperçus du thème, petites corrections.
- Correction du sélecteur de page parent : la page en cours d'édition n'est plus proposée comme parent (évite une référence circulaire).
- Correction de l'accès au gestionnaire de fichiers juste après la connexion (lecture du cookie via la session PHP).

## Version 16.2.00
### 🚀 Améliorations
- Paramètre de langue homogène : `lang` est désormais émis dans tous les liens (site, administration, sitemap, `llms.txt`, API), comme dans le gestionnaire de fichiers ; `l` reste accepté en entrée, donc les liens et favoris existants continuent de fonctionner. Le calcul est centralisé dans `Tools::getLanguageQueryParam()`, ce qui supprime sept copies divergentes dans la mise en page.
### 🔍 API / IA
- La langue servie par l'API est explicite et déterministe : `?lang=<code>` (alias `?l=` conservé) sélectionne la version linguistique ; sans paramètre, c'est la langue **par défaut du site** qui est renvoyée, jamais celle de la session. La réponse est donc identique avec et sans cookie, et peut être mise en cache (`Content-Language`).
- `?lang=all` sur `/api/pages` renvoie toutes les langues installées en une seule réponse ; chaque langue expose son étiquette BCP 47 et son URL d'API (`languages`).
- Les URL renvoyées par l'API sont épinglées sur la langue (`?lang=`) dès que le site en installe plusieurs : une URL désigne toujours la même version linguistique. Une langue inconnue répond en 400 avec la liste des valeurs acceptées.
### 🛠️ Corrections
- La route s'arrête désormais au premier « & » : les paramètres de requête (`lang=`, `l=`, `limit=`, `q=`, `editFullScreen=`…) ne sont plus collés au dernier segment. `?accueil&editFullScreen=1` et `?blog&page=2` renvoyaient une 404 ; `?api/search/mot&limit=2` cherchait « mot&limit=2 ». Les paramètres `?q=` et `?limit=` de l'API sont de ce fait fonctionnels.
- `?lang=` accepte les variantes d'écriture d'un même code : `fr-FR` (tag BCP 47 publié dans `languageTag`), `fr_fr`, `FR` ou `fr` désignent l'identifiant installé `fr_FR`. Un client qui réutilise le tag annoncé ne reçoit plus une erreur 400.
- **Couverture de l'index** : `/api/pages`, `/api/search` et `llms.txt` interrompaient la descente sur une page parente non exposable (page désactivée ou placée dans une barre latérale) ; ses sous-pages publiques étaient donc absentes de l'index alors qu'elles figuraient au sitemap et répondaient en 200 sur `/api/content/<id>`. Le filtrage décide désormais de l'exposition **page par page**, sans bloquer le parcours des enfants : l'API et `llms.txt` recouvrent exactement les URL du sitemap. Les pages **masquées du menu** (position 0) sont incluses, comme dans le sitemap : elles sont publiques et servies en 200. Le parcours part de `getHierarchy(null, false)` et repêche en fin de course toute page publique jamais atteinte (ascendant absent ou chaîne incomplète) : aucune page ne peut plus être omise silencieusement.
- Réponses d'API réellement sans état : plus d'en-tête `Set-Cookie` sur une réponse déclarée `public, max-age=300` (un cache partagé pouvait resservir un identifiant de session), et `Content-Type: application/json; charset=utf-8`.
- Écriture de `llms.txt` : un fichier non inscriptible par PHP (propriétaire ou permissions) laissait l'ancien contenu en place sans que rien ne le signale, le bouton annonçant « carte du site mise à jour ». L'échec est désormais remonté dans la notification.
- **`llms.txt`, mise en forme** : la forme `?q=` de la recherche est documentée en plus du segment d'URL (ce dernier ne transporte pas les espaces et renvoie 404) ; les gabarits d'URL contenant `<…>` passent en texte simple, une cible de lien ne pouvant pas contenir de chevrons en Markdown ; les fins de ligne Windows présentes dans les métadonnées du site sont normalisées.
- **Sitemap** : la génération ne parcourait que deux niveaux d'arborescence et s'arrêtait sur une page parente masquée ; les pages orphelines (absentes du menu mais publiques et atteignables par un lien interne) en étaient absentes. Elle s'appuie désormais sur `getHierarchy(null, false)` avec un parcours récursif, le même filtrage page par page et le même repêchage que `llms.txt` : sitemap, `llms.txt` et `/api/pages` listent le même ensemble de pages.
- Une page sans fichier de contenu renvoie une chaîne vide au lieu du repli francophone « Aucun contenu trouvé. », qui n'a pas sa place dans une réponse d'une autre langue.
- Ajustement et correction des bases de langues.
- Application de l'instance d'un module :
  - Les formulaires déclarent désormais leur instance de module via le champ caché moduleInstanceId.
  - isPost() ne traite le POST que pour l’instance émettrice, évitant qu’il soit consommé par les autres modules ou instances de la page.


## Version 16.1.00
### 🚀 Améliorations
- Nouvelle option de filtrage et d'autorisation des scripts insérés dans les iframe (conifiguration du site).
- Toilettage des fichiers de langues.

## Version 16.0.00
### 🐘 Compatibilité PHP
- Compatibilité **PHP 8.5** : le core et les modules s'exécutent sans avertissement de dépréciation.
- Version minimale requise ramenée à **PHP 8.1** (mise à jour du `README.md`).
- `Icamys\SitemapGenerator` : bibliothèque intégrée au core (version 4.3.1) et non maintenue depuis 3 ans.
Correction de bugs internes.
- Flux RSS de blog et news : nullable explicite (`?array`) sur les paramètres optionnels de `FeedWriter`, déprécié depuis PHP 8.4.
- `Helper::translate()` : plus d'avertissement lorsque le texte ou le dictionnaire est indisponible, et protection contre une erreur fatale si le dictionnaire n'est pas encore chargé.
- Gestionnaire de fichiers : les appels sans effet depuis PHP 8.0 ne sont plus exécutés (`curl_close()`, `finfo_close()`, `imagedestroy()`).
- `imagepolygon()` : utilisation de la signature moderne à partir de PHP 8.0 ; le liseré du gestionnaire d'images refonctionne sur PHP 8, avec un rendu identique.
- Fuite de descripteur corrigée lors d'une erreur de téléchargement (le fichier n'était pas refermé en cas d'échec).

### 🛡️ Sécurité
- **Captcha** : vérification côté serveur, à usage unique, pour la connexion, les commentaires de blog et les formulaires.
- **2FA et réinitialisation de mot de passe** : génération de clés cryptographiques, vérification en temps constant et limitation des tentatives de 2FA.
- **Import CSV** : filtrage des champs texte. **Adresse IP** : lecture depuis `REMOTE_ADDR` uniquement.
- **Installation ZIP** : blocage des chemins dangereux et limitation des destinations autorisées.
- **Édition de page** : neutralisation des fermetures de balises injectées dans les champs JS/CSS.
- **En-têtes HTTP** : ajout des principales protections CSP, anti-frame, anti-MIME-sniffing et Referrer-Policy.
- **Réinitialisation du mot de passe** : les liens utilisent l'URL de base du site, déterminée dynamiquement à partir du domaine courant et de l'option URL intelligente, au lieu d'une URL canonique stockée en configuration.

### 🚀 Modules multiples par page
- Une page peut accueillir plusieurs modules, y compris plusieurs instances d'un même module, en haut, dans le contenu ou en bas de page.
- L'éditeur permet d'ajouter, supprimer, réordonner et configurer les instances. Le routeur les affiche et conserve la compatibilité avec les anciennes pages.
- L'onglet Contenu propose un bouton « Configurer » pour chaque module en position libre (même action que l'engrenage de l'onglet Extension).
- La gestion des modules (Extension) affiche l'instance des modules placés dans le contenu, sous la forme « Nom (instanceId) ».
- Migration automatique des pages existantes vers `pageModules` (toutes les langues installées).
- Les actions peuvent cibler une instance déclarée ; la duplication de page copie aussi ses instances, données et fichiers.
- La gestion des modules tient compte des instances et indique les pages qui les utilisent. Les codes `[MODULE:…]` ne sont plus affichés dans l'éditeur.

### 🔍 SEO / IA
- Génération de `llms.txt` avec les pages publiques et les articles publiés des modules blog/news de toutes les langues installées.
- Option d'autorisation des IA et bouton de génération dans *Configuration > Référencement* ; le fichier est actualisé avec le sitemap et à l'enregistrement de la configuration.
- Si l'option est désactivée, `llms.txt` indique explicitement le refus d'exploration. L'option est activée par défaut et proposée à l'installation.
- API de lecture JSON en lecture seule pour les modèles de langage : `api`, `api/pages`, `api/content/<pageId>[/<articleId>]`, `api/search/<termes>`. Réponses filtrées par les mêmes règles que `llms.txt` ; 403 si l'option IA est désactivée, 400/404 en cas de requête invalide. URLs valides avec ou sans réécriture d'URL.

### 📱 Affichage mobile des tableaux
- Sur smartphone, les lignes de tableau s'affichent en cartes pleine largeur avec les en-têtes près des valeurs ; les actions sont élargies et centrées.

### 🛠️ Corrections
- Sitemap : prise en compte des articles publiés des instances blog/news multimodules ; les articles programmés ne sont pas listés avant leur date de publication.
- Les identifiants d'instance sont uniques entre les pages pour isoler les données des modules.
- Isolation du cache JsonDB entre sous-domaines.
- Un module du noyau protégé redirige les visiteurs non connectés vers la connexion si l'option correspondante est activée.
- Editeur de texte réduit (commentaires blog), la fenêtre de confirmation d'un commentaire n'affiche plus la fenêtre d'alerte du navigateur. Nettoyage de l'initialisation de l'éditeur réduit.
- Mise à jour des modules.
- Compatibilité CLI : `Helper::checkServerSoftware()` ne produit plus d'avertissement PHP 8.1 lorsque `$_SERVER['SERVER_SOFTWARE']` est absent.
- Nettoyage des fichiers junks (._*) créés lors de la décompression de l'archive de mise à jour.

## Version 15.2.00
### 🚀 Améliorations
- Module slider : ajout d'options de dimensions (rapport de dimensions automatique, hauteur fixe ou ratio fixe, avec choix de la hauteur et du mode d'ajustement de l'image).
### 🛠️ Corrections
- Module slider : affichage des miniatures dans la configuration, repli sur l'image originale si la miniature est indisponible (GIF animés, échec de génération).

## Version 15.1.04
###  🛠️ Corrections
- Correctif des modules slider et gallery :
    - Méthode de découverte des dossiers,
    - Miniature des images dans le slider.

## Version 15.1.03
###  🛠️ Corrections
- Corrige l'absence d'affichage du pied de page personnalisé.

## Version 15.1.02
### 🚀 Améliorations
- Mise à jour de la langue espagnole.
- La balise méta permet l'autopromotion.

## Version 15.1.01
###  🛠️ Corrections
- Edition de la bannière impossible depuis le thème.

## Version 15.1.00
### 🚀 Améliorations
- Déplacement du bouton de gestion de la structure du menu.
- Refactorisarion classe Layout.
###  🛠️ Corrections
- Sécurise la langue de contenu : impossible d'injecter une valeur invalide en session (`ZWII_SITE_CONTENT`).
- Migration des actions page/menu vers le paramètre `l` (fin du 3e segment d'URL), avec compatibilité rewrite et non-rewrite.
- Le paramètre `l` n'est ajouté que si nécessaire (site multilingue, langue non par défaut) et les liens admin sont fiabilisés (dont l'accueil).

## Version 15.0.08
###  🛠️ Corrections
- Corrige la gestion de la langue par défaut : le marqueur `.default` est désormais toujours recréé lors du changement de langue par défaut, même si aucun marqueur n'existait auparavant.
- Fiabilise la mise à jour du marqueur de langue par défaut : suppression des anciens marqueurs résiduels et conservation d'un seul marqueur actif.
- Corrige l'installateur : le marqueur `.default` est maintenant créé même si le dossier de langue existe déjà.

## Version 15.0.07
###  🛠️ Corrections
- Corrige la génération des liens du module recherche quand les URL intelligentes sont désactivées (suppression du double `?`).
- Restaure la compatibilité PHP 7.3 dans la génération du sitemap (remplacement de `str_contains` par une alternative compatible).

## Version 15.0.06
###  🛠️ Corrections
- Corrige l'indexation du sitemap pour ne référencer que les langues réellement installées.
- Supprime les sous-sitemaps orphelins lors d'une régénération complète.
- Déclenche automatiquement la régénération du sitemap après suppression d'une langue de contenu et après changement de langue par défaut.

## Version 15.0.05
### 🚀 Améliorations
- Génération automatique des métas de page :
  - Remplissage automatique du méta-titre et de la méta-description quand les champs sont vides.
  - Génération améliorée de la méta-description avec sélection de phrases pertinentes (au lieu du simple début du HTML).
- Catégories d'onglets dans l'édition de page précisées **Gabarits et navigation** et **Permission & SEO**.

## Version 15.0.04
###  🛠️ Corrections
- Corrige la génération des fichiers sitemap et robots.txt pour qu'ils soient écrits à la racine du site.

## Version 15.0.03
###  🛠️ Corrections
- Corrige la suppression d'une langue depuis la vue de configuration des langues.
- Améliore la compatibilité des migrations avec les anciens dossiers `ressource` et les nouveaux dossiers `resource`.

## Version 15.0.02
###  🛠️ Corrections
- Corrige un problème de sélection des pages du site dans l'éditeur TinyMCE.

## Version 15.0.01
###  🛠️ Corrections
- Corrige un problème avec la génération des sitemaps.

## Version 15.0.00
### 🚀 Améliorations
- L'identité du site (titre, description, pages spéciales, étiquettes, cookies) est désormais accessible directement depuis un onglet **Identité** dans la configuration, sans passer par le module Langues.
- Vue Langues modernisée : boutons d'action en icônes, bascule de langue active directement dans le tableau.
- Modernisation de la vue de gestion des langues.
- Mise à jour des fichiers de langues livrés.
### 🔍 SEO multilingue
- Référencement amélioré pour les sites multilangues : chaque version linguistique dispose désormais de son propre sitemap (`sitemap-{lang}.xml`), indexé dans un `sitemap.xml` central.
- Les robots des moteurs de recherche peuvent accéder à n'importe quelle version linguistique via le paramètre `?l={lang}` sans nécessiter de session active.
- Les liens canoniques, les menus et le plan du site HTML intègrent le paramètre de langue pour les versions non-défaut.
- Seuls Google et Bing reçoivent la notification du sitemap index.
###  🛠️ Corrections
- Rendu des hauteurs de polices de caractères dans l'éditeur.
