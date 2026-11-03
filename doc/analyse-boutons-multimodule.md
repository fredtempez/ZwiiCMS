# Analyse globale — Écriture des boutons dans les modules (multi-module)

> Objectif : recenser **tous** les patterns d'URL utilisés par les boutons, liens, redirections
> et retours des modules, déterminer lesquels sont corrects en **multi-module**, et isoler les
> cas à corriger. S'appuie sur `core/trait/Data.trait.php` (`getUrl`, `getPageId`, `getUrlArg`,
> `resolveModuleInstanceUrl`, `resolveModuleInstanceKeys`) et `core/class/router.class.php`
> (`resolveInstanceUrl`, `renderModules`).

---

## 1. Rappel du mécanisme (ce qui rend `getUrl(0)` correct)

Le routeur **réécrit l'URL** avant tout routage (`Router::resolveInstanceUrl()`) :

- URL **courte** générée par un module : `/<instanceId>/<action>[/<reste>]`
- URL **canonique** obtenue : `/<pageId>/<action>/<instanceId>[/<reste>]`

Puis, pendant l'exécution du module, `getUrl()` est réécrit
(`Data::resolveModuleInstanceUrl`) :

| Méthode | En contexte d'instance | Hors contexte |
|---|---|---|
| `getUrl(0)` | **instanceId** | pageId |
| `getPageId()` | **pageId hôte** | pageId |
| `getUrl(1)` | action | action |
| `getUrl(2)` | **instanceId** (canonique) ou 1er arg | 1er arg |
| `getUrlArg(n)` | n-ième argument **après** l'instanceId | n-ième argument après l'action |

**Conséquence centrale** : un lien `helper::baseUrl() . $this->getUrl(0) . '/config'`
produit `/<instanceId>/config`, que le routeur **reconnaît et réécrit** en
`/<pageId>/config/<instanceId>` → cible la bonne instance. **Donc, pour les actions
internes au module (config, option, add, edit, theme, update…), `getUrl(0)` est correct
et suffisant.**

