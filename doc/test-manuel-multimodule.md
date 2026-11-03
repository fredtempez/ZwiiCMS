# Test manuel — Multi-module v16.0.00 (ZwiICMS)

> **Objectif** : valider manuellement le bon fonctionnement du multi-module après la
> refonte de l'identité d'instance (convention de clé `moduleId-<zone>`).
> **Public** : testeur / développeur, sur une instance locale.
> **Docs liées** : `guide-multimodule-data.md`, `instruction-conformite-multimodule.md`,
> `analyse-boutons-multimodule.md`, `BRIEF-multi-module-v16.md`.

---

## 1. Rappel de la convention à valider

| Notion | Valeur |
|---|---|
| Clé d'instance dans `pageModules` | `moduleId-top`, `moduleId-bottom`, `moduleId-free-<uniq>` (ex. `gallery-top`, `gallery-free-k3f9`, `gallery-bottom`) |
| Zones | `top` (1 module), `free` (N modules), `bottom` (1 module) |
| Jeton `free` | **chaque** instance `free` (même la 1ʳᵉ) reçoit un jeton unique `<uniq>` (sans tiret), généré côté client et réutilisé par le serveur |
| Champ `position` | **plus stocké** dans `pageModules` ; déduit de la clé |
| Données de l'instance | `['module', <instanceId>, …]` (via `getUrl(0)`) |
| Données de la page hôte | `['page', <pageId>, …]` (via `getPageId()`) |
| Marqueur de contenu (zone free) | `[MODULE:<instanceId>]` |

**Règle de déduction de zone** (`Core::getInstancePosition($instanceId)`, méthode du
cœur ; `page::getInstancePosition()` en est un simple relais) :
- contient `-free` → `free` (**testé en premier** : le jeton unique suit `-free`) ;
- se termine par `-top` → `top` ;
- se termine par `-bottom` → `bottom` ;
- sinon → `bottom` (défaut de compatibilité).

---

## 2. Pré-requis

- Installation ZwiICMS neuve **ou** site existant migré (`dataVersion = 160000`).
- Au moins deux modules **multi-instance** disponibles : `gallery`, `slider`, `search`
  ou `form` (`MULTIMODULE = true`, `SINGLEINSTANCE = false`).
- Au moins un module **mono-instance** disponible. Deux cas, tous deux présents dans le
  dépôt :
  - mono-instance **par** `MULTIMODULE` (`false`) : `blog`, `redirection` ;
  - mono-instance **malgré** `MULTIMODULE = true`, ramené à une seule instance par
    `SINGLEINSTANCE = true` : `news`.
- Accès **éditeur** minimum pour ouvrir l'édition de page.

### 2.1 Règle d'instanciation (source : `Core::isMultiInstanceAllowed()`)

| `MULTIMODULE` | `SINGLEINSTANCE` | Instances autorisées |
|---|---|---|
| absent ou `false` | — (non lue) | **une seule** |
| `true` | absent ou `false` | plusieurs |
| `true` | `true` | **une seule** |

Cette règle est appliquée à **trois** endroits, qui doivent rester cohérents :
1. `Router::renderModules()` — ne rend qu'une instance des modules mono-instance ;
2. `page::$multiModuleIds` → `edit.js.php` — désactive l'option déjà choisie ailleurs (aide à la saisie) ;
3. `page::buildPageModulesFromInput()` — **contrôle serveur** : n'enregistre qu'une
   instance des modules mono-instance (voir série G).

> Le point 2 n'est qu'une **aide à la saisie** : il est contournable (JS désactivé, POST
> forgé, formulaire périmé). Le point 3 est le contrôle **autoritaire**.

Outils : navigateur (onglet Extension = `[MODULE:…]`, onglet Contenu = liste), et un
accès aux fichiers `site/data/<langue>/page.json` + `module.json` pour les vérifications
de structure.

---

## 3. Cas de test

### T1 — Création d'une instance unique par zone

**Étapes**
1. Créer une page `essai-multi`.
2. Ouvrir **Édition → onglet Extension**.
3. Zone haute : choisir un module (ex. `search`).
4. Zone centrale : ajouter une ligne, choisir un module `free` (ex. `slider`).
5. Zone basse : choisir un module (ex. `form`).
6. Enregistrer.

**Attendu (UI)**
- bouton contenu de la zone centrale affiche `[MODULE:slider-free]`.

