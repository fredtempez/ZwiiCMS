# Instruction — Mise en conformité des modules avec le multi-module (ZwiICMS)

> **Public** : agent de développement (humain ou IA) chargé de rendre un module
> conforme à l'architecture **multi-module** de ZwiICMS.
> **Objectif** : produire un audit puis appliquer les corrections, module par module,
> sans régression.
> **Docs liées** : `guide-multimodule-data.md`, `analyse-boutons-multimodule.md`,
> `guide-ecriture-modules.md`, `api-core-methods.md`.

---

## 0. Ce que tu dois produire

Pour le ou les modules qui te sont confiés :

1. Un **audit** (tableau) listant chaque bouton/lien/redirection et son verdict.
2. Les **corrections** de code strictement nécessaires.
3. Un **rapport final** (voir §7) : ce qui a été changé, ce qui a été volontairement laissé, et pourquoi.

Tu ne dois **jamais** introduire de changement cosmétique non demandé (versions,
`@link`, contenus de vue, CSS…) : c'est une source de régression silencieuse.

---

## 1. Rappel du mécanisme (à comprendre avant d'agir)

Le routeur réécrit l'URL avant tout routage (`Router::resolveInstanceUrl()`) :

- URL **courte** produite par un module : `/<instanceId>/<action>[/<reste>]`
- URL **canonique** obtenue : `/<pageId>/<action>/<instanceId>[/<reste>]`

Pendant l'exécution d'un module, `getUrl()` est réécrit :

| Méthode | En contexte d'instance | Hors contexte |
|---|---|---|
| `getUrl(0)` | **instanceId** | pageId |
| `getPageId()` | **pageId hôte** (toujours) | pageId |
| `getUrl(1)` | action | action |
| `getUrl(2)` | **instanceId** (canonique) | 1er argument |
| `getUrlArg(n)` | n-ième argument **après** l'instanceId | n-ième argument après l'action |
| `getPageEditUrl()` | URL d'édition de la page hôte | idem |

**Conséquence centrale** : un lien `helper::baseUrl() . $this->getUrl(0) . '/config'`
produit `/<instanceId>/config`, que le routeur **réécrit** en `/<pageId>/config/<instanceId>`.
→ Pour **toutes** les actions internes au module, `getUrl(0)` est **correct et suffisant**.

---

## 2. Arbre de décision — quel identifiant utiliser ?

| Besoin | À utiliser | À NE PAS utiliser |
|---|---|---|
| Lien vers une action du **même module** (`config`, `add`, `edit`, `option`, `data`, `theme`, `delete`, `comment`, …) | `getUrl(0) . '/<action>'` | `getPageId()` |
| Données **propres à l'instance** du module (`['module', …, 'posts', …]`, config, contenu) | `['module', $this->getUrl(0), …]` | `getPageId()` |
| Données de **la page hôte** (`['page', …, 'css'|'js'|'pageModules'|'title'|'role']`) | `['page', $this->getPageId(), …]` | `getUrl(0)` |
| Lire un **argument d'action** (id d'item, de galerie, d'utilisateur, auth…) dans un module `MULTIMODULE = true` | `getUrlArg(n)` | `getUrl(2 + n)` codé en dur |
| Bouton **retour vers l'édition de la page** hôte | `$this->getPageEditUrl()` | `'page/edit/' . getUrl(0)` |
| Lien **public** vers un article/item depuis `config` | `getUrl(0) . '/' . $itemId` | — |
| Structure d'URL d'une action **paramétrée** (lien email, etc.) | schéma canonique `/<pageId>/<action>/<instanceId>/<arg0>/<arg1>` | concaténation fragile |

---

## 3. Procédure d'audit (par module)

Pour **chaque** module, exécute et relève les résultats :

### 3.1 Retours vers l'édition de page
```bash
grep -rn "page/edit/" module/<module> --include="*.php"
```
- **Attendu** : aucune occurrence de `page/edit/` construite manuellement.
- **Correction** : remplacer par `$this->getPageEditUrl()`.