Idem pour les données : `['module', $this->getUrl(0), …]` est correct (substitué par
l'instanceId via `resolveModuleInstanceKeys`).

---

## 2. Catégorisation des boutons (état réel du code)

### A. Navigation interne au module depuis `config` (option, add, edit, theme, update, data, categories…)
**Pattern** : `helper::baseUrl() . $this->getUrl(0) . '/<action>'`
**Verdict : ✅ CORRECT** (routé/réécrit vers `/<pageId>/<action>/<instanceId>`).
Exemples : `blog/view/config/config.php` → `/option`, `/add` ; `slider/view/config/config.php`
→ `/theme`, `/update` ; `download/view/config/config.php` → `/categories`, `/add`, `/option`.

### B. Retour vers `config` depuis une sous-vue (edit, add, option, theme, update, comment…)
**Pattern** : `helper::baseUrl() . $this->getUrl(0) . '/config'`
**Verdict : ✅ CORRECT** (même mécanisme).
Exemples : `blog/view/option/option.php`, `blog/view/edit/edit.php`, `gallery/view/theme/theme.php`,
`download/view/edit/edit.php`, `upload/view/data/data.php`, etc.

### C. Redirections après traitement (contrôleurs `*.php`)
**Pattern** : `'redirect' => helper::baseUrl() . $this->getUrl(0) . '/<action>'`
**Verdict : ✅ CORRECT** (même réécriture ; ex. `blog::add()` → `/config`, `sondage` → `/data`).

### D. Retour vers l'**édition de la page hôte** depuis `config`
Deux conventions coexistantes :

| Pattern | Verdict | Remarque |
|---|---|---|
| `'page/edit/' . $this->getUrl(0)` | ⚠️ **dépend** | En contexte d'instance, `getUrl(0)` = instanceId. Le routeur réécrit `page/edit/<instanceId>` → `page/edit/<pageId>` **uniquement** si le 3e segment (index 2) est l'instanceId. Donc `page/edit/<instanceId>` fonctionne, mais **sans** segment langue qui suit, ou avec la langue **après** l'instanceId (`page/edit/<instanceId>/<lang>`) — le routeur remplace l'index 2 uniquement. |
| `'page/edit/' . $this->getPageId() . '/' . self::$siteContent` | ✅ **ROBUSTE** | Convention explicite recommandée par la doc (`guide-multimodule-data.md`, `getPageId()`). |

**Cas réel problématique** (signalé) : `blog/config` génère
`page/edit/<getUrl(0)>/<lang>` = `page/edit/<instanceId>/<lang>`. Le routeur remplace
l'index 2 (`<instanceId>`) par le pageId → OK **si** l'index 2 est bien l'instanceId.
Mais plusieurs modules (download, resource, calendar, geolocation) génèrent
`page/edit/<getUrl(0)>` **sans langue ni suffixe** : dans ce cas l'index 2 = instanceId →
réécrit correctement. En revanche, dès qu'un paramètre supplémentaire précède, le
mécanisme devient fragile.

**Recommandation** : uniformiser sur `getPageId()` + langue explicite.

### E. Liens publics vers un article/une page à partir de `config`
**Pattern** : `helper::baseurl() . $this->getUrl(0) . '/' . $articleId`
**Verdict : ✅ CORRECT** (route vers `/<pageId>/<instanceId>/<articleId>`… à vérifier selon
la position `free`, mais le mécanisme reste cohérent).

### F. Navigation dans le cœur (module `page`)
**Pattern** : `'page/edit/' . $this->getUrl(2)` et `getData(['page', $this->getUrl(2)])`
**Verdict : ✅ CORRECT en admin** (pas de contexte d'instance pour le module `page`).

---

## 3. Points faibles identifiés

1. **Retour vers `page/edit`** : mélange de `$this->getUrl(0)` (≈10 fichiers) et de
   `$this->getPageId()` (≈6 fichiers). Le premier dépend d'une réécriture implicite du
   routeur (`resolveInstanceUrl`, branche `$first === 'page'`) ; le second est explicite.
   → **Uniformiser sur `getPageId()`**.

2. **Segment langue** inégal : certains ajoutent `'/' . self::$siteContent`, d'autres non
   (download, resource, calendar, geolocation, sondage). Sans conséquence en mono-langue,
   mais incohérent et fragile en multi-langue.

3. **`getUrl(3 + $index)` / `getUrl(2 + $index)` codés en dur** dans d'anciens modules
   (non `MULTIMODULE`) pour lire un argument : à remplacer par `getUrlArg($index)` dans
   tout module déclaré `MULTIMODULE = true`. (Le module `blog` utilise déjà `getUrlArg(0)`.)

4. **Les redirections** ne devraient pas transporter `instanceId` en premier segment si
   l'action cible un autre module ; hors sujet ici mais à surveiller.

---

## 4. Tableau de synthèse

| Catégorie | Pattern correct | Pattern à éviter |
|---|---|---|
| Action interne module | `getUrl(0) . '/<action>'` | — |
| Retour `config` | `getUrl(0) . '/config'` | — |
| Redirection interne | `getUrl(0) . '/<action>'` | — |
| Retour édition page | `getPageId() . '/' . <lang>` | `getUrl(0)` seul (fragile/incohérent) |
| Argument après action | `getUrlArg(n)` | `getUrl(2+n)` codé en dur |
| Données module | `['module', getUrl(0), …]` | — |
| Données page hôte | `['page', getPageId(), …]` | `['page', getUrl(0), …]` |

---

## 5. Conclusion

**La grande majorité des boutons (`getUrl(0) . '/config'`, `/option`, `/add`, `/edit`…) est
déjà correcte** : le routeur réécrit ces URL courtes vers le schéma canonique
`/<pageId>/<action>/<instanceId>`. Il n'est donc **pas** nécessaire de remplacer
`$this->getUrl(0) . '/config'` par `$this->getPageId() . '/config/' . $this->getUrl(0)` :
les deux approches produisent le même résultat, et la première est celle du cœur.

Le **seul point réellement incohérent** est le **retour vers l'édition de la page hôte**
(catégorie D), où le code mélange `getUrl(0)` et `getPageId()`. La correction recommandée
est d'**uniformiser sur `getPageId()`** (avec la langue le cas échéant), conformément à
`guide-multimodule-data.md`.