**Attendu (`page.json`)**
```php
'essai-multi' => [
    // …
    'pageModules' => [
        'search-top'   => ['moduleId' => 'search'],
        'slider-free'  => ['moduleId' => 'slider'],
        'form-bottom'  => ['moduleId' => 'form'],
    ],
]
```
- Aucun champ `position` dans les entrées. ✅
- Clés en `moduleId-<zone>`. ✅

---

### T2 — Plusieurs instances en zone « free »

**Étapes**
1. Sur `essai-multi`, onglet Extension.
2. Zone centrale : ajouter une **2ᵉ** ligne, choisir **le même module** que la 1ʳᵉ
   (ex. `slider`).
3. Enregistrer.

**Attendu (`page.json`)**
```php
'pageModules' => [
    // …
    'slider-free-k3f9' => ['moduleId' => 'slider'],   // jeton unique
    'slider-free-m7x2' => ['moduleId' => 'slider'],   // jeton unique distinct
]
```
- Chaque instance reçoit un **jeton unique** (`-free-<uniq>`), y compris la 1ʳᵉ. ✅
- L'id d'une instance ne dépend pas de l'autre (aucun suffixe séquentiel). ✅
- Séparation des données : `module.json` contient `['module', 'slider-free-k3f9']` **et**
  `['module', 'slider-free-m7x2']` distincts. ✅

---

### T3 — Un module mono-instance ne peut pas être dupliqué

**Étapes (voie normale, UI)**
1. Onglet Extension, zone centrale : choisir un module **mono-instance** (ex. `news`).
2. Ajouter une 2ᵉ ligne : l'option `news` doit être **désactivée** dans le sélecteur
   (règle `edit.js.php` → `refreshModuleSelectAvailability()`).
3. Enregistrer.

**Attendu (UI)**
- L'option du module mono-instance est **grisée** dans tous les autres sélecteurs de la
  page (y compris les zones fixes haut/bas).
- Le doublon est impossible à créer depuis l'interface.

**Attendu (`page.json`)**
- Une **seule** clé pour ce module (`news-<pageId>-free-<uniq>`).

**Variante « contrôle serveur »** : voir **G2** (POST forgé), qui vérifie le même
invariant en contournant l'UI. L'ancien attendu (« `page.json` peut contenir les deux
clés, le routeur ignore la 2ᵉ ») **n'est plus valable** : depuis le garde-fou
`buildPageModulesFromInput()`, le doublon n'est **plus enregistré du tout**.

---

### T4 — Changement de zone = nouvelle identité

**Étapes**
1. Sur `essai-multi`, déplacer le module `search` de la zone **haute** vers la zone
   **basse** (sélection dans les deux selects fixes).
2. Enregistrer.

**Attendu**
- `search-top` **disparaît** ; `search-bottom` **apparaît**.
- Les données de l'ancienne instance (`['module','search-top']`) sont purgées.
- Le dossier de données `search-top` est supprimé (module avec `dataDirectory`).

---

### T5 — Marqueur `[MODULE:…]` dans le contenu (zone free)

**Étapes**
1. Page `essai-page-contenu` avec un module `free` (ex. `gallery-free`).
2. Onglet **Contenu** : insérer le code `[MODULE:gallery-free]` au milieu d'un paragraphe.
3. Enregistrer, afficher la page publiquement.

**Attendu**
- Le module s'affiche **à l'emplacement** du marqueur (et non en bas).
- Si le marqueur `[MODULE:gallery-free]` est **absent** du contenu, le module `free`
  n'est **pas** rendu (pas de repli en bas). ✅

**Variante** : marqueur générique `[MODULE]` → le premier module `free` non encore
substitué remplace ce marqueur (remplacement unique).

---

### T6 — Duplication de page

**Étapes**
1. Dupliquer `essai-multi` (onglet Formulaire → « Dupliquer »).