### 3.2 Boutons de retour config / sous-vues
```bash
grep -rn "'href'" module/<module> --include="*.php" | grep -iE "getUrl|getPageId|getPageEditUrl|page/edit"
```
- **Attendu** : retour config → `getPageEditUrl()` ; navigation interne → `getUrl(0) . '/<action>'`.

### 3.3 Arguments d'action codés en dur
```bash
grep -rn "getUrl(2)\|getUrl(3)\|getUrl(1)" module/<module>/<module>.php
```
- Ne signaler comme **anomalie** que si le module a `const MULTIMODULE = true;`
  **et** que l'appel sert à lire un **argument applicatif** après l'action.
- **Correction** : remplacer par `getUrlArg($index)` (`getUrl(2)` → `getUrlArg(0)`, etc.).
- **Ne pas toucher** aux usages de `getUrl(1)` servant à la détection d'action/routage
  (ex. `intval($this->getUrl(1)) === 0`) : c'est un idiome du cœur.

### 3.4 Incohérences de casse
```bash
grep -rn "geturl(" module/<module> --include="*.php"
```
- **Correction** : `geturl(` → `getUrl(` (sans bug fonctionnel, mais à uniformiser dans `module/`).
- **Hors périmètre** : `core/` (fichiers partagés, identiques entre projets → ne pas modifier).

### 3.5 Redirections après traitement
```bash
grep -rn "'redirect'" module/<module>/<module>.php | grep -v "view/"
```
- Redirection vers une action du **même module** → `getUrl(0) . '/<action>'` : ✅ correct.
- Ne pas confondre avec un lien vers `page/edit` (cf. 3.1).

### 3.6 Données de la page hôte
```bash
grep -rn "getData(\['page'\|setData(\['page'\|deleteData(\['page'" module/<module> --include="*.php"
```
- **Anomalie** : `['page', $this->getUrl(0), …]` dans un module `MULTIMODULE = true`.
- **Correction** : `['page', $this->getPageId(), …]`.

### 3.7 À ne PAS auditer (faux positifs connus)
- `module/<module>/profil/view/edit.inc.php` : utilise `getUrl(2)`/`getUrl(3)` dans un
  contexte **profil** (cœur `user/profil`), identique dans tous les modules → correct.
- Modules avec `const MULTIMODULE = false;` (ex. `download`, `redirection`) : pas de
  décalage d'instance, donc `getUrl(2)` y reste l'argument légitime.
- Lignes **commentées** : ne pas modifier.

---

## 4. Grille de verdict (à remplir dans le rapport)

Pour chaque lien rencontré :

| Fichier:ligne | Lien | Catégorie | Verdict | Action |
|---|---|---|---|---|
| `view/config/config.php:5` | `getPageEditUrl()` | Retour page hôte | ✅ | — |
| `view/config/config.php:12` | `getUrl(0) . '/users'` | Action interne | ✅ | — |
| `view/edit/edit.php:6` | `geturl(0) . '/users'` | Action interne | ❌ casse | `getUrl` |
| … | … | … | … | … |

---

## 5. Corrections — règles et exemples

### 5.1 Pattern correct (aucune action)
```php
// Navigation interne : boutons option/add/edit/data…
'href' => helper::baseUrl() . $this->getUrl(0) . '/option',

// Retour vers l'édition de la page hôte
'href' => $this->getPageEditUrl(),

// Lecture d'argument dans un module MULTIMODULE
$itemId = $this->getUrlArg(0);
```

### 5.2 Corrections à appliquer
```php
// ❌ avant
'href' => helper::baseUrl() . $this->geturl(0) . '/users',
// ✅ après  (uniformisation de casse)
'href' => helper::baseUrl() . $this->getUrl(0) . '/users',

// ❌ avant  (module MULTIMODULE, argument lu en dur)
$id = $this->getUrl(2);
// ✅ après
$id = $this->getUrlArg(0);

// ❌ avant  (retour page hôte fragile)
'href' => helper::baseUrl() . 'page/edit/' . $this->getUrl(0),
// ✅ après
'href' => $this->getPageEditUrl(),

// ❌ avant  (donnée de page hôte avec instanceId)
$this->getData(['page', $this->getUrl(0), 'css']);
// ✅ après
$this->getData(['page', $this->getPageId(), 'css']);
```

---

## 6. Cas particuliers documentés

### 6.1 Lien invitant à une action paramétrée (ex. validation par email)
Certains modules forgent un lien vers une action avec arguments (ex. `suscribe::validate`) :
```
helper::baseUrl(true) . $this->getPageId() . '/validate/'
    . ($this->getPageId() === $this->getUrl(0) ? '' : $this->getUrl(0) . '/') . $userId . '/' . $auth;
```
C'est le **schéma canonique** : `<pageId>/validate/[<instanceId>/]<arg0>/<arg1>`.
- **Mono-module** : `getPageId() === getUrl(0)` → pas d'instanceId inséré.
- **Multi-module** : instanceId inséré → `getUrlArg(0)`/`getUrlArg(1)` reçoivent bien les arguments.
→ **Correct** : ne pas réécrire, mais vérifier la cohérence de l'indexation des args.

### 6.2 URLs « free » et marqueurs
Un module en position `free` n'est rendu que si son marqueur `[MODULE:<instanceId>]`
(ou `[MODULE]`) est présent dans le contenu de page. Les liens publics doivent viser
`getUrl(0) . '/' . $itemId` (route vers `/<pageId>/<instanceId>/<itemId>` selon la position).

---

## 7. Format du rapport final attendu

```
## Module : <nom>

### Audit
- Constante MULTIMODULE : true|false
- Liens analysés : <N>
- Anomalies trouvées : <N>

| Fichier:ligne | Verdict | Correction |
|---|---|---|
| … | … | … |

### Corrections appliquées
- <fichier> : <description précise>

### Laissé volontairement
- <fichier/ligne> : <raison> (ex. contexte profil, ligne commentée, module MULTIMODULE=false)

### Vérification
- `grep page/edit/ module/<nom>` → aucune
- `grep geturl( module/<nom>` → aucune
- Pas de modification hors périmètre (versions, @link, contenus, CSS)
```

---

## 8. Checklist avant de rendre le travail

- [ ] Aucun `page/edit/` construit manuellement (retours → `getPageEditUrl()`).
- [ ] Les actions internes utilisent `getUrl(0) . '/<action>'`.
- [ ] Les arguments d'action des modules `MULTIMODULE = true` utilisent `getUrlArg(n)`.
- [ ] Les données de page hôte utilisent `getPageId()`.
- [ ] Aucune incohérence de casse `geturl(` dans `module/`.
- [ ] `core/` **non modifié** (fichiers partagés).
- [ ] **Aucun** changement de version, `@link`, CSS, contenu de vue ou `enum.json`
      non demandé.
- [ ] Le diff est **minimal** et justifié ligne par ligne.
- [ ] Rapport final rédigé (§7).

---

## 9. Pièges à éviter (leçons des audits précédents)

1. **Copier-coller entre projets** : ne jamais écraser un module par la version d'un autre
   dépôt (entraîne des régressions : `zwiicampus`↔`zwiicms`, perte de vues, retour de version).
   Toujours vérifier `git status` / `git diff` avant de committer.
2. **Sur-corriger** : si un pattern est déjà correct (ex. `getUrl(0) . '/option'`),
   ne pas le remplacer par `getPageId() . '/option/' . getUrl(0)` : le routeur produit
   le même résultat et le pattern `getUrl(0)` est celui du cœur.
3. **Confondre pageId et instanceId** dans les accès `['page', …]` → données introuvables
   ou écrites sous une clé fantôme.
4. **Toucher aux faux positifs** (`profil/view/edit.inc.php`, modules `MULTIMODULE = false`)
   introduit des régressions sans bénéfice.
5. **Commit unique et ciblé** : un commit par correction logique, message clair en français.

---

## 10. Références de code

- `core/trait/Data.trait.php` : `getUrl`, `getPageId`, `getUrlArg`, `getPageEditUrl`,
  `resolveModuleInstanceUrl`, `resolveModuleInstanceKeys`.
- `core/class/router.class.php` : `resolveInstanceUrl`, `renderModules`.