**Attendu**
- La copie conserve **les mêmes clés** `pageModules` (`search-top`, `slider-free`…).
- Les **données** et **dossiers** de chaque instance de la copie reçoivent un nouvel
  id unique (pas d'écrasement de la page source).
- Les shortcodes `[MODULE:<clé>]` du contenu sont renumérotés si nécessaire.

---

### T7 — Renommage de page

> **Comportement à connaître** : les clés d'instance des **zones fixes**
> (`top`/`bottom`) sont **scopées par le pageId** (`moduleId-<pageId>-<zone>`).
> Renommer une page **renomme donc ces clés**. Le report des données
> (`['module', <clé>]`, dossier `dataDirectory`, `theme.style`) vers les
> nouvelles clés est assuré par `carryOverModuleInstanceData()`. Les clés de la
> **zone libre** (`…-free-<uniq>`), elles, sont transmises par le client et
> réutilisées telles quelles : elles **ne changent pas** au renommage.

**Étapes**
1. Créer `essai-multi` avec un module en zone **haute** (ex. `gallery`), un
   module en zone **libre** (ex. `slider`) et un module en zone **basse** (ex. `form`).
2. Renseigner une configuration non triviale pour chaque module (ex. sélectionner
   un dossier d'images pour `gallery`, ajouter une image à `slider`) afin de
   pouvoir détecter une perte de données.
3. Renommer `essai-multi` en `essai-multi-renomme`.
4. Enregistrer, puis rouvrir la page en édition et l'afficher publiquement.

**Attendu (`page.json`)**
- Clés des zones fixes **renommées** avec le nouveau pageId :
  `gallery-essai-multi-renomme-top`, `form-essai-multi-renomme-bottom`.
- Clé de la zone libre **inchangée** (`slider-free-<uniq>` ou
  `slider-essai-multi-free-<uniq>` selon son format d'origine).
- **Aucune** clé scopée sur l'ancien pageId ne subsiste
  (pas de `gallery-essai-multi-top` résiduel).

**Attendu (`module.json` et dossiers)**
- Les données de chaque instance sont accessibles sous les **nouvelles** clés :
  la configuration saisie à l'étape 2 est **intégralement conservée** (dossier
  d'images, légendes, thème). **Aucune perte.**
- Les entrées `['module', <ancienne clé>]` ont été **purgées**.
- Pour un module avec `dataDirectory`, le dossier a été **déplacé**
  (`module/<module>/data/<nouvelle clé>/`), pas recréé vide.
- `theme.style` pointe le nouveau dossier.

**Attendu (rendu)**
- Les trois modules s'affichent **à leur place** (haut / milieu / bas), avec
  leur configuration d'origine.
- Le shortcode `[MODULE:…]` de la zone libre reste valide (sa clé n'a pas changé).

> ⚠️ **Régression historique** : tant que la capture des instances
> (`$existingModules`) était faite *après* la suppression de l'ancienne page,
> elle renvoyait `null` et la réconciliation ne s'exécutait jamais : les clés des
> zones fixes n'étaient pas reportées et les modules apparaissaient « installés
> mais non configurés » (ex. `slider` sans image). Ce test est le garde-fou
> contre ce retour en arrière.

---

### T8 — Suppression de page

**Étapes**
1. Supprimer `essai-multi`.
2. Vérifier `module.json` et les dossiers de données.

**Attendu**
- Chaque instance déclarée est purgée : données `['module', <instanceId>]` **et**
  dossier `dataDirectory<instanceId>`.
- Pas de données orphelines.

---

### T9 — Migration d'un site legacy

**Pré-requis** : sauvegarde d'un `page.json` avec l'ancien format
(`moduleId` + `modulePosition`, et/ou clés d'instance = pageId), `dataVersion < 160000`.

**Attendu après application de `update.inc.php`**
- Bloc **16.0.00** : pages legacy → `pageModules` reconstruit en `moduleId-<zone>`.
- Migration des données (`module.<ancienneClé>` → `module.<nouvelleClé>`), dossiers
  déplacés, shortcodes réécrits, `config.seo.llms` activé.
- `dataVersion = 160000` en fin de migration.

---

### T10 — Multi-lingue

**Étapes**
1. Installer deux langues de contenu (ex. `fr_FR`, `en_EN`).
2. Placer des modules sur une page dans **chaque** langue.

**Attendu**
- La migration/normalisation s'applique à **toutes** les langues de contenu.
- Le contexte de langue est restauré en fin de migration (`self::$siteContent`).

---

### T11 — URL de base du site et réinitialisation du mot de passe

**Étapes**
1. Ouvrir **Configuration > Configuration** (onglet Setup).
2. Repérer le champ *URL de base du site*, sous la case **Apache URL intelligentes**.
3. Basculer l'option URL intelligente et observer la valeur.
4. Se déconnecter, cliquer sur *Mot de passe oublié*, saisir l'adresse d'un compte.
5. Ouvrir le mail reçu et suivre le lien de réinitialisation.

**Attendu**
- Le champ est **désactivé** (non saisissable) et affiche une **URL absolue**
  incluant le protocole : `https://domaine.tld[/sous-dossier]/` si l'URL
  intelligente est active, `…/?` sinon.
- Aucune valeur n'est écrite dans `site/data/<langue>/config.json` : la clé
  `canonicalUrl` **n'existe pas** (elle n'est plus stockée).
- Le lien du mail est **absolu** (`https://…`) et mène au formulaire de
  réinitialisation ; l'URL reste correcte avec et sans URL intelligente.
- En contexte sans hôte HTTP (CLI, cron), `Helper::canonicalBaseUrl()` renvoie
  une chaîne vide et le message « URL de base indéterminable » est journalisé.

---

## 3 bis. Série G — Contrôle d'unicité CÔTÉ SERVEUR

> **But** : valider `page::buildPageModulesFromInput()`, qui applique la règle
> `Core::isMultiInstanceAllowed()` **à l'enregistrement**, indépendamment du JavaScript.
> Ces tests **contournent volontairement** l'UI : ce sont des tests de robustesse.

**Outils** : onglet réseau du navigateur, ou `curl`, ou désactivation du JS. Pour un POST
forgé, le plus simple est de désactiver le JS (`about:config` → `javascript.enabled`,
ou l'extension idoine), de saisir le formulaire, puis de **réactiver l'option grisée**
via la console avant de soumettre.

Après chaque test, contrôler `page.json` **et** le journal (`site/data/journal.log`,
si la journalisation est active dans Configuration > Configuration).

---

### G1 — Deux instances d'un module multi-instance : conservées

**Étapes**
1. Page avec deux lignes de zone centrale portant **le même** module `slider`
   (`MULTIMODULE = true`, `SINGLEINSTANCE = false`).
2. Enregistrer.

**Attendu**
- `page.json` contient **les deux** clés (`slider-<pageId>-free-<uniq1>` et `…<uniq2>`).
- Les deux instances sont rendues publiquement.
- Aucune ligne « ignorée » dans le journal.

---

### G2 — Deux instances d'un module `SINGLEINSTANCE` : la 2ᵉ est écartée

**Étapes**
1. Page avec deux lignes portant **le même** module `news`
   (`MULTIMODULE = true`, `SINGLEINSTANCE = true`).
2. Forcer la soumission sans le filtre JS (voir outils ci-dessus).
3. Enregistrer.

**Attendu**
- `page.json` ne contient **qu'une** clé pour `news` : la **première** soumise.
- Le journal contient : `Instance <clé> ignorée : le module news n'autorise qu'une instance par page.`
- **Aucune** donnée orpheline : ni `['module', <clé écartée>]` dans `module.json`, ni
  dossier `module/news/data/<clé écartée>/`.

---

### G3 — Zones fixes vs zone libre : c'est la zone haute qui gagne

**Étapes**
1. Placer `news` en zone **haute** (`pageModulesTopModuleId`).
2. Ajouter une ligne en zone **centrale** avec `news` également
   (`pageModulesFreeModuleId<row>`).
3. Forcer la soumission.

**Attendu**
- Seule la clé de la zone **haute** (`news-<pageId>-top`) est conservée.
- Justification : l'ordre de construction — et donc de rendu du routeur — est
  **haut → libre → bas**. L'instance conservée est celle que le routeur aurait affichée.

---

### G4 — Auto-réparation d'une page portant déjà un doublon

**Pré-requis** : fabriquer un `page.json` contenant **deux** clés `news`
(par exemple en éditant le fichier à la main, ou avec une version antérieure au garde-fou).

**Étapes**
1. Ouvrir la page en édition et **enregistrer sans rien changer**.

**Attendu**
- L'instance excédentaire est **purgée** au prochain enregistrement : sa clé disparaît de
  `pageModules`, son entrée `['module', <clé>]` est supprimée et son dossier de données
  est effacé (via la réconciliation des instances retirées de `edit()`).
- L'instance conservée (la première) garde ses données intactes.

---

### G5 — Module tiers sans `MULTIMODULE` : protégé par défaut

**Étapes**
1. Utiliser un module **tiers** ne déclarant ni `MULTIMODULE` ni `SINGLEINSTANCE`.
2. Tenter de le placer deux fois (POST forgé).

**Attendu**
- Une seule instance est conservée : absence de `MULTIMODULE` ⇒ `false` ⇒ mono-instance.
- Aucune régression : c'est le comportement du routeur, désormais appliqué aussi au stockage.

---

### G6 — Non-régression : page sans doublon strictement inchangée

**Étapes**
1. Sur une page ne contenant **aucun** doublon (mix de modules multi et mono-instance).
2. Enregistrer, puis recharger en édition et réenregistrer.

**Attendu**
- Le contenu de `pageModules` est **identique** (mêmes clés, **même ordre**, mêmes
  valeurs) avant et après enregistrement.
- Aucune ligne de journal n'est produite.

> C'est le test de garde anti-régression : le garde-fou ne doit **rien** modifier sur une
> page saine. Toute différence ici signale une régression.

---

## 4. Vérifications transverses

- [ ] **Sitemap** : `updateSitemap()` produit les URLs correctes (instanceId → pageId hôte).
- [ ] **Meta description blog** : le test `moduleId === 'blog'` fonctionne en multi-module.
- [ ] **Barres latérales** : les modules des barres ne sont pas affectés par l'instance.
- [ ] **Vendors** : les librairies JS/CSS des modules sont chargées **une seule fois**.
- [ ] **Vitrine (page simple)** : une page sans module valide affiche son contenu (pas de 404).
- [ ] **Anomalies de contexte** : aucun message d'anomalie dans le journal
      (`Layout` affiche les anomalies détectées par `checkModuleInstanceContext`).
- [ ] **Fuite de contexte** : profondeur de pile `getModuleInstanceDepth() === 0`
      après la boucle de rendu (pas de log « non équilibré »).

---

## 5. Grille de résultats (à remplir)

| Test | Description | Résultat | Commentaire |
|------|-------------|----------|-------------|
| T1 | instance unique par zone | ☐ OK ☐ KO | |
| T2 | plusieurs instances `free` (jeton unique) | ☐ OK ☐ KO | |
| T3 | mono-instance non dupliqué | ☐ OK ☐ KO | |
| T4 | changement de zone = nouvelle identité | ☐ OK ☐ KO | |
| T5 | marqueur `[MODULE:…]` zone free | ☐ OK ☐ KO | |
| T6 | duplication de page | ☐ OK ☐ KO | |
| T7 | renommage de page (report des instances) | ☐ OK ☐ KO | |
| T8 | suppression de page | ☐ OK ☐ KO | |
| T9 | migration legacy | ☐ OK ☐ KO | |
| T10 | multi-lingue | ☐ OK ☐ KO | |
| T11 | URL de base du site / reset mot de passe | ☐ OK ☐ KO | |
| G1 | serveur : module multi-instance conservé (×2) | ☐ OK ☐ KO | |
| G2 | serveur : module `SINGLEINSTANCE` → 1 instance | ☐ OK ☐ KO | |
| G3 | serveur : zone haute prioritaire sur zone libre | ☐ OK ☐ KO | |
| G4 | serveur : auto-réparation d'un doublon hérité | ☐ OK ☐ KO | |
| G5 | serveur : module tiers sans `MULTIMODULE` | ☐ OK ☐ KO | |
| G6 | serveur : non-régression (page saine inchangée) | ☐ OK ☐ KO | |

---

## 6. Commandes utiles (vérification structure JSON)

Inspecter les clés d'instance d'une page :

```sh
# Clés pageModules de la page
grep -n "pageModules" -A 20 site/data/fr_FR/page.json

# Clés de données de module
grep -n "\"" site/data/fr_FR/module.json | head -40
```

Vérifier qu'aucune clé ne contient d'ancien format (id de page seul, `moduleId` nu) :

```sh
# Doit n'afficher que des clés `*-top`, `*-bottom`, `*-free-<uniq>`
grep -oE "\"[a-z0-9_]+-(top|bottom|free-[a-z0-9]+)\"" site/data/fr_FR/page.json
```

> ⚠️ Sur un module `dataDirectory`, vérifier la présence du dossier
> `module/<module>/data/<instanceId>/` (ex. `module/gallery/data/<instanceId>/`).

---

## 7. Critères d'acceptation

Le test est **validé** si :
1. Tous les cas T1 → T11 sont **OK**.
2. Tous les cas G1 → G6 sont **OK** (contrôle serveur de l'unicité).
3. `page.json` ne contient **aucun** champ `position` dans `pageModules`.
4. Aucune donnée orpheline dans `module.json` après suppression/renommage.
5. Aucun message d'anomalie de contexte dans le journal.
6. Le rendu public (top / free / bottom) respecte l'ordre et les marqueurs.
